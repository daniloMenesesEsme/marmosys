<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Models\FinancialCategory;
use App\Models\FinancialAccount;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialTransaction::with(['category', 'account', 'client'])
            ->when($request->tipo, function($q, $tipo) {
                return $q->where('tipo', $tipo);
            })
            ->when($request->status, function($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->data_inicio, function($q, $data) {
                return $q->where('data_vencimento', '>=', $data);
            })
            ->when($request->data_fim, function($q, $data) {
                return $q->where('data_vencimento', '<=', $data);
            });

        $transactions = $query->latest()->paginate(15);
        return view('financial.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $categories = FinancialCategory::all();
        $accounts = FinancialAccount::where('ativo', true)->get();
        $clients = Client::where('ativo', true)->get();
        return view('financial.transactions.create', compact('categories', 'accounts', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:financial_categories,id',
            'account_id' => 'required|exists:financial_accounts,id',
            'tipo' => 'required|in:receita,despesa,transferencia',
            'valor' => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'data_pagamento' => 'nullable|date',
            'descricao' => 'required|string|max:255',
            'documento' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'budget_id' => 'nullable|exists:budgets,id',
            'observacoes' => 'nullable|string',
            'parcelas' => 'nullable|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $transaction = new FinancialTransaction($validated);
            $transaction->user_id = auth()->id();
            $transaction->status = $request->data_pagamento ? 'pago' : 'pendente';
            $transaction->save();

            // Se tiver parcelas, cria as parcelas
            if ($request->parcelas > 1) {
                $valorParcela = round($validated['valor'] / $request->parcelas, 2);
                $dataVencimento = \Carbon\Carbon::parse($validated['data_vencimento']);

                for ($i = 1; $i <= $request->parcelas; $i++) {
                    $transaction->installments()->create([
                        'numero' => $i,
                        'valor' => $valorParcela,
                        'data_vencimento' => $dataVencimento->copy()->addMonths($i - 1),
                        'status' => 'pendente'
                    ]);
                }
            }

            // Se for pago, atualiza o saldo da conta
            if ($request->data_pagamento) {
                $account = FinancialAccount::findOrFail($validated['account_id']);
                $valor = $validated['valor'] * ($validated['tipo'] === 'receita' ? 1 : -1);
                $account->saldo_atual += $valor;
                $account->save();
            }

            DB::commit();
            return redirect()->route('financial.transactions.index')->with('success', 'Transação registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao registrar transação: ' . $e->getMessage());
        }
    }

    public function show(FinancialTransaction $transaction)
    {
        $transaction->load(['category', 'account', 'client', 'budget', 'installments']);
        return view('financial.transactions.show', compact('transaction'));
    }

    public function pay(Request $request, FinancialTransaction $transaction)
    {
        $validated = $request->validate([
            'data_pagamento' => 'required|date',
            'valor_pago' => 'required|numeric|min:0.01'
        ]);

        try {
            DB::beginTransaction();

            $transaction->update([
                'data_pagamento' => $validated['data_pagamento'],
                'status' => 'pago'
            ]);

            $account = $transaction->account;
            $valor = $validated['valor_pago'] * ($transaction->tipo === 'receita' ? 1 : -1);
            $account->saldo_atual += $valor;
            $account->save();

            DB::commit();
            return redirect()->route('financial.transactions.show', $transaction)
                ->with('success', 'Pagamento registrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao registrar pagamento: ' . $e->getMessage());
        }
    }
} 
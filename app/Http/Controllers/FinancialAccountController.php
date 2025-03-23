<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use App\Models\FinancialCategory;
use App\Models\Budget;
use App\Models\CostCenter;
use Illuminate\Http\Request;

class FinancialAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialAccount::with('category')
            ->when($request->tipo, function($q) use ($request) {
                if ($request->tipo === 'receita') {
                    return $q->receitas();
                }
                if ($request->tipo === 'despesa') {
                    return $q->despesas();
                }
            })
            ->when($request->status, function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->data_inicio, function($q) use ($request) {
                return $q->where('data_vencimento', '>=', $request->data_inicio);
            })
            ->when($request->data_fim, function($q) use ($request) {
                return $q->where('data_vencimento', '<=', $request->data_fim);
            });

        $totais = [
            'a_receber' => FinancialAccount::receitas()->pendentes()->sum('valor'),
            'a_pagar' => FinancialAccount::despesas()->pendentes()->sum('valor'),
            'recebido' => FinancialAccount::receitas()->pagas()->sum('valor'),
            'pago' => FinancialAccount::despesas()->pagas()->sum('valor'),
        ];

        $accounts = $query->latest('data_vencimento')->paginate(10);

        $categories = FinancialCategory::where('ativo', true)
            ->orderBy('nome')
            ->get()
            ->groupBy('tipo');

        return view('financial.accounts.index', compact('accounts', 'totais', 'categories'));
    }

    public function create()
    {
        $categories = FinancialCategory::where('ativo', true)
            ->orderBy('nome')
            ->get();
            
        $budgets = Budget::with('client')
            ->whereDoesntHave('financial_account')
            ->latest()
            ->get();
            
        $costCenters = CostCenter::where('ativo', true)
            ->orderBy('nome')
            ->get();
            
        return view('financial.accounts.form', compact('categories', 'budgets', 'costCenters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'category_id' => 'required|exists:financial_categories,id',
            'tipo' => 'required|in:receita,despesa',
            'status' => 'required|in:pendente,pago,cancelado',
            'data_vencimento' => 'required|date',
            'data_pagamento' => 'nullable|date',
            'observacoes' => 'nullable|string'
        ]);

        FinancialAccount::create($validated);

        return redirect()->route('financial.accounts.index')
            ->with('success', 'Conta criada com sucesso!');
    }

    public function edit(FinancialAccount $account)
    {
        $categories = FinancialCategory::where('ativo', true)
            ->orderBy('nome')
            ->get();
            
        $budgets = Budget::with('client')
            ->where(function($query) use ($account) {
                $query->whereDoesntHave('financial_account')
                      ->orWhere('id', $account->budget_id);
            })
            ->latest()
            ->get();
            
        $costCenters = CostCenter::where('ativo', true)
            ->orderBy('nome')
            ->get();
            
        return view('financial.accounts.form', compact('account', 'categories', 'budgets', 'costCenters'));
    }

    public function update(Request $request, FinancialAccount $account)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'category_id' => 'required|exists:financial_categories,id',
            'tipo' => 'required|in:receita,despesa',
            'status' => 'required|in:pendente,pago,cancelado',
            'data_vencimento' => 'required|date',
            'data_pagamento' => 'nullable|date',
            'observacoes' => 'nullable|string'
        ]);

        $account->update($validated);

        return redirect()->route('financial.accounts.index')
            ->with('success', 'Conta atualizada com sucesso!');
    }

    public function destroy(FinancialAccount $account)
    {
        $account->delete();

        return redirect()->route('financial.accounts.index')
            ->with('success', 'Conta excluída com sucesso!');
    }

    public function pay(FinancialAccount $account)
    {
        $account->update([
            'status' => 'pago',
            'data_pagamento' => now()
        ]);

        return redirect()->route('financial.accounts.index')
            ->with('success', 'Conta marcada como paga!');
    }
} 
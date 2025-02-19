<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\FinancialAccount;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->get('tipo', 'receita');
        $status = $request->get('status', 'pendente');
        $mes = $request->get('mes', Carbon::now()->month);
        $ano = $request->get('ano', Carbon::now()->year);

        $contas = FinancialAccount::with('categoria')
            ->where('tipo', $tipo)
            ->when($status !== 'todos', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->whereYear('data_vencimento', $ano)
            ->whereMonth('data_vencimento', $mes)
            ->orderBy('data_vencimento')
            ->paginate(10);

        $categorias = FinancialCategory::where('tipo', $tipo)
            ->where('ativo', true)
            ->get();

        return view('financial.accounts.index', compact(
            'contas',
            'categorias',
            'tipo',
            'status',
            'mes',
            'ano'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|max:255',
            'valor' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:financial_categories,id',
            'data_vencimento' => 'required|date',
            'data_pagamento' => 'nullable|date',
            'observacao' => 'nullable',
            'tipo' => 'required|in:receita,despesa',
            'status' => 'required|in:pendente,pago,cancelado'
        ]);

        FinancialAccount::create($validated);

        return redirect()->route('financial.accounts.index', ['tipo' => $request->tipo])
            ->with('success', 'Conta cadastrada com sucesso!');
    }

    public function update(Request $request, FinancialAccount $account)
    {
        $validated = $request->validate([
            'descricao' => 'required|max:255',
            'valor' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:financial_categories,id',
            'data_vencimento' => 'required|date',
            'data_pagamento' => 'nullable|date',
            'observacao' => 'nullable',
            'status' => 'required|in:pendente,pago,cancelado'
        ]);

        $account->update($validated);

        return redirect()->route('financial.accounts.index', ['tipo' => $account->tipo])
            ->with('success', 'Conta atualizada com sucesso!');
    }

    public function destroy(FinancialAccount $account)
    {
        $tipo = $account->tipo;
        $account->delete();

        return redirect()->route('financial.accounts.index', ['tipo' => $tipo])
            ->with('success', 'Conta excluída com sucesso!');
    }
} 
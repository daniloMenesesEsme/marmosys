<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index()
    {
        $mes = request('mes', Carbon::now()->month);
        $ano = request('ano', Carbon::now()->year);

        $budgets = Budget::with('category')
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->get();

        $categories = FinancialCategory::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('financial.budgets.index', compact('budgets', 'categories', 'mes', 'ano'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:financial_categories,id',
            'valor' => 'required|numeric|min:0.01',
            'mes' => 'required|integer|min:1|max:12',
            'ano' => 'required|integer|min:2000'
        ]);

        Budget::create($validated);

        return redirect()->route('financial.budgets.index')
            ->with('success', 'Orçamento criado com sucesso!');
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:financial_categories,id',
            'valor' => 'required|numeric|min:0.01',
            'mes' => 'required|integer|min:1|max:12',
            'ano' => 'required|integer|min:2000'
        ]);

        $budget->update($validated);

        return redirect()->route('financial.budgets.index')
            ->with('success', 'Orçamento atualizado com sucesso!');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()->route('financial.budgets.index')
            ->with('success', 'Orçamento excluído com sucesso!');
    }
} 
<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\FinancialGoal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index()
    {
        $goals = FinancialGoal::orderBy('data_final')->get();
        return view('financial.goals.index', compact('goals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|max:255',
            'valor_meta' => 'required|numeric|min:0.01',
            'valor_atual' => 'required|numeric|min:0',
            'data_inicial' => 'required|date',
            'data_final' => 'required|date|after:data_inicial',
            'observacoes' => 'nullable'
        ]);

        FinancialGoal::create($validated);

        return redirect()->route('financial.goals.index')
            ->with('success', 'Meta criada com sucesso!');
    }

    public function update(Request $request, FinancialGoal $goal)
    {
        $validated = $request->validate([
            'descricao' => 'required|max:255',
            'valor_meta' => 'required|numeric|min:0.01',
            'valor_atual' => 'required|numeric|min:0',
            'data_inicial' => 'required|date',
            'data_final' => 'required|date|after:data_inicial',
            'status' => 'required|in:em_andamento,concluida,cancelada',
            'observacoes' => 'nullable'
        ]);

        $goal->update($validated);

        return redirect()->route('financial.goals.index')
            ->with('success', 'Meta atualizada com sucesso!');
    }

    public function destroy(FinancialGoal $goal)
    {
        $goal->delete();

        return redirect()->route('financial.goals.index')
            ->with('success', 'Meta excluída com sucesso!');
    }
} 
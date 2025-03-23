<?php

namespace App\Http\Controllers;

use App\Models\FinancialGoal;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialGoalController extends Controller
{
    public function index()
    {
        $goals = FinancialGoal::orderBy('created_at', 'desc')->get();
        return view('financial.goals.index', compact('goals'));
    }

    public function create()
    {
        $categories = FinancialCategory::where('ativo', true)
            ->orderBy('nome')
            ->get()
            ->groupBy('tipo');

        return view('financial.goals.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor_meta' => 'required|numeric|min:0',
            'valor_atual' => 'required|numeric|min:0',
            'data_inicial' => 'required|date',
            'data_final' => 'required|date|after:data_inicial',
            'observacoes' => 'nullable|string'
        ]);

        $validated['status'] = 'em_andamento';
        $validated['percentual'] = ($validated['valor_atual'] / $validated['valor_meta']) * 100;

        FinancialGoal::create($validated);

        return redirect()->route('financial.goals.index')
            ->with('success', 'Meta financeira criada com sucesso!');
    }

    public function edit(FinancialGoal $goal)
    {
        $categories = FinancialCategory::where('ativo', true)
            ->orderBy('nome')
            ->get()
            ->groupBy('tipo');

        return view('financial.goals.form', compact('goal', 'categories'));
    }

    public function update(Request $request, FinancialGoal $goal)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor_meta' => 'required|numeric|min:0',
            'valor_atual' => 'required|numeric|min:0',
            'data_inicial' => 'required|date',
            'data_final' => 'required|date|after:data_inicial',
            'status' => 'required|in:em_andamento,concluida,cancelada',
            'observacoes' => 'nullable|string'
        ]);

        $validated['percentual'] = ($validated['valor_atual'] / $validated['valor_meta']) * 100;

        $goal->update($validated);

        return redirect()->route('financial.goals.index')
            ->with('success', 'Meta financeira atualizada com sucesso!');
    }

    public function destroy(FinancialGoal $goal)
    {
        $goal->delete();

        return redirect()->route('financial.goals.index')
            ->with('success', 'Meta financeira excluída com sucesso!');
    }

    public function updateStatus()
    {
        $metas = FinancialGoal::all();

        foreach ($metas as $meta) {
            if ($meta->data_final < Carbon::today()) {
                $meta->update(['status' => 'atrasada']);
            } elseif ($meta->progresso >= 100) {
                $meta->update(['status' => 'concluida']);
            } else {
                $meta->update(['status' => 'em_andamento']);
            }
        }

        return redirect()
            ->route('financial.goals.index')
            ->with('success', 'Status das metas atualizado com sucesso!');
    }
} 
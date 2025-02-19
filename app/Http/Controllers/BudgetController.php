<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Client;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->get('mes', Carbon::now()->month);
        $ano = $request->get('ano', Carbon::now()->year);
        
        $budgets = Budget::where('mes', $mes)
                        ->where('ano', $ano)
                        ->with('categoria')
                        ->get();
                        
        $categorias = FinancialCategory::where('ativo', true)->get();

        return view('budgets.index', compact('budgets', 'categorias', 'mes', 'ano'));
    }

    public function create()
    {
        $clients = Client::where('ativo', true)->orderBy('nome')->get();
        return view('budgets.form', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:financial_categories,id',
            'valor' => 'required|numeric|min:0',
            'mes' => 'required|integer|min:1|max:12',
            'ano' => 'required|integer|min:2000|max:2100',
        ]);

        Budget::create($validated);

        return redirect()->route('budgets.index', [
            'mes' => $request->mes,
            'ano' => $request->ano
        ])->with('success', 'Orçamento criado com sucesso!');
    }

    public function show(Budget $budget)
    {
        $budget->load(['client', 'items']);
        return view('budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $clients = Client::where('ativo', true)->orderBy('nome')->get();
        $budget->load('items');
        return view('budgets.form', compact('budget', 'clients'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:financial_categories,id',
            'valor' => 'required|numeric|min:0',
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.index', [
            'mes' => $budget->mes,
            'ano' => $budget->ano
        ])->with('success', 'Orçamento atualizado com sucesso!');
    }

    public function destroy(Budget $budget)
    {
        $mes = $budget->mes;
        $ano = $budget->ano;
        
        $budget->delete();

        return redirect()->route('budgets.index', [
            'mes' => $mes,
            'ano' => $ano
        ])->with('success', 'Orçamento excluído com sucesso!');
    }

    public function copy(Request $request)
    {
        $request->validate([
            'mes_origem' => 'required|integer|between:1,12',
            'ano_origem' => 'required|integer|min:2000',
            'mes_destino' => 'required|integer|between:1,12',
            'ano_destino' => 'required|integer|min:2000',
        ]);

        $orcamentos = Budget::where('mes', $request->mes_origem)
            ->where('ano', $request->ano_origem)
            ->get();

        foreach ($orcamentos as $orcamento) {
            Budget::updateOrCreate(
                [
                    'categoria_id' => $orcamento->categoria_id,
                    'mes' => $request->mes_destino,
                    'ano' => $request->ano_destino
                ],
                [
                    'valor_limite' => $orcamento->valor_limite,
                    'notificar_percentual' => $orcamento->notificar_percentual
                ]
            );
        }

        return redirect()
            ->route('financial.budgets.index', [
                'mes' => $request->mes_destino, 
                'ano' => $request->ano_destino
            ])
            ->with('success', 'Orçamentos copiados com sucesso!');
    }
} 
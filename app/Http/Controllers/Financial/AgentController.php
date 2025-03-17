<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Financial\AgentRequest;
use App\Models\FinancialAgent;
use App\Models\FinancialCategory;
use App\Models\CostCenter;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialAgent::query()
            ->with(['category', 'costCenter']);

        // Filtros
        if ($request->filled('codigo')) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Ordenação
        $direction = $request->direction == 'desc' ? 'desc' : 'asc';
        $sort = $request->sort ?? 'nome';
        $allowedSorts = ['codigo', 'nome', 'tipo'];
        
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        $agents = $query->paginate(15);

        return view('financial.registration.agents.index', [
            'agents' => $agents,
            'direction' => $direction == 'asc' ? 'desc' : 'asc'
        ]);
    }

    public function create()
    {
        $tipos = FinancialAgent::TIPOS;
        $categories = FinancialCategory::all();
        $costCenters = CostCenter::all();
        return view('financial.registration.agents.form', compact('tipos', 'categories', 'costCenters'));
    }

    public function store(AgentRequest $request)
    {
        try {
            $agent = FinancialAgent::create($request->validated());

            return redirect()
                ->route('financial.registration.agents.index')
                ->with('success', 'Agente financeiro cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar agente financeiro: ' . $e->getMessage());
        }
    }

    public function edit(FinancialAgent $agent)
    {
        $tipos = FinancialAgent::TIPOS;
        $categories = FinancialCategory::all();
        $costCenters = CostCenter::all();
        return view('financial.registration.agents.form', compact('agent', 'tipos', 'categories', 'costCenters'));
    }

    public function update(AgentRequest $request, FinancialAgent $agent)
    {
        try {
            $agent->update($request->validated());

            return redirect()
                ->route('financial.registration.agents.index')
                ->with('success', 'Agente financeiro atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar agente financeiro: ' . $e->getMessage());
        }
    }

    public function destroy(FinancialAgent $agent)
    {
        try {
            if (!$agent->podeSerExcluido()) {
                $dependencias = $agent->verificarDependencias();
                return redirect()
                    ->back()
                    ->with('error', 'Este agente não pode ser excluído pois possui vínculos com: ' . implode(', ', $dependencias));
            }

            $agent->delete();

            return redirect()
                ->route('financial.registration.agents.index')
                ->with('success', 'Agente financeiro excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir agente financeiro: ' . $e->getMessage());
        }
    }
} 
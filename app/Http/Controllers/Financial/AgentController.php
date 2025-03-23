<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Financial\AgentRequest;
use App\Models\FinancialAgent;
use App\Models\FinancialCategory;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Erro ao listar agentes financeiros: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao listar agentes financeiros.');
        }
    }

    public function create()
    {
        try {
            $tipos = FinancialAgent::TIPOS;
            $categories = FinancialCategory::orderBy('nome')->get();
            $costCenters = CostCenter::orderBy('nome')->get();
            
            return view('financial.registration.agents.form', compact('tipos', 'categories', 'costCenters'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de agente financeiro: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao carregar formulário.');
        }
    }

    public function store(AgentRequest $request)
    {
        try {
            DB::beginTransaction();

            $agent = FinancialAgent::create($request->validated());

            DB::commit();

            Log::info('Agente financeiro cadastrado com sucesso', [
                'id' => $agent->id,
                'nome' => $agent->nome
            ]);

            return redirect()
                ->route('financial.registration.agents.index')
                ->with('success', 'Agente financeiro cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar agente financeiro: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar agente financeiro. ' . $e->getMessage());
        }
    }

    public function edit(FinancialAgent $agent)
    {
        try {
            $tipos = FinancialAgent::TIPOS;
            $categories = FinancialCategory::orderBy('nome')->get();
            $costCenters = CostCenter::orderBy('nome')->get();
            
            return view('financial.registration.agents.form', compact('agent', 'tipos', 'categories', 'costCenters'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar formulário de edição: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao carregar formulário de edição.');
        }
    }

    public function update(AgentRequest $request, FinancialAgent $agent)
    {
        try {
            DB::beginTransaction();

            $agent->update($request->validated());

            DB::commit();

            Log::info('Agente financeiro atualizado com sucesso', [
                'id' => $agent->id,
                'nome' => $agent->nome
            ]);

            return redirect()
                ->route('financial.registration.agents.index')
                ->with('success', 'Agente financeiro atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar agente financeiro: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar agente financeiro. ' . $e->getMessage());
        }
    }

    public function destroy(FinancialAgent $agent)
    {
        try {
            DB::beginTransaction();

            // Verifica dependências
            if (!$agent->podeSerExcluido()) {
                $dependencias = $agent->verificarDependencias();
                throw new \Exception('Este agente financeiro não pode ser excluído pois está vinculado a: ' . implode(', ', $dependencias));
            }

            $agent->delete();

            DB::commit();

            Log::info('Agente financeiro removido com sucesso', [
                'id' => $agent->id,
                'nome' => $agent->nome
            ]);

            return redirect()
                ->route('financial.registration.agents.index')
                ->with('success', 'Agente financeiro removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao remover agente financeiro: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
} 
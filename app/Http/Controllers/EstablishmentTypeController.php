<?php

namespace App\Http\Controllers;

use App\Models\EstablishmentType;
use App\Models\Client;
use App\Models\ServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstablishmentTypeController extends Controller
{
    /**
     * Exibe uma lista de tipos de estabelecimento
     */
    public function index(Request $request)
    {
        $query = EstablishmentType::query();
        
        // Filtros
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('potential_level')) {
            $query->where('potential_level', '>=', $request->potential_level);
        }
        
        // Ordenação
        $query->orderBy($request->sort ?? 'name', $request->direction ?? 'asc');
        
        $establishmentTypes = $query->withCount(['clients', 'serviceAreas'])->paginate(10);
        
        // Estatísticas por nível de potencial
        $potentialStats = EstablishmentType::select('potential_level', DB::raw('count(*) as total'))
            ->groupBy('potential_level')
            ->orderBy('potential_level')
            ->get();
            
        return view('establishment_types.index', compact('establishmentTypes', 'potentialStats'));
    }

    /**
     * Exibe o formulário de criação de tipo de estabelecimento
     */
    public function create()
    {
        $potentialLevels = [
            1 => 'Muito Baixo',
            2 => 'Baixo',
            3 => 'Médio-Baixo',
            4 => 'Médio',
            5 => 'Médio-Alto',
            6 => 'Alto',
            7 => 'Muito Alto',
            8 => 'Extremo',
            9 => 'Premium',
            10 => 'VIP'
        ];
        
        return view('establishment_types.create', compact('potentialLevels'));
    }

    /**
     * Armazena um novo tipo de estabelecimento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'potential_level' => 'required|integer|min:1|max:10',
            'status' => 'required|in:active,inactive'
        ]);
        
        try {
            DB::beginTransaction();
            
            $type = EstablishmentType::create($validated);
            
            DB::commit();
            
            return redirect()->route('establishment-types.index')
                ->with('success', 'Tipo de estabelecimento cadastrado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar tipo de estabelecimento: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar tipo de estabelecimento: ' . $e->getMessage());
        }
    }

    /**
     * Exibe os detalhes de um tipo de estabelecimento
     */
    public function show(EstablishmentType $establishmentType)
    {
        // Carregar relacionamentos
        $establishmentType->load(['clients', 'serviceAreas.location', 'serviceAreas.seller']);
        
        // Contar clientes deste tipo
        $clientCount = $establishmentType->clients()->count();
        
        // Lista de clientes
        $clients = $establishmentType->clients()
            ->with('location')
            ->get();
        
        // Áreas de serviço que cobrem este tipo
        $serviceAreas = $establishmentType->serviceAreas()
            ->with('location', 'seller')
            ->get();
            
        // Obter dados para o gráfico de clientes por localidade
        $clientsByLocation = Client::where('establishment_type_id', $establishmentType->id)
            ->select('location_id', DB::raw('count(*) as total'))
            ->groupBy('location_id')
            ->with('location')
            ->having('total', '>', 0)
            ->get();
            
        // Estatísticas de vendas
        $salesStats = [
            'total' => 0,
            'average' => 0,
            'highest' => 0,
            'lowest' => 0,
            'avg_ticket' => 0,
            'count' => 0,
            'sales_per_client' => $clientCount > 0 ? 0 : 0  // Evita divisão por zero
        ];
            
        return view('establishment_types.show', compact(
            'establishmentType', 
            'clientCount',
            'clients',
            'serviceAreas', 
            'clientsByLocation',
            'salesStats'
        ));
    }

    /**
     * Exibe o formulário para editar um tipo de estabelecimento
     */
    public function edit(EstablishmentType $establishmentType)
    {
        $potentialLevels = [
            1 => 'Muito Baixo',
            2 => 'Baixo',
            3 => 'Médio-Baixo',
            4 => 'Médio',
            5 => 'Médio-Alto',
            6 => 'Alto',
            7 => 'Muito Alto',
            8 => 'Extremo',
            9 => 'Premium',
            10 => 'VIP'
        ];
        
        return view('establishment_types.edit', compact('establishmentType', 'potentialLevels'));
    }

    /**
     * Atualiza um tipo de estabelecimento
     */
    public function update(Request $request, EstablishmentType $establishmentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'potential_level' => 'required|integer|min:1|max:10',
            'status' => 'required|in:active,inactive'
        ]);
        
        try {
            DB::beginTransaction();
            
            $establishmentType->update($validated);
            
            DB::commit();
            
            return redirect()->route('establishment-types.index')
                ->with('success', 'Tipo de estabelecimento atualizado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar tipo de estabelecimento: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Erro ao atualizar tipo de estabelecimento: ' . $e->getMessage());
        }
    }

    /**
     * Remove um tipo de estabelecimento
     */
    public function destroy(EstablishmentType $establishmentType)
    {
        // Verificar se existem clientes associados
        if ($establishmentType->clients()->exists()) {
            return back()->with('error', 'Não é possível excluir este tipo de estabelecimento pois existem clientes associados.');
        }
        
        // Verificar se existem áreas de serviço associadas
        if ($establishmentType->serviceAreas()->exists()) {
            return back()->with('error', 'Não é possível excluir este tipo de estabelecimento pois existem áreas de atendimento associadas.');
        }
        
        try {
            $establishmentType->delete();
            
            return redirect()->route('establishment-types.index')
                ->with('success', 'Tipo de estabelecimento excluído com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao excluir tipo de estabelecimento: ' . $e->getMessage());
            
            return back()->with('error', 'Erro ao excluir tipo de estabelecimento: ' . $e->getMessage());
        }
    }
    
    /**
     * Exibe o dashboard com métricas do tipo de estabelecimento
     */
    public function dashboard()
    {
        // Potencial total por tipo
        $potentialByType = EstablishmentType::select(
                'establishment_types.name',
                'establishment_types.potential_level',
                DB::raw('count(clients.id) as client_count'),
                DB::raw('(count(clients.id) * establishment_types.potential_level) as total_potential')
            )
            ->leftJoin('clients', 'clients.establishment_type_id', '=', 'establishment_types.id')
            ->groupBy('establishment_types.id', 'establishment_types.name', 'establishment_types.potential_level')
            ->orderBy('total_potential', 'desc')
            ->get();
            
        // Clientes por tipo
        $clientsByType = EstablishmentType::withCount('clients')
            ->orderBy('clients_count', 'desc')
            ->get();
            
        // Áreas de serviço por tipo
        $areasByType = EstablishmentType::withCount('serviceAreas')
            ->orderBy('service_areas_count', 'desc')
            ->get();
            
        return view('establishment_types.dashboard', compact(
            'potentialByType',
            'clientsByType',
            'areasByType'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ServiceArea;
use App\Models\Location;
use App\Models\EstablishmentType;
use App\Models\Seller;
use App\Models\Client;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ServiceAreaController extends Controller
{
    /**
     * Exibe uma lista de áreas de atendimento
     */
    public function index(Request $request)
    {
        $query = ServiceArea::with(['location', 'establishmentType', 'seller']);
        
        // Filtros
        if ($request->filled('search')) {
            $query->whereHas('location', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })->orWhereHas('seller', function($q) use ($request) {
                $q->where('nome', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        
        if ($request->filled('establishment_type_id')) {
            $query->where('establishment_type_id', $request->establishment_type_id);
        }
        
        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Ordenação
        $query->orderBy($request->sort ?? 'id', $request->direction ?? 'desc');
        
        $serviceAreas = $query->paginate(10);
        
        // Para filtros
        $locations = Location::active()->orderBy('name')->get();
        $establishmentTypes = EstablishmentType::active()->orderBy('name')->get();
        $sellers = Seller::active()->orderBy('nome')->get();
        
        return view('service_areas.index', compact(
            'serviceAreas', 
            'locations', 
            'establishmentTypes', 
            'sellers'
        ));
    }

    /**
     * Exibe o formulário de criação de área de atendimento
     */
    public function create()
    {
        $locations = Location::active()->orderBy('name')->get();
        $establishmentTypes = EstablishmentType::active()->orderBy('name')->get();
        $sellers = Seller::active()->orderBy('nome')->get();
        
        return view('service_areas.create', compact('locations', 'establishmentTypes', 'sellers'));
    }

    /**
     * Armazena uma nova área de atendimento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'establishment_type_id' => 'nullable|exists:establishment_types,id',
            'seller_id' => 'nullable|exists:sellers,id',
            'goal_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        // Verificar se já existe uma área com esta combinação
        $exists = ServiceArea::where('location_id', $validated['location_id'])
            ->where('establishment_type_id', $validated['establishment_type_id'])
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Já existe uma área de atendimento para esta combinação de localidade e tipo de estabelecimento.');
        }
        
        try {
            DB::beginTransaction();
            
            $serviceArea = ServiceArea::create($validated);
            
            DB::commit();
            
            return redirect()->route('service-areas.index')
                ->with('success', 'Área de atendimento cadastrada com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar área de atendimento: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar área de atendimento: ' . $e->getMessage());
        }
    }

    /**
     * Exibe os detalhes de uma área de atendimento
     */
    public function show(ServiceArea $serviceArea)
    {
        $serviceArea->load(['location', 'establishmentType', 'seller']);
        
        // Contar clientes nesta área
        $clientCount = Client::where('location_id', $serviceArea->location_id)
            ->when($serviceArea->establishment_type_id, function($query) use ($serviceArea) {
                return $query->where('establishment_type_id', $serviceArea->establishment_type_id);
            })
            ->count();
            
        // Obter estatísticas de desempenho
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;
        
        $currentYearStats = $this->getYearlyPerformance($serviceArea, $currentYear);
        $lastYearStats = $this->getYearlyPerformance($serviceArea, $lastYear);
        $monthlyStats = $this->getMonthlyPerformance($serviceArea, $currentYear);
        
        return view('service_areas.show', compact(
            'serviceArea', 
            'clientCount', 
            'currentYearStats', 
            'lastYearStats', 
            'monthlyStats'
        ));
    }

    /**
     * Exibe o formulário para editar uma área de atendimento
     */
    public function edit(ServiceArea $serviceArea)
    {
        $serviceArea->load(['location', 'establishmentType', 'seller']);
        
        $locations = Location::active()->orderBy('name')->get();
        $establishmentTypes = EstablishmentType::active()->orderBy('name')->get();
        $sellers = Seller::active()->orderBy('nome')->get();
        
        return view('service_areas.edit', compact(
            'serviceArea', 
            'locations', 
            'establishmentTypes', 
            'sellers'
        ));
    }

    /**
     * Atualiza uma área de atendimento
     */
    public function update(Request $request, ServiceArea $serviceArea)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'establishment_type_id' => 'nullable|exists:establishment_types,id',
            'seller_id' => 'nullable|exists:sellers,id',
            'goal_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        // Verificar se já existe outra área com esta combinação
        $exists = ServiceArea::where('id', '!=', $serviceArea->id)
            ->where('location_id', $validated['location_id'])
            ->where('establishment_type_id', $validated['establishment_type_id'])
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Já existe uma área de atendimento para esta combinação de localidade e tipo de estabelecimento.');
        }
        
        try {
            DB::beginTransaction();
            
            $serviceArea->update($validated);
            
            DB::commit();
            
            return redirect()->route('service-areas.index')
                ->with('success', 'Área de atendimento atualizada com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar área de atendimento: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Erro ao atualizar área de atendimento: ' . $e->getMessage());
        }
    }

    /**
     * Remove uma área de atendimento
     */
    public function destroy(ServiceArea $serviceArea)
    {
        try {
            $serviceArea->delete();
            
            return redirect()->route('service-areas.index')
                ->with('success', 'Área de atendimento excluída com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao excluir área de atendimento: ' . $e->getMessage());
            
            return back()->with('error', 'Erro ao excluir área de atendimento: ' . $e->getMessage());
        }
    }
    
    /**
     * Exibe o mapa de cobertura
     */
    public function map()
    {
        // Carrega todos os vendedores ativos
        $sellers = Seller::active()->get();
        
        // Carrega todos os tipos de estabelecimento ativos
        $establishmentTypes = EstablishmentType::active()->get();
        
        // Inicializa o array com todos os vendedores ativos
        $sellerAreas = [];
        foreach ($sellers as $seller) {
            $sellerAreas[$seller->id] = [
                'seller' => $seller,
                'areas' => []
            ];
        }
        
        // Adiciona a opção "Sem vendedor"
        $sellerAreas[0] = [
            'seller' => null,
            'areas' => []
        ];
        
        // Carrega as áreas de serviço
        $serviceAreas = ServiceArea::with(['location', 'establishmentType', 'seller'])
            ->whereHas('location', function($query) {
                $query->whereNotNull('latitude')->whereNotNull('longitude');
            })
            ->where('status', 'active')
            ->get();
            
        // Organiza as áreas por vendedor
        foreach ($serviceAreas as $area) {
            $sellerId = $area->seller_id ?? 0;
            if (!isset($sellerAreas[$sellerId])) {
                $sellerAreas[$sellerId] = [
                    'seller' => $area->seller,
                    'areas' => []
                ];
            }
            $sellerAreas[$sellerId]['areas'][] = $area;
        }
        
        // Remove vendedores sem áreas para não poluir o filtro
        $sellerAreas = array_filter($sellerAreas, function($data) {
            return count($data['areas']) > 0 || ($data['seller'] && $data['seller']->ativo);
        });
            
        return view('service_areas.map', compact('sellerAreas', 'establishmentTypes'));
    }
    
    /**
     * Exibe o dashboard com métricas de performance
     */
    public function dashboard()
    {
        // Áreas sem vendedor
        $unassignedCount = ServiceArea::whereNull('seller_id')
            ->where('status', 'active')
            ->count();
            
        // Áreas sem clientes
        $areasWithoutClients = ServiceArea::where('status', 'active')
            ->whereHas('location', function($query) {
                $query->doesntHave('clients');
            })
            ->count();
            
        // Melhores vendedores por área
        $topSellers = ServiceArea::select('seller_id', DB::raw('count(*) as area_count'))
            ->whereNotNull('seller_id')
            ->where('status', 'active')
            ->groupBy('seller_id')
            ->with('seller')
            ->orderBy('area_count', 'desc')
            ->limit(5)
            ->get();
            
        // Desempenho por tipo de estabelecimento
        $typePerformance = EstablishmentType::select(
                'establishment_types.id',
                'establishment_types.name',
                DB::raw('count(service_areas.id) as area_count'),
                DB::raw('count(budgets.id) as sale_count'),
                DB::raw('sum(budgets.valor_final) as total_sales')
            )
            ->leftJoin('service_areas', 'service_areas.establishment_type_id', '=', 'establishment_types.id')
            ->leftJoin('budgets', function($join) {
                $join->on('budgets.seller_id', '=', 'service_areas.seller_id')
                    ->whereRaw('budgets.client_id in (select id from clients where clients.establishment_type_id = establishment_types.id)');
            })
            ->groupBy('establishment_types.id', 'establishment_types.name')
            ->orderBy('total_sales', 'desc')
            ->get();
            
        return view('service_areas.dashboard', compact(
            'unassignedCount',
            'areasWithoutClients',
            'topSellers',
            'typePerformance'
        ));
    }
    
    /**
     * Obter estatísticas de desempenho anual para uma área
     */
    private function getYearlyPerformance(ServiceArea $serviceArea, $year)
    {
        if (!$serviceArea->seller_id) {
            return [
                'total_sales' => 0,
                'sale_count' => 0,
                'goal_amount' => $serviceArea->goal_amount,
                'goal_percentage' => 0
            ];
        }
        
        $query = Budget::where('seller_id', $serviceArea->seller_id)
            ->whereYear('data', $year)
            ->whereHas('client', function($query) use ($serviceArea) {
                $query->where('location_id', $serviceArea->location_id);
                if ($serviceArea->establishment_type_id) {
                    $query->where('establishment_type_id', $serviceArea->establishment_type_id);
                }
            });
            
        $total = $query->sum('valor_final');
        $count = $query->count();
        
        return [
            'total_sales' => $total,
            'sale_count' => $count,
            'goal_amount' => $serviceArea->goal_amount,
            'goal_percentage' => $serviceArea->goal_amount > 0 ? ($total / $serviceArea->goal_amount) * 100 : 0
        ];
    }
    
    /**
     * Obter estatísticas de desempenho mensal para uma área
     */
    private function getMonthlyPerformance(ServiceArea $serviceArea, $year)
    {
        if (!$serviceArea->seller_id) {
            return [];
        }
        
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $query = Budget::where('seller_id', $serviceArea->seller_id)
                ->whereYear('data', $year)
                ->whereMonth('data', $month)
                ->whereHas('client', function($query) use ($serviceArea) {
                    $query->where('location_id', $serviceArea->location_id);
                    if ($serviceArea->establishment_type_id) {
                        $query->where('establishment_type_id', $serviceArea->establishment_type_id);
                    }
                });
                
            $total = $query->sum('valor_final');
            $count = $query->count();
            
            $monthlyData[] = [
                'month' => Carbon::createFromDate($year, $month, 1)->format('F'),
                'month_number' => $month,
                'total_sales' => $total,
                'sale_count' => $count,
                'monthly_goal' => $serviceArea->goal_amount ? $serviceArea->goal_amount / 12 : 0,
                'goal_percentage' => $serviceArea->goal_amount ? (($total / ($serviceArea->goal_amount / 12)) * 100) : 0
            ];
        }
        
        return $monthlyData;
    }

    /**
     * Verifica se já existe uma área de atendimento para a combinação de localidade e tipo
     */
    public function checkServiceArea(Request $request)
    {
        $locationId = $request->input('location_id');
        $typeId = $request->input('establishment_type_id');
        $excludeId = $request->input('exclude_id');
        
        $query = ServiceArea::where('location_id', $locationId);
        
        if ($typeId) {
            $query->where('establishment_type_id', $typeId);
        } else {
            $query->whereNull('establishment_type_id');
        }
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $exists = $query->exists();
        
        return response()->json(['exists' => $exists]);
    }
}

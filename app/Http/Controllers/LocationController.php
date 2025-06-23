<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\ServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Exibe uma lista de localidades
     */
    public function index(Request $request)
    {
        $query = Location::query();
        
        // Filtros
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }
        
        // Ordenação
        $query->orderBy($request->sort ?? 'name', $request->direction ?? 'asc');
        
        $locations = $query->paginate(10);
        
        // Para o seletor de pais no filtro
        $parents = Location::whereNull('parent_id')
            ->orWhere('type', 'state')
            ->orderBy('name')
            ->get();
            
        // Estatísticas por tipo
        $locationStats = Location::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();
            
        return view('locations.index', compact('locations', 'parents', 'locationStats'));
    }

    /**
     * Exibe o formulário de criação de localidade
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Armazena uma nova localidade
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cep' => 'required|string|size:9',
            'state' => 'required|string|size:2',
            'city' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Encontra o estado
            $state = Location::where('type', 'state')
                ->where('code', $validated['state'])
                ->first();
                
            if (!$state) {
                throw new \Exception('Estado não encontrado');
            }
            
            // Busca ou cria a cidade
            $city = Location::firstOrCreate(
                [
                    'type' => 'city',
                    'parent_id' => $state->id,
                    'name' => $validated['city']
                ],
                [
                    'status' => 'active'
                ]
            );
            
            // Cria o bairro
            $location = Location::create([
                'name' => $validated['name'],
                'type' => 'neighborhood',
                'parent_id' => $city->id,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'code' => $validated['cep'],
            ]);
            
            DB::commit();
            
            return redirect()->route('locations.index')
                ->with('success', 'Localidade cadastrada com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar localidade: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar localidade: ' . $e->getMessage());
        }
    }

    /**
     * Exibe os detalhes de uma localidade
     */
    public function show(Location $location)
    {
        // Carregar dados relacionados
        $location->load('parent', 'children');
        
        // Contagem de clientes nesta localidade
        $clientCount = $location->clients()->count();
        
        // Áreas de serviço associadas
        $serviceAreas = ServiceArea::with('seller', 'establishmentType')
            ->where('location_id', $location->id)
            ->get();
            
        // Hierarquia completa
        $hierarchy = $this->getLocationHierarchy($location);
        
        return view('locations.show', compact('location', 'clientCount', 'serviceAreas', 'hierarchy'));
    }

    /**
     * Exibe o formulário para editar uma localidade
     */
    public function edit(Location $location)
    {
        // Carrega os relacionamentos
        $location->load('parent.parent');
        
        // Se for um bairro, precisamos do estado e cidade
        if ($location->type === 'neighborhood') {
            $city = $location->parent; // Cidade é o pai direto
            $state = $city ? $city->parent : null; // Estado é o pai da cidade
            
            return view('locations.edit', compact('location', 'city', 'state'));
        }
        
        // Se for uma cidade
        if ($location->type === 'city') {
            $state = $location->parent; // Estado é o pai direto
            
            return view('locations.edit', compact('location', 'state'));
        }
        
        return view('locations.edit', compact('location'));
    }

    /**
     * Atualiza uma localidade
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $location->update($validated);
            
            DB::commit();
            
            return redirect()->route('locations.index')
                ->with('success', 'Localidade atualizada com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar localidade: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Erro ao atualizar localidade: ' . $e->getMessage());
        }
    }

    /**
     * Remove uma localidade
     */
    public function destroy(Location $location)
    {
        // Verificar se existem filhos
        if ($location->children()->exists()) {
            return back()->with('error', 'Não é possível excluir esta localidade pois existem localidades filhas associadas.');
        }
        
        // Verificar se existem áreas de serviço
        if ($location->serviceAreas()->exists()) {
            return back()->with('error', 'Não é possível excluir esta localidade pois existem áreas de atendimento associadas.');
        }
        
        // Verificar se existem clientes
        if ($location->clients()->exists()) {
            return back()->with('error', 'Não é possível excluir esta localidade pois existem clientes associados.');
        }
        
        try {
            $location->delete();
            
            return redirect()->route('locations.index')
                ->with('success', 'Localidade excluída com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao excluir localidade: ' . $e->getMessage());
            
            return back()->with('error', 'Erro ao excluir localidade: ' . $e->getMessage());
        }
    }
    
    /**
     * Exibe o mapa de localidades
     */
    public function map()
    {
        $locations = Location::where('latitude', '!=', null)
            ->where('longitude', '!=', null)
            ->with('serviceAreas.seller')
            ->get();
            
        return view('locations.map', compact('locations'));
    }
    
    /**
     * Método para importação de localidades em lote
     */
    public function import()
    {
        return view('locations.import');
    }
    
    /**
     * Processa a importação de localidades
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'type' => 'required|in:state,city,neighborhood',
            'parent_type' => 'nullable|required_if:type,city,neighborhood|in:state,city',
        ]);
        
        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $records = array_map('str_getcsv', file($path));
            
            // Cabeçalho
            $header = array_shift($records);
            
            $imported = 0;
            $errors = [];
            
            DB::beginTransaction();
            
            foreach ($records as $record) {
                $data = array_combine($header, $record);
                
                // Validar campos obrigatórios
                if (empty($data['name'])) {
                    $errors[] = "Linha " . ($imported + 1) . ": Nome em branco";
                    continue;
                }
                
                // Encontrar parent_id se necessário
                $parentId = null;
                if ($request->type != 'state' && !empty($data['parent_name'])) {
                    $parent = Location::where('name', $data['parent_name'])
                        ->where('type', $request->parent_type)
                        ->first();
                    
                    if ($parent) {
                        $parentId = $parent->id;
                    } else {
                        $errors[] = "Linha " . ($imported + 1) . ": Pai '" . $data['parent_name'] . "' não encontrado";
                        continue;
                    }
                }
                
                // Criar localidade
                Location::create([
                    'name' => $data['name'],
                    'type' => $request->type,
                    'parent_id' => $parentId,
                    'code' => $data['code'] ?? null,
                    'latitude' => $data['latitude'] ?? null,
                    'longitude' => $data['longitude'] ?? null,
                    'description' => $data['description'] ?? null,
                    'status' => 'active',
                ]);
                
                $imported++;
            }
            
            DB::commit();
            
            return back()->with([
                'success' => $imported . ' localidades importadas com sucesso!',
                'errors' => $errors,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro na importação de localidades: ' . $e->getMessage());
            
            return back()->with('error', 'Erro na importação: ' . $e->getMessage());
        }
    }
    
    /**
     * Verifica se definir um novo pai criaria um ciclo na hierarquia
     */
    private function wouldCreateCycle($locationId, $newParentId)
    {
        // Se o novo pai for o mesmo que a localidade, temos um ciclo
        if ($locationId == $newParentId) {
            return true;
        }
        
        $parent = Location::find($newParentId);
        
        // Se o pai não existir, não há ciclo
        if (!$parent) {
            return false;
        }
        
        // Verificar os ancestrais do novo pai
        while ($parent && $parent->parent_id) {
            // Se algum ancestral for a localidade atual, temos um ciclo
            if ($parent->parent_id == $locationId) {
                return true;
            }
            
            $parent = $parent->parent;
        }
        
        return false;
    }
    
    /**
     * Obtém a hierarquia completa da localidade
     */
    private function getLocationHierarchy($location)
    {
        $hierarchy = [$location];
        $current = $location;
        
        // Adicionar todos os pais
        while ($current->parent) {
            array_unshift($hierarchy, $current->parent);
            $current = $current->parent;
        }
        
        return $hierarchy;
    }

    /**
     * Dados dos estados brasileiros e suas cidades
     */
    private $estadosMunicipios = [
        'AC' => 22,
        'AL' => 102,
        'AP' => 16,
        'AM' => 62,
        'BA' => 417,
        'CE' => 184,
        'DF' => 1,
        'ES' => 78,
        'GO' => 246,
        'MA' => 217,
        'MT' => 141,
        'MS' => 79,
        'MG' => 853,
        'PA' => 144,
        'PB' => 223,
        'PR' => 399,
        'PE' => 184,
        'PI' => 224,
        'RJ' => 92,
        'RN' => 167,
        'RS' => 497,
        'RO' => 52,
        'RR' => 15,
        'SC' => 295,
        'SP' => 645,
        'SE' => 75,
        'TO' => 139
    ];

    /**
     * Retorna lista de cidades de um estado usando API do IBGE
     */
    public function getCities($state)
    {
        try {
            // Busca cidades existentes para o estado
            $cities = Location::where('type', 'city')
                ->where('parent_id', function($query) use ($state) {
                    $query->select('id')
                        ->from('locations')
                        ->where('type', 'state')
                        ->where('code', $state)
                        ->limit(1);
                })
                ->orderBy('name')
                ->get();

            // Se não encontrou cidades, busca da API do IBGE
            if ($cities->isEmpty()) {
                $response = file_get_contents("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$state}/municipios");
                $municipios = json_decode($response, true);

                if ($municipios) {
                    // Encontra o estado
                    $estado = Location::where('type', 'state')
                        ->where('code', $state)
                        ->first();

                    if ($estado) {
                        // Cria as cidades no banco
                        foreach ($municipios as $municipio) {
                            Location::firstOrCreate(
                                [
                                    'type' => 'city',
                                    'parent_id' => $estado->id,
                                    'name' => $municipio['nome']
                                ],
                                [
                                    'status' => 'active'
                                ]
                            );
                        }

                        // Busca novamente as cidades
                        $cities = Location::where('type', 'city')
                            ->where('parent_id', $estado->id)
                            ->orderBy('name')
                            ->get();
                    }
                }
            }

            // Formata para o select
            return response()->json($cities->pluck('name')
                ->mapWithKeys(function($name) {
                    return [$name => null];
                })
                ->all()
            );

        } catch (\Exception $e) {
            Log::error('Erro ao buscar cidades: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar cidades'], 500);
        }
    }

    /**
     * Retorna lista de bairros de uma cidade
     */
    public function getNeighborhoods($state, $city)
    {
        try {
            // Busca o ID da cidade
            $cityId = Location::where('type', 'city')
                ->where('name', $city)
                ->whereHas('parent', function($query) use ($state) {
                    $query->where('type', 'state')
                        ->where('code', $state);
                })
                ->value('id');

            if (!$cityId) {
                return response()->json([]);
            }

            // Busca bairros existentes
            $neighborhoods = Location::where('type', 'neighborhood')
                ->where('parent_id', $cityId)
                ->orderBy('name')
                ->pluck('name')
                ->mapWithKeys(function($name) {
                    return [$name => null];
                })
                ->all();

            return response()->json($neighborhoods);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar bairros: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar bairros'], 500);
        }
    }

    /**
     * Retorna coordenadas geográficas
     */
    public function getGeocode($state, $city, $neighborhood)
    {
        try {
            $location = Location::where('type', 'neighborhood')
                ->where('name', $neighborhood)
                ->whereHas('parent', function($query) use ($city, $state) {
                    $query->where('type', 'city')
                        ->where('name', $city)
                        ->whereHas('parent', function($q) use ($state) {
                            $q->where('type', 'state')
                                ->where('code', $state);
                        });
                })
                ->first();

            if ($location && $location->latitude && $location->longitude) {
                return response()->json([
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude
                ]);
            }

            return response()->json(['error' => 'Coordenadas não encontradas'], 404);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar coordenadas: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar coordenadas'], 500);
        }
    }

    /**
     * Display the dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        try {
            // Carrega as estatísticas
            $totalLocations = Location::count();
            $activeLocations = Location::where('status', 'active')->count();
            $inactiveLocations = Location::where('status', 'inactive')->count();
            
            // Carrega as últimas localidades com seus relacionamentos
            $recentLocations = Location::with(['parent', 'parent.parent'])
                ->where('type', 'neighborhood')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($location) {
                    return [
                        'name' => $location->name, // bairro
                        'city' => $location->parent ? $location->parent->name : '-', // cidade
                        'state' => $location->parent && $location->parent->parent ? $location->parent->parent->code : '-', // estado
                        'status' => $location->status,
                        'created_at' => $location->created_at
                    ];
                });

            return view('locations.dashboard', compact(
                'totalLocations',
                'activeLocations',
                'inactiveLocations',
                'recentLocations'
            ));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar dashboard de localidades: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar o dashboard. Por favor, tente novamente.');
        }
    }
}

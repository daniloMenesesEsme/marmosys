<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Seller;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::withCount('sellers')->orderBy('name')->paginate(10);
        
        // Dados para o gráfico de vendedores por região
        $regionsChart = Region::withCount('sellers')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Dados para o gráfico de vendedores por estado
        $stateData = Region::selectRaw('state, count(*) as total_regions, 
                                        SUM((SELECT COUNT(*) FROM sellers WHERE sellers.region_id = regions.id)) as total_sellers')
            ->groupBy('state')
            ->get();
        
        return view('regions.index', compact('regions', 'regionsChart', 'stateData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('regions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'state' => 'required|string|size:2',
            'status' => 'required|in:active,inactive',
        ]);

        Region::create($validated);

        return redirect()->route('regions.index')
            ->with('success', 'Região cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        $sellers = Seller::where('region_id', $region->id)->get();
        return view('regions.show', compact('region', 'sellers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        return view('regions.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'state' => 'required|string|size:2',
            'status' => 'required|in:active,inactive',
        ]);

        $region->update($validated);

        return redirect()->route('regions.index')
            ->with('success', 'Região atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        // Verificar se existem vendedores associados
        $hasSellers = Seller::where('region_id', $region->id)->exists();
        
        if ($hasSellers) {
            return redirect()->route('regions.index')
                ->with('error', 'Não é possível excluir esta região pois existem vendedores associados a ela.');
        }
        
        $region->delete();

        return redirect()->route('regions.index')
            ->with('success', 'Região excluída com sucesso!');
    }
    
    /**
     * Alternar o status da região (ativo/inativo)
     */
    public function toggleStatus(Region $region)
    {
        $region->status = $region->status === 'active' ? 'inactive' : 'active';
        $region->save();
        
        $statusText = $region->status === 'active' ? 'ativada' : 'desativada';
        
        return redirect()->route('regions.index')
            ->with('success', "Região {$statusText} com sucesso!");
    }
} 
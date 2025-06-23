<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\PdfHelper;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Seller::query();
        
        // Filtros
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('status')) {
            $query->where('ativo', $request->status === 'ativo');
        }
        
        // Ordenação
        $query->orderBy('nome');
        
        $sellers = $query->paginate(10);
        
        return view('sellers.index', compact('sellers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regions = \App\Models\Region::where('status', 'active')->orderBy('name')->get();
        return view('sellers.form', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'cpf' => 'nullable|string|max:14|unique:sellers,cpf',
            'rg' => 'nullable|string|max:20',
            'telefone' => 'nullable|string|max:15',
            'celular' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100|unique:sellers,email',
            'endereco' => 'nullable|string|max:200',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'percentual_comissao' => 'nullable|numeric|min:0|max:100',
            'meta_mensal' => 'nullable|numeric|min:0',
            'data_admissao' => 'nullable|date',
            'data_demissao' => 'nullable|date',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
            'region_id' => 'nullable|exists:regions,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $seller = Seller::create($validated);
            
            DB::commit();
            
            return redirect()
                ->route('sellers.index')
                ->with('success', 'Vendedor cadastrado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar vendedor: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        return view('sellers.show', compact('seller'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seller $seller)
    {
        $regions = \App\Models\Region::where('status', 'active')->orderBy('name')->get();
        return view('sellers.form', compact('seller', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'cpf' => 'nullable|string|max:14|unique:sellers,cpf,' . $seller->id,
            'rg' => 'nullable|string|max:20',
            'telefone' => 'nullable|string|max:15',
            'celular' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100|unique:sellers,email,' . $seller->id,
            'endereco' => 'nullable|string|max:200',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'percentual_comissao' => 'nullable|numeric|min:0|max:100',
            'meta_mensal' => 'nullable|numeric|min:0',
            'data_admissao' => 'nullable|date',
            'data_demissao' => 'nullable|date',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
            'region_id' => 'nullable|exists:regions,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $seller->update($validated);
            
            DB::commit();
            
            return redirect()
                ->route('sellers.index')
                ->with('success', 'Vendedor atualizado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar vendedor: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller)
    {
        try {
            // Verificar se o vendedor está vinculado a orçamentos
            if ($seller->budgets()->exists()) {
                return back()->with('error', 'Não é possível excluir este vendedor pois ele está vinculado a orçamentos.');
            }
            
            $seller->delete();
            
            return redirect()
                ->route('sellers.index')
                ->with('success', 'Vendedor excluído com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao excluir vendedor: ' . $e->getMessage());
            
            return back()->with('error', 'Erro ao excluir vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o relatório de vendedores.
     */
    public function report(Request $request)
    {
        $query = Seller::query();

        // Filtros
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('status')) {
            $query->where('ativo', $request->status === 'ativo');
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_admissao', [$request->data_inicio, $request->data_fim]);
        } elseif ($request->filled('data_inicio')) {
            $query->where('data_admissao', '>=', $request->data_inicio);
        } elseif ($request->filled('data_fim')) {
            $query->where('data_admissao', '<=', $request->data_fim);
        }

        if ($request->filled('comissao_min') && $request->filled('comissao_max')) {
            $query->whereBetween('percentual_comissao', [$request->comissao_min, $request->comissao_max]);
        } elseif ($request->filled('comissao_min')) {
            $query->where('percentual_comissao', '>=', $request->comissao_min);
        } elseif ($request->filled('comissao_max')) {
            $query->where('percentual_comissao', '<=', $request->comissao_max);
        }

        // Buscar vendedores com orçamentos no período
        if ($request->filled('periodo_inicio') && $request->filled('periodo_fim')) {
            $query->whereHas('budgets', function($q) use ($request) {
                $q->whereBetween('data', [$request->periodo_inicio, $request->periodo_fim]);
            });
        }

        $sellers = $query->withCount(['budgets' => function($query) use ($request) {
            if ($request->filled('periodo_inicio') && $request->filled('periodo_fim')) {
                $query->whereBetween('data', [$request->periodo_inicio, $request->periodo_fim]);
            }
        }])->paginate(15);

        // Calcular totais
        $totals = [
            'vendedores_ativos' => Seller::where('ativo', true)->count(),
            'vendedores_inativos' => Seller::where('ativo', false)->count(),
            'total_vendedores' => Seller::count()
        ];

        // Verificar se requisição pede PDF
        if ($request->has('pdf')) {
            return $this->generatePdf($sellers, $totals, $request);
        }

        return view('sellers.report', compact('sellers', 'totals'));
    }

    /**
     * Gera um PDF do relatório de vendedores.
     */
    public function generatePdf($sellers, $totals, $request)
    {
        // Garante que as consultas serão executadas para o PDF
        if ($sellers instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $sellers = $sellers->getCollection();
        }
        
        $data = [
            'sellers' => $sellers,
            'totals' => $totals,
            'filters' => (object)[
                'nome' => $request->nome,
                'status' => $request->status,
                'data_inicio' => $request->filled('data_inicio') ? \Carbon\Carbon::parse($request->data_inicio) : null,
                'data_fim' => $request->filled('data_fim') ? \Carbon\Carbon::parse($request->data_fim) : null,
                'comissao_min' => $request->comissao_min,
                'comissao_max' => $request->comissao_max,
                'periodo_inicio' => $request->filled('periodo_inicio') ? \Carbon\Carbon::parse($request->periodo_inicio) : null,
                'periodo_fim' => $request->filled('periodo_fim') ? \Carbon\Carbon::parse($request->periodo_fim) : null,
            ]
        ];
        
        // Opções personalizadas para o PDF
        $options = [
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'isJavascriptEnabled' => true,
            'dpi' => 150,
            'defaultEncoding' => 'UTF-8',
            'isFontSubsettingEnabled' => true,
            'enable_remote' => true,
            'enable_php' => true,
            'enable_javascript' => true,
            'images' => true,
            'tempDir' => storage_path('app/pdf-temp'),
            'chroot' => public_path(),
            'logOutputFile' => storage_path('logs/pdf.log'),
            'debugPng' => false,
            'debugKeepTemp' => false,
        ];

        return \App\Helpers\PdfHelper::generate('sellers.pdf', $data, 'relatorio_vendedores.pdf', 'portrait', $options);
    }

    /**
     * Exibe a página de gestão de regiões de vendedores.
     */
    public function regions()
    {
        $sellers = Seller::with('region')->where('ativo', true)->paginate(10);
        
        // Agrupar vendedores por estado
        $stateRegions = DB::table('sellers')
            ->select('estado', DB::raw('count(*) as total'))
            ->whereNotNull('estado')
            ->groupBy('estado')
            ->get();
        
        // Agrupar vendedores por região cadastrada
        $customRegions = DB::table('sellers')
            ->join('regions', 'sellers.region_id', '=', 'regions.id')
            ->select('regions.name', 'regions.state', DB::raw('count(*) as total'))
            ->whereNotNull('sellers.region_id')
            ->groupBy('regions.id', 'regions.name', 'regions.state')
            ->get();
        
        // Obter todas as regiões cadastradas
        $allRegions = \App\Models\Region::where('status', 'active')->get();
        
        return view('sellers.regions', compact('sellers', 'stateRegions', 'customRegions', 'allRegions'));
    }

    // Método para verificar se já existe vendedor para a região (para AJAX)
    public function checkRegionSeller($regionId)
    {
        $seller = Seller::where('region_id', $regionId)
                   ->where('ativo', true)
                   ->first();
        
        return response()->json([
            'seller' => $seller ? [
                'id' => $seller->id,
                'name' => $seller->nome
            ] : null
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuppliersExport;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Supplier::query();

            // Filtros
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('razao_social', 'like', "%{$search}%")
                      ->orWhere('nome_fantasia', 'like', "%{$search}%")
                      ->orWhere('cnpj', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('telefone', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('ativo', $request->status === 'ativo');
            }

            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            $suppliers = $query->orderBy('razao_social')->paginate(10)->withQueryString();

            return view('suppliers.index', compact('suppliers'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar fornecedores', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erro ao carregar fornecedores. Por favor, tente novamente.');
        }
    }

    public function create()
    {
        return view('suppliers.form');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'razao_social' => 'required|max:255',
                'nome_fantasia' => 'required|max:255',
                'cnpj' => 'required|unique:suppliers|max:18',
                'inscricao_estadual' => 'nullable|max:20',
                'inscricao_municipal' => 'nullable|max:20',
                'telefone' => 'required|max:15',
                'email' => 'required|email|max:255',
                'cep' => 'required|max:9',
                'endereco' => 'required|max:255',
                'numero' => 'required|max:10',
                'complemento' => 'nullable|max:255',
                'bairro' => 'required|max:255',
                'cidade' => 'required|max:255',
                'estado' => 'required|size:2',
                'observacoes' => 'nullable',
                'ativo' => 'boolean'
            ]);

            $supplier = Supplier::create($validated);

            Log::info('Fornecedor criado com sucesso', [
                'id' => $supplier->id,
                'razao_social' => $supplier->razao_social
            ]);

            return redirect()->route('suppliers.index')
                ->with('success', 'Fornecedor cadastrado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao criar fornecedor', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar fornecedor. Por favor, tente novamente.');
        }
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.form', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        try {
            $validated = $request->validate([
                'razao_social' => 'required|max:255',
                'nome_fantasia' => 'required|max:255',
                'cnpj' => 'required|max:18|unique:suppliers,cnpj,' . $supplier->id,
                'inscricao_estadual' => 'nullable|max:20',
                'inscricao_municipal' => 'nullable|max:20',
                'telefone' => 'required|max:15',
                'email' => 'required|email|max:255',
                'cep' => 'required|max:9',
                'endereco' => 'required|max:255',
                'numero' => 'required|max:10',
                'complemento' => 'nullable|max:255',
                'bairro' => 'required|max:255',
                'cidade' => 'required|max:255',
                'estado' => 'required|size:2',
                'observacoes' => 'nullable',
                'ativo' => 'boolean'
            ]);

            $supplier->update($validated);

            Log::info('Fornecedor atualizado com sucesso', [
                'id' => $supplier->id,
                'razao_social' => $supplier->razao_social
            ]);

            return redirect()->route('suppliers.index')
                ->with('success', 'Fornecedor atualizado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar fornecedor', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar fornecedor. Por favor, tente novamente.');
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->ativo = !$supplier->ativo;
            $supplier->save();

            $status = $supplier->ativo ? 'ativado' : 'inativado';

            Log::info('Status do fornecedor alterado', [
                'id' => $supplier->id,
                'razao_social' => $supplier->razao_social,
                'status' => $status
            ]);

            return redirect()->route('suppliers.index')
                ->with('success', "Fornecedor {$status} com sucesso!");

        } catch (\Exception $e) {
            Log::error('Erro ao alterar status do fornecedor', [
                'error' => $e->getMessage(),
                'id' => $supplier->id
            ]);

            return back()->with('error', 'Erro ao alterar status do fornecedor. Por favor, tente novamente.');
        }
    }

    public function findByCNPJ($cnpj)
    {
        try {
            $supplier = Supplier::where('cnpj', $cnpj)->first();
            return response()->json($supplier);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar fornecedor por CNPJ', [
                'error' => $e->getMessage(),
                'cnpj' => $cnpj
            ]);
            return response()->json(['error' => 'Fornecedor não encontrado'], 404);
        }
    }

    public function report(Request $request)
    {
        try {
            $query = Supplier::query();

            // Filtro por data
            if ($request->filled('date_start')) {
                $query->whereDate('created_at', '>=', $request->date_start);
            }
            if ($request->filled('date_end')) {
                $query->whereDate('created_at', '<=', $request->date_end);
            }

            // Filtro por status
            if ($request->filled('status')) {
                $query->where('ativo', $request->status === 'ativo');
            }

            // Filtro por estado
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            // Ordenação
            $query->orderBy('razao_social');

            $suppliers = $query->get();

            // Exportação para Excel
            if ($request->has('export') && $request->export === 'excel') {
                return Excel::download(new SuppliersExport($suppliers), 'fornecedores.xlsx');
            }

            return view('suppliers.report', compact('suppliers'));
        } catch (\Exception $e) {
            Log::error('Erro ao gerar relatório de fornecedores: ' . $e->getMessage());
            return redirect()
                ->route('suppliers.index')
                ->with('error', 'Erro ao gerar relatório. Por favor, tente novamente.');
        }
    }

    public function regions()
    {
        try {
            $regions = Supplier::select('estado', DB::raw('count(*) as total'))
                ->groupBy('estado')
                ->orderBy('estado')
                ->get();

            return view('suppliers.regions', compact('regions'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar regiões de fornecedores: ' . $e->getMessage());
            return redirect()->route('suppliers.index')
                ->with('error', 'Erro ao carregar regiões de fornecedores. Por favor, tente novamente.');
        }
    }
} 
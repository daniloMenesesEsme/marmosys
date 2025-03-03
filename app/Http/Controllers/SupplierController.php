<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('nome')
            ->paginate(10);
        
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf_cnpj' => 'nullable|string|max:18|unique:suppliers',
            'endereco' => 'nullable|string',
            'ativo' => 'boolean'
        ]);

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf_cnpj' => 'nullable|string|max:18|unique:suppliers,cpf_cnpj,' . $supplier->id,
            'endereco' => 'nullable|string',
            'ativo' => 'boolean'
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Fornecedor excluído com sucesso!');
    }
} 
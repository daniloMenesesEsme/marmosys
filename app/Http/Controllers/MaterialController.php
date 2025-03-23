<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with('category')->where('ativo', true)->paginate(10);
        return view('stock.materials.index', compact('materials'));
    }

    public function create()
    {
        $categories = MaterialCategory::all();
        return view('stock.materials.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:material_categories,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'unidade_medida' => 'required|in:m2,unidade,kg,litro',
            'estoque_minimo' => 'required|numeric|min:0',
            'estoque_atual' => 'required|numeric|min:0',
            'preco_custo' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0'
        ]);

        Material::create($validated);
        return redirect()->route('stock.materials.index')->with('success', 'Material cadastrado com sucesso!');
    }

    public function edit(Material $material)
    {
        $categories = MaterialCategory::all();
        return view('stock.materials.edit', compact('material', 'categories'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:material_categories,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'unidade_medida' => 'required|in:m2,unidade,kg,litro',
            'estoque_minimo' => 'required|numeric|min:0',
            'preco_custo' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0'
        ]);

        $material->update($validated);
        return redirect()->route('stock.materials.index')->with('success', 'Material atualizado com sucesso!');
    }

    public function destroy(Material $material)
    {
        $material->update(['ativo' => false]);
        return redirect()->route('stock.materials.index')->with('success', 'Material desativado com sucesso!');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $materiais = Material::where('nome', 'like', "%{$query}%")
            ->orWhere('codigo', 'like', "%{$query}%")
            ->get();
        
        return response()->json($materiais);
    }
} 
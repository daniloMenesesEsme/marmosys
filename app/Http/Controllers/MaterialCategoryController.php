<?php

namespace App\Http\Controllers;

use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class MaterialCategoryController extends Controller
{
    public function index()
    {
        $categories = MaterialCategory::withCount('materials')->get();
        return view('stock.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('stock.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:marmore,granito,insumo,consumo',
            'descricao' => 'nullable|string'
        ]);

        MaterialCategory::create($validated);
        return redirect()->route('stock.categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(MaterialCategory $category)
    {
        return view('stock.categories.edit', compact('category'));
    }

    public function update(Request $request, MaterialCategory $category)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:marmore,granito,insumo,consumo',
            'descricao' => 'nullable|string'
        ]);

        $category->update($validated);
        return redirect()->route('stock.categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }
} 
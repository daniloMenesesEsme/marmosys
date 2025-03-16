<?php

namespace App\Http\Controllers;

use App\Models\FinancialCategory;
use Illuminate\Http\Request;

class FinancialCategoryController extends Controller
{
    public function index()
    {
        $categories = FinancialCategory::orderBy('nome')->paginate(10);
        return view('financial.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('financial.categories.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|max:255',
            'tipo' => 'required|in:analitica,sintetica',
            'natureza' => 'required|in:receita,despesa',
            'codigo_contabil_externo' => 'nullable|max:20',
            'cor' => 'nullable|max:7',
            'icone' => 'nullable|max:50',
            'ativo' => 'boolean'
        ]);

        // Se não foi enviado, define como true
        $validated['ativo'] = $request->has('ativo');

        FinancialCategory::create($validated);

        return redirect()->route('financial.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(FinancialCategory $category)
    {
        return view('financial.categories.form', compact('category'));
    }

    public function update(Request $request, FinancialCategory $category)
    {
        $validated = $request->validate([
            'nome' => 'required|max:255',
            'tipo' => 'required|in:analitica,sintetica',
            'natureza' => 'required|in:receita,despesa',
            'codigo_contabil_externo' => 'nullable|max:20',
            'cor' => 'nullable|max:7',
            'icone' => 'nullable|max:50',
            'ativo' => 'boolean'
        ]);

        // Se não foi enviado, define como false
        $validated['ativo'] = $request->has('ativo');

        $category->update($validated);

        return redirect()->route('financial.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(FinancialCategory $category)
    {
        $category->ativo = !$category->ativo;
        $category->save();

        $status = $category->ativo ? 'ativada' : 'inativada';
        return redirect()->route('financial.categories.index')
            ->with('success', "Categoria {$status} com sucesso!");
    }
} 
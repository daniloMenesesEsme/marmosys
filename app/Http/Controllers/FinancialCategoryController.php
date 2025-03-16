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
            'tipo' => 'required|in:receita,despesa',
            'descricao' => 'nullable'
        ]);

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
            'tipo' => 'required|in:receita,despesa',
            'descricao' => 'nullable'
        ]);

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
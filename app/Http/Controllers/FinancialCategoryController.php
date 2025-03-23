<?php

namespace App\Http\Controllers;

use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        try {
            $validated = $request->validate([
                'nome' => 'required|max:255',
                'tipo' => 'required|in:ANALITICA,SINTETICA',
                'natureza' => 'required|in:receita,despesa',
                'cor' => 'nullable|max:7',
                'icone' => 'nullable|max:50',
                'descricao' => 'nullable',
                'ativo' => 'boolean'
            ]);

            // Define valores padrão
            $validated['cor'] = $validated['cor'] ?? '#2196F3';
            $validated['icone'] = $validated['icone'] ?? 'attach_money';
            $validated['ativo'] = $validated['ativo'] ?? true;

            $category = FinancialCategory::create($validated);
            
            Log::info('Categoria financeira criada com sucesso', [
                'id' => $category->id,
                'nome' => $category->nome
            ]);
            
            return redirect('/financial/categories')
                ->with('success', 'Categoria criada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao criar categoria financeira', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'trace' => $e->getTraceAsString(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar categoria. Por favor, tente novamente.');
        }
    }

    public function edit(FinancialCategory $category)
    {
        return view('financial.categories.form', compact('category'));
    }

    public function update(Request $request, FinancialCategory $category)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|max:255',
                'tipo' => 'required|in:ANALITICA,SINTETICA',
                'natureza' => 'required|in:receita,despesa',
                'descricao' => 'nullable',
                'cor' => 'nullable|max:7',
                'icone' => 'nullable|max:50',
                'ativo' => 'boolean'
            ]);

            // Define valores padrão para cor e icone se não fornecidos
            $validated['cor'] = $validated['cor'] ?? '#2196F3';
            $validated['icone'] = $validated['icone'] ?? 'attach_money';
            $validated['ativo'] = $validated['ativo'] ?? true;

            $category->update($validated);

            Log::info('Categoria financeira atualizada com sucesso', [
                'id' => $category->id,
                'nome' => $category->nome
            ]);

            return redirect('/financial/categories')
                ->with('success', 'Categoria atualizada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar categoria financeira', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'trace' => $e->getTraceAsString(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar categoria. Por favor, tente novamente.');
        }
    }

    public function destroy(FinancialCategory $category)
    {
        try {
            $category->ativo = !$category->ativo;
            $category->save();

            $status = $category->ativo ? 'ativada' : 'inativada';
            
            Log::info('Status da categoria financeira alterado', [
                'id' => $category->id,
                'nome' => $category->nome,
                'status' => $status
            ]);

            return redirect('/financial/categories')
                ->with('success', "Categoria {$status} com sucesso!");

        } catch (\Exception $e) {
            Log::error('Erro ao alterar status da categoria financeira', [
                'error' => $e->getMessage(),
                'id' => $category->id
            ]);

            return back()->with('error', 'Erro ao alterar status da categoria. Por favor, tente novamente.');
        }
    }
} 
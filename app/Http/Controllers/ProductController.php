<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('nome')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|unique:products|max:50',
            'nome' => 'required|max:255',
            'descricao' => 'nullable',
            'preco_venda' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0'
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
                        ->with('success', 'Produto cadastrado com sucesso!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'codigo' => 'required|max:50|unique:products,codigo,' . $product->id,
            'nome' => 'required|max:255',
            'descricao' => 'nullable',
            'preco_venda' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'ativo' => 'boolean'
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
                        ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        $product->ativo = false;
        $product->save();

        return redirect()->route('products.index')
                        ->with('success', 'Produto desativado com sucesso!');
    }

    public function ajustarEstoque(Request $request, Product $product)
    {
        $request->validate([
            'quantidade' => 'required|numeric',
            'tipo_movimento' => 'required|in:entrada,saida',
            'observacao' => 'required'
        ]);

        $quantidade = $request->quantidade;
        if ($request->tipo_movimento === 'saida') {
            $quantidade = -$quantidade;
        }

        $product->estoque_atual += $quantidade;
        $product->save();

        // Registra o movimento no histórico
        $product->movimentos()->create([
            'tipo' => $request->tipo_movimento,
            'quantidade' => abs($quantidade),
            'observacao' => $request->observacao,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('products.show', $product)
            ->with('success', 'Estoque ajustado com sucesso!');
    }
} 
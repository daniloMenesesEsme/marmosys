<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Enums\ProductType;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por busca
        if ($request->filled('busca')) {
            $search = $request->busca;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('ativo', $request->status);
        }

        $products = $query->orderBy('nome')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // Cria um produto vazio para o formulário
        $product = new Product();
        
        return view('products.form', compact('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => ['required', 'string', Rule::in(array_column(ProductType::cases(), 'value'))],
            'codigo' => 'required|string|unique:products,codigo',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco_custo' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque' => 'required|numeric|min:0',
            'estoque_minimo' => 'required|numeric|min:0',
            'unidade_medida' => 'required|string',
            'categoria' => 'nullable|string|max:255',
            'fornecedor' => 'nullable|string|max:255',
        ]);

        try {
            $product = Product::create($validated);
            
            return redirect()
                ->route('products.show', $product)
                ->with('success', 'Produto cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao cadastrar produto: ' . $e->getMessage()]);
        }
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
            'tipo' => ['required', 'string', Rule::in(array_column(ProductType::cases(), 'value'))],
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco_custo' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque' => 'required|numeric|min:0',
            'estoque_minimo' => 'required|numeric|min:0',
            'unidade_medida' => 'required|string',
            'categoria' => 'nullable|string|max:255',
            'fornecedor' => 'nullable|string|max:255',
        ]);

        try {
            // Atualiza o status ativo/inativo
            $validated['ativo'] = $request->has('ativo');
            
            $product->update($validated);
            
            return redirect()
                ->route('products.index')
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
        }
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
        $validated = $request->validate([
            'tipo_movimento' => 'required|in:entrada,saida',
            'quantidade' => 'required|numeric|min:0.01',
            'observacao' => 'required|string'
        ]);

        try {
            // Verifica se tem estoque suficiente para saída
            if ($validated['tipo_movimento'] === 'saida' && $product->estoque < $validated['quantidade']) {
                return redirect()
                    ->back()
                    ->withErrors(['error' => 'Estoque insuficiente para esta saída.']);
            }

            $product->ajustarEstoque(
                $validated['quantidade'],
                $validated['tipo_movimento'],
                $validated['observacao']
            );

            return redirect()
                ->back()
                ->with('success', 'Estoque ajustado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Erro ao ajustar estoque: ' . $e->getMessage()]);
        }
    }

    public function generateCode(string $type)
    {
        try {
            // Validação do tipo
            if (!in_array($type, array_column(ProductType::cases(), 'value'))) {
                throw new \InvalidArgumentException('Tipo de produto inválido');
            }

            $code = Product::generateNextCode($type);
            return response($code, 200);
        } catch (\Exception $e) {
            return response('Erro ao gerar código: ' . $e->getMessage(), 500);
        }
    }
} 
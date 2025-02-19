<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with(['material', 'user'])
            ->latest()
            ->paginate(15);
        return view('stock.movements.index', compact('movements'));
    }

    public function create()
    {
        $materials = Material::where('ativo', true)->get();
        return view('stock.movements.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'tipo' => 'required|in:entrada,saida',
            'quantidade' => 'required|numeric|min:0.01',
            'valor_unitario' => 'required|numeric|min:0',
            'numero_nota' => 'nullable|string',
            'fornecedor' => 'nullable|string',
            'observacao' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $material = Material::findOrFail($validated['material_id']);
            
            if ($validated['tipo'] === 'saida' && $validated['quantidade'] > $material->estoque_atual) {
                throw new \Exception('Quantidade insuficiente em estoque.');
            }

            $movimento = new StockMovement($validated);
            $movimento->user_id = auth()->id();
            $movimento->save();

            $quantidade = $validated['quantidade'] * ($validated['tipo'] === 'entrada' ? 1 : -1);
            $material->estoque_atual += $quantidade;
            $material->save();

            DB::commit();
            return redirect()->route('stock.movements.index')->with('success', 'Movimentação registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao registrar movimentação: ' . $e->getMessage());
        }
    }
} 
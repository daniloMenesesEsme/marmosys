<?php

namespace App\Http\Controllers;

use App\Models\CostCenter;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    public function index()
    {
        $costCenters = CostCenter::orderBy('nome')->get();
        return view('financial.cost-centers.index', compact('costCenters'));
    }

    public function create()
    {
        return view('financial.cost-centers.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|max:255|unique:cost_centers',
            'descricao' => 'nullable|max:1000',
            'ativo' => 'boolean'
        ]);

        CostCenter::create($validated);

        return redirect()
            ->route('financial.cost-centers.index')
            ->with('success', 'Centro de Custo criado com sucesso!');
    }

    public function edit(CostCenter $costCenter)
    {
        return view('financial.cost-centers.form', compact('costCenter'));
    }

    public function update(Request $request, CostCenter $costCenter)
    {
        $validated = $request->validate([
            'nome' => 'required|max:255|unique:cost_centers,nome,' . $costCenter->id,
            'descricao' => 'nullable|max:1000',
            'ativo' => 'boolean'
        ]);

        $costCenter->update($validated);

        return redirect()
            ->route('financial.cost-centers.index')
            ->with('success', 'Centro de Custo atualizado com sucesso!');
    }

    public function destroy(CostCenter $costCenter)
    {
        if ($costCenter->financial_accounts()->exists()) {
            return redirect()
                ->route('financial.cost-centers.index')
                ->with('error', 'Este Centro de Custo possui contas vinculadas e não pode ser excluído.');
        }

        $costCenter->delete();

        return redirect()
            ->route('financial.cost-centers.index')
            ->with('success', 'Centro de Custo excluído com sucesso!');
    }
} 
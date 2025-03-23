<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\BudgetRoom;
use App\Models\BudgetItem;
use PDF;
use App\Models\Company;
use App\Models\Material;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('Dados recebidos no store:', [
            'todos_os_dados' => $request->all(),
            'observacoes' => $request->observacoes
        ]);
        \Log::info('=== INÍCIO DO STORE ===');
        \Log::info('Dados recebidos:', $request->all());

        try {
            \DB::beginTransaction();

            // Criar orçamento usando Query Builder
            $budgetId = \DB::table('budgets')->insertGetId([
                'numero' => $request->numero,
                'data' => $request->data,
                'previsao_entrega' => $request->previsao_entrega,
                'client_id' => $request->client_id,
                'status' => 'aguardando_aprovacao',
                'valor_total' => 0,
                'desconto' => 0,
                'valor_final' => 0,
                'data_validade' => now()->addDays(30),
                'observacoes' => $request->observacoes,
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log para debug após inserção
            \Log::info('Orçamento criado:', [
                'id' => $budgetId,
                'observacoes' => \DB::table('budgets')->where('id', $budgetId)->value('observacoes')
            ]);

            $budget = Budget::findOrFail($budgetId);

            // Processar ambientes e itens
            if ($request->has('rooms')) {
                foreach ($request->rooms as $roomData) {
                    $room = $budget->rooms()->create([
                        'nome' => $roomData['nome']
                    ]);

                    foreach ($roomData['items'] as $itemData) {
                        $material = Material::findOrFail($itemData['material_id']);
                        
                        $room->items()->create([
                            'material_id' => $itemData['material_id'],
                            'quantidade' => $itemData['quantidade'],
                            'unidade' => $itemData['unidade'],
                            'largura' => $itemData['largura'],
                            'altura' => $itemData['altura'],
                            'valor_unitario' => $material->preco_venda,
                            'valor_total' => $material->preco_venda * $itemData['quantidade'],
                            'descricao' => $material->nome
                        ]);
                    }
                }
            }

            $budget->recalcularTotal();

            \DB::commit();

            return redirect()->route('financial.budgets.show', $budget)
                ->with('success', 'Orçamento criado com sucesso!');

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Erro ao salvar orçamento:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erro ao salvar orçamento: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Budget $budget)
    {
        \Log::info('Dados recebidos para atualização:', $request->all());

        try {
            \DB::beginTransaction();

            // Atualizar dados básicos
            $budget->data = $request->data;
            $budget->previsao_entrega = $request->previsao_entrega;
            $budget->client_id = $request->client_id;
            $budget->data_validade = $request->data_validade;
            $budget->observacoes = $request->observacoes;
            $budget->save();

            \Log::info('Orçamento atualizado:', $budget->fresh()->toArray());

            // Atualizar ambientes e itens
            if ($request->has('rooms')) {
                $budget->rooms()->delete();
                foreach ($request->rooms as $roomData) {
                    $room = $budget->rooms()->create([
                        'nome' => $roomData['nome'],
                        'valor_total' => 0
                    ]);

                    if (isset($roomData['items'])) {
                        foreach ($roomData['items'] as $itemData) {
                            $room->items()->create($itemData);
                        }
                    }
                }
            }

            \DB::commit();
            return redirect()->route('financial.budgets.show', $budget)
                ->with('success', 'Orçamento atualizado com sucesso!');

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Erro ao atualizar orçamento:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erro ao atualizar orçamento: ' . $e->getMessage());
        }
    }

    public function show(Budget $budget)
    {
        $budget->load(['rooms.items', 'client']);
        
        \Log::info('Dados do orçamento no show:', [
            'id' => $budget->id,
            'observacoes' => $budget->observacoes,
            'dados_completos' => $budget->toArray()
        ]);
        
        return view('financial.budgets.show', compact('budget'));
    }

    public function generatePdf(Budget $budget)
    {
        // Carrega todas as relações necessárias
        $budget->load(['rooms.items.material', 'client']);
        
        // Log para debug
        \Log::info('Gerando PDF:', [
            'budget_id' => $budget->id,
            'observacoes' => $budget->observacoes,
            'dados_completos' => $budget->toArray()
        ]);

        $company = Company::first();
        
        $pdf = PDF::loadView('financial.budgets.pdf', [
            'budget' => $budget,
            'company' => $company
        ]);
        
        return $pdf->stream('orcamento-' . $budget->numero . '.pdf');
    }
}
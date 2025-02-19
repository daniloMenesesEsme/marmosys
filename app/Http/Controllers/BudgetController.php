<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Client;
use App\Models\Material; // Descomente ou adicione esta linha no topo
// use App\Models\Material; // Comentar temporariamente
use App\Models\FinancialCategory;
use App\Models\BudgetMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF; // Adicione no topo do arquivo (requer laravel-dompdf)

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $budgets = Budget::with(['client', 'rooms.items'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        $clients = Client::orderBy('nome')->get();
        $materiais = BudgetMaterial::where('ativo', true)
            ->orderBy('nome')
            ->get();
        
        return view('financial.budgets.create', compact('clients', 'materiais'));
    }

    public function store(Request $request)
    {
        // Adicione este log para debug
        \Log::info('Dados recebidos:', $request->all());

        DB::beginTransaction();
        try {
            // Converter a data do formato brasileiro para o formato do banco
            $previsao_entrega = Carbon::createFromFormat('d/m/Y', $request->previsao_entrega)->format('Y-m-d');
            
            $budget = Budget::create([
                'client_id' => $request->client_id,
                'status' => 'aguardando_aprovacao',
                'previsao_entrega' => $previsao_entrega,
                'valor_total' => 0,
                'numero' => 'ORC-' . date('Y') . str_pad(Budget::count() + 1, 5, '0', STR_PAD_LEFT),
                'user_id' => auth()->id()
            ]);

            $valorTotal = 0;

            foreach ($request->rooms as $roomData) {
                $room = $budget->rooms()->create([
                    'nome' => $roomData['nome'],
                    'valor_total' => 0
                ]);

                $valorAmbiente = 0;
                foreach ($roomData['items'] as $itemData) {
                    $valorItem = $itemData['quantidade'] * $itemData['valor_unitario'];
                    $valorAmbiente += $valorItem;

                    $room->items()->create([
                        'material_id' => $itemData['material_id'],
                        'largura' => $itemData['largura'],
                        'altura' => $itemData['altura'],
                        'quantidade' => $itemData['quantidade'],
                        'valor_unitario' => $itemData['valor_unitario'],
                        'valor_total' => $valorItem
                    ]);
                }

                $room->update(['valor_total' => $valorAmbiente]);
                $valorTotal += $valorAmbiente;
            }

            $budget->update([
                'valor_total' => $valorTotal,
                'valor_final' => $valorTotal
            ]);
            
            DB::commit();
            return redirect()->route('financial.budgets.show', $budget)
                ->with('success', 'Orçamento criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Erro ao criar orçamento: ' . $e->getMessage());
            return back()->with('error', 'Erro ao criar orçamento: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Budget $budget)
    {
        $budget->load(['client', 'rooms.items.material']);
        return view('budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $clients = Client::where('ativo', true)->orderBy('nome')->get();
        $budget->load('items');
        return view('budgets.form', compact('budget', 'clients'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|exists:financial_categories,id',
            'valor' => 'required|numeric|min:0',
        ]);

        $budget->update($validated);

        return redirect()->route('financial.budgets.index', [
            'mes' => $budget->mes,
            'ano' => $budget->ano
        ])->with('success', 'Orçamento atualizado com sucesso!');
    }

    public function destroy(Budget $budget)
    {
        $mes = $budget->mes;
        $ano = $budget->ano;
        
        $budget->delete();

        return redirect()->route('financial.budgets.index', [
            'mes' => $mes,
            'ano' => $ano
        ])->with('success', 'Orçamento excluído com sucesso!');
    }

    public function copy(Request $request)
    {
        $request->validate([
            'mes_origem' => 'required|integer|between:1,12',
            'ano_origem' => 'required|integer|min:2000',
            'mes_destino' => 'required|integer|between:1,12',
            'ano_destino' => 'required|integer|min:2000',
        ]);

        $orcamentos = Budget::where('mes', $request->mes_origem)
            ->where('ano', $request->ano_origem)
            ->get();

        foreach ($orcamentos as $orcamento) {
            Budget::updateOrCreate(
                [
                    'categoria_id' => $orcamento->categoria_id,
                    'mes' => $request->mes_destino,
                    'ano' => $request->ano_destino
                ],
                [
                    'valor_limite' => $orcamento->valor_limite,
                    'notificar_percentual' => $orcamento->notificar_percentual
                ]
            );
        }

        return redirect()
            ->route('financial.budgets.index', [
                'mes' => $request->mes_destino, 
                'ano' => $request->ano_destino
            ])
            ->with('success', 'Orçamentos copiados com sucesso!');
    }

    public function generatePdf(Budget $budget, Request $request)
    {
        $budget->load(['client', 'rooms.items.material']);
        $pdf = PDF::loadView('financial.budgets.pdf', compact('budget'));
        
        if ($request->preview) {
            return $pdf->stream('orcamento-' . $budget->numero . '.pdf');
        }
        
        return $pdf->download('orcamento-' . $budget->numero . '.pdf');
    }

    public function printView(Budget $budget)
    {
        $budget->load(['client', 'rooms.items.material']);
        return view('financial.budgets.print', compact('budget'));
    }
} 
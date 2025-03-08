<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Client;
use App\Models\BudgetMaterial;
use App\Models\BudgetRoom;
use App\Models\BudgetItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use App\Models\ApprovalLog;
use App\Models\CompanySetting;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Config;
use App\Models\Company;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::with(['client', 'rooms.items'])
            ->orderBy('created_at', 'desc');

        // Filtro de busca geral
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf_cnpj', 'like', "%{$search}%")
                        ->orWhere('telefone', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro de status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro de data
        if ($request->filled('data_inicio')) {
            $query->whereDate('data', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data', '<=', $request->data_fim);
        }

        $budgets = $query->paginate(10);
        
        return view('financial.budgets.index', compact('budgets'));
    }

    public function create()
    {
        $clients = Client::where('ativo', true)
            ->orderBy('nome')
            ->get();
            
        // Alterando para buscar da tabela products
        $materiais = Product::where('ativo', true)
            ->orderBy('nome')
            ->get();
        
        $numero = 'ORC-' . date('Y') . str_pad(Budget::count() + 1, 4, '0', STR_PAD_LEFT);
        
        return view('financial.budgets.create', compact('clients', 'materiais', 'numero'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Iniciando criação do orçamento com dados:', $request->all());

            $validated = $request->validate([
                'numero' => 'required|unique:budgets,numero',
                'data' => 'required|date',
                'previsao_entrega' => 'required|date',
                'client_id' => 'required|exists:clients,id',
                'rooms' => 'required|array|min:1',
                'rooms.*.nome' => 'required|string',
                'rooms.*.items' => 'required|array|min:1',
                'rooms.*.items.*.material_id' => 'required|exists:products,id',
                'rooms.*.items.*.quantidade' => 'required|numeric|min:0.01',
                'rooms.*.items.*.unidade' => 'required|string',
                'rooms.*.items.*.largura' => 'required|numeric|min:0',
                'rooms.*.items.*.altura' => 'required|numeric|min:0',
                'observacoes' => 'nullable|string|max:1000'
            ]);

            \Log::info('Dados validados:', $validated);

            DB::beginTransaction();

            // Cria o orçamento base
            $budget = Budget::create([
                'numero' => $request->numero,
                'data' => $request->data,
                'previsao_entrega' => $request->previsao_entrega,
                'client_id' => $request->client_id,
                'status' => 'aguardando_aprovacao',
                'observacoes' => $request->observacoes,
                'valor_total' => 0,
                'desconto' => 0,
                'valor_final' => 0,
                'data_validade' => now()->addDays(30),
                'user_id' => auth()->id()
            ]);

            \Log::info('Orçamento base criado:', $budget->toArray());

            $valorTotalOrcamento = 0;

            // Processa cada ambiente e seus itens
            foreach ($request->rooms as $index => $roomData) {
                \Log::info("Processando ambiente {$index}:", $roomData);
                
                $room = $budget->rooms()->create([
                    'nome' => $roomData['nome']
                ]);

                \Log::info("Ambiente criado:", $room->toArray());

                foreach ($roomData['items'] as $itemIndex => $itemData) {
                    \Log::info("Processando item {$itemIndex} do ambiente {$index}:", $itemData);
                    
                    $material = Product::findOrFail($itemData['material_id']);
                    \Log::info("Material encontrado:", $material->toArray());
                    
                    $area = $itemData['largura'] * $itemData['altura'];
                    $quantidade = $itemData['quantidade'];
                    $valorUnitario = $material->preco_venda;
                    $valorTotal = $quantidade * $area * $valorUnitario;
                    
                    $valorTotalOrcamento += $valorTotal;

                    $item = $room->items()->create([
                        'material_id' => $material->id,
                        'quantidade' => $quantidade,
                        'unidade' => $itemData['unidade'],
                        'largura' => $itemData['largura'],
                        'altura' => $itemData['altura'],
                        'valor_unitario' => $valorUnitario,
                        'valor_total' => $valorTotal,
                        'descricao' => $material->descricao ?? $material->nome
                    ]);

                    \Log::info("Item criado:", $item->toArray());
                }
            }

            // Atualiza os valores totais do orçamento
            $budget->update([
                'valor_total' => $valorTotalOrcamento,
                'valor_final' => $valorTotalOrcamento
            ]);

            \Log::info("Orçamento atualizado com valores totais:", [
                'valor_total' => $valorTotalOrcamento,
                'valor_final' => $valorTotalOrcamento
            ]);

            DB::commit();
            \Log::info('Orçamento salvo com sucesso');

            return redirect()
                ->route('financial.budgets.show', $budget)
                ->with('success', 'Orçamento criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Erro ao criar orçamento:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar orçamento: ' . $e->getMessage());
        }
    }

    public function show(Budget $budget)
    {
        $company = CompanySetting::first();
        $budget->load(['client', 'rooms.items.material']);
        return view('financial.budgets.show', compact('budget', 'company'));
    }

    public function edit(Budget $budget)
    {
        $clients = Client::where('ativo', true)
            ->orderBy('nome')
            ->get();
            
        // Alterado de BudgetMaterial para Product
        $materiais = Product::where('ativo', true)
            ->orderBy('nome')
            ->get();
            
        $budget->load(['rooms.items.material']);
        
        return view('financial.budgets.edit', compact('budget', 'clients', 'materiais'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'data' => 'required|date_format:Y-m-d',
            'rooms' => 'required|array',
            'rooms.*.nome' => 'required|string',
            'rooms.*.items' => 'required|array',
            'rooms.*.items.*.material_id' => 'required|exists:products,id',
            'rooms.*.items.*.quantidade' => 'required|numeric|min:0.001',
            'rooms.*.items.*.unidade' => 'required|string',
            'rooms.*.items.*.largura' => 'required|numeric|min:0',
            'rooms.*.items.*.altura' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Atualiza dados básicos do orçamento
            $budget->update([
                'client_id' => $request->client_id,
                'data' => $request->data,
                'data_validade' => Carbon::parse($request->data)->addDays(30)
            ]);

            // Remove todos os ambientes e itens antigos
            foreach ($budget->rooms as $room) {
                $room->items()->delete();
            }
            $budget->rooms()->delete();

            $valorTotalOrcamento = 0;

            // Cria os novos ambientes e itens
            foreach ($request->rooms as $roomData) {
                $valorTotalAmbiente = 0;
                
                $room = $budget->rooms()->create([
                    'nome' => $roomData['nome'],
                    'valor_total' => 0
                ]);

                foreach ($roomData['items'] as $itemData) {
                    $valorTotal = $itemData['quantidade'] * $itemData['valor_unitario'];
                    
                    $room->items()->create([
                        'material_id' => $itemData['material_id'],
                        'quantidade' => $itemData['quantidade'],
                        'unidade' => $itemData['unidade'],
                        'largura' => $itemData['largura'],
                        'altura' => $itemData['altura'],
                        'valor_unitario' => $itemData['valor_unitario'],
                        'valor_total' => $valorTotal
                    ]);

                    $valorTotalAmbiente += $valorTotal;
                }

                $room->update(['valor_total' => $valorTotalAmbiente]);
                $valorTotalOrcamento += $valorTotalAmbiente;
            }

            // Atualiza os valores totais do orçamento
            $budget->update([
                'valor_total' => $valorTotalOrcamento,
                'valor_final' => $valorTotalOrcamento - $budget->desconto
            ]);
            
            DB::commit();
            return redirect()
                ->route('financial.budgets.show', $budget)
                ->with('success', 'Orçamento atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar orçamento: ' . $e->getMessage());
        }
    }

    public function destroy(Budget $budget)
    {
        try {
            // Remove todos os relacionamentos
            foreach ($budget->rooms as $room) {
                $room->items()->delete();
            }
            $budget->rooms()->delete();
            
            // Remove o orçamento
            $budget->delete();
            
            return redirect()
                ->route('financial.budgets.index')
                ->with('success', 'Orçamento excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir orçamento: ' . $e->getMessage());
        }
    }

    public function generatePdf(Budget $budget)
    {
        try {
            \Log::info('Iniciando geração do PDF do orçamento #' . $budget->numero);

            // Carrega o orçamento com relacionamentos necessários
            $budget->load(['client', 'rooms.items.material']);
            
            // Busca dados da empresa
            $company = Company::first();
            
            // Log para debug
            \Log::info('Dados da empresa:', ['company' => $company->toArray()]);

            // Configura o PDF
            $pdf = PDF::loadView('financial.budgets.pdf', [
                'budget' => $budget,
                'company' => $company
            ]);

            $pdf->setPaper('A4');
            
            $filename = 'orcamento_' . $budget->numero . '.pdf';

            \Log::info('PDF gerado com sucesso');

            return $pdf->stream($filename);

        } catch (\Exception $e) {
            \Log::error('Erro ao gerar PDF:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erro ao gerar PDF do orçamento.');
        }
    }

    public function printView(Budget $budget)
    {
        $budget->load(['client', 'rooms.items.material']);
        return view('financial.budgets.print', compact('budget'));
    }

    public function approve(Request $request, Budget $budget)
    {
        if ($request->action === 'approve') {
            $budget->update([
                'status' => 'aprovado',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            // Registra o log de aprovação
            ApprovalLog::create([
                'budget_id' => $budget->id,
                'user_id' => auth()->id(),
                'action' => 'approve'
            ]);

            return redirect()
                ->route('financial.budgets.show', $budget)
                ->with('success', 'Orçamento aprovado com sucesso!');
        }

        $request->validate([
            'motivo_reprovacao' => 'required|string'
        ]);

        $budget->update([
            'status' => 'reprovado',
            'motivo_reprovacao' => $request->motivo_reprovacao
        ]);

        // Registra o log de rejeição
        ApprovalLog::create([
            'budget_id' => $budget->id,
            'user_id' => auth()->id(),
            'action' => 'reject',
            'motivo' => $request->motivo_reprovacao
        ]);

        return redirect()
            ->route('financial.budgets.show', $budget)
            ->with('success', 'Orçamento reprovado com sucesso!');
    }
} 
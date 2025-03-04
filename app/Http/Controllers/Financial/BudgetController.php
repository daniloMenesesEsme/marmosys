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

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $budgets = Budget::with(['client', 'rooms.items'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('financial.budgets.index', compact('budgets'));
    }

    public function create()
    {
        $clients = Client::where('ativo', true)
            ->orderBy('nome')
            ->get();
            
        $materiais = BudgetMaterial::where('ativo', true)
            ->orderBy('nome')
            ->get();
        
        // Número sequencial do orçamento
        $numero = 'ORC-' . date('Y') . str_pad(Budget::count() + 1, 4, '0', STR_PAD_LEFT);
        
        return view('financial.budgets.create', compact('clients', 'materiais', 'numero'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|unique:budgets,numero',
            'data' => 'required|date',
            'previsao_entrega' => 'required|date|after_or_equal:data',
            'client_id' => 'required|exists:clients,id',
            'rooms' => 'required|array|min:1',
            'rooms.*.nome' => 'required|string',
            'rooms.*.items' => 'required|array|min:1',
            'rooms.*.items.*.material_id' => 'required|exists:budget_materials,id',
            'rooms.*.items.*.quantidade' => 'required|numeric|min:0.01',
            'rooms.*.items.*.unidade' => 'required|string',
            'rooms.*.items.*.largura' => 'required|numeric|min:0',
            'rooms.*.items.*.altura' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            \Log::info('Iniciando criação do orçamento');
            
            // Cria o orçamento
            $budget = Budget::create([
                'numero' => $request->numero,
                'data' => $request->data,
                'previsao_entrega' => $request->previsao_entrega,
                'client_id' => $request->client_id,
                'status' => 'aguardando_aprovacao',
                'valor_total' => 0,
                'desconto' => 0,
                'valor_final' => 0,
                'data_validade' => Carbon::parse($request->data)->addDays(30),
                'user_id' => auth()->id()
            ]);

            \Log::info('Orçamento base criado:', $budget->toArray());

            $valorTotalOrcamento = 0;

            foreach ($request->rooms as $roomData) {
                \Log::info('Processando ambiente:', $roomData);
                $valorTotalAmbiente = 0;
                
                $room = $budget->rooms()->create([
                    'nome' => $roomData['nome'],
                    'valor_total' => 0
                ]);

                foreach ($roomData['items'] as $itemData) {
                    $material = BudgetMaterial::findOrFail($itemData['material_id']);
                    
                    // Calcula valor do item considerando quantidade e dimensões
                    $area = $itemData['largura'] * $itemData['altura'];
                    $quantidade = $itemData['quantidade'];
                    $valorUnitario = $material->preco_venda;
                    $valorItem = $quantidade * $area * $valorUnitario;

                    $room->items()->create([
                        'material_id' => $itemData['material_id'],
                        'quantidade' => $quantidade,
                        'unidade' => $itemData['unidade'],
                        'descricao' => $material->nome,
                        'largura' => $itemData['largura'],
                        'altura' => $itemData['altura'],
                        'valor_unitario' => $valorUnitario,
                        'valor_total' => $valorItem
                    ]);

                    $valorTotalAmbiente += $valorItem;
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
            \Log::info('Orçamento salvo com sucesso');
            
            return redirect()
                ->route('financial.budgets.show', $budget)
                ->with('success', 'Orçamento criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao criar orçamento:', [
                'message' => $e->getMessage(),
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
            
        $materiais = BudgetMaterial::where('ativo', true)
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
            'rooms.*.items.*.material_id' => 'required|exists:budget_materials,id',
            'rooms.*.items.*.quantidade' => 'required|numeric|min:0.001',
            'rooms.*.items.*.unidade' => 'required|string',
            'rooms.*.items.*.descricao' => 'required|string',
            'rooms.*.items.*.largura' => 'required|numeric|min:0',
            'rooms.*.items.*.altura' => 'required|numeric|min:0',
            'rooms.*.items.*.valor_unitario' => 'required|numeric|min:0'
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
                        'descricao' => $itemData['descricao'],
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
            // Busca as configurações da empresa
            $company = CompanySetting::first();
            
            if (!$company) {
                // Se não existir configuração, cria uma padrão
                $company = CompanySetting::create([
                    'nome_empresa' => 'MarmoSys',
                    'cnpj' => '00.000.000/0001-00',
                    'endereco' => 'Endereço da Empresa',
                    'telefone' => '(00) 0000-0000',
                    'email' => 'contato@empresa.com',
                    'observacoes_orcamento' => 'Orçamento válido por 15 dias.'
                ]);
            }

            // Carrega a view do PDF com os dados
            $pdf = PDF::loadView('financial.budgets.pdf', compact('budget', 'company'));

            // Retorna o PDF para download
            return $pdf->stream('orcamento-' . $budget->id . '.pdf');

        } catch (\Exception $e) {
            // Log do erro
            \Log::error('Erro ao gerar PDF: ' . $e->getMessage());
            
            // Retorna para a página anterior com mensagem de erro
            return back()->with('error', 'Erro ao gerar PDF do orçamento. Por favor, tente novamente.');
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
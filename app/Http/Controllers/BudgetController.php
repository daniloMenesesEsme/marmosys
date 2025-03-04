<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use PDF;
use App\Models\CompanySetting;

class BudgetController extends Controller
{
    public function approve(Request $request, Budget $budget)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'motivo_reprovacao' => 'required_if:action,reject'
        ]);

        if ($request->action === 'approve') {
            $budget->update([
                'status' => 'aprovado',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            return redirect()
                ->route('budgets.show', $budget)
                ->with('success', 'Orçamento aprovado com sucesso!');
        }

        $budget->update([
            'status' => 'reprovado',
            'motivo_reprovacao' => $request->motivo_reprovacao
        ]);

        return redirect()
            ->route('budgets.show', $budget)
            ->with('success', 'Orçamento reprovado com sucesso!');
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
} 
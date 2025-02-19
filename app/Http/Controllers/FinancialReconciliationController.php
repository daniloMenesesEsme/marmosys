<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use App\Models\FinancialCategory;
use App\Services\OFXParser;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialReconciliationController extends Controller
{
    public function index()
    {
        $contas = FinancialAccount::with('category')
            ->where('status', 'pendente')
            ->where('data_vencimento', '<=', now()->addDays(30))
            ->orderBy('data_vencimento')
            ->get()
            ->groupBy('category.tipo');

        $totais = [
            'receitas' => $contas->get('receita', collect())->sum('valor'),
            'despesas' => $contas->get('despesa', collect())->sum('valor')
        ];

        $formasPagamento = [
            'Dinheiro',
            'PIX',
            'Cartão de Crédito',
            'Cartão de Débito',
            'Transferência',
            'Boleto',
            'Cheque'
        ];

        return view('financial.reconciliation.index', compact(
            'contas',
            'totais',
            'formasPagamento'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'contas' => 'required|array',
            'contas.*' => 'exists:financial_accounts,id',
            'data_pagamento' => 'required|date',
            'forma_pagamento' => 'required|string'
        ]);

        $dataPagamento = Carbon::parse($request->data_pagamento);

        FinancialAccount::whereIn('id', $request->contas)
            ->update([
                'status' => 'pago',
                'data_pagamento' => $dataPagamento,
                'forma_pagamento' => $request->forma_pagamento
            ]);

        return redirect()
            ->route('financial.reconciliation.index')
            ->with('success', 'Contas conciliadas com sucesso!');
    }

    public function importForm()
    {
        $categories = FinancialCategory::where('ativo', true)
            ->orderBy('nome')
            ->get()
            ->groupBy('tipo');

        return view('financial.reconciliation.import', compact('categories'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'arquivo_ofx' => 'required|file|mimes:ofx',
            'categoria_receita' => 'required|exists:financial_categories,id',
            'categoria_despesa' => 'required|exists:financial_categories,id',
        ]);

        try {
            // Lê o arquivo OFX
            $content = file_get_contents($request->file('arquivo_ofx')->path());
            $parser = new OFXParser($content);
            $transactions = $parser->getTransactions();

            // Categorias selecionadas
            $categoriaReceita = FinancialCategory::find($request->categoria_receita);
            $categoriaDespesa = FinancialCategory::find($request->categoria_despesa);

            // Importa as transações
            foreach ($transactions as $transaction) {
                FinancialAccount::create([
                    'category_id' => $transaction['tipo'] === 'receita' 
                        ? $categoriaReceita->id 
                        : $categoriaDespesa->id,
                    'descricao' => $transaction['descricao'],
                    'valor' => $transaction['valor'],
                    'data_vencimento' => $transaction['data'],
                    'data_pagamento' => $transaction['data'],
                    'status' => 'pago',
                    'documento' => $transaction['documento'],
                    'forma_pagamento' => 'Transferência',
                ]);
            }

            return redirect()
                ->route('financial.reconciliation.index')
                ->with('success', count($transactions) . ' transações importadas com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao processar arquivo: ' . $e->getMessage());
        }
    }
} 
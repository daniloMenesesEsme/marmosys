<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashFlowProjectionController extends Controller
{
    public function index(Request $request)
    {
        // Período de projeção (padrão: 6 meses)
        $meses = (int) ($request->meses ?? 6);
        
        // Data inicial
        $dataInicial = Carbon::now()->startOfMonth();
        
        // Saldo atual
        $saldoAtual = FinancialAccount::whereHas('category', function($query) {
                $query->where('tipo', 'receita');
            })
            ->where('status', 'pago')
            ->sum('valor') -
            FinancialAccount::whereHas('category', function($query) {
                $query->where('tipo', 'despesa');
            })
            ->where('status', 'pago')
            ->sum('valor');

        // Buscar projeção mês a mês
        $projecao = collect();
        $saldoProjetado = $saldoAtual;

        for ($i = 0; $i < $meses; $i++) {
            $data = $dataInicial->copy()->addMonths($i);
            
            // Receitas previstas
            $receitasPrevistas = FinancialAccount::whereHas('category', function($query) {
                    $query->where('tipo', 'receita');
                })
                ->whereYear('data_vencimento', $data->year)
                ->whereMonth('data_vencimento', $data->month)
                ->where('status', 'pendente')
                ->sum('valor');

            // Despesas previstas
            $despesasPrevistas = FinancialAccount::whereHas('category', function($query) {
                    $query->where('tipo', 'despesa');
                })
                ->whereYear('data_vencimento', $data->year)
                ->whereMonth('data_vencimento', $data->month)
                ->where('status', 'pendente')
                ->sum('valor');

            // Calcular saldo projetado
            $saldoProjetado += ($receitasPrevistas - $despesasPrevistas);

            // Adicionar ao array de projeção
            $projecao->push([
                'mes' => $data->format('m'),
                'ano' => $data->format('Y'),
                'nome' => $data->format('F/Y'),
                'receitas' => $receitasPrevistas,
                'despesas' => $despesasPrevistas,
                'saldo_mes' => $receitasPrevistas - $despesasPrevistas,
                'saldo_projetado' => $saldoProjetado
            ]);
        }

        // Buscar detalhes por categoria
        $categorias = DB::table('financial_accounts as fa')
            ->join('financial_categories as fc', 'fa.category_id', '=', 'fc.id')
            ->whereDate('fa.data_vencimento', '>=', $dataInicial)
            ->whereDate('fa.data_vencimento', '<=', $dataInicial->copy()->addMonths($meses))
            ->where('fa.status', 'pendente')
            ->select(
                'fc.nome',
                'fc.tipo',
                DB::raw('COUNT(*) as total_contas'),
                DB::raw('SUM(fa.valor) as valor_total')
            )
            ->groupBy('fc.id', 'fc.nome', 'fc.tipo')
            ->orderBy('fc.tipo')
            ->orderBy('valor_total', 'desc')
            ->get();

        return view('financial.projections.index', compact(
            'projecao',
            'categorias',
            'saldoAtual',
            'meses'
        ));
    }
} 
<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\FinancialExport;
use Maatwebsite\Excel\Facades\Excel;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $ano = $request->ano ?? date('Y');
        $mes = $request->mes ?? date('m');

        // Receitas e Despesas por Categoria
        $categorias = DB::table('financial_accounts as fa')
            ->join('financial_categories as fc', 'fa.category_id', '=', 'fc.id')
            ->whereYear('fa.data_vencimento', $ano)
            ->whereMonth('fa.data_vencimento', $mes)
            ->where('fa.status', 'pago')
            ->select('fc.nome', 'fc.tipo', DB::raw('SUM(fa.valor) as total'))
            ->groupBy('fc.id', 'fc.nome', 'fc.tipo')
            ->get();

        // Totais do Mês
        $totais = [
            'receitas' => $categorias->where('tipo', 'receita')->sum('total'),
            'despesas' => $categorias->where('tipo', 'despesa')->sum('total'),
            'saldo' => $categorias->where('tipo', 'receita')->sum('total') - 
                      $categorias->where('tipo', 'despesa')->sum('total')
        ];

        // Fluxo de Caixa Diário
        $fluxoDiario = DB::table('financial_accounts as fa')
            ->join('financial_categories as fc', 'fa.category_id', '=', 'fc.id')
            ->whereYear('fa.data_pagamento', $ano)
            ->whereMonth('fa.data_pagamento', $mes)
            ->where('fa.status', 'pago')
            ->select(
                'fa.data_pagamento',
                DB::raw('SUM(CASE WHEN fc.tipo = "receita" THEN fa.valor ELSE 0 END) as receitas'),
                DB::raw('SUM(CASE WHEN fc.tipo = "despesa" THEN fa.valor ELSE 0 END) as despesas')
            )
            ->groupBy('fa.data_pagamento')
            ->orderBy('fa.data_pagamento')
            ->get();

        return view('financial.reports.index', compact(
            'categorias', 
            'totais', 
            'fluxoDiario',
            'ano',
            'mes'
        ));
    }

    public function pdf(Request $request)
    {
        // Converte para inteiro para garantir que não tenha zero à esquerda
        $ano = (int) ($request->ano ?? date('Y'));
        $mes = (int) ($request->mes ?? date('m'));

        // Busca os dados
        $categorias = DB::table('financial_accounts as fa')
            ->join('financial_categories as fc', 'fa.category_id', '=', 'fc.id')
            ->whereYear('fa.data_vencimento', $ano)
            ->whereMonth('fa.data_vencimento', $mes)
            ->where('fa.status', 'pago')
            ->select('fc.nome', 'fc.tipo', DB::raw('SUM(fa.valor) as total'))
            ->groupBy('fc.id', 'fc.nome', 'fc.tipo')
            ->get();

        $totais = [
            'receitas' => $categorias->where('tipo', 'receita')->sum('total'),
            'despesas' => $categorias->where('tipo', 'despesa')->sum('total'),
            'saldo' => $categorias->where('tipo', 'receita')->sum('total') - 
                      $categorias->where('tipo', 'despesa')->sum('total')
        ];

        $fluxoDiario = DB::table('financial_accounts as fa')
            ->join('financial_categories as fc', 'fa.category_id', '=', 'fc.id')
            ->whereYear('fa.data_pagamento', $ano)
            ->whereMonth('fa.data_pagamento', $mes)
            ->where('fa.status', 'pago')
            ->select(
                'fa.data_pagamento',
                DB::raw('SUM(CASE WHEN fc.tipo = "receita" THEN fa.valor ELSE 0 END) as receitas'),
                DB::raw('SUM(CASE WHEN fc.tipo = "despesa" THEN fa.valor ELSE 0 END) as despesas')
            )
            ->groupBy('fa.data_pagamento')
            ->orderBy('fa.data_pagamento')
            ->get();

        $meses = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];

        $pdf = PDF::loadView('financial.reports.pdf', compact(
            'categorias', 
            'totais', 
            'fluxoDiario',
            'ano',
            'mes',
            'meses'
        ));

        return $pdf->download("relatorio-financeiro-{$meses[$mes]}-{$ano}.pdf");
    }

    public function excel(Request $request)
    {
        $ano = (int) ($request->ano ?? date('Y'));
        $mes = (int) ($request->mes ?? date('m'));
        
        $meses = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];

        return Excel::download(
            new FinancialExport($ano, $mes),
            "financeiro-{$meses[$mes]}-{$ano}.xlsx"
        );
    }
} 
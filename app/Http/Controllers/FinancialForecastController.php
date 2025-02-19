<?php

namespace App\Http\Controllers;

use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialForecastController extends Controller
{
    public function index(Request $request)
    {
        $meses = collect([
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março',
            4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
            7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro',
            10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ]);

        // Próximos 6 meses
        $periodos = collect();
        $dataInicial = Carbon::now()->startOfMonth();
        
        for ($i = 0; $i < 6; $i++) {
            $data = $dataInicial->copy()->addMonths($i);
            $periodos->push([
                'mes' => $data->month,
                'ano' => $data->year,
                'nome' => $meses[$data->month] . '/' . $data->year
            ]);
        }

        // Buscar contas futuras
        $previsao = $periodos->map(function ($periodo) {
            $receitas = FinancialAccount::whereHas('category', function ($query) {
                    $query->where('tipo', 'receita');
                })
                ->whereYear('data_vencimento', $periodo['ano'])
                ->whereMonth('data_vencimento', $periodo['mes'])
                ->where('status', 'pendente')
                ->sum('valor');

            $despesas = FinancialAccount::whereHas('category', function ($query) {
                    $query->where('tipo', 'despesa');
                })
                ->whereYear('data_vencimento', $periodo['ano'])
                ->whereMonth('data_vencimento', $periodo['mes'])
                ->where('status', 'pendente')
                ->sum('valor');

            return array_merge($periodo, [
                'receitas' => $receitas,
                'despesas' => $despesas,
                'saldo' => $receitas - $despesas
            ]);
        });

        // Contas vencidas
        $contasVencidas = FinancialAccount::with('category')
            ->where('data_vencimento', '<', now())
            ->where('status', 'pendente')
            ->orderBy('data_vencimento')
            ->get()
            ->groupBy('category.tipo');

        // Totais vencidos
        $totaisVencidos = [
            'receitas' => $contasVencidas->get('receita', collect())->sum('valor'),
            'despesas' => $contasVencidas->get('despesa', collect())->sum('valor')
        ];

        return view('financial.forecast.index', compact(
            'previsao',
            'contasVencidas',
            'totaisVencidos'
        ));
    }
} 
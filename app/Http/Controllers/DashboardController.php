<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Product;
use App\Models\FinancialAccount;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Totais
        $totalClientes = Client::where('ativo', true)->count();
        $totalProdutos = Product::where('ativo', true)->count();
        
        // Contas a Receber
        $contasReceber = FinancialAccount::where('tipo', 'receita')
                                        ->where('status', 'pendente')
                                        ->sum('valor');
        
        // Contas a Pagar
        $contasPagar = FinancialAccount::where('tipo', 'despesa')
                                      ->where('status', 'pendente')
                                      ->sum('valor');

        // Dados dos últimos 6 meses
        $ultimosMeses = collect([]);
        for ($i = 5; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i);
            $mes = $data->format('M/Y');
            
            $receitas = FinancialAccount::where('tipo', 'receita')
                ->whereYear('data_vencimento', $data->year)
                ->whereMonth('data_vencimento', $data->month)
                ->sum('valor');
                
            $despesas = FinancialAccount::where('tipo', 'despesa')
                ->whereYear('data_vencimento', $data->year)
                ->whereMonth('data_vencimento', $data->month)
                ->sum('valor');

            $ultimosMeses->push([
                'mes' => $mes,
                'receitas' => $receitas,
                'despesas' => $despesas
            ]);
        }

        return view('dashboard', compact(
            'totalClientes',
            'totalProdutos',
            'contasReceber',
            'contasPagar',
            'ultimosMeses'
        ));
    }
} 
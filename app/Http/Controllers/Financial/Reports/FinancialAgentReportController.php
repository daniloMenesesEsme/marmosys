<?php

namespace App\Http\Controllers\Financial\Reports;

use App\Http\Controllers\Controller;
use App\Models\FinancialAgent;
use App\Models\FinancialCategory;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialAgentExport;
use Illuminate\Support\Facades\Schema;
use App\Imports\FinancialAgentImport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Helpers\PdfHelper;

class FinancialAgentReportController extends Controller
{
    public function index(Request $request)
    {
        // Verificar se a tabela de transações existe
        $hasTransactionsTable = Schema::hasTable('financial_transactions');

        // Query base
        $query = FinancialAgent::query()
            ->with(['category', 'costCenter']);
        
        // Adicionar contagem e soma apenas se a tabela existir
        if ($hasTransactionsTable) {
            $query->withCount('transactions')
                  ->withSum('transactions', 'valor');
        }

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status == 'ativo');
        }
        
        if ($request->filled('financial_category_id')) {
            $query->where('financial_category_id', $request->financial_category_id);
        }
        
        if ($request->filled('cost_center_id')) {
            $query->where('cost_center_id', $request->cost_center_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $orderBy = $request->input('order_by', 'nome');
        $direction = $request->input('direction', 'asc');
        $query->orderBy($orderBy, $direction);
        
        // Executar a consulta
        $agents = $query->get();
        
        // Dados para os filtros
        $categories = FinancialCategory::orderBy('nome')->get();
        $costCenters = CostCenter::orderBy('nome')->get();
        
        // Totalizadores
        $totals = [
            'total_agents' => $agents->count(),
            'total_active' => $agents->where('status', true)->count(),
            'total_transactions' => $hasTransactionsTable ? $agents->sum('transactions_count') : 0,
            'total_value' => $hasTransactionsTable ? $agents->sum('transactions_sum_valor') : 0
        ];
        
        // Exportação
        if ($request->has('export')) {
            if ($request->export == 'pdf') {
                return $this->exportPdf($agents, $totals, $hasTransactionsTable);
            } elseif ($request->export == 'excel') {
                return $this->exportExcel($agents, $hasTransactionsTable);
            }
        }
        
        return view('financial.reports.agents.index', compact(
            'agents', 
            'categories', 
            'costCenters', 
            'totals',
            'hasTransactionsTable'
        ));
    }
    
    private function exportPdf($agents, $totals, $hasTransactionsTable)
    {
        // Criar dados para a view
        $data = [
            'agents' => $agents,
            'totals' => $totals,
            'hasTransactionsTable' => $hasTransactionsTable,
            'filters' => [
                'tipo' => request('tipo', 'Todos'),
                'status' => request('status', 'Todos'),
                'categoria' => request('financial_category_id') 
                    ? \App\Models\FinancialCategory::find(request('financial_category_id'))->nome 
                    : 'Todas',
                'centro_custo' => request('cost_center_id') 
                    ? \App\Models\CostCenter::find(request('cost_center_id'))->nome 
                    : 'Todos'
            ]
        ];
        
        // Usar o helper para gerar o PDF
        return PdfHelper::generate(
            'financial.reports.agents.pdf',
            $data,
            'relatorio_agentes_financeiros.pdf'
        );
    }
    
    private function exportExcel($agents, $hasTransactionsTable)
    {
        return Excel::download(new FinancialAgentExport($agents, $hasTransactionsTable), 'relatorio_agentes_financeiros.xlsx');
    }

    // Método para exibir o formulário de importação
    public function showImportForm()
    {
        return view('financial.reports.agents.import');
    }

    // Método para processar a importação
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'Selecione um arquivo para importar',
            'file.file' => 'O arquivo selecionado é inválido',
            'file.mimes' => 'O arquivo deve ser no formato Excel (xlsx, xls) ou CSV',
            'file.max' => 'O tamanho máximo do arquivo é de 2MB',
        ]);

        try {
            Excel::import(new FinancialAgentImport, $request->file('file'));
            
            return redirect()
                ->route('financial.reports.agents')
                ->with('success', 'Agentes financeiros importados com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao importar agentes financeiros: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Método para exportar modelo em Excel para importação
    public function exportTemplate()
    {
        $data = collect([
            [
                'codigo' => 'BC001',
                'nome' => 'Banco Exemplo',
                'tipo' => 'banco',
                'categoria_financeira' => 'Financeiro',
                'centro_de_custo' => 'Administrativo',
                'status' => 'ativo',
                'observacoes' => 'Banco principal',
                'codigo_banco' => '001',
                'agencia' => '1234',
                'conta' => '12345',
                'digito' => '6',
                'cnpj' => ''
            ],
            [
                'codigo' => 'FIN001',
                'nome' => 'Financeira Exemplo',
                'tipo' => 'financeira',
                'categoria_financeira' => 'Financeiro',
                'centro_de_custo' => 'Administrativo',
                'status' => 'ativo',
                'observacoes' => 'Financeira principal',
                'codigo_banco' => '',
                'agencia' => '',
                'conta' => '',
                'digito' => '',
                'cnpj' => '12345678000199'
            ]
        ]);

        return Excel::download(
            new class($data) implements FromCollection, WithHeadings, ShouldAutoSize {
                private $data;
                
                public function __construct($data)
                {
                    $this->data = $data;
                }
                
                public function collection()
                {
                    return $this->data;
                }
                
                public function headings(): array
                {
                    return [
                        'codigo',
                        'nome',
                        'tipo',
                        'categoria_financeira',
                        'centro_de_custo',
                        'status',
                        'observacoes',
                        'codigo_banco',
                        'agencia',
                        'conta',
                        'digito',
                        'cnpj'
                    ];
                }
            }, 
            'modelo_importacao_agentes.xlsx'
        );
    }
} 
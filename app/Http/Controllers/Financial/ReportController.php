<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Financial\ReportService;
use App\Http\Requests\Financial\ReportFilterRequest;
use App\Services\Exporters\PDFExporter;
use App\Services\Exporters\ExcelExporter;
use App\Enums\PaymentMethod;
use App\DTOs\Financial\ReportFilterDTO;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialReportExport;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService,
        private PDFExporter $pdfExporter,
        private ExcelExporter $excelExporter
    ) {}

    public function index(Request $request)
    {
        $filters = ReportFilterDTO::fromRequest($request->all());
        $transactions = $this->reportService->getFilteredTransactions($filters);
        
        $tipos = ['receita' => 'Receita', 'despesa' => 'Despesa'];
        $status = ['pendente' => 'Pendente', 'pago' => 'Pago', 'cancelado' => 'Cancelado'];
        
        return view('financial.reports.index', compact('transactions', 'tipos', 'status'));
    }

    public function exportPDF(Request $request)
    {
        $filters = ReportFilterDTO::fromRequest($request->all());
        $transactions = $this->reportService->getFilteredTransactions($filters);
        
        $pdf = PDF::loadView('financial.reports.pdf', [
            'transactions' => $transactions,
            'filters' => $filters
        ]);
        
        return $pdf->stream('relatorio_financeiro.pdf', ['Attachment' => false]); // Garante que abrirá no navegador
    }

    public function exportExcel(Request $request)
    {
        $filters = ReportFilterDTO::fromRequest($request->all());
        
        return Excel::download(
            new FinancialReportExport($filters), 
            'relatorio_financeiro.xlsx'
        ); // Download via popup
    }
} 
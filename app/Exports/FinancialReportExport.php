<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Services\Financial\ReportService;
use App\DTOs\Financial\ReportFilterDTO;
use Illuminate\Support\Collection;

class FinancialReportExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithTitle,
    WithProperties,
    ShouldAutoSize
{
    private $transactions;
    private $filters;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
        $this->transactions = app(ReportService::class)->getFilteredTransactions($filters);
    }

    public function collection(): Collection
    {
        return $this->transactions;
    }

    public function title(): string
    {
        return 'Relatório Financeiro';
    }

    public function properties(): array
    {
        return [
            'creator'        => auth()->user()->name,
            'lastModifiedBy' => auth()->user()->name,
            'title'         => 'Relatório Financeiro - MarmosyS',
            'description'   => 'Relatório financeiro gerado em ' . now()->format('d/m/Y H:i:s'),
            'subject'       => 'Relatório Financeiro',
            'keywords'      => 'relatorio,financeiro,marmosys',
            'category'      => 'Relatórios Financeiros',
            'manager'       => auth()->user()->name,
            'company'       => 'MarmosyS',
        ];
    }

    public function headings(): array
    {
        return [
            'Data Vencimento',
            'Descrição',
            'Tipo',
            'Valor',
            'Status',
            'Data Pagamento'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->data_vencimento->format('d/m/Y'),
            $transaction->descricao,
            ucfirst($transaction->tipo),
            'R$ ' . number_format($transaction->valor, 2, ',', '.'),
            ucfirst($transaction->status),
            $transaction->data_pagamento ? $transaction->data_pagamento->format('d/m/Y') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Estilo para o cabeçalho
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1976D2'],
            ],
        ]);

        // Bordas para toda a tabela
        $sheet->getStyle('A1:F' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Alinhamento
        $sheet->getStyle('A:F')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('D:D')->getAlignment()->setHorizontal('right');

        // Adiciona totais ao final
        $totalRow = $lastRow + 2;
        $sheet->setCellValue('A' . $totalRow, 'Totais:');
        $sheet->mergeCells('A' . $totalRow . ':F' . $totalRow);
        $sheet->getStyle('A' . $totalRow)->getFont()->setBold(true);

        $sheet->setCellValue('A' . ($totalRow + 1), 'Total Receitas: R$ ' . number_format($this->transactions->where('tipo', 'receita')->sum('valor'), 2, ',', '.'));
        $sheet->setCellValue('A' . ($totalRow + 2), 'Total Despesas: R$ ' . number_format($this->transactions->where('tipo', 'despesa')->sum('valor'), 2, ',', '.'));
        $sheet->setCellValue('A' . ($totalRow + 3), 'Saldo: R$ ' . number_format($this->transactions->where('tipo', 'receita')->sum('valor') - $this->transactions->where('tipo', 'despesa')->sum('valor'), 2, ',', '.'));

        return $sheet;
    }
} 
<?php

namespace App\Exports;

use App\Models\FinancialAgent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancialAgentExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize
{
    protected $agents;
    protected $hasTransactionsTable;

    /**
     * @param mixed $agents
     * @param bool $hasTransactionsTable
     */
    public function __construct($agents, $hasTransactionsTable = false)
    {
        $this->agents = $agents;
        $this->hasTransactionsTable = $hasTransactionsTable;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->agents;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            'Código',
            'Nome',
            'Tipo',
            'Categoria',
            'Centro de Custo',
            'Status',
        ];

        if ($this->hasTransactionsTable) {
            $headings[] = 'Qtd. Transações';
            $headings[] = 'Valor Total';
        }

        return $headings;
    }

    /**
     * @param mixed $agent
     * @return array
     */
    public function map($agent): array
    {
        $tipos = [
            'banco' => 'Banco',
            'financeira' => 'Financeira',
            'outros' => 'Outros'
        ];

        $data = [
            $agent->codigo,
            $agent->nome,
            $tipos[$agent->tipo] ?? '-',
            $agent->category->nome ?? '-',
            $agent->costCenter->nome ?? '-',
            $agent->status ? 'Ativo' : 'Inativo',
        ];

        if ($this->hasTransactionsTable) {
            $data[] = $agent->transactions_count ?? 0;
            $data[] = 'R$ ' . number_format($agent->transactions_sum_valor ?? 0, 2, ',', '.');
        }

        return $data;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $lastColumn = $this->hasTransactionsTable ? 'H' : 'F';
        
        // Estilo para o cabeçalho
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1976D2'],
            ],
        ]);

        // Estilo para as células de dados
        $sheet->getStyle('A2:' . $lastColumn . ($this->agents->count() + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Alinhamento para colunas específicas
        if ($this->hasTransactionsTable) {
            $sheet->getStyle('G2:G' . ($this->agents->count() + 1))->getAlignment()->setHorizontal('center');
            $sheet->getStyle('H2:H' . ($this->agents->count() + 1))->getAlignment()->setHorizontal('right');
        }

        return [];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // Código
            'B' => 40, // Nome
            'C' => 15, // Tipo
            'D' => 25, // Categoria
            'E' => 25, // Centro de Custo
            'F' => 12, // Status
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Agentes Financeiros';
    }
} 
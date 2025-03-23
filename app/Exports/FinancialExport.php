<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\FinancialAccount;

class FinancialExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $ano;
    protected $mes;

    public function __construct($ano, $mes)
    {
        $this->ano = $ano;
        $this->mes = $mes;
    }

    public function collection()
    {
        return FinancialAccount::with('category')
            ->whereYear('data_vencimento', $this->ano)
            ->whereMonth('data_vencimento', $this->mes)
            ->orderBy('data_vencimento')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Data Vencimento',
            'Categoria',
            'Tipo',
            'Descrição',
            'Valor',
            'Status',
            'Data Pagamento',
            'Forma Pagamento',
            'Observações'
        ];
    }

    public function map($account): array
    {
        return [
            $account->data_vencimento->format('d/m/Y'),
            $account->category->nome,
            ucfirst($account->category->tipo),
            $account->descricao,
            number_format($account->valor, 2, ',', '.'),
            ucfirst($account->status),
            $account->data_pagamento ? $account->data_pagamento->format('d/m/Y') : '-',
            $account->forma_pagamento ?: '-',
            $account->observacoes ?: '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:I' => ['alignment' => ['horizontal' => 'left']],
            'E' => ['alignment' => ['horizontal' => 'right']],
        ];
    }
} 
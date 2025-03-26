<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class SuppliersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $suppliers;

    public function __construct(Collection $suppliers)
    {
        $this->suppliers = $suppliers;
    }

    public function collection()
    {
        return $this->suppliers;
    }

    public function headings(): array
    {
        return [
            'Razão Social',
            'Nome Fantasia',
            'CNPJ',
            'Inscrição Estadual',
            'Inscrição Municipal',
            'Telefone',
            'Email',
            'CEP',
            'Endereço',
            'Número',
            'Complemento',
            'Bairro',
            'Cidade',
            'Estado',
            'Status',
            'Data Cadastro'
        ];
    }

    public function map($supplier): array
    {
        return [
            $supplier->razao_social,
            $supplier->nome_fantasia,
            $supplier->formatted_cnpj,
            $supplier->inscricao_estadual,
            $supplier->inscricao_municipal,
            $supplier->formatted_telefone,
            $supplier->email,
            $supplier->cep,
            $supplier->endereco,
            $supplier->numero,
            $supplier->complemento,
            $supplier->bairro,
            $supplier->cidade,
            $supplier->estado,
            $supplier->ativo ? 'Ativo' : 'Inativo',
            $supplier->created_at->format('d/m/Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }
} 
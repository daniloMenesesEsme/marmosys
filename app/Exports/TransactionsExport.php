<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    private $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'Data',
            'Cliente',
            'Valor',
            'Forma de Pagamento',
            'Status'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->data->format('d/m/Y'),
            $transaction->cliente->nome,
            $transaction->valor,
            $transaction->forma_pagamento->label(),
            $transaction->status
        ];
    }
} 
<?php

namespace App\Repositories\Financial;

use App\Models\Transaction;
use App\DTOs\Financial\ReportFilterDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TransactionRepository
{
    public function __construct(
        private Transaction $model
    ) {}

    public function getFiltered(ReportFilterDTO $filters): Collection
    {
        return $this->model->query()
            ->when($filters->dataInicio, function (Builder $query) use ($filters) {
                $query->whereDate('data', '>=', $filters->dataInicio);
            })
            ->when($filters->dataFim, function (Builder $query) use ($filters) {
                $query->whereDate('data', '<=', $filters->dataFim);
            })
            ->when($filters->formaPagamento, function (Builder $query) use ($filters) {
                $query->where('forma_pagamento', $filters->formaPagamento);
            })
            ->when($filters->status, function (Builder $query) use ($filters) {
                $query->where('status', $filters->status);
            })
            ->when($filters->clienteId, function (Builder $query) use ($filters) {
                $query->where('cliente_id', $filters->clienteId);
            })
            ->with(['cliente'])
            ->orderBy('data', 'desc')
            ->get();
    }

    public function getTotalizadores(Collection $transactions): array
    {
        return [
            'total' => $transactions->sum('valor'),
            'por_forma_pagamento' => $transactions
                ->groupBy(fn($t) => $t->forma_pagamento->value)
                ->map(fn($group) => $group->sum('valor')),
            'por_status' => $transactions
                ->groupBy('status')
                ->map(fn($group) => $group->sum('valor'))
        ];
    }
} 
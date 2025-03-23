<?php

namespace App\Services\Financial;

use App\Models\Transaction;
use App\DTOs\Financial\ReportFilterDTO;
use App\Repositories\Financial\TransactionRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ReportService
{
    public function __construct(
        private TransactionRepository $repository
    ) {}

    public function getFilteredTransactions(ReportFilterDTO $filters): Collection
    {
        return Transaction::query()
            ->when($filters->dataInicio, fn($query) => 
                $query->whereDate('data_vencimento', '>=', $filters->dataInicio))
            ->when($filters->dataFim, fn($query) => 
                $query->whereDate('data_vencimento', '<=', $filters->dataFim))
            ->when($filters->tipo, fn($query) => 
                $query->where('tipo', $filters->tipo))
            ->when($filters->status, fn($query) => 
                $query->where('status', $filters->status))
            ->with(['cliente'])
            ->orderBy('data_vencimento', 'desc')
            ->get();
    }

    public function getTotalizadores(Collection $transactions): array
    {
        return $this->repository->getTotalizadores($transactions);
    }

    private function generateCacheKey(ReportFilterDTO $filters): string
    {
        return sprintf(
            'financial_report_%s_%s_%s_%s_%s',
            $filters->dataInicio?->format('Y-m-d') ?? 'all',
            $filters->dataFim?->format('Y-m-d') ?? 'all',
            $filters->tipo ?? 'all',
            $filters->status ?? 'all',
            $filters->clienteId ?? 'all'
        );
    }
} 
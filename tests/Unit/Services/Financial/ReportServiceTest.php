<?php

namespace Tests\Unit\Services\Financial;

use Tests\TestCase;
use App\Services\Financial\ReportService;
use App\DTOs\Financial\ReportFilterDTO;
use App\Repositories\Financial\TransactionRepository;
use DateTime;
use Mockery;

class ReportServiceTest extends TestCase
{
    private ReportService $service;
    private TransactionRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(TransactionRepository::class);
        $this->service = new ReportService($this->repository);
    }

    public function test_get_filtered_transactions()
    {
        $filters = new ReportFilterDTO(
            dataInicio: new DateTime(),
            dataFim: new DateTime(),
            formaPagamento: null,
            status: null,
            clienteId: null
        );

        $this->repository
            ->shouldReceive('getFiltered')
            ->once()
            ->with($filters)
            ->andReturn(collect([]));

        $result = $this->service->getFilteredTransactions($filters);

        $this->assertNotNull($result);
    }

    public function test_get_totalizadores()
    {
        $transactions = collect([
            (object)['valor' => 100, 'forma_pagamento' => 'cartao'],
            (object)['valor' => 200, 'forma_pagamento' => 'cartao'],
        ]);

        $this->repository
            ->shouldReceive('getTotalizadores')
            ->once()
            ->with($transactions)
            ->andReturn([
                'total' => 300,
                'por_forma_pagamento' => ['cartao' => 300],
                'por_status' => []
            ]);

        $result = $this->service->getTotalizadores($transactions);

        $this->assertEquals(300, $result['total']);
    }
} 
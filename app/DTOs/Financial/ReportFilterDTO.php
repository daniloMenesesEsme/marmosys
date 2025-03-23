<?php

namespace App\DTOs\Financial;

use DateTime;

class ReportFilterDTO
{
    public function __construct(
        public readonly ?DateTime $dataInicio = null,
        public readonly ?DateTime $dataFim = null,
        public readonly ?string $tipo = null,
        public readonly ?string $status = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            dataInicio: isset($data['data_inicio']) ? new DateTime($data['data_inicio']) : null,
            dataFim: isset($data['data_fim']) ? new DateTime($data['data_fim']) : null,
            tipo: $data['tipo'] ?? null,
            status: $data['status'] ?? null
        );
    }
} 
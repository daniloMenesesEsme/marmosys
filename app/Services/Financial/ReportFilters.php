class ReportFilters
{
    public function __construct(
        public ?DateTimeInterface $dataInicio = null,
        public ?DateTimeInterface $dataFim = null,
        public ?PaymentMethod $formaPagamento = null,
        public ?string $status = null,
        public ?int $clienteId = null
    ) {}
} 
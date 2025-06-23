# Arquitetura do Sistema Marmosys

## Visão Geral da Arquitetura

O Marmosys é construído seguindo uma arquitetura em camadas, utilizando o padrão MVC (Model-View-Controller) com elementos adicionais para garantir uma melhor separação de responsabilidades e manutenibilidade do código.

```
[Cliente Web/Mobile]
         ↓
[Load Balancer/Nginx]
         ↓
[Application Server]
    ↓    ↓    ↓
[Cache][DB][Storage]
```

### Camadas da Aplicação

1. **Apresentação**
   - Views (Blade Templates)
   - JavaScript/Vue.js Components
   - CSS (Materialize)

2. **Aplicação**
   - Controllers
   - Services
   - DTOs
   - Requests

3. **Domínio**
   - Models
   - Repositories
   - Interfaces
   - Value Objects

4. **Infraestrutura**
   - Database
   - Cache
   - Queue
   - Storage

## Padrões de Projeto Utilizados

### 1. Repository Pattern
```php
interface ClientRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

class ClientRepository implements ClientRepositoryInterface
{
    protected $model;

    public function __construct(Client $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    // ... outros métodos
}
```

### 2. Service Layer
```php
class BudgetService
{
    protected $budgetRepository;
    protected $clientRepository;

    public function __construct(
        BudgetRepositoryInterface $budgetRepository,
        ClientRepositoryInterface $clientRepository
    ) {
        $this->budgetRepository = $budgetRepository;
        $this->clientRepository = $clientRepository;
    }

    public function createBudget(array $data)
    {
        DB::beginTransaction();
        try {
            $client = $this->clientRepository->find($data['client_id']);
            $budget = $this->budgetRepository->create($data);
            
            // Lógica adicional...
            
            DB::commit();
            return $budget;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

### 3. DTO (Data Transfer Objects)
```php
class BudgetDTO
{
    public $numero;
    public $clienteId;
    public $items;
    public $valorTotal;

    public static function fromRequest(Request $request): self
    {
        $dto = new self();
        $dto->numero = $request->numero;
        $dto->clienteId = $request->client_id;
        $dto->items = $request->items;
        $dto->valorTotal = $request->valor_total;
        return $dto;
    }
}
```

### 4. Form Request Validation
```php
class BudgetRequest extends FormRequest
{
    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1'
        ];
    }
}
```

## Infraestrutura

### 1. Banco de Dados
- MySQL 8.0+
- Migrations para versionamento
- Seeds para dados iniciais
- Backup automático

### 2. Cache
```php
// Configuração Redis
'redis' => [
    'client' => env('REDIS_CLIENT', 'phpredis'),
    'default' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD', null),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DB', 0),
    ],
],

// Exemplo de uso
Cache::remember('user_permissions', 3600, function () {
    return Permission::all();
});
```

### 3. Queue
```php
// Configuração de filas
'queue' => [
    'default' => env('QUEUE_CONNECTION', 'redis'),
    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
        ],
    ],
],

// Exemplo de Job
class ProcessBudgetPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $budget;

    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
    }

    public function handle()
    {
        // Processar PDF do orçamento
    }
}
```

## Segurança

### 1. Autenticação
- Laravel Sanctum para API
- Session based auth para web
- Proteção contra CSRF
- Rate limiting

### 2. Autorização
```php
// Policies
class BudgetPolicy
{
    public function view(User $user, Budget $budget)
    {
        return $user->hasRole('admin') || 
               $user->id === $budget->user_id;
    }
}

// Gates
Gate::define('manage-budgets', function (User $user) {
    return $user->hasPermission('budgets.manage');
});
```

### 3. Validação de Dados
- Form Requests
- Sanitização de inputs
- Validação customizada
- Proteção XSS

## Performance

### 1. Otimizações
- Eager loading de relacionamentos
- Cache de consultas frequentes
- Indexação de banco de dados
- Compressão de assets

### 2. Monitoramento
```php
// Logging de performance
DB::listen(function ($query) {
    if ($query->time > 100) {
        Log::warning('Consulta lenta detectada', [
            'sql' => $query->sql,
            'time' => $query->time
        ]);
    }
});
```

## Integração Contínua

### 1. Pipeline de Deploy
```yaml
# .github/workflows/deploy.yml
name: Deploy
on:
  push:
    branches: [ main ]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: php artisan test
      - name: Deploy to Production
        if: success()
        run: |
          # Deploy steps
```

### 2. Testes Automatizados
```php
class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_criar_orcamento()
    {
        $response = $this->post('/api/budgets', [
            'client_id' => 1,
            'items' => [
                [
                    'product_id' => 1,
                    'quantity' => 2
                ]
            ]
        ]);

        $response->assertStatus(201);
    }
}
```

## Escalabilidade

### 1. Horizontal Scaling
- Load balancing
- Session sharing
- Cache distribution
- Queue workers

### 2. Vertical Scaling
- Query optimization
- Resource management
- Memory usage control
- Background processing

## Monitoramento e Logs

### 1. Logging
```php
// Configuração de logs
'logging' => [
    'default' => env('LOG_CHANNEL', 'stack'),
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'slack'],
        ],
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],
    ],
],
```

### 2. Métricas
- Tempo de resposta
- Uso de recursos
- Erros e exceções
- Acessos e usuários

## Backup e Recuperação

### 1. Estratégia de Backup
- Backup diário do banco
- Backup semanal completo
- Retenção de 30 dias
- Armazenamento externo

### 2. Disaster Recovery
- Procedimentos documentados
- Testes periódicos
- RPO e RTO definidos
- Ambiente de contingência

## Considerações de Desenvolvimento

### 1. Ambiente Local
```bash
# Configuração do ambiente
composer install
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
```

### 2. Padrões de Código
- PSR-12
- Laravel best practices
- Code review
- Documentação inline

### 3. Versionamento
- Git flow
- Semantic versioning
- Conventional commits
- Pull request template

## Documentação

### 1. API Documentation
```php
/**
 * @OA\Post(
 *     path="/api/budgets",
 *     summary="Criar novo orçamento",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/BudgetRequest")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Orçamento criado com sucesso"
 *     )
 * )
 */
```

### 2. Código Fonte
- PHPDoc blocks
- README files
- Changelog
- Contributing guide 
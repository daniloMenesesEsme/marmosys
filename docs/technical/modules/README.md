# Módulos do Sistema Marmosys

## Visão Geral
O Marmosys é composto por diversos módulos integrados que trabalham em conjunto para fornecer uma solução completa de gestão para empresas do setor de mármore e granito.

## Módulos Implementados

### 1. [Cadastros Básicos](./registrations.md)
- Clientes
- Fornecedores
- Vendedores
- Funcionários
- Usuários
- Perfis

### 2. [Financeiro](./financial.md)
- Contas a Pagar e Receber
- Fluxo de Caixa
- Conciliação Bancária
- Formas de Pagamento
- Centros de Custo
- Categorias Financeiras
- Metas Financeiras
- Relatórios Financeiros

### 3. [Estoque](./inventory.md)
- Produtos
- Materiais
- Categorias
- Movimentações
- Controle de Estoque
- Inventário

### 4. [Orçamentos](./budgets.md)
- Geração de Orçamentos
- Ambientes e Itens
- Aprovação
- Integração com Financeiro
- Relatórios de Vendas

### 5. [Relatórios](./reports.md)
- Relatórios Gerenciais
- Dashboards
- Gráficos e Indicadores
- Exportação de Dados

## Módulos em Desenvolvimento

### 1. Produção
- Ordem de Serviço
- Cronograma de Produção
- Controle de Qualidade
- Apontamentos

### 2. Logística
- Gestão de Entregas
- Roteirização
- Controle de Frota
- Rastreamento

### 3. Compras
- Solicitação de Compras
- Cotações
- Ordem de Compra
- Gestão de Fornecedores

### 4. RH
- Gestão de Funcionários
- Controle de Ponto
- Folha de Pagamento
- Benefícios

### 5. Manutenção
- Equipamentos
- Manutenção Preventiva
- Histórico
- Custos

## Integrações Planejadas

### 1. Fiscal
- NFe
- NFSe
- Contabilidade
- SPED

### 2. E-commerce
- Catálogo Online
- Pedidos Web
- Pagamento Online
- Integração com Marketplace

### 3. Mobile
- App Vendedores
- App Entrega
- App Cliente
- App Produção

## Estrutura dos Módulos

Cada módulo segue uma estrutura padrão de organização:

```
módulo/
├── Controllers/     # Controladores do módulo
├── Models/         # Modelos de dados
├── Services/       # Regras de negócio
├── Repositories/   # Acesso a dados
├── Requests/       # Validação de dados
├── Resources/      # API Resources
├── Events/         # Eventos do módulo
├── Listeners/      # Listeners de eventos
├── Jobs/          # Jobs em background
├── Notifications/ # Notificações
└── Views/         # Templates Blade
```

## Padrões de Desenvolvimento

### 1. Nomenclatura
- Controllers: `NomeController.php`
- Models: `Nome.php`
- Migrations: `yyyy_mm_dd_create_nome_table.php`
- Views: `nome/action.blade.php`

### 2. Rotas
```php
Route::group(['prefix' => 'modulo', 'as' => 'modulo.'], function () {
    Route::get('/', 'ModuloController@index')->name('index');
    Route::get('/create', 'ModuloController@create')->name('create');
    Route::post('/', 'ModuloController@store')->name('store');
    Route::get('/{id}', 'ModuloController@show')->name('show');
    Route::get('/{id}/edit', 'ModuloController@edit')->name('edit');
    Route::put('/{id}', 'ModuloController@update')->name('update');
    Route::delete('/{id}', 'ModuloController@destroy')->name('destroy');
});
```

### 3. Controllers
```php
class ModuloController extends Controller
{
    protected $service;

    public function __construct(ModuloService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $items = $this->service->list();
        return view('modulo.index', compact('items'));
    }

    // ... outros métodos
}
```

### 4. Services
```php
class ModuloService
{
    protected $repository;

    public function __construct(ModuloRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        return $this->repository->paginate(10);
    }

    // ... outros métodos
}
```

## Permissões e ACL

Cada módulo define suas próprias permissões:

```php
// config/permissions.php
return [
    'modulo' => [
        'view' => 'Visualizar módulo',
        'create' => 'Criar registros',
        'edit' => 'Editar registros',
        'delete' => 'Excluir registros'
    ]
];
```

## Testes

Cada módulo deve incluir seus próprios testes:

```php
class ModuloTest extends TestCase
{
    use RefreshDatabase;

    public function test_listar_registros()
    {
        $response = $this->get(route('modulo.index'));
        $response->assertStatus(200);
    }

    // ... outros testes
}
```

## Documentação

Cada módulo deve manter sua própria documentação:

1. README.md com visão geral
2. Documentação de API
3. Regras de negócio
4. Fluxos de trabalho
5. Casos de uso

## Manutenção

### 1. Logs
- Logs específicos por módulo
- Monitoramento de erros
- Auditoria de ações
- Performance metrics

### 2. Cache
- Cache de consultas
- Cache de views
- Cache de configurações
- Invalidação inteligente

### 3. Jobs
- Processamento em background
- Filas por módulo
- Retry policies
- Error handling

## Contribuição

Para contribuir com um módulo:

1. Criar branch feature
2. Seguir padrões de código
3. Adicionar testes
4. Atualizar documentação
5. Criar pull request 
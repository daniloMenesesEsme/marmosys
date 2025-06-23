# Módulo Financeiro

## Visão Geral
O módulo financeiro do Marmosys é responsável por toda a gestão financeira da empresa, incluindo contas a pagar, contas a receber, fluxo de caixa, conciliação bancária e relatórios financeiros.

## Estrutura do Módulo

### Controllers
- `FinancialCategoryController`: Gerenciamento de categorias financeiras
- `FinancialAccountController`: Controle de contas financeiras
- `FinancialTransactionController`: Gestão de transações
- `PaymentMethodController`: Formas de pagamento
- `PaymentPlanController`: Planos de pagamento
- `FinancialGoalController`: Metas financeiras
- `CostCenterController`: Centros de custo
- `CashFlowProjectionController`: Projeção de fluxo de caixa
- `FinancialReconciliationController`: Conciliação financeira
- `FinancialForecastController`: Previsão financeira
- `FinancialReportController`: Relatórios financeiros

### Models
```php
// Categoria Financeira
class FinancialCategory extends Model
{
    protected $fillable = [
        'nome',
        'tipo', // ANALITICA, SINTETICA
        'natureza', // receita, despesa
        'codigo_contabil',
        'ativo'
    ];
}

// Conta Financeira
class FinancialAccount extends Model
{
    protected $fillable = [
        'category_id',
        'cost_center_id',
        'descricao',
        'valor',
        'tipo', // receita, despesa
        'status', // pendente, pago, cancelado
        'data_vencimento',
        'data_pagamento',
        'observacoes'
    ];
}
```

## Funcionalidades Principais

### 1. Gestão de Categorias
- Categorias analíticas e sintéticas
- Natureza (receita/despesa)
- Código contábil
- Status de ativação

### 2. Contas a Pagar e Receber
- Cadastro de contas
- Controle de vencimentos
- Baixa de pagamentos
- Estorno de operações
- Anexos de comprovantes

### 3. Fluxo de Caixa
```php
public function getFluxoCaixa($dataInicial, $dataFinal)
{
    return FinancialAccount::query()
        ->whereBetween('data_vencimento', [$dataInicial, $dataFinal])
        ->select(
            'tipo',
            'status',
            DB::raw('SUM(valor) as total')
        )
        ->groupBy('tipo', 'status')
        ->get();
}
```

### 4. Formas de Pagamento
- Configuração de métodos de pagamento
- Taxas e juros
- Parcelamento
- Integração com PDV

### 5. Conciliação Bancária
- Importação de extratos
- Conciliação automática
- Conciliação manual
- Relatório de divergências

### 6. Metas Financeiras
- Definição de objetivos
- Acompanhamento de progresso
- Alertas de desvios
- Relatórios de performance

## Regras de Negócio

### Validações
```php
// Exemplo de validação de transação financeira
$validated = $request->validate([
    'category_id' => 'required|exists:financial_categories,id',
    'account_id' => 'required|exists:financial_accounts,id',
    'tipo' => 'required|in:receita,despesa,transferencia',
    'valor' => 'required|numeric|min:0.01',
    'data_vencimento' => 'required|date',
    'data_pagamento' => 'nullable|date',
    'descricao' => 'required|string|max:255',
    'documento' => 'nullable|string|max:255',
    'client_id' => 'nullable|exists:clients,id',
    'budget_id' => 'nullable|exists:budgets,id',
    'observacoes' => 'nullable|string',
    'parcelas' => 'nullable|integer|min:1'
]);
```

### Regras Importantes
1. Toda transação deve ter uma categoria
2. Não permitir exclusão de categorias com movimentações
3. Validar saldo antes de efetuar pagamentos
4. Manter histórico de alterações (log)
5. Backup automático antes de operações críticas

## Relatórios

### 1. Demonstrativo de Resultados (DRE)
```php
public function getDRE($periodo)
{
    $receitas = FinancialAccount::whereHas('category', function($query) {
        $query->where('natureza', 'receita');
    })->sum('valor');

    $despesas = FinancialAccount::whereHas('category', function($query) {
        $query->where('natureza', 'despesa');
    })->sum('valor');

    return [
        'receitas' => $receitas,
        'despesas' => $despesas,
        'resultado' => $receitas - $despesas
    ];
}
```

### 2. Fluxo de Caixa Projetado
- Projeção de recebimentos
- Projeção de pagamentos
- Saldo projetado
- Análise de tendências

### 3. Relatórios Gerenciais
- Inadimplência
- Aging list
- Análise por centro de custo
- Performance por categoria

## Integrações

### 1. Integração Bancária
- Importação de extratos
- Exportação de remessa
- Retorno de cobranças

### 2. Integração Fiscal
- Geração de NF-e
- Geração de NFS-e
- Exportação para contabilidade

## Permissões e Segurança

### Níveis de Acesso
1. Administrador Financeiro
   - Acesso total ao módulo
2. Operador Financeiro
   - Lançamentos e baixas
   - Consulta de relatórios
3. Consulta
   - Apenas visualização

### ACL (Access Control List)
```php
Gate::define('financial-admin', function (User $user) {
    return $user->hasRole('financial-admin');
});

Gate::define('financial-operator', function (User $user) {
    return $user->hasRole('financial-operator');
});
```

## Logs e Auditoria

### Eventos Monitorados
1. Criação de lançamentos
2. Alteração de valores
3. Baixas e estornos
4. Exclusões
5. Alterações em configurações

### Estrutura do Log
```php
class FinancialLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'old_data',
        'new_data',
        'ip_address'
    ];
}
```

## Configurações do Módulo

### Parâmetros Configuráveis
1. Dias para vencimento padrão
2. Taxas de juros padrão
3. Multa padrão
4. Categorias padrão
5. Centros de custo obrigatórios

### Cache
```php
// Exemplo de cache de dashboard financeiro
Cache::remember('financial_dashboard', 3600, function () {
    return [
        'saldo_atual' => $this->getSaldoAtual(),
        'receitas_mes' => $this->getReceitasMes(),
        'despesas_mes' => $this->getDespesasMes(),
        'projecao_mes' => $this->getProjecaoMes()
    ];
});
```

## Testes

### Testes Unitários
```php
class FinancialTransactionTest extends TestCase
{
    public function test_criar_transacao()
    {
        $data = [
            'category_id' => 1,
            'valor' => 100.00,
            'tipo' => 'receita',
            'data_vencimento' => now()
        ];

        $transaction = FinancialTransaction::create($data);
        $this->assertDatabaseHas('financial_transactions', $data);
    }
}
```

### Testes de Integração
- Teste de importação de extratos
- Teste de conciliação
- Teste de geração de relatórios
- Teste de integrações bancárias

## Manutenção

### Tarefas Agendadas
1. Atualização de juros e multas
2. Geração de relatórios automáticos
3. Backup de dados financeiros
4. Limpeza de logs antigos
5. Atualização de projeções

### Monitoramento
- Alertas de inconsistências
- Monitoramento de performance
- Logs de erros
- Métricas de uso 
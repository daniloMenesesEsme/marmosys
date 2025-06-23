# Módulo de Orçamentos

## Visão Geral
O módulo de orçamentos do Marmosys é responsável por todo o processo de criação, gestão e acompanhamento de orçamentos para clientes. O sistema permite a criação de orçamentos detalhados com múltiplos ambientes e itens, cálculo automático de valores e integração com o módulo financeiro.

## Estrutura do Módulo

### Controllers
- `BudgetController`: Gerenciamento de orçamentos
- `BudgetItemController`: Itens do orçamento
- `BudgetRoomController`: Ambientes do orçamento
- `BudgetStatusController`: Status do orçamento
- `BudgetPaymentController`: Condições de pagamento

### Models
```php
// Orçamento
class Budget extends Model
{
    protected $fillable = [
        'numero',
        'data',
        'previsao_entrega',
        'client_id',
        'seller_id',
        'status',
        'valor_total',
        'desconto',
        'valor_final',
        'data_validade',
        'observacoes',
        'user_id',
        'payment_method_id',
        'payment_condition',
        'payment_installments',
        'payment_fee',
        'first_installment_date'
    ];

    // Relacionamentos
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function rooms()
    {
        return $this->hasMany(BudgetRoom::class);
    }
}

// Ambiente do Orçamento
class BudgetRoom extends Model
{
    protected $fillable = [
        'budget_id',
        'nome',
        'observacoes'
    ];

    public function items()
    {
        return $this->hasMany(BudgetItem::class);
    }
}

// Item do Orçamento
class BudgetItem extends Model
{
    protected $fillable = [
        'budget_room_id',
        'material_id',
        'quantidade',
        'unidade',
        'largura',
        'altura',
        'valor_unitario',
        'valor_total',
        'observacoes'
    ];
}
```

## Funcionalidades Principais

### 1. Geração de Orçamentos
```php
public function create()
{
    // Gerar número sequencial para o orçamento
    $ano = date('Y');
    $ultimoNumero = Budget::whereYear('created_at', $ano)
        ->orderBy('id', 'desc')
        ->first();
    
    $sequencial = 1;
    if ($ultimoNumero) {
        preg_match('/ORC-\d+(\d{4})$/', $ultimoNumero->numero, $matches);
        $sequencial = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
    }
    
    $numero = 'ORC-' . $ano . str_pad($sequencial, 4, '0', STR_PAD_LEFT);
    
    // Recuperar dados para o formulário
    $clients = Client::where('ativo', true)->get();
    $sellers = Seller::where('ativo', true)->get();
    $paymentMethods = PaymentMethod::where('ativo', true)->get();
    $materiais = Product::where('ativo', true)->get();
    
    return view('budgets.create', compact(
        'numero', 
        'clients', 
        'sellers',
        'paymentMethods', 
        'materiais'
    ));
}
```

### 2. Cálculos e Validações
```php
public function calcularValores(BudgetItem $item)
{
    // Cálculo da área
    $area = $item->largura * $item->altura;
    
    // Valor total do item
    $valorTotal = $area * $item->valor_unitario;
    
    // Aplicar regras de arredondamento
    $valorTotal = ceil($valorTotal * 100) / 100;
    
    return [
        'area' => $area,
        'valor_total' => $valorTotal
    ];
}
```

### 3. Fluxo de Aprovação
- Aguardando aprovação
- Aprovado
- Reprovado
- Em produção
- Concluído
- Cancelado

### 4. Integração com Financeiro
```php
public function gerarContasReceber(Budget $budget)
{
    // Validar se já existem contas geradas
    if ($budget->contas()->exists()) {
        throw new Exception('Já existem contas geradas para este orçamento');
    }

    // Calcular valores das parcelas
    $valorParcela = $budget->valor_final / $budget->payment_installments;
    
    // Gerar contas a receber
    for ($i = 1; $i <= $budget->payment_installments; $i++) {
        $dataVencimento = Carbon::parse($budget->first_installment_date)
            ->addMonths($i - 1);
            
        FinancialAccount::create([
            'category_id' => 1, // ID da categoria de receita
            'descricao' => "Orçamento #{$budget->numero} - Parcela {$i}/{$budget->payment_installments}",
            'valor' => $valorParcela,
            'tipo' => 'receita',
            'status' => 'pendente',
            'data_vencimento' => $dataVencimento,
            'client_id' => $budget->client_id,
            'budget_id' => $budget->id
        ]);
    }
}
```

## Regras de Negócio

### Validações
```php
$validated = $request->validate([
    'numero' => 'required|unique:budgets,numero',
    'data' => 'required|date',
    'previsao_entrega' => 'required|date',
    'client_id' => 'required|exists:clients,id',
    'seller_id' => 'nullable|exists:sellers,id',
    'rooms' => 'required|array|min:1',
    'rooms.*.nome' => 'required|string',
    'rooms.*.items' => 'required|array|min:1',
    'rooms.*.items.*.material_id' => 'required|exists:products,id',
    'rooms.*.items.*.quantidade' => 'required|numeric|min:0.01',
    'rooms.*.items.*.unidade' => 'required|string',
    'rooms.*.items.*.largura' => 'required|numeric|min:0',
    'rooms.*.items.*.altura' => 'required|numeric|min:0',
    'observacoes' => 'nullable|string|max:1000',
    'payment_method_id' => 'nullable|exists:payment_methods,id',
    'payment_installments' => 'required_with:payment_method_id|integer|min:1',
    'payment_condition' => 'nullable|string',
    'first_installment_date' => 'required_with:payment_method_id|date'
]);
```

### Regras Importantes
1. Número do orçamento deve ser único
2. Pelo menos um ambiente por orçamento
3. Pelo menos um item por ambiente
4. Validar estoque disponível
5. Calcular prazos de entrega
6. Controlar validade do orçamento

## Relatórios

### 1. Relatório de Orçamentos
- Por período
- Por cliente
- Por vendedor
- Por status
- Taxa de conversão

### 2. Análise de Itens
- Itens mais orçados
- Valor médio por item
- Margem de lucro
- Sazonalidade

### 3. Performance
- Performance por vendedor
- Taxa de aprovação
- Tempo médio de aprovação
- Valor médio dos orçamentos

## Integrações

### 1. Integração com Estoque
- Verificação de disponibilidade
- Reserva de material
- Baixa automática

### 2. Integração com Produção
- Geração de ordem de produção
- Cronograma de produção
- Acompanhamento de status

## Permissões e Segurança

### Níveis de Acesso
1. Administrador
   - Acesso total
2. Vendedor
   - Criar e editar próprios orçamentos
   - Visualizar todos os orçamentos
3. Produção
   - Visualizar orçamentos aprovados
   - Atualizar status de produção

### ACL
```php
Gate::define('budget-admin', function (User $user) {
    return $user->hasRole('budget-admin');
});

Gate::define('budget-seller', function (User $user) {
    return $user->hasRole('seller');
});
```

## Interface do Usuário

### 1. Formulário de Orçamento
- Seleção de cliente
- Adição dinâmica de ambientes
- Adição dinâmica de itens
- Cálculo automático de valores
- Preview do orçamento

### 2. Listagem de Orçamentos
- Filtros avançados
- Ordenação personalizada
- Ações em lote
- Exportação de dados

### 3. Visualização de Orçamento
- Layout profissional
- Dados do cliente
- Lista de itens
- Condições comerciais
- Opções de aprovação

## Impressão e Exportação

### 1. Modelo de Impressão
```php
public function generatePDF(Budget $budget)
{
    $data = [
        'budget' => $budget,
        'company' => Company::first(),
        'logo' => public_path('images/logo.png')
    ];
    
    $pdf = PDF::loadView('budgets.pdf', $data);
    return $pdf->download("orcamento_{$budget->numero}.pdf");
}
```

### 2. Formatos Disponíveis
- PDF
- Excel
- HTML para e-mail
- JSON para API

## Notificações

### 1. E-mails Automáticos
- Novo orçamento
- Aprovação/Reprovação
- Vencimento próximo
- Alterações de status

### 2. Notificações Internas
- Dashboard
- Alertas no sistema
- Notificações push

## Testes

### Testes Unitários
```php
class BudgetTest extends TestCase
{
    public function test_criar_orcamento()
    {
        $data = [
            'client_id' => 1,
            'seller_id' => 1,
            'rooms' => [
                [
                    'nome' => 'Sala',
                    'items' => [
                        [
                            'material_id' => 1,
                            'quantidade' => 1,
                            'largura' => 2,
                            'altura' => 2
                        ]
                    ]
                ]
            ]
        ];

        $budget = Budget::create($data);
        $this->assertDatabaseHas('budgets', ['client_id' => 1]);
    }
}
```

### Testes de Integração
- Fluxo completo de orçamento
- Integração com financeiro
- Geração de documentos
- Notificações

## Manutenção

### Tarefas Agendadas
1. Atualização de status
2. Limpeza de orçamentos vencidos
3. Relatórios automáticos
4. Backup de documentos

### Monitoramento
- Performance do módulo
- Uso de recursos
- Erros e exceções
- Tempo de resposta 
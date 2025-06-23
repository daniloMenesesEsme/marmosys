# Marmosys - Sistema de Gestão Empresarial
## Documento Técnico de Arquitetura, Tecnologias e Segurança

**Versão:** 1.0.0  
**Data:** 26/05/2025  
**Autor:** Equipe de Desenvolvimento Marmosys  
**Classificação:** Técnico - Confidencial

---

# Sumário Executivo

O Marmosys é um sistema de gestão empresarial (ERP) desenvolvido especificamente para empresas do setor de mármore e granito. Este documento apresenta a arquitetura técnica, stack tecnológica, padrões de desenvolvimento, medidas de segurança e estrutura de engenharia implementadas no sistema.

---

# 1. Visão Geral do Sistema

## 1.1 Propósito
Sistema integrado de gestão empresarial que automatiza e otimiza processos operacionais, financeiros e administrativos de empresas do setor de mármore e granito.

## 1.2 Características Principais
- **Modular:** Arquitetura baseada em módulos independentes
- **Escalável:** Suporta crescimento de dados e usuários
- **Seguro:** Implementa múltiplas camadas de segurança
- **Responsivo:** Interface adaptável a diferentes dispositivos
- **Localizável:** Suporte completo ao português brasileiro

---

# 2. Stack Tecnológica

## 2.1 Backend

### Framework Principal
- **Laravel 10.x**
  - Framework PHP moderno e robusto
  - Padrão MVC (Model-View-Controller)
  - ORM Eloquent para abstração de banco de dados
  - Sistema de rotas RESTful
  - Middleware para autenticação e autorização

### Linguagem de Programação
- **PHP 8.1+**
  - Tipagem forte
  - Atributos (Attributes)
  - Union Types
  - Match expressions
  - Performance otimizada

### Banco de Dados
- **MySQL 8.0+**
  - Engine InnoDB
  - Charset UTF8MB4 (suporte completo Unicode)
  - Collation utf8mb4_unicode_ci
  - Suporte a transações ACID
  - Índices otimizados

### Dependências Principais
```json
{
  "barryvdh/laravel-dompdf": "^3.1",      // Geração de PDFs
  "doctrine/dbal": "^3.9",                // Abstração de banco
  "guzzlehttp/guzzle": "^7.2",           // Cliente HTTP
  "laravel/sanctum": "^3.3",             // Autenticação API
  "maatwebsite/excel": "^3.1"            // Importação/Exportação Excel
}
```

## 2.2 Frontend

### Framework CSS
- **Materialize CSS 1.0**
  - Design Material Design
  - Componentes responsivos
  - Grid system flexível
  - Ícones integrados

### JavaScript
- **Vanilla JavaScript ES6+**
  - Promises e Async/Await
  - Modules (ES6)
  - Fetch API para requisições AJAX
  - Event Listeners modernos

### Template Engine
- **Blade Templates (Laravel)**
  - Sintaxe limpa e intuitiva
  - Herança de templates
  - Componentes reutilizáveis
  - Escape automático de XSS

## 2.3 Ferramentas de Desenvolvimento

### Qualidade de Código
- **Laravel Pint:** Formatação automática de código
- **PHPUnit:** Testes unitários e de integração
- **Faker:** Geração de dados para testes

### Debug e Monitoramento
- **Spatie Laravel Ignition:** Debug avançado
- **Laravel Telescope:** Monitoramento de aplicação
- **Logs estruturados:** Sistema de logging personalizado

---

# 3. Arquitetura do Sistema

## 3.1 Padrão Arquitetural

### MVC (Model-View-Controller)
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│      VIEW       │    │   CONTROLLER    │    │      MODEL      │
│                 │    │                 │    │                 │
│ • Templates     │◄──►│ • Lógica de     │◄──►│ • Eloquent ORM  │
│ • Blade         │    │   Negócio       │    │ • Validações    │
│ • JavaScript    │    │ • Validações    │    │ • Relacionamen- │
│ • CSS           │    │ • Responses     │    │   tos           │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 3.2 Estrutura de Diretórios

```
marmosys/
├── app/                          # Lógica da aplicação
│   ├── Http/
│   │   ├── Controllers/          # Controladores
│   │   ├── Middleware/           # Middlewares customizados
│   │   └── Requests/             # Form Requests (validação)
│   ├── Models/                   # Modelos Eloquent
│   ├── Providers/                # Service Providers
│   └── Services/                 # Serviços de negócio
├── config/                       # Configurações
├── database/
│   ├── migrations/               # Migrações do banco
│   ├── seeders/                  # Seeders
│   └── factories/                # Factories para testes
├── public/                       # Assets públicos
│   ├── css/                      # Estilos CSS
│   ├── js/                       # Scripts JavaScript
│   └── images/                   # Imagens
├── resources/
│   ├── views/                    # Templates Blade
│   ├── lang/                     # Arquivos de tradução
│   └── css/                      # Sass/CSS fonte
├── routes/                       # Definição de rotas
├── storage/                      # Arquivos de armazenamento
└── tests/                        # Testes automatizados
```

## 3.3 Camadas da Aplicação

### 1. Camada de Apresentação
- **Templates Blade:** Renderização server-side
- **JavaScript:** Interatividade client-side
- **CSS/Materialize:** Estilização e layout
- **Validação Frontend:** Validação em tempo real

### 2. Camada de Aplicação
- **Controllers:** Orquestração de requisições
- **Middleware:** Interceptação de requisições
- **Form Requests:** Validação de entrada
- **Resources:** Transformação de dados

### 3. Camada de Domínio
- **Models:** Representação de entidades
- **Services:** Lógica de negócio
- **Repositories:** Abstração de acesso a dados
- **Events/Listeners:** Comunicação entre módulos

### 4. Camada de Infraestrutura
- **Database:** Persistência de dados
- **File System:** Armazenamento de arquivos
- **Cache:** Sistema de cache (Redis/File)
- **Queue:** Processamento assíncrono

---

# 4. Segurança

## 4.1 Autenticação e Autorização

### Sistema de Autenticação
```php
// Configuração de autenticação
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],
```

### Controle de Acesso
- **Middleware de Autenticação:** Verificação de usuário logado
- **Middleware de Autorização:** Verificação de permissões
- **Gates e Policies:** Controle granular de acesso
- **Roles e Permissions:** Sistema hierárquico de permissões

## 4.2 Proteção contra Vulnerabilidades

### CSRF (Cross-Site Request Forgery)
```php
// Token CSRF em todos os formulários
@csrf
```

### XSS (Cross-Site Scripting)
```php
// Escape automático no Blade
{{ $variavel }} // Escapado automaticamente
{!! $variavel !!} // Raw (apenas quando necessário)
```

### SQL Injection
```php
// Query Builder e Eloquent ORM
User::where('email', $email)->first(); // Protegido automaticamente
```

### Mass Assignment
```php
// Fillable/Guarded nos Models
protected $fillable = ['name', 'email'];
protected $guarded = ['id', 'password'];
```

## 4.3 Criptografia

### Configuração de Criptografia
- **Algoritmo:** AES-256-CBC
- **Chave:** Gerada automaticamente (APP_KEY)
- **Hashing:** Bcrypt para senhas
- **Dados Sensíveis:** Criptografados antes do armazenamento

```php
// Exemplo de criptografia
$encrypted = Crypt::encryptString('dados sensíveis');
$decrypted = Crypt::decryptString($encrypted);
```

## 4.4 Validação de Dados

### Form Requests
```php
class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'cpf' => 'required|cpf',
            'cnpj' => 'nullable|cnpj',
        ];
    }
}
```

### Sanitização
- **Trim automático:** Remoção de espaços
- **Conversão de strings vazias:** Para null
- **Validação de tipos:** Strict typing

## 4.5 Logs e Auditoria

### Sistema de Logs
```php
// Configuração de logs
'channels' => [
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => 'debug',
        'days' => 14,
    ],
],
```

### Auditoria de Ações
- **Login/Logout:** Registro de acessos
- **CRUD Operations:** Log de alterações
- **Tentativas de Acesso:** Monitoramento de segurança
- **Soft Deletes:** Exclusão lógica com rastreabilidade

---

# 5. Padrões de Desenvolvimento

## 5.1 Convenções de Código

### PSR Standards
- **PSR-1:** Padrão básico de codificação
- **PSR-2:** Guia de estilo de codificação
- **PSR-4:** Autoloading padrão
- **PSR-12:** Extensão do PSR-2

### Nomenclatura
```php
// Classes: PascalCase
class UserController extends Controller

// Métodos: camelCase
public function getUserById($id)

// Variáveis: camelCase
$userName = 'João Silva';

// Constantes: UPPER_SNAKE_CASE
const MAX_USERS = 100;

// Tabelas: snake_case plural
users, user_profiles, financial_accounts
```

## 5.2 Estrutura de Controllers

```php
class UserController extends Controller
{
    public function index()     // Listar recursos
    public function create()    // Formulário de criação
    public function store()     // Salvar novo recurso
    public function show($id)   // Exibir recurso específico
    public function edit($id)   // Formulário de edição
    public function update($id) // Atualizar recurso
    public function destroy($id)// Remover recurso
}
```

## 5.3 Relacionamentos Eloquent

```php
// Relacionamentos bem definidos
class User extends Model
{
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
```

---

# 6. Performance e Otimização

## 6.1 Cache

### Estratégias de Cache
- **Query Cache:** Cache de consultas frequentes
- **View Cache:** Cache de templates compilados
- **Route Cache:** Cache de rotas para produção
- **Config Cache:** Cache de configurações

```php
// Exemplo de cache
Cache::remember('users.all', 3600, function () {
    return User::all();
});
```

## 6.2 Otimização de Queries

### Eager Loading
```php
// Evita N+1 queries
$users = User::with(['profile', 'orders'])->get();
```

### Índices de Banco
```php
// Migração com índices
Schema::table('users', function (Blueprint $table) {
    $table->index('email');
    $table->index(['status', 'created_at']);
});
```

## 6.3 Assets

### Otimização de Assets
- **Minificação:** CSS e JavaScript minificados
- **Compressão:** Gzip habilitado
- **CDN:** Assets estáticos servidos via CDN
- **Lazy Loading:** Carregamento sob demanda

---

# 7. Backup e Recuperação

## 7.1 Estratégia de Backup

### Backup Automático
```php
// Comando personalizado de backup
php artisan backup:run
```

### Tipos de Backup
- **Diário:** Backup incremental
- **Semanal:** Backup completo
- **Mensal:** Backup arquival

### Armazenamento
- **Local:** Servidor principal
- **Remoto:** Cloud storage (AWS S3, Google Cloud)
- **Redundância:** Múltiplas cópias

## 7.2 Recuperação de Desastres

### RTO (Recovery Time Objective): 4 horas
### RPO (Recovery Point Objective): 1 hora

### Procedimentos
1. **Identificação do problema**
2. **Isolamento do ambiente**
3. **Restauração do backup**
4. **Verificação de integridade**
5. **Retomada das operações**

---

# 8. Monitoramento e Logs

## 8.1 Sistema de Monitoramento

### Métricas Monitoradas
- **Performance:** Tempo de resposta, throughput
- **Recursos:** CPU, memória, disco
- **Erros:** Exceções, falhas de sistema
- **Usuários:** Acessos, sessões ativas

### Alertas
- **Críticos:** Falhas de sistema, indisponibilidade
- **Avisos:** Performance degradada, recursos limitados
- **Informativos:** Atualizações, manutenções

## 8.2 Estrutura de Logs

```php
// Tipos de log
Log::emergency('Sistema indisponível');
Log::alert('Ação imediata necessária');
Log::critical('Condição crítica');
Log::error('Erro na aplicação');
Log::warning('Aviso importante');
Log::notice('Evento normal significativo');
Log::info('Informação geral');
Log::debug('Informação de depuração');
```

---

# 9. Testes

## 9.1 Estratégia de Testes

### Pirâmide de Testes
```
    ┌─────────────┐
    │   E2E Tests │  ← Poucos, caros, lentos
    ├─────────────┤
    │Integration  │  ← Médios
    │   Tests     │
    ├─────────────┤
    │   Unit      │  ← Muitos, baratos, rápidos
    │   Tests     │
    └─────────────┘
```

### Cobertura de Testes
- **Objetivo:** 80% de cobertura mínima
- **Críticos:** 100% para módulos financeiros
- **Unitários:** Modelos e serviços
- **Integração:** Controllers e APIs

## 9.2 Ferramentas de Teste

```php
// PHPUnit - Testes unitários
class UserTest extends TestCase
{
    public function test_user_creation()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', [
            'email' => $user->email
        ]);
    }
}
```

---

# 10. Deployment e DevOps

## 10.1 Ambiente de Desenvolvimento

### Configuração Local
- **Laravel Sail:** Ambiente Docker
- **Artisan Server:** Servidor de desenvolvimento
- **Hot Reload:** Atualização automática

### Controle de Versão
- **Git:** Sistema de versionamento
- **GitFlow:** Workflow de branches
- **Conventional Commits:** Padrão de commits

## 10.2 Pipeline de Deploy

```yaml
# Exemplo de pipeline CI/CD
stages:
  - test
  - build
  - deploy

test:
  script:
    - composer install
    - php artisan test

build:
  script:
    - npm run production
    - composer install --no-dev

deploy:
  script:
    - php artisan migrate --force
    - php artisan config:cache
    - php artisan route:cache
```

---

# 11. Conformidade e Regulamentações

## 11.1 LGPD (Lei Geral de Proteção de Dados)

### Medidas Implementadas
- **Consentimento:** Termos de uso e política de privacidade
- **Minimização:** Coleta apenas de dados necessários
- **Transparência:** Informações claras sobre uso dos dados
- **Segurança:** Criptografia e controle de acesso
- **Direitos:** Portabilidade, exclusão, correção

### Dados Pessoais Tratados
- **Identificação:** Nome, CPF, RG
- **Contato:** E-mail, telefone, endereço
- **Profissionais:** Cargo, empresa, histórico
- **Financeiros:** Dados bancários (criptografados)

## 11.2 Segurança da Informação

### ISO 27001 - Controles Implementados
- **A.9:** Controle de acesso
- **A.10:** Criptografia
- **A.12:** Segurança nas operações
- **A.13:** Segurança nas comunicações
- **A.14:** Aquisição, desenvolvimento e manutenção

---

# 12. Roadmap Técnico

## 12.1 Próximas Implementações

### Curto Prazo (3 meses)
- **Redis Cache:** Implementação de cache distribuído
- **Queue System:** Processamento assíncrono
- **API REST:** Endpoints para integração
- **Docker:** Containerização completa

### Médio Prazo (6 meses)
- **Microserviços:** Separação de módulos críticos
- **Elasticsearch:** Busca avançada
- **WebSockets:** Notificações em tempo real
- **PWA:** Progressive Web App

### Longo Prazo (12 meses)
- **GraphQL:** API mais flexível
- **Kubernetes:** Orquestração de containers
- **Machine Learning:** Análises preditivas
- **Blockchain:** Rastreabilidade de produtos

---

# 13. Conclusão

O Marmosys foi desenvolvido seguindo as melhores práticas de engenharia de software, com foco em segurança, performance e manutenibilidade. A arquitetura modular permite evolução contínua, enquanto as medidas de segurança garantem proteção adequada dos dados empresariais.

## 13.1 Pontos Fortes
- **Arquitetura sólida:** MVC bem estruturado
- **Segurança robusta:** Múltiplas camadas de proteção
- **Código limpo:** Padrões consistentes
- **Documentação completa:** Facilitando manutenção
- **Testes abrangentes:** Garantindo qualidade

## 13.2 Próximos Passos
- **Implementação de novos módulos**
- **Otimizações de performance**
- **Expansão das funcionalidades**
- **Integração com sistemas externos**
- **Evolução da arquitetura**

---

**Documento elaborado pela Equipe Técnica Marmosys**  
**Para uso interno e parceiros autorizados**  
**Versão 1.0 - Maio 2025** 
# Documentação Técnica - Sistema Marmosys

## Visão Geral
O Marmosys é um sistema de gestão empresarial (ERP) desenvolvido especificamente para empresas do setor de mármore e granito. O sistema foi construído utilizando Laravel como framework backend, Materialize CSS para interface frontend, e segue padrões modernos de desenvolvimento de software.

### Versão Atual
- Versão: v1.0.0
- Data de Lançamento: 23/03/2024

### Stack Tecnológica
- Backend: Laravel 10.x
- Frontend: Materialize CSS
- Banco de Dados: MySQL
- Cache: Redis
- Servidor Web: Nginx/Apache

### Estrutura de Diretórios
```
marmosys/
├── app/
│   ├── Console/         # Comandos personalizados
│   ├── Contracts/       # Interfaces de contrato
│   ├── DTOs/           # Objetos de Transferência de Dados
│   ├── Enums/          # Enumerações
│   ├── Exports/        # Classes de exportação
│   ├── Helpers/        # Funções auxiliares
│   ├── Http/           # Controllers, Middleware, Requests
│   ├── Imports/        # Classes de importação
│   ├── Interfaces/     # Interfaces
│   ├── Models/         # Modelos Eloquent
│   ├── Providers/      # Provedores de serviço
│   ├── Repositories/   # Repositórios
│   ├── Rules/          # Regras de validação
│   ├── Services/       # Serviços
│   └── View/           # Componentes de view
├── config/             # Configurações
├── database/          
│   ├── factories/      # Factories para testes
│   ├── migrations/     # Migrações do banco
│   └── seeders/       # Seeders para dados iniciais
├── docs/              # Documentação
├── public/            # Arquivos públicos
├── resources/         # Views, assets
├── routes/            # Definições de rotas
├── storage/           # Arquivos de armazenamento
└── tests/             # Testes automatizados
```

## Índice da Documentação

1. [Arquitetura do Sistema](./architecture.md)
2. [Módulos do Sistema](./modules/README.md)
   - [Cadastros](./modules/registrations.md)
   - [Financeiro](./modules/financial.md)
   - [Estoque](./modules/inventory.md)
   - [Orçamentos](./modules/budgets.md)
   - [Relatórios](./modules/reports.md)
3. [Banco de Dados](./database.md)
4. [APIs e Integrações](./apis.md)
5. [Segurança](./security.md)
6. [Deploy e Ambiente](./deployment.md)
7. [Padrões de Código](./coding-standards.md)
8. [Testes](./testing.md)

## Convenções e Padrões

### Padrões de Código
- PSR-12 para estilo de código PHP
- Convenções de nomenclatura Laravel
- ESLint para JavaScript
- SASS/SCSS para estilos

### Padrões de Commits
```
feat: nova funcionalidade
fix: correção de bug
docs: alteração em documentação
style: formatação de código
refactor: refatoração de código
test: adição/modificação de testes
chore: alteração em arquivos de build
```

### Branches
- `main`: branch principal, código em produção
- `develop`: branch de desenvolvimento
- `feature/*`: novas funcionalidades
- `hotfix/*`: correções urgentes
- `release/*`: preparação para release

## Contribuição
Para contribuir com o projeto, siga os seguintes passos:

1. Clone o repositório
2. Crie uma branch para sua feature (`git checkout -b feature/nome-da-feature`)
3. Faça commit das alterações (`git commit -m 'feat: descrição da feature'`)
4. Push para a branch (`git push origin feature/nome-da-feature`)
5. Crie um Pull Request

## Contatos e Suporte
- Email: suporte@marmosys.com.br
- Documentação: https://docs.marmosys.com.br
- Sistema de Tickets: https://suporte.marmosys.com.br 
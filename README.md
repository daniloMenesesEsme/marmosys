# 💎 Marmosys - Sistema de Gestão para Empresas de Mármore e Granito

<div align="center">

![Marmosys Logo](https://img.shields.io/badge/Marmosys-v1.0.0-blue?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql)

**Sistema ERP completo desenvolvido especificamente para o setor de mármore e granito**

[📖 Documentação](#documentação) • [🚀 Instalação](#instalação) • [🔧 Configuração](#configuração) • [📊 Funcionalidades](#funcionalidades)

</div>

---

## 📋 Sobre o Projeto

O **Marmosys** é um sistema de gestão empresarial (ERP) desenvolvido especificamente para empresas do setor de mármore e granito. O sistema automatiza e otimiza processos operacionais, financeiros e administrativos, proporcionando maior eficiência e controle para o negócio.

### 🎯 Características Principais

- **🏢 ERP Completo:** Gestão integrada de todos os processos empresariais
- **💎 Especializado:** Desenvolvido especificamente para o setor de mármore e granito
- **🚀 Moderno:** Tecnologias atuais e interface responsiva
- **🔒 Seguro:** Múltiplas camadas de segurança e conformidade com LGPD
- **🌐 Localizável:** Suporte completo ao português brasileiro

---

## 🛠 Stack Tecnológica

### Backend
- **Framework:** Laravel 10.x
- **Linguagem:** PHP 8.1+
- **Banco de Dados:** MySQL 8.0+
- **Cache:** Redis (opcional)

### Frontend
- **CSS Framework:** Materialize CSS
- **JavaScript:** Vanilla ES6+
- **Template Engine:** Blade (Laravel)

### Ferramentas
- **PDF:** DomPDF
- **Excel:** Maatwebsite Excel
- **Autenticação:** Laravel Sanctum
- **Testes:** PHPUnit

---

## 📊 Funcionalidades

### ✅ Módulos Implementados

#### 📋 Cadastros Básicos
- Clientes completos (PF/PJ)
- Fornecedores
- Vendedores
- Funcionários
- Usuários e Perfis

#### 💰 Financeiro
- Contas a Pagar e Receber
- Fluxo de Caixa
- Conciliação Bancária
- Formas de Pagamento
- Centros de Custo
- Metas Financeiras

#### 📦 Estoque
- Controle de Produtos
- Gestão de Materiais
- Movimentações
- Inventário
- Categorias

#### 📝 Orçamentos
- Geração por Ambiente
- Cálculos Automáticos
- Sistema de Aprovação
- Integração Financeira

#### 📊 Relatórios
- Dashboard Executivo
- Relatórios Gerenciais
- Gráficos Interativos
- Exportação de Dados

### 🚧 Em Desenvolvimento

- 🏭 **Produção:** Ordem de Serviço, Cronograma, Qualidade
- 🚛 **Logística:** Entregas, Roteirização, Rastreamento
- 🛒 **Compras:** Cotações, Pedidos, Recebimento
- 👥 **RH:** Gestão de Pessoal, Ponto, Folha
- 🔧 **Manutenção:** Equipamentos, Preventiva, Histórico

### 🔮 Roadmap

- 📊 **Integrações Fiscais:** NFe, NFSe, SPED
- 🛍️ **E-commerce:** Catálogo Online, Marketplace
- 📱 **Apps Mobile:** Vendedores, Entregas, Clientes
- 🤖 **IA:** Análises Preditivas, Otimizações

---

## 🚀 Instalação

### Pré-requisitos
- PHP 8.1 ou superior
- Composer
- MySQL 8.0 ou superior
- Node.js (opcional, para assets)

### 1. Clone o Repositório
```bash
git clone https://github.com/seu-usuario/marmosys.git
cd marmosys
```

### 2. Instale as Dependências
```bash
composer install
```

### 3. Configure o Ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure o Banco de Dados
Edite o arquivo `.env` com suas configurações:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marmosys
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Execute as Migrações
```bash
php artisan migrate --seed
```

### 6. Inicie o Servidor
```bash
php artisan serve
```

Acesse: `http://localhost:8000`

---

## 🔧 Configuração

### Usuário Padrão
- **Email:** admin@marmosys.com.br
- **Senha:** admin123

### Configurações Importantes

#### Timezone
```env
APP_TIMEZONE=America/Sao_Paulo
```

#### Locale
```env
APP_LOCALE=pt_BR
FAKER_LOCALE=pt_BR
```

#### Cache (Opcional)
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

---

## 📖 Documentação

### 📚 Documentação Técnica
- [📋 Documento Técnico Completo](docs/Marmosys%20-%20Documento%20Técnico%20de%20Arquitetura%20e%20Segurança.md)
- [🏗️ Arquitetura do Sistema](docs/technical/README.md)
- [🔒 Segurança e Conformidade](docs/technical/README.md#segurança)

### 📦 Módulos
- [💰 Módulo Financeiro](docs/technical/modules/financial.md)
- [📝 Módulo de Orçamentos](docs/technical/modules/budget.md)
- [📋 Índice de Módulos](docs/technical/modules/README.md)

### 📊 Apresentações
- [📋 Documentação Completa (Word)](docs/Marmosys%20-%20Documentação%20Técnica.docx)
- [🎯 Apresentação Executiva (PowerPoint)](docs/Marmosys-Apresentacao-Simples.pptx)

---

## 🔒 Segurança

### Recursos Implementados
- ✅ Autenticação segura com Laravel Sanctum
- ✅ Proteção CSRF em todos os formulários
- ✅ Escape automático XSS no Blade
- ✅ Proteção SQL Injection via Eloquent ORM
- ✅ Criptografia AES-256-CBC
- ✅ Sistema completo de logs e auditoria
- ✅ Conformidade com LGPD

### Controle de Acesso
- **Administrador:** Acesso completo
- **Gerente:** Relatórios e aprovações
- **Operador:** Operações diárias

---

## 🧪 Testes

### Executar Testes
```bash
php artisan test
```

### Cobertura
- **Objetivo:** 80% cobertura mínima
- **Críticos:** 100% módulos financeiros
- **Tipos:** Unitários, Integração, Feature

---

## 📈 Status do Projeto

### Implementação Atual
- ✅ **60%** - Módulos Core implementados
- 🚧 **40%** - Módulos em desenvolvimento

### Módulos por Status
- ✅ **7 Módulos** - Completamente implementados
- 🚧 **5 Módulos** - Em desenvolvimento
- 📋 **3 Módulos** - Planejados

---

## 🤝 Contribuição

### Como Contribuir
1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Crie um Pull Request

### Padrões de Código
- **PSR-12** para PHP
- **Conventional Commits** para mensagens
- **Testes obrigatórios** para novas funcionalidades

---

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

---

## 📞 Suporte

### Contato
- **Email:** suporte@marmosys.com.br
- **Site:** www.marmosys.com.br
- **Telefone:** (XX) XXXX-XXXX

### Issues
Para reportar bugs ou solicitar funcionalidades, use as [Issues do GitHub](https://github.com/seu-usuario/marmosys/issues).

---

## 🏆 Créditos

Desenvolvido com ❤️ pela equipe Marmosys

### Tecnologias Utilizadas
- [Laravel](https://laravel.com) - Framework PHP
- [Materialize CSS](https://materializecss.com) - Framework CSS
- [MySQL](https://mysql.com) - Banco de Dados
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) - Geração de PDFs

---

<div align="center">

**⭐ Se este projeto te ajudou, considere dar uma estrela!**

Made with ❤️ in Brazil 🇧🇷

</div> 
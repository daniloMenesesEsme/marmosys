# MarmosyS - Sistema de Gestão

## Sobre
Sistema de gestão desenvolvido com Laravel e MaterializeCSS, focado em:
- Gestão Financeira
- Controle de Clientes
- Gestão de Produtos
- Relatórios Gerenciais

## Versão Atual
v1.0.0-beta

## Padrões do Sistema
- MVPS (MarmosyS Visual Pattern System)
- MRPS (MarmosyS Report Pattern System)

## Requisitos
- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js

## Instalação
1. Clone o repositório
2. Execute `composer install`
3. Copie `.env.example` para `.env`
4. Configure o banco de dados no `.env`
5. Execute `php artisan key:generate`
6. Execute `php artisan migrate`
7. Execute `php artisan db:seed` (opcional)

## Desenvolvimento
- Padrões PSR-12
- Laravel Best Practices
- MaterializeCSS para interface 
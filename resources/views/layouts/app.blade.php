<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    @stack('styles')
</head>
<body>
    <nav class="blue darken-2">
        <div class="nav-wrapper">
            <div class="container">
                <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <a href="{{ url('/dashboard') }}" class="brand-logo center">{{ config('app.name') }}</a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-icons left">exit_to_app</i>Sair
                    </a></li>
                </ul>
            </div>
        </div>
    </nav>

    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="background blue darken-2">
                </div>
                <span class="white-text name">{{ Auth::user()->name }}</span>
                <span class="white-text email">{{ Auth::user()->email }}</span>
            </div>
        </li>
        
        <li><a href="{{ route('dashboard') }}" class="waves-effect">
            <i class="material-icons">dashboard</i>Dashboard
        </a></li>
        
        <li><a href="{{ route('clients.index') }}" class="waves-effect">
            <i class="material-icons">people</i>Clientes
        </a></li>
        
        <li><a href="{{ route('financial.budgets.index') }}" class="waves-effect">
            <i class="material-icons">description</i>Orçamentos
        </a></li>
        
        <li><a href="{{ route('products.index') }}" class="waves-effect">
            <i class="material-icons">inventory</i>Produtos
        </a></li>
        
        <li>
            <div class="divider"></div>
        </li>
        
        <li><a class="subheader">Financeiro</a></li>
        <li><a href="{{ route('financial.accounts.index') }}" class="waves-effect">
            <i class="material-icons">account_balance</i>Contas
        </a></li>
        <li><a href="{{ route('financial.categories.index') }}" class="waves-effect">
            <i class="material-icons">category</i>Categorias
        </a></li>
        <li><a href="{{ route('financial.reports.index') }}" class="waves-effect">
            <i class="material-icons">assessment</i>Relatórios
        </a></li>
        <li><a href="{{ route('financial.forecast.index') }}" class="waves-effect">
            <i class="material-icons">trending_up</i>Previsão
        </a></li>
        <li><a href="{{ route('financial.reconciliation.index') }}" class="waves-effect">
            <i class="material-icons">check_circle</i>Conciliação
        </a></li>
        <li><a href="{{ route('financial.cost-centers.index') }}" class="waves-effect">
            <i class="material-icons">business</i>Centros de Custo
        </a></li>
        <!--
        <li><a href="{{ route('financial.projections.index') }}" class="waves-effect">
            <i class="material-icons">timeline</i>Projeção
        </a></li>
        -->
        <li><a href="{{ route('financial.goals.index') }}" class="waves-effect">
            <i class="material-icons">flag</i>Metas
        </a></li>
        
        <li>
            <div class="divider"></div>
        </li>
        
        <li><a class="subheader">Configurações</a></li>
        <li><a href="{{ route('settings.backup.index') }}" class="waves-effect">
            <i class="material-icons">backup</i>Backup do Sistema
        </a></li>

        <li>
            <div class="divider"></div>
        </li>
        
        <li><a href="{{ route('logout') }}" class="waves-effect"  
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="material-icons">exit_to_app</i>Sair
        </a></li>
    </ul>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <main>
        <div class="container" style="margin-left: 300px; padding: 20px;">
            @yield('content')
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            M.AutoInit();
        });
    </script>
    @stack('scripts')
</body>
</html> 
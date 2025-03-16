<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title')</title>
    
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilo personalizado para o menu */
        .sidenav {
            width: 300px; /* Menu mais largo */
        }
        
        .sidenav li > a {
            font-size: 16px !important; /* Fonte maior */
            height: 50px !important; /* Altura maior */
            line-height: 50px !important;
            padding: 0 16px !important;
        }
        
        .sidenav .collapsible-header {
            font-size: 16px !important;
            height: 50px !important;
            line-height: 50px !important;
            padding: 0 16px !important;
        }
        
        /* Ícone de seta personalizado */
        .sidenav .collapsible-header i.right {
            margin-right: 0;
            font-size: 24px;
        }
        
        /* Espaçamento dos itens do submenu */
        .sidenav .collapsible-body li a {
            padding-left: 32px !important;
        }
        
        /* Cor de fundo quando expandido */
        .sidenav .collapsible-body {
            background-color: rgba(0,0,0,0.05);
        }
    </style>
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
        
        <li><a href="{{ route('companies.index') }}" class="waves-effect">
            <i class="material-icons">business</i>Empresas
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
        
        <li>
            <ul class="collapsible collapsible-accordion">
                <li>
                    <a class="collapsible-header waves-effect">
                        <i class="material-icons">account_balance</i>
                        <span style="margin-left: 8px;">Financeiro</span>
                        <i class="fas fa-chevron-down right"></i>
                    </a>
                    <div class="collapsible-body">
                        <ul>
                            <!-- Submenu Cadastro -->
                            <li>
                                <ul class="collapsible">
                                    <li>
                                        <a class="collapsible-header waves-effect">
                                            <i class="material-icons">list</i>
                                            <span style="margin-left: 8px;">Cadastro</span>
                                            <i class="fas fa-chevron-down right"></i>
                                        </a>
                                        <div class="collapsible-body">
                                            <ul>
                                                <li>
                                                    <a href="{{ route('financial.categories.index') }}" class="waves-effect">
                                                        <i class="material-icons">category</i>
                                                        <span>Categorias</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('financial.cost-centers.index') }}" class="waves-effect">
                                                        <i class="material-icons">business</i>
                                                        <span>Centros de Custo</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('financial.registration.payment-methods.index') }}" class="waves-effect">
                                                        <i class="material-icons">payment</i>
                                                        <span>Forma de Pagamento</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('financial.registration.payment-plans.index') }}" class="waves-effect">
                                                        <i class="material-icons">account_balance_wallet</i>
                                                        <span>Plano de Pagamento</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Outros itens do menu Financeiro -->
                            <li>
                                <a href="{{ route('financial.accounts.index') }}" class="waves-effect">
                                    <i class="material-icons">account_balance</i>Contas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('financial.reports.index') }}" class="waves-effect">
                                    <i class="material-icons">assessment</i>Relatórios
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('financial.forecast.index') }}" class="waves-effect">
                                    <i class="material-icons">trending_up</i>Previsão
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('financial.reconciliation.index') }}" class="waves-effect">
                                    <i class="material-icons">check_circle</i>Conciliação
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        
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

    <!-- Scripts (na ordem correta) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa todos os componentes do Materialize
            M.AutoInit();

            // Inicializa a navegação lateral
            var sidenav = document.querySelectorAll('.sidenav');
            M.Sidenav.init(sidenav);

            // Inicializa todos os menus colapsáveis
            var elems = document.querySelectorAll('.collapsible');
            M.Collapsible.init(elems, {
                accordion: false // Permite múltiplos itens abertos
            });

            // Previne que o menu feche ao clicar em itens dentro dele
            document.querySelectorAll('.collapsible-body a:not(.collapsible-header)').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });

            // Adiciona margem aos itens do submenu
            document.querySelectorAll('.collapsible .collapsible-body').forEach(function(submenu) {
                submenu.style.marginLeft = '10px';
            });
        });
    </script>
    @stack('scripts')

    <!-- Adicione antes do fechamento do body -->
    <footer class="page-footer" style="position: fixed; bottom: 0; width: 100%; z-index: 1000; background-color: #1976d2; height: 40px; line-height: 30px; padding: 0;">
        <div class="footer-copyright" style="background-color: rgba(0,0,0,0.1); height: 100%;">
            <div class="container">
                <div class="row mb-0" style="margin-bottom: 0 !important;">
                    <div class="col s6" style="font-size: 15px;">
                        © {{ date('Y') }} MarmosyS
                    </div>
                    <div class="col s6 right-align" style="font-size: 15px;">
                        {{ \App\Helpers\VersionHelper::getVersion() }}
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Adicione um espaço para evitar que o conteúdo fique sob o footer -->
    <div style="height: 30px;"></div>
</body>
</html> 
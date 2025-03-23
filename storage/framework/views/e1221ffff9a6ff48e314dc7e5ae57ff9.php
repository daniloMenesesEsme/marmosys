<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
    
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="<?php echo e(asset('css/styles.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
            padding-left: 300px;
            padding-top: 64px;
        }

        /* Ajustes para selects do Materialize */
        .select-wrapper input.select-dropdown {
            width: calc(100% - 20px) !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            margin-right: 20px !important;
            text-align: left !important;
        }
        
        .select-wrapper .caret {
            position: absolute !important;
            right: 0 !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }

        /* Navbar fixa */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            height: 64px;
            line-height: 64px;
            padding-left: 300px;
        }

        nav .brand-logo {
            position: relative;
            left: 0;
            transform: none;
            padding-left: 20px;
        }

        /* Menu lateral */
        .sidenav {
            width: 300px;
            top: 64px;
            height: calc(100% - 64px);
        }
        
        .sidenav li > a {
            font-size: 16px !important;
            height: 50px !important;
            line-height: 50px !important;
            padding: 0 16px !important;
        }
        
        .sidenav .collapsible-header {
            font-size: 16px !important;
            height: 50px !important;
            line-height: 50px !important;
            padding: 0 16px !important;
        }
        
        .sidenav .collapsible-header i.right {
            margin-right: 0;
            font-size: 24px;
        }
        
        .sidenav .collapsible-body li a {
            padding-left: 32px !important;
        }
        
        .sidenav .collapsible-body {
            background-color: rgba(0,0,0,0.05);
        }

        @media only screen and (max-width: 992px) {
            nav {
                padding-left: 0;
            }

            nav .brand-logo {
                left: 50%;
                transform: translateX(-50%);
            }

            main {
                padding-left: 0;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navbar fixa -->
    <nav class="blue darken-2">
        <div class="nav-wrapper">
            <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <a href="<?php echo e(url('/dashboard')); ?>" class="brand-logo"><?php echo e(config('app.name')); ?></a>
            <ul class="right">
                <li><a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="material-icons left">exit_to_app</i>Sair
                </a></li>
            </ul>
        </div>
    </nav>

    <!-- Menu lateral -->
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="background blue darken-2">
                </div>
                <span class="white-text name"><?php echo e(Auth::user()->name); ?></span>
                <span class="white-text email"><?php echo e(Auth::user()->email); ?></span>
            </div>
        </li>
        
        <li><a href="<?php echo e(route('dashboard')); ?>" class="waves-effect">
            <i class="material-icons">dashboard</i>Dashboard
        </a></li>
        
        <li><a href="<?php echo e(route('companies.index')); ?>" class="waves-effect">
            <i class="material-icons">business</i>Empresas
        </a></li>
        
        <li><a href="<?php echo e(route('clients.index')); ?>" class="waves-effect">
            <i class="material-icons">people</i>Clientes
        </a></li>
        
        <li><a href="<?php echo e(route('financial.budgets.index')); ?>" class="waves-effect">
            <i class="material-icons">description</i>Orçamentos
        </a></li>
        
        <li><a href="<?php echo e(route('products.index')); ?>" class="waves-effect">
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
                                                    <a href="<?php echo e(route('financial.categories.index')); ?>" class="waves-effect">
                                                        <i class="material-icons">category</i>
                                                        <span>Categorias</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo e(route('financial.cost-centers.index')); ?>" class="waves-effect">
                                                        <i class="material-icons">business</i>
                                                        <span>Centros de Custo</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo e(route('financial.registration.payment-methods.index')); ?>" class="waves-effect">
                                                        <i class="material-icons">payment</i>
                                                        <span>Forma de Pagamento</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo e(route('financial.registration.payment-plans.index')); ?>" class="waves-effect">
                                                        <i class="material-icons">account_balance_wallet</i>
                                                        <span>Plano de Pagamento</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo e(route('financial.registration.agents.index')); ?>" class="waves-effect">
                                                        <i class="material-icons">account_balance</i>
                                                        <span>Agentes Financeiros</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Outros itens do menu Financeiro -->
                            <li>
                                <a href="<?php echo e(route('financial.accounts.index')); ?>" class="waves-effect">
                                    <i class="material-icons">account_balance</i>Contas
                                </a>
                            </li>
                            <li>
                                <a href="/financial/reports" class="waves-effect">
                                    <i class="material-icons">assessment</i>Relatórios
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('financial.forecast.index')); ?>" class="waves-effect">
                                    <i class="material-icons">trending_up</i>Previsão
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('financial.reconciliation.index')); ?>" class="waves-effect">
                                    <i class="material-icons">check_circle</i>Conciliação
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        
        <li><a href="<?php echo e(route('financial.goals.index')); ?>" class="waves-effect">
            <i class="material-icons">flag</i>Metas
        </a></li>
        
        <li>
            <div class="divider"></div>
        </li>
        
        <li><a class="subheader">Configurações</a></li>
        <li><a href="<?php echo e(route('settings.backup.index')); ?>" class="waves-effect">
            <i class="material-icons">backup</i>Backup do Sistema
        </a></li>
        
        <li><a href="<?php echo e(route('diagnostic.index')); ?>" class="waves-effect">
            <i class="material-icons">build</i>Diagnóstico do Sistema
        </a></li>

        <li>
            <div class="divider"></div>
        </li>
        
        <li><a href="<?php echo e(route('logout')); ?>" class="waves-effect"  
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="material-icons">exit_to_app</i>Sair
        </a></li>
    </ul>

    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
    </form>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
        
        <!-- Versão do sistema -->
        <footer class="page-footer blue darken-2" style="padding-top: 0; margin-top: 30px;">
            <div class="footer-copyright">
                <div class="container">
                    <div class="row" style="margin-bottom: 0;">
                        <div class="col s6">
                            &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>

                        </div>
                        <div class="col s6 right-align">
                            <span class="white-text">Versão <?php echo e(config('version.number')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            M.AutoInit();
            var sidenav = document.querySelectorAll('.sidenav');
            M.Sidenav.init(sidenav);
            var elems = document.querySelectorAll('.collapsible');
            M.Collapsible.init(elems, {
                accordion: false
            });
            document.querySelectorAll('.collapsible-body a:not(.collapsible-header)').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH C:\laragon\www\marmosys\resources\views/layouts/app.blade.php ENDPATH**/ ?>
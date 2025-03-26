

<?php $__env->startSection('title', 'Relatório Financeiro'); ?>

<?php
    $meses = [
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    ];
?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="row">
        <div class="col s12">
            <div class="page-title">
                <i class="material-icons">assessment</i>
                <h4>Relatório Financeiro</h4>
            </div>

            <!-- Menu de Relatórios -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Relatórios Disponíveis</span>
                    <div class="row">
                        <div class="col s12 m6 l4">
                            <a href="<?php echo e(route('financial.reports.index')); ?>" class="btn waves-effect waves-light blue darken-1 btn-large btn-block">
                                <i class="material-icons left">account_balance</i>
                                Fluxo Financeiro
                            </a>
                        </div>
                        <div class="col s12 m6 l4">
                            <a href="<?php echo e(route('financial.reports.agents')); ?>" class="btn waves-effect waves-light teal darken-1 btn-large btn-block">
                                <i class="material-icons left">business</i>
                                Agentes Financeiros
                            </a>
                        </div>
                        <!-- Adicione mais botões para outros relatórios aqui -->
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card">
                <div class="card-content">
                    <form action="<?php echo e(route('financial.reports.index')); ?>" method="GET">
                        <div class="row mb-0">
                            <div class="input-field col s12 m2">
                                <i class="material-icons prefix">event</i>
                                <input type="text" class="datepicker" name="data_inicio" value="<?php echo e(request('data_inicio')); ?>" id="data_inicio">
                                <label for="data_inicio">Data Início</label>
                            </div>

                            <div class="input-field col s12 m2">
                                <i class="material-icons prefix">event</i>
                                <input type="text" class="datepicker" name="data_fim" value="<?php echo e(request('data_fim')); ?>" id="data_fim">
                                <label for="data_fim">Data Fim</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">swap_vert</i>
                                <input type="text" id="tipo_display" class="dropdown-trigger" data-target="dropdown_tipo" 
                                    value="<?php echo e(request('tipo') ? ucfirst($tipos[request('tipo')]) : 'Todos'); ?>" readonly>
                                <input type="hidden" name="tipo" id="tipo_value" value="<?php echo e(request('tipo')); ?>">
                                <label for="tipo_display" class="active">Tipo</label>
                                
                                <ul id="dropdown_tipo" class="dropdown-content">
                                    <li><a href="#!" data-value="">Todos</a></li>
                                    <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="#!" data-value="<?php echo e($value); ?>"><?php echo e($label); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>

                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">flag</i>
                                <input type="text" id="status_display" class="dropdown-trigger" data-target="dropdown_status" 
                                    value="<?php echo e(request('status') ? ucfirst($status[request('status')]) : 'Todos'); ?>" readonly>
                                <input type="hidden" name="status" id="status_value" value="<?php echo e(request('status')); ?>">
                                <label for="status_display" class="active">Status</label>
                                
                                <ul id="dropdown_status" class="dropdown-content">
                                    <li><a href="#!" data-value="">Todos</a></li>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a href="#!" data-value="<?php echo e($value); ?>"><?php echo e($label); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>

                            <div class="input-field col s12 m2">
                                <button type="submit" class="btn waves-effect waves-light teal">
                                    <i class="material-icons">search</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Botões de Exportação e Tabela -->
            <div class="card">
                <div class="card-content">
                    <div class="card-title d-flex">
                        <div class="flex-grow-1">Resultados</div>
                        <div>
                            <a href="<?php echo e(route('financial.reports.export.pdf')); ?>" 
                               class="btn-floating waves-effect waves-light red tooltipped" 
                               data-position="bottom" 
                               data-tooltip="Exportar PDF"
                               target="_blank">
                                <i class="material-icons">picture_as_pdf</i>
                            </a>
                            <a href="<?php echo e(route('financial.reports.export.excel')); ?>" 
                               class="btn-floating waves-effect waves-light green tooltipped" 
                               data-position="bottom" 
                               data-tooltip="Exportar Excel">
                                <i class="material-icons">grid_on</i>
                            </a>
                        </div>
                    </div>

                    <table class="highlight responsive-table">
                        <thead>
                            <tr>
                                <th>Data Vencimento</th>
                                <th>Descrição</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Data Pagamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($transaction->data_vencimento->format('d/m/Y')); ?></td>
                                    <td><?php echo e($transaction->descricao); ?></td>
                                    <td>
                                        <i class="material-icons tiny <?php echo e($transaction->tipo === 'receita' ? 'green-text' : 'red-text'); ?>">
                                            <?php echo e($transaction->tipo === 'receita' ? 'arrow_upward' : 'arrow_downward'); ?>

                                        </i>
                                        <?php echo e(ucfirst($transaction->tipo)); ?>

                                    </td>
                                    <td>R$ <?php echo e(number_format($transaction->valor, 2, ',', '.')); ?></td>
                                    <td>
                                        <span class="badge <?php echo e($transaction->status === 'pago' ? 'green' : ($transaction->status === 'cancelado' ? 'red' : 'orange')); ?> white-text">
                                            <?php echo e(ucfirst($transaction->status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($transaction->data_pagamento ? $transaction->data_pagamento->format('d/m/Y') : '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="center-align">Nenhum registro encontrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Totalizadores -->
                    <?php if($transactions->isNotEmpty()): ?>
                        <div class="row mt-4">
                            <div class="col s12">
                                <div class="card-panel grey lighten-4">
                                    <h5 class="m-0"><i class="material-icons left">calculate</i> Totais</h5>
                                    <div class="row mb-0">
                                        <div class="col s12 m4">
                                            <p class="green-text">
                                                <i class="material-icons tiny">arrow_upward</i>
                                                <strong>Total Receitas:</strong> R$ <?php echo e(number_format($transactions->where('tipo', 'receita')->sum('valor'), 2, ',', '.')); ?>

                                            </p>
                                        </div>
                                        <div class="col s12 m4">
                                            <p class="red-text">
                                                <i class="material-icons tiny">arrow_downward</i>
                                                <strong>Total Despesas:</strong> R$ <?php echo e(number_format($transactions->where('tipo', 'despesa')->sum('valor'), 2, ',', '.')); ?>

                                            </p>
                                        </div>
                                        <div class="col s12 m4">
                                            <p class="<?php echo e($transactions->where('tipo', 'receita')->sum('valor') - $transactions->where('tipo', 'despesa')->sum('valor') >= 0 ? 'green-text' : 'red-text'); ?>">
                                                <i class="material-icons tiny">account_balance</i>
                                                <strong>Saldo:</strong> R$ <?php echo e(number_format($transactions->where('tipo', 'receita')->sum('valor') - $transactions->where('tipo', 'despesa')->sum('valor'), 2, ',', '.')); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content {
    padding: 15px;
}
.page-title {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}
.page-title i {
    margin-right: 10px;
}
.page-title h4 {
    margin: 0;
}
.card-title.d-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.mt-4 {
    margin-top: 2rem !important;
}
.mb-0 {
    margin-bottom: 0 !important;
}
.btn-block {
    display: block;
    width: 100%;
    margin-bottom: 15px;
    text-align: left;
    height: 54px;
    line-height: 54px;
}
.btn-block i {
    margin-right: 8px;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa datepickers com opção de limpar
    var elems = document.querySelectorAll('.datepicker');
    M.Datepicker.init(elems, {
        format: 'dd/mm/yyyy',
        showClearBtn: true,
        i18n: {
            months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            cancel: 'Cancelar',
            clear: 'Limpar',
            done: 'OK'
        }
    });

    // Inicializa dropdowns
    var elems = document.querySelectorAll('.dropdown-trigger');
    M.Dropdown.init(elems, {
        constrainWidth: false,
        coverTrigger: false
    });

    // Handlers para os dropdowns
    document.querySelectorAll('#dropdown_tipo li a').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var value = this.getAttribute('data-value');
            var text = this.textContent;
            document.getElementById('tipo_display').value = text;
            document.getElementById('tipo_value').value = value;
        });
    });

    document.querySelectorAll('#dropdown_status li a').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var value = this.getAttribute('data-value');
            var text = this.textContent;
            document.getElementById('status_display').value = text;
            document.getElementById('status_value').value = value;
        });
    });
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/reports/index.blade.php ENDPATH**/ ?>
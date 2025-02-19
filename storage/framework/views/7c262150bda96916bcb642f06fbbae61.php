

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
<div class="row">
    <!-- Filtros -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <form action="<?php echo e(route('financial.reports.index')); ?>" method="GET">
                    <div class="row mb-0">
                        <div class="input-field col s12 m4">
                            <select name="mes" id="mes">
                                <?php $__currentLoopData = $meses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($num); ?>" <?php echo e($mes == $num ? 'selected' : ''); ?>>
                                        <?php echo e($nome); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="mes">Mês</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <select name="ano" id="ano">
                                <?php $__currentLoopData = range(date('Y')-2, date('Y')+1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($a); ?>" <?php echo e($ano == $a ? 'selected' : ''); ?>>
                                        <?php echo e($a); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="ano">Ano</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">search</i>
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Após o formulário de filtro -->
    <div class="col s12 right-align" style="margin-top: -20px; margin-bottom: 20px;">
        <a href="<?php echo e(route('financial.reports.excel', ['mes' => $mes, 'ano' => $ano])); ?>" 
           class="btn waves-effect waves-light green" style="margin-right: 10px;">
            <i class="material-icons left">table_chart</i>
            Exportar Excel
        </a>
        
        <a href="<?php echo e(route('financial.reports.pdf', ['mes' => $mes, 'ano' => $ano])); ?>" 
           class="btn waves-effect waves-light red" target="_blank">
            <i class="material-icons left">picture_as_pdf</i>
            Baixar PDF
        </a>
    </div>

    <!-- Cards de Totais -->
    <div class="col s12 m4">
        <div class="card green lighten-1">
            <div class="card-content white-text">
                <span class="card-title">Receitas</span>
                <h4>R$ <?php echo e(number_format($totais['receitas'], 2, ',', '.')); ?></h4>
            </div>
        </div>
    </div>

    <div class="col s12 m4">
        <div class="card red lighten-1">
            <div class="card-content white-text">
                <span class="card-title">Despesas</span>
                <h4>R$ <?php echo e(number_format($totais['despesas'], 2, ',', '.')); ?></h4>
            </div>
        </div>
    </div>

    <div class="col s12 m4">
        <div class="card <?php echo e($totais['saldo'] >= 0 ? 'blue' : 'orange'); ?> lighten-1">
            <div class="card-content white-text">
                <span class="card-title">Saldo</span>
                <h4>R$ <?php echo e(number_format($totais['saldo'], 2, ',', '.')); ?></h4>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Fluxo de Caixa Diário</span>
                <canvas id="fluxoCaixa" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabelas -->
    <div class="col s12 m6">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Receitas por Categoria</span>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th class="right-align">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $categorias->where('tipo', 'receita'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($categoria->nome); ?></td>
                            <td class="right-align">R$ <?php echo e(number_format($categoria->total, 2, ',', '.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th class="right-align">R$ <?php echo e(number_format($totais['receitas'], 2, ',', '.')); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col s12 m6">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Despesas por Categoria</span>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th class="right-align">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $categorias->where('tipo', 'despesa'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($categoria->nome); ?></td>
                            <td class="right-align">R$ <?php echo e(number_format($categoria->total, 2, ',', '.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th class="right-align">R$ <?php echo e(number_format($totais['despesas'], 2, ',', '.')); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Fluxo Diário -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Fluxo de Caixa Diário</span>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th class="right-align">Receitas</th>
                            <th class="right-align">Despesas</th>
                            <th class="right-align">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $fluxoDiario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(\Carbon\Carbon::parse($dia->data_pagamento)->format('d/m/Y')); ?></td>
                            <td class="right-align">R$ <?php echo e(number_format($dia->receitas, 2, ',', '.')); ?></td>
                            <td class="right-align">R$ <?php echo e(number_format($dia->despesas, 2, ',', '.')); ?></td>
                            <td class="right-align">R$ <?php echo e(number_format($dia->receitas - $dia->despesas, 2, ',', '.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    // Dados para o gráfico
    var fluxoDiario = <?php echo json_encode($fluxoDiario, 15, 512) ?>;
    var labels = fluxoDiario.map(dia => {
        return new Date(dia.data_pagamento).toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit'
        });
    });
    var receitas = fluxoDiario.map(dia => dia.receitas);
    var despesas = fluxoDiario.map(dia => dia.despesas);

    // Configuração do gráfico
    var ctx = document.getElementById('fluxoCaixa').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Receitas',
                data: receitas,
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                fill: true
            }, {
                label: 'Despesas',
                data: despesas,
                borderColor: '#F44336',
                backgroundColor: 'rgba(244, 67, 54, 0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw.toLocaleString('pt-BR', {
                                style: 'currency',
                                currency: 'BRL'
                            });
                            return context.dataset.label + ': ' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/reports/index.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Projeção de Fluxo de Caixa'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Saldo Atual -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Saldo Atual</span>
                <h4 class="<?php echo e($saldoAtual >= 0 ? 'green-text' : 'red-text'); ?>">
                    R$ <?php echo e(number_format($saldoAtual, 2, ',', '.')); ?>

                </h4>
            </div>
        </div>
    </div>

    <!-- Filtro de Meses -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <form action="<?php echo e(route('financial.projections.index')); ?>" method="GET">
                    <div class="row" style="margin-bottom: 0;">
                        <div class="input-field col s12 m6">
                            <select name="meses" onchange="this.form.submit()">
                                <?php $__currentLoopData = [3, 6, 12, 24]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($option); ?>" <?php echo e($meses == $option ? 'selected' : ''); ?>>
                                        <?php echo e($option); ?> meses
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label>Período de Projeção</label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Gráfico de Projeção -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Projeção de Fluxo de Caixa</span>
                <canvas id="graficoProjecao" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabela de Projeção -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Detalhamento Mensal</span>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Período</th>
                            <th class="right-align">Receitas</th>
                            <th class="right-align">Despesas</th>
                            <th class="right-align">Saldo do Mês</th>
                            <th class="right-align">Saldo Projetado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $projecao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($mes['nome']); ?></td>
                            <td class="right-align green-text">
                                R$ <?php echo e(number_format($mes['receitas'], 2, ',', '.')); ?>

                            </td>
                            <td class="right-align red-text">
                                R$ <?php echo e(number_format($mes['despesas'], 2, ',', '.')); ?>

                            </td>
                            <td class="right-align <?php echo e($mes['saldo_mes'] >= 0 ? 'green-text' : 'red-text'); ?>">
                                R$ <?php echo e(number_format($mes['saldo_mes'], 2, ',', '.')); ?>

                            </td>
                            <td class="right-align <?php echo e($mes['saldo_projetado'] >= 0 ? 'green-text' : 'red-text'); ?>">
                                R$ <?php echo e(number_format($mes['saldo_projetado'], 2, ',', '.')); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detalhamento por Categoria -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Detalhamento por Categoria</span>
                
                <div class="row">
                    <!-- Receitas -->
                    <div class="col s12 m6">
                        <h5>Receitas Previstas</h5>
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th class="right-align">Valor Total</th>
                                    <th class="center-align">Qtd. Contas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $categorias->where('tipo', 'receita'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($categoria->nome); ?></td>
                                    <td class="right-align">
                                        R$ <?php echo e(number_format($categoria->valor_total, 2, ',', '.')); ?>

                                    </td>
                                    <td class="center-align"><?php echo e($categoria->total_contas); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Despesas -->
                    <div class="col s12 m6">
                        <h5>Despesas Previstas</h5>
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th class="right-align">Valor Total</th>
                                    <th class="center-align">Qtd. Contas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $categorias->where('tipo', 'despesa'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($categoria->nome); ?></td>
                                    <td class="right-align">
                                        R$ <?php echo e(number_format($categoria->valor_total, 2, ',', '.')); ?>

                                    </td>
                                    <td class="center-align"><?php echo e($categoria->total_contas); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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

    var projecao = <?php echo json_encode($projecao, 15, 512) ?>;
    var ctx = document.getElementById('graficoProjecao').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: projecao.map(p => p.nome),
            datasets: [{
                label: 'Receitas',
                data: projecao.map(p => p.receitas),
                backgroundColor: 'rgba(76, 175, 80, 0.5)',
                borderColor: '#4CAF50',
                borderWidth: 1
            }, {
                label: 'Despesas',
                data: projecao.map(p => p.despesas),
                backgroundColor: 'rgba(244, 67, 54, 0.5)',
                borderColor: '#F44336',
                borderWidth: 1
            }, {
                label: 'Saldo Projetado',
                data: projecao.map(p => p.saldo_projetado),
                type: 'line',
                borderColor: '#2196F3',
                backgroundColor: 'rgba(33, 150, 243, 0.1)',
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/projections/index.blade.php ENDPATH**/ ?>
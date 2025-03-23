

<?php $__env->startSection('title', 'Previsão Financeira'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Contas Vencidas -->
    <div class="col s12">
        <div class="card red lighten-4">
            <div class="card-content">
                <span class="card-title">Contas Vencidas</span>
                
                <div class="row">
                    <div class="col s12 m6">
                        <h5>Receitas Vencidas: R$ <?php echo e(number_format($totaisVencidos['receitas'], 2, ',', '.')); ?></h5>
                        <?php if($contasVencidas->has('receita')): ?>
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Vencimento</th>
                                        <th>Descrição</th>
                                        <th class="right-align">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $contasVencidas['receita']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($conta->data_vencimento->format('d/m/Y')); ?></td>
                                        <td><?php echo e($conta->descricao); ?></td>
                                        <td class="right-align">R$ <?php echo e(number_format($conta->valor, 2, ',', '.')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Não há receitas vencidas.</p>
                        <?php endif; ?>
                    </div>

                    <div class="col s12 m6">
                        <h5>Despesas Vencidas: R$ <?php echo e(number_format($totaisVencidos['despesas'], 2, ',', '.')); ?></h5>
                        <?php if($contasVencidas->has('despesa')): ?>
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Vencimento</th>
                                        <th>Descrição</th>
                                        <th class="right-align">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $contasVencidas['despesa']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($conta->data_vencimento->format('d/m/Y')); ?></td>
                                        <td><?php echo e($conta->descricao); ?></td>
                                        <td class="right-align">R$ <?php echo e(number_format($conta->valor, 2, ',', '.')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Não há despesas vencidas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Previsão Futura -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Previsão para os Próximos 6 Meses</span>
                
                <canvas id="graficoPrevisao" style="width: 100%; height: 300px;"></canvas>

                <table class="striped mt-4">
                    <thead>
                        <tr>
                            <th>Período</th>
                            <th class="right-align">Receitas Previstas</th>
                            <th class="right-align">Despesas Previstas</th>
                            <th class="right-align">Saldo Previsto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $previsao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($periodo['nome']); ?></td>
                            <td class="right-align">R$ <?php echo e(number_format($periodo['receitas'], 2, ',', '.')); ?></td>
                            <td class="right-align">R$ <?php echo e(number_format($periodo['despesas'], 2, ',', '.')); ?></td>
                            <td class="right-align">
                                <span class="<?php echo e($periodo['saldo'] >= 0 ? 'green-text' : 'red-text'); ?>">
                                    R$ <?php echo e(number_format($periodo['saldo'], 2, ',', '.')); ?>

                                </span>
                            </td>
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
    var previsao = <?php echo json_encode($previsao, 15, 512) ?>;
    var labels = previsao.map(p => p.nome);
    var receitas = previsao.map(p => p.receitas);
    var despesas = previsao.map(p => p.despesas);
    var saldos = previsao.map(p => p.saldo);

    var ctx = document.getElementById('graficoPrevisao').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Receitas',
                data: receitas,
                backgroundColor: 'rgba(76, 175, 80, 0.5)',
                borderColor: '#4CAF50',
                borderWidth: 1
            }, {
                label: 'Despesas',
                data: despesas,
                backgroundColor: 'rgba(244, 67, 54, 0.5)',
                borderColor: '#F44336',
                borderWidth: 1
            }, {
                label: 'Saldo',
                data: saldos,
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/forecast/index.blade.php ENDPATH**/ ?>
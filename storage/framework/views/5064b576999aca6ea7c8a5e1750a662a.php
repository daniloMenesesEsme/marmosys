

<?php $__env->startSection('title', 'Regiões de Vendedores'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Regiões de Vendedores</span>
                    
                    <div class="row">
                        <div class="col s12 m6">
                            <h5>Distribuição por Estado</h5>
                            <div style="height: 300px; margin-top: 20px;">
                                <canvas id="regionChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="col s12 m6">
                            <h5>Detalhes por Estado</h5>
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th>Total de Vendedores</th>
                                        <th>Porcentagem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $totalVendedores = $regions->sum('total');
                                    ?>
                                    
                                    <?php $__empty_1 = true; $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($region->estado); ?></td>
                                            <td><?php echo e($region->total); ?></td>
                                            <td><?php echo e(number_format(($region->total / $totalVendedores) * 100, 1)); ?>%</td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="3" class="center-align">Nenhuma região encontrada</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th><?php echo e($totalVendedores); ?></th>
                                        <th>100%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col s12">
                            <h5>Vendedores por Região</h5>
                            <table class="striped responsive-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Estado</th>
                                        <th>Cidade</th>
                                        <th>Telefone</th>
                                        <th>Email</th>
                                        <th>Comissão (%)</th>
                                        <th>Meta Mensal</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($seller->nome); ?></td>
                                            <td><?php echo e($seller->estado ?: '-'); ?></td>
                                            <td><?php echo e($seller->cidade ?: '-'); ?></td>
                                            <td><?php echo e($seller->telefone ?: $seller->celular); ?></td>
                                            <td><?php echo e($seller->email); ?></td>
                                            <td><?php echo e(number_format($seller->percentual_comissao, 2)); ?>%</td>
                                            <td>R$ <?php echo e(number_format($seller->meta_mensal, 2, ',', '.')); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('sellers.show', $seller)); ?>" class="btn-floating btn-small waves-effect waves-light blue">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" class="center-align">Nenhum vendedor encontrado</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            
                            <?php echo e($sellers->links()); ?>

                        </div>
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
        // Dados para o gráfico
        var estados = [
            <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                '<?php echo e($region->estado); ?>',
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ];
        
        var totais = [
            <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($region->total); ?>,
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ];
        
        // Cores aleatórias para o gráfico
        var cores = [];
        for (var i = 0; i < estados.length; i++) {
            cores.push(
                'hsl(' + (i * 360 / estados.length) + ', 70%, 60%)'
            );
        }
        
        // Criar o gráfico
        var ctx = document.getElementById('regionChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: estados,
                datasets: [{
                    data: totais,
                    backgroundColor: cores
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' vendedores (' + percentage + '%)';
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/sellers/regions.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Regiões de Fornecedores'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col s12">
            <h4 class="header">
                <i class="material-icons left">place</i>
                Regiões de Fornecedores
            </h4>
        </div>
    </div>

    <div class="row">
        <!-- Mapa do Brasil -->
        <div class="col s12 m6">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Distribuição por Estado</span>
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>

        <!-- Tabela de Distribuição -->
        <div class="col s12 m6">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Quantidade por Estado</span>
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Quantidade</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $total = $regions->sum('total');
                            ?>
                            <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($region->estado); ?></td>
                                    <td><?php echo e($region->total); ?></td>
                                    <td><?php echo e(number_format(($region->total / $total) * 100, 1)); ?>%</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th><?php echo e($total); ?></th>
                                <th>100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Resumo por Região -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Resumo por Região</span>
                    <?php
                        $regioes = [
                            'Norte' => ['AC', 'AM', 'AP', 'PA', 'RO', 'RR', 'TO'],
                            'Nordeste' => ['AL', 'BA', 'CE', 'MA', 'PB', 'PE', 'PI', 'RN', 'SE'],
                            'Centro-Oeste' => ['DF', 'GO', 'MT', 'MS'],
                            'Sudeste' => ['ES', 'MG', 'RJ', 'SP'],
                            'Sul' => ['PR', 'RS', 'SC']
                        ];

                        $totaisPorRegiao = [];
                        foreach ($regioes as $regiao => $estados) {
                            $totaisPorRegiao[$regiao] = $regions->whereIn('estado', $estados)->sum('total');
                        }
                    ?>

                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Região</th>
                                <th>Quantidade</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $totaisPorRegiao; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $regiao => $totalRegiao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($regiao); ?></td>
                                    <td><?php echo e($totalRegiao); ?></td>
                                    <td><?php echo e(number_format(($totalRegiao / $total) * 100, 1)); ?>%</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados para o mapa
    const regions = <?php echo json_encode($regions, 15, 512) ?>;
    const total = <?php echo e($total); ?>;

    // Criar gráfico de pizza para distribuição por região
    const ctx = document.getElementById('map').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: regions.map(r => r.estado),
            datasets: [{
                data: regions.map(r => r.total),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56',
                    '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#36A2EB',
                    '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384',
                    '#36A2EB', '#FFCE56'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                title: {
                    display: true,
                    text: 'Distribuição de Fornecedores por Estado'
                }
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/suppliers/regions.blade.php ENDPATH**/ ?>
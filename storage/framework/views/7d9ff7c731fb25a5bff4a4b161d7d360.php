

<?php $__env->startSection('title', 'Centros de Custo'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Centros de Custo
                    <a href="<?php echo e(route('financial.cost-centers.create')); ?>" 
                       class="btn-floating waves-effect waves-light blue right">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th width="150">Total Despesas</th>
                            <th width="100">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $costCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($center->nome); ?></td>
                            <td><?php echo e($center->descricao); ?></td>
                            <td>
                                <span class="chip <?php echo e($center->ativo ? 'green' : 'red'); ?> white-text">
                                    <?php echo e($center->ativo ? 'Ativo' : 'Inativo'); ?>

                                </span>
                            </td>
                            <td class="right-align">
                                R$ <?php echo e(number_format($center->financial_accounts()
                                    ->whereHas('category', function($q) {
                                        $q->where('tipo', 'despesa');
                                    })
                                    ->sum('valor'), 2, ',', '.')); ?>

                            </td>
                            <td class="center-align">
                                <a href="<?php echo e(route('financial.cost-centers.edit', $center)); ?>" 
                                   class="btn-small waves-effect waves-light orange">
                                    <i class="material-icons">edit</i>
                                </a>
                                
                                <?php if(!$center->financial_accounts()->exists()): ?>
                                <form action="<?php echo e(route('financial.cost-centers.destroy', $center)); ?>" 
                                      method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-small waves-effect waves-light red" 
                                            onclick="return confirm('Tem certeza que deseja excluir?')">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="center-align">Nenhum centro de custo cadastrado.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/cost-centers/index.blade.php ENDPATH**/ ?>
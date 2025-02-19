

<?php $__env->startSection('title', 'Orçamentos'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Orçamentos</span>

                <div class="row">
                    <div class="col s12">
                        <a href="<?php echo e(route('financial.budgets.create')); ?>" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>
                            Novo Orçamento
                        </a>
                    </div>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Valor Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($budget->client->nome ?? 'N/A'); ?></td>
                                <td><?php echo e($budget->created_at->format('d/m/Y')); ?></td>
                                <td>
                                    <?php switch($budget->status):
                                        case ('aguardando_aprovacao'): ?>
                                            <span class="orange-text">Aguardando Aprovação</span>
                                            <?php break; ?>
                                        <?php case ('producao'): ?>
                                            <span class="blue-text">Em Produção</span>
                                            <?php break; ?>
                                        <?php case ('entregue'): ?>
                                            <span class="green-text">Entregue</span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="grey-text"><?php echo e($budget->status); ?></span>
                                    <?php endswitch; ?>
                                </td>
                                <td>R$ <?php echo e(number_format($budget->valor_total, 2, ',', '.')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('financial.budgets.show', $budget)); ?>" class="btn-floating waves-effect waves-light blue">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    <a href="<?php echo e(route('financial.budgets.edit', $budget)); ?>" class="btn-floating waves-effect waves-light orange">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <button type="button" onclick="confirmDelete('<?php echo e($budget->id); ?>')" class="btn-floating waves-effect waves-light red">
                                        <i class="material-icons">delete</i>
                                    </button>
                                    <form id="delete-form-<?php echo e($budget->id); ?>" action="<?php echo e(route('financial.budgets.destroy', $budget)); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="center-align">Nenhum orçamento encontrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php echo e($budgets->links()); ?>

            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir este orçamento?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/budgets/index.blade.php ENDPATH**/ ?>
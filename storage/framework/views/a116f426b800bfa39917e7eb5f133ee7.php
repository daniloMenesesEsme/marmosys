

<?php $__env->startSection('title', 'Orçamentos'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .action-buttons .btn-floating {
        margin: 0 3px;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Orçamentos</span>
                
                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Telefone</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($budget->numero); ?></td>
                                <td><?php echo e($budget->data->format('d/m/Y')); ?></td>
                                <td><?php echo e($budget->client->nome); ?></td>
                                <td><?php echo e($budget->client->telefone); ?></td>
                                <td>R$ <?php echo e(number_format($budget->valor_total, 2, ',', '.')); ?></td>
                                <td>
                                    <span class="chip <?php echo e($budget->status_class); ?>">
                                        <?php echo e($budget->status_text); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('financial.budgets.show', $budget)); ?>"
                                       class="btn-floating waves-effect waves-light blue tooltipped"
                                       data-position="top" 
                                       data-tooltip="Visualizar">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    
                                    <a href="<?php echo e(route('financial.budgets.edit', $budget)); ?>"
                                       class="btn-floating waves-effect waves-light green tooltipped"
                                       data-position="top" 
                                       data-tooltip="Editar">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    
                                    <a href="<?php echo e(route('financial.budgets.pdf', $budget)); ?>"
                                       class="btn-floating waves-effect waves-light purple tooltipped"
                                       data-position="top" 
                                       data-tooltip="Gerar PDF">
                                        <i class="material-icons">picture_as_pdf</i>
                                    </a>
                                    
                                    <form action="<?php echo e(route('financial.budgets.destroy', $budget)); ?>" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este orçamento?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="btn-floating waves-effect waves-light red tooltipped"
                                                data-position="top"
                                                data-tooltip="Excluir">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="center">Nenhum orçamento encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php echo e($budgets->links()); ?>

            </div>
            <div class="card-action">
                <a href="<?php echo e(route('financial.budgets.create')); ?>" 
                   class="btn waves-effect waves-light">
                    <i class="material-icons left">add</i>
                    Novo Orçamento
                </a>
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

function printBudget(id) {
    window.open(`/financial/budgets/${id}/print`, '_blank');
}

document.addEventListener('DOMContentLoaded', function() {
    var tooltips = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(tooltips);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/budgets/index.blade.php ENDPATH**/ ?>
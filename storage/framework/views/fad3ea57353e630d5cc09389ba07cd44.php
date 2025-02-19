

<?php $__env->startSection('title', 'Categorias Financeiras'); ?>

<?php
use Illuminate\Support\Str;
?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Categorias Financeiras</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="<?php echo e(route('financial.categories.create')); ?>" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>Nova Categoria
                        </a>
                    </div>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($category->nome); ?></td>
                            <td>
                                <span class="chip <?php echo e($category->tipo_class); ?>">
                                    <?php echo e($category->tipo_text); ?>

                                </span>
                            </td>
                            <td><?php echo e(Str::limit($category->descricao, 50) ?: '-'); ?></td>
                            <td>
                                <span class="chip <?php echo e($category->ativo ? 'green white-text' : 'red white-text'); ?>">
                                    <?php echo e($category->ativo ? 'Ativa' : 'Inativa'); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('financial.categories.edit', $category)); ?>" class="btn-small waves-effect waves-light orange">
                                    <i class="material-icons">edit</i>
                                </a>
                                <form action="<?php echo e(route('financial.categories.destroy', $category)); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-small waves-effect waves-light red" onclick="return confirm('Tem certeza que deseja <?php echo e($category->ativo ? 'inativar' : 'ativar'); ?> esta categoria?')">
                                        <i class="material-icons"><?php echo e($category->ativo ? 'delete' : 'restore'); ?></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="center-align">Nenhuma categoria cadastrada</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="center-align">
                    <?php echo e($categories->links('vendor.pagination.materialize')); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/categories/index.blade.php ENDPATH**/ ?>
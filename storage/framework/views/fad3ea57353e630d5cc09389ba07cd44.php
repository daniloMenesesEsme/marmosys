

<?php $__env->startSection('title', 'Categorias Financeiras'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    <div class="row">
                        <div class="col s6">
                            <h5>
                                <i class="material-icons left">category</i>
                                Categorias Financeiras
                            </h5>
                        </div>
                        <div class="col s6 right-align">
                            <a href="<?php echo e(route('financial.categories.create')); ?>" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">add</i>
                                Nova Categoria
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="row">
                    <form action="<?php echo e(route('financial.categories.index')); ?>" method="GET" class="col s12">
                        <div class="row mb-0">
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">search</i>
                                <input type="text" id="nome" name="nome" value="<?php echo e(request('nome')); ?>">
                                <label for="nome">Buscar por Nome</label>
                            </div>
                            <div class="input-field col s6 m4">
                                <select name="natureza">
                                    <option value="">Todas as Naturezas</option>
                                    <option value="receita" <?php echo e(request('natureza') == 'receita' ? 'selected' : ''); ?>>Receita</option>
                                    <option value="despesa" <?php echo e(request('natureza') == 'despesa' ? 'selected' : ''); ?>>Despesa</option>
                                </select>
                                <label>Natureza</label>
                            </div>
                            <div class="input-field col s6 m4">
                                <select name="status">
                                    <option value="">Todos os Status</option>
                                    <option value="ativo" <?php echo e(request('status') == 'ativo' ? 'selected' : ''); ?>>Ativo</option>
                                    <option value="inativo" <?php echo e(request('status') == 'inativo' ? 'selected' : ''); ?>>Inativo</option>
                                </select>
                                <label>Status</label>
                            </div>
                            <div class="col s12" style="margin-top: 10px;">
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="material-icons left">search</i>
                                    Filtrar
                                </button>
                                <a href="<?php echo e(route('financial.categories.index')); ?>" class="btn waves-effect waves-light red">
                                    <i class="material-icons left">clear</i>
                                    Limpar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="striped highlight responsive-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Natureza</th>
                            <th>Código Contábil</th>
                            <th>Status</th>
                            <th class="center-align">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <i class="material-icons left"><?php echo e($category->icone); ?></i>
                                <?php echo e($category->nome); ?>

                            </td>
                            <td>
                                <span class="chip">
                                    <?php echo e($category->tipo_formatado); ?>

                                </span>
                            </td>
                            <td>
                                <span class="chip <?php echo e($category->natureza == 'receita' ? 'light-green' : 'deep-orange'); ?> white-text">
                                    <?php echo e($category->natureza == 'receita' ? 'Receita' : 'Despesa'); ?>

                                </span>
                            </td>
                            <td><?php echo e($category->codigo_contabil_externo ?: '-'); ?></td>
                            <td>
                                <span class="chip <?php echo e($category->ativo ? 'green' : 'red'); ?> white-text">
                                    <?php echo e($category->ativo ? 'Ativa' : 'Inativa'); ?>

                                </span>
                            </td>
                            <td class="center-align">
                                <a href="<?php echo e(route('financial.categories.edit', $category)); ?>" 
                                   class="btn-small waves-effect waves-light orange tooltipped"
                                   data-position="top" 
                                   data-tooltip="Editar">
                                    <i class="material-icons">edit</i>
                                </a>
                                <form action="<?php echo e(route('financial.categories.destroy', $category)); ?>" 
                                      method="POST" 
                                      style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="btn-small waves-effect waves-light <?php echo e($category->ativo ? 'red' : 'green'); ?> tooltipped"
                                            data-position="top"
                                            data-tooltip="<?php echo e($category->ativo ? 'Inativar' : 'Ativar'); ?>"
                                            onclick="return confirm('Tem certeza que deseja <?php echo e($category->ativo ? 'inativar' : 'ativar'); ?> esta categoria?')">
                                        <i class="material-icons"><?php echo e($category->ativo ? 'delete' : 'restore'); ?></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="center-align">
                                <i class="material-icons medium grey-text">info</i>
                                <p class="grey-text">Nenhuma categoria encontrada.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="row" style="margin-top: 20px;">
                    <div class="col s12">
                        <?php echo e($categories->onEachSide(2)->links('vendor.pagination.materialize')); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltips = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(tooltips);

    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/categories/index.blade.php ENDPATH**/ ?>
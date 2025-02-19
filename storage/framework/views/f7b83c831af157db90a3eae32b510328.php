

<?php $__env->startSection('title', 'Produtos'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Produtos
                    <a href="#modal-novo" class="btn-floating btn waves-effect waves-light right modal-trigger">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Preço Venda</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($product->codigo); ?></td>
                                <td><?php echo e($product->nome); ?></td>
                                <td>R$ <?php echo e(number_format($product->preco_venda, 2, ',', '.')); ?></td>
                                <td><?php echo e($product->estoque); ?></td>
                                <td>
                                    <?php if($product->ativo): ?>
                                        <span class="new badge green" data-badge-caption="">Ativo</span>
                                    <?php else: ?>
                                        <span class="new badge grey" data-badge-caption="">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="#modal-editar-<?php echo e($product->id); ?>" class="btn-small waves-effect waves-light modal-trigger">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <?php if($product->ativo): ?>
                                        <a href="#modal-deletar-<?php echo e($product->id); ?>" class="btn-small red waves-effect waves-light modal-trigger">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="center">Nenhum produto cadastrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php echo e($products->links()); ?>

            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Produto -->
<div id="modal-novo" class="modal">
    <form action="<?php echo e(route('products.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-content">
            <h4>Novo Produto</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="codigo" name="codigo" required>
                    <label for="codigo">Código</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" id="nome" name="nome" required>
                    <label for="nome">Nome</label>
                </div>
                <div class="input-field col s12">
                    <textarea id="descricao" name="descricao" class="materialize-textarea"></textarea>
                    <label for="descricao">Descrição</label>
                </div>
                <div class="input-field col s6">
                    <input type="number" step="0.01" id="preco_venda" name="preco_venda" required>
                    <label for="preco_venda">Preço de Venda</label>
                </div>
                <div class="input-field col s6">
                    <input type="number" id="estoque" name="estoque" required>
                    <label for="estoque">Estoque</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
        </div>
    </form>
</div>

<!-- Modais de Edição -->
<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div id="modal-editar-<?php echo e($product->id); ?>" class="modal">
        <form action="<?php echo e(route('products.update', $product)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="modal-content">
                <h4>Editar Produto</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="codigo-<?php echo e($product->id); ?>" name="codigo" value="<?php echo e($product->codigo); ?>" required>
                        <label for="codigo-<?php echo e($product->id); ?>">Código</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" id="nome-<?php echo e($product->id); ?>" name="nome" value="<?php echo e($product->nome); ?>" required>
                        <label for="nome-<?php echo e($product->id); ?>">Nome</label>
                    </div>
                    <div class="input-field col s12">
                        <textarea id="descricao-<?php echo e($product->id); ?>" name="descricao" class="materialize-textarea"><?php echo e($product->descricao); ?></textarea>
                        <label for="descricao-<?php echo e($product->id); ?>">Descrição</label>
                    </div>
                    <div class="input-field col s6">
                        <input type="number" step="0.01" id="preco_venda-<?php echo e($product->id); ?>" name="preco_venda" value="<?php echo e($product->preco_venda); ?>" required>
                        <label for="preco_venda-<?php echo e($product->id); ?>">Preço de Venda</label>
                    </div>
                    <div class="input-field col s6">
                        <input type="number" id="estoque-<?php echo e($product->id); ?>" name="estoque" value="<?php echo e($product->estoque); ?>" required>
                        <label for="estoque-<?php echo e($product->id); ?>">Estoque</label>
                    </div>
                    <div class="input-field col s12">
                        <label>
                            <input type="checkbox" name="ativo" value="1" <?php echo e($product->ativo ? 'checked' : ''); ?>>
                            <span>Ativo</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
            </div>
        </form>
    </div>

    <!-- Modal de Exclusão -->
    <div id="modal-deletar-<?php echo e($product->id); ?>" class="modal">
        <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja desativar o produto "<?php echo e($product->nome); ?>"?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Confirmar</button>
            </div>
        </form>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);
        
        var textareas = document.querySelectorAll('.materialize-textarea');
        M.textareaAutoResize(textareas);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/products/index.blade.php ENDPATH**/ ?>
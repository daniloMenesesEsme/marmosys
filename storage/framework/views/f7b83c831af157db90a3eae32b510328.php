

<?php $__env->startSection('title', 'Produtos'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Produtos e Serviços
                    <a href="<?php echo e(route('products.create')); ?>" class="btn-floating btn waves-effect waves-light blue right">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <!-- Filtros -->
                <div class="row">
                    <form action="<?php echo e(route('products.index')); ?>" method="GET" class="col s12">
                        <div class="row">
                            <div class="input-field col s12 m3">
                                <select name="tipo" id="filtro-tipo">
                                    <option value="">Todos os Tipos</option>
                                    <?php $__currentLoopData = \App\Enums\ProductType::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->value); ?>" <?php echo e(request('tipo') == $type->value ? 'selected' : ''); ?>>
                                            <?php echo e($type->label()); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <label for="filtro-tipo">Tipo</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="busca" name="busca" value="<?php echo e(request('busca')); ?>">
                                <label for="busca">Buscar</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <select name="status" id="filtro-status">
                                    <option value="">Todos os Status</option>
                                    <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>>Ativos</option>
                                    <option value="0" <?php echo e(request('status') == '0' ? 'selected' : ''); ?>>Inativos</option>
                                </select>
                                <label for="filtro-status">Status</label>
                            </div>
                            <div class="col s12 m3">
                                <button type="submit" class="btn waves-effect waves-light blue">
                                    <i class="material-icons left">search</i>
                                    Filtrar
                                </button>
                                <a href="<?php echo e(route('products.index')); ?>" class="btn waves-effect waves-light grey">
                                    <i class="material-icons left">clear</i>
                                    Limpar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Preço</th>
                            <th class="center-align">Estoque</th>
                            <th>Status</th>
                            <th class="right-align">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($product->codigo); ?></td>
                                <td><?php echo e($product->nome); ?></td>
                                <td>
                                    <span class="chip">
                                        <?php echo e($product->tipo?->label() ?? 'Produto'); ?>

                                    </span>
                                </td>
                                <td>R$ <?php echo e(number_format($product->preco_venda, 2, ',', '.')); ?></td>
                                <td class="center-align">
                                    <?php if($product->tipo !== \App\Enums\ProductType::SERVICE): ?>
                                        <span class="chip <?php echo e($product->status_estoque['class']); ?>">
                                            <?php echo e($product->estoque_atual); ?> <?php echo e($product->unidade_medida); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="chip blue white-text">Serviço</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="chip <?php echo e($product->ativo ? 'green' : 'grey'); ?> white-text">
                                        <?php echo e($product->ativo ? 'Ativo' : 'Inativo'); ?>

                                    </span>
                                </td>
                                <td class="right-align">
                                    <a href="<?php echo e(route('products.show', $product)); ?>" class="btn-small waves-effect waves-light blue">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn-small waves-effect waves-light orange">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <?php if($product->ativo): ?>
                                        <a href="#modal-deletar-<?php echo e($product->id); ?>" class="btn-small waves-effect waves-light red modal-trigger">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="center-align">Nenhum item encontrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php echo e($products->links()); ?>

            </div>
        </div>
    </div>
</div>

<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <!-- Modal de Exclusão -->
    <div id="modal-deletar-<?php echo e($product->id); ?>" class="modal">
        <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja desativar "<?php echo e($product->nome); ?>"?</p>
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

    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/products/index.blade.php ENDPATH**/ ?>
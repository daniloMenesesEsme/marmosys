

<?php $__env->startSection('title', 'Formas de Pagamento'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="padding: 0 30px;">
    <div class="row">
        <div class="col s12">
            <div class="card" style="margin: 15px 0;">
                <div class="card-content">
                    <div class="card-title">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Formas de Pagamento</h4>
                            <a href="<?php echo e(route('financial.registration.payment-methods.create')); ?>" 
                               class="btn-floating btn-large waves-effect waves-light green tooltipped"
                               data-position="left" 
                               data-tooltip="Nova Forma de Pagamento">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-0">
                        <form id="filter-form" class="col s12">
                            <div class="row mb-0">
                                <div class="input-field col s12 m3">
                                    <select name="tipo" id="tipo" onchange="this.form.submit()">
                                        <option value="">Todos os Tipos</option>
                                        <?php $__currentLoopData = App\Models\PaymentMethod::TIPOS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $valor => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($valor); ?>" <?php echo e(request('tipo') == $valor ? 'selected' : ''); ?>>
                                                <?php echo e($label); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <label for="tipo">Tipo</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <select name="status" id="status" onchange="this.form.submit()">
                                        <option value="">Todos os Status</option>
                                        <option value="ativo" <?php echo e(request('status') == 'ativo' ? 'selected' : ''); ?>>Ativo</option>
                                        <option value="inativo" <?php echo e(request('status') == 'inativo' ? 'selected' : ''); ?>>Inativo</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                                <div class="input-field col s12 m4">
                                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>">
                                    <label for="search">Buscar</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <button type="submit" class="btn waves-effect waves-light">
                                        <i class="material-icons">search</i>
                                    </button>
                                    <a href="<?php echo e(route('financial.registration.payment-methods.index')); ?>" 
                                       class="btn waves-effect waves-light red">
                                        <i class="material-icons">clear</i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela -->
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Parcelas Padrão</th>
                                <th>Taxa Padrão</th>
                                <th>Status</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($method->nome); ?></td>
                                    <td>
                                        <span class="chip <?php echo e($method->isAvistaOnly() ? 'green' : 'blue'); ?> white-text">
                                            <?php echo e($method->tipo_formatado); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($method->isAvistaOnly()): ?>
                                            <span class="chip grey white-text">À Vista</span>
                                        <?php else: ?>
                                            <?php echo e($method->parcelas_padrao); ?>x
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($method->isAvistaOnly()): ?>
                                            <span class="chip grey white-text">Sem Taxa</span>
                                        <?php else: ?>
                                            <?php echo e(number_format($method->taxa_padrao, 2)); ?>%
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="chip <?php echo e($method->ativo ? 'green' : 'red'); ?> white-text">
                                            <?php echo e($method->ativo ? 'Ativo' : 'Inativo'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="buttons-actions">
                                            <a href="<?php echo e(route('financial.registration.payment-methods.edit', $method)); ?>" 
                                               class="btn-floating waves-effect waves-light amber"
                                               title="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <button type="button"
                                                    onclick="deleteItem('<?php echo e(route('financial.registration.payment-methods.destroy', $method)); ?>')"
                                                    class="btn-floating waves-effect waves-light red"
                                                    title="Excluir">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="center-align">Nenhuma forma de pagamento encontrada</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="row">
                        <div class="col s12">
                            <?php echo e($paymentMethods->links('vendor.pagination.materialize')); ?>

                        </div>
                    </div>

                    <!-- Botão Adicionar -->
                    <div class="fixed-action-btn">
                        <a href="<?php echo e(route('financial.registration.payment-methods.create')); ?>" 
                           class="btn-floating btn-large waves-effect waves-light teal">
                            <i class="material-icons">add</i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="modal-delete" class="modal">
    <div class="modal-content">
        <h4>Confirmar Exclusão</h4>
        <p>Tem certeza que deseja excluir esta forma de pagamento?</p>
    </div>
    <div class="modal-footer">
        <form id="delete-form" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            <button type="submit" class="modal-close waves-effect waves-green btn-flat">Confirmar</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa os selects
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    // Inicializa os tooltips
    var tooltips = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(tooltips);

    // Inicializa os modais
    var modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);
});

function deleteItem(url) {
    const form = document.getElementById('delete-form');
    form.action = url;
    var modal = M.Modal.getInstance(document.getElementById('modal-delete'));
    modal.open();
}

<?php if(session('success')): ?>
    M.toast({html: '<?php echo e(session("success")); ?>', classes: 'green'});
<?php endif; ?>

<?php if(session('error')): ?>
    M.toast({html: '<?php echo e(session("error")); ?>', classes: 'red'});
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.d-flex {
    display: flex !important;
}
.justify-content-between {
    justify-content: space-between !important;
}
.align-items-center {
    align-items: center !important;
}
.buttons-actions {
    display: flex;
    gap: 8px;
}
.chip {
    height: 24px;
    padding: 0 12px;
    border-radius: 12px;
    line-height: 24px;
}
.card-title {
    padding-bottom: 20px;
}
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/registration/payment-methods/index.blade.php ENDPATH**/ ?>
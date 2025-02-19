

<?php $__env->startSection('title', isset($category) ? 'Editar Categoria' : 'Nova Categoria'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title"><?php echo e(isset($category) ? 'Editar Categoria' : 'Nova Categoria'); ?></span>

                <form action="<?php echo e(isset($category) ? route('financial.categories.update', $category) : route('financial.categories.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($category)): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="nome" name="nome" value="<?php echo e(old('nome', $category->nome ?? '')); ?>" required>
                            <label for="nome">Nome*</label>
                            <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="red-text"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="input-field col s12 m6">
                            <select name="tipo" id="tipo" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="receita" <?php echo e((old('tipo', $category->tipo ?? '') == 'receita') ? 'selected' : ''); ?>>Receita</option>
                                <option value="despesa" <?php echo e((old('tipo', $category->tipo ?? '') == 'despesa') ? 'selected' : ''); ?>>Despesa</option>
                            </select>
                            <label for="tipo">Tipo*</label>
                            <?php $__errorArgs = ['tipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="red-text"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="descricao" name="descricao" class="materialize-textarea"><?php echo e(old('descricao', $category->descricao ?? '')); ?></textarea>
                            <label for="descricao">Descrição</label>
                            <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="red-text"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="<?php echo e(route('financial.categories.index')); ?>" class="btn waves-effect waves-light grey">
                                <i class="material-icons left">arrow_back</i>
                                Voltar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    var textareas = document.querySelectorAll('textarea');
    M.textareaAutoResize(textareas);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/categories/form.blade.php ENDPATH**/ ?>
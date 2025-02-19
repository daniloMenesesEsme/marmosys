

<?php $__env->startSection('title', isset($costCenter) ? 'Editar Centro de Custo' : 'Novo Centro de Custo'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <?php echo e(isset($costCenter) ? 'Editar Centro de Custo' : 'Novo Centro de Custo'); ?>

                </span>

                <form action="<?php echo e(isset($costCenter) 
                    ? route('financial.cost-centers.update', $costCenter) 
                    : route('financial.cost-centers.store')); ?>" 
                      method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($costCenter)): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" id="nome" name="nome" 
                                   value="<?php echo e(old('nome', $costCenter->nome ?? '')); ?>" required>
                            <label for="nome">Nome</label>
                            <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="red-text"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="input-field col s12">
                            <textarea id="descricao" name="descricao" 
                                      class="materialize-textarea"><?php echo e(old('descricao', $costCenter->descricao ?? '')); ?></textarea>
                            <label for="descricao">Descrição</label>
                            <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="red-text"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="col s12">
                            <label>
                                <input type="checkbox" class="filled-in" name="ativo" value="1"
                                       <?php echo e(old('ativo', $costCenter->ativo ?? true) ? 'checked' : ''); ?>>
                                <span>Ativo</span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            
                            <a href="<?php echo e(route('financial.cost-centers.index')); ?>" 
                               class="btn waves-effect waves-light red">
                                <i class="material-icons left">cancel</i>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/cost-centers/form.blade.php ENDPATH**/ ?>
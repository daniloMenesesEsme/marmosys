

<?php $__env->startSection('title', 'Importar Extrato Bancário'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Importar Arquivo OFX</span>

                <form action="<?php echo e(route('financial.reconciliation.process')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="row">
                        <div class="file-field input-field col s12">
                            <div class="btn blue">
                                <span>Arquivo</span>
                                <input type="file" name="arquivo_ofx" accept=".ofx" required>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" 
                                       placeholder="Selecione o arquivo OFX do banco">
                            </div>
                            <?php $__errorArgs = ['arquivo_ofx'];
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

                        <div class="input-field col s12 m6">
                            <select name="categoria_receita" required>
                                <option value="" disabled selected>Selecione</option>
                                <?php $__currentLoopData = $categories->get('receita', collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->nome); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label>Categoria para Receitas</label>
                            <?php $__errorArgs = ['categoria_receita'];
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

                        <div class="input-field col s12 m6">
                            <select name="categoria_despesa" required>
                                <option value="" disabled selected>Selecione</option>
                                <?php $__currentLoopData = $categories->get('despesa', collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->nome); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label>Categoria para Despesas</label>
                            <?php $__errorArgs = ['categoria_despesa'];
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
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn-large waves-effect waves-light blue">
                                <i class="material-icons left">cloud_upload</i>
                                Importar Transações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col s12">
        <div class="card blue-grey lighten-5">
            <div class="card-content">
                <span class="card-title">Instruções</span>
                <ul class="browser-default">
                    <li>Exporte o arquivo OFX do seu banco</li>
                    <li>Selecione as categorias padrão para receitas e despesas</li>
                    <li>As transações serão importadas automaticamente</li>
                    <li>Valores positivos serão considerados receitas</li>
                    <li>Valores negativos serão considerados despesas</li>
                    <li>Todas as transações serão marcadas como pagas</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/reconciliation/import.blade.php ENDPATH**/ ?>
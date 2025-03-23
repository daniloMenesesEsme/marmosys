

<?php $__env->startSection('title', isset($paymentPlan) ? 'Editar Plano de Pagamento' : 'Novo Plano de Pagamento'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="padding: 0 30px;">
    <div class="row">
        <div class="col s12">
            <div class="card" style="margin: 15px 0;">
                <div class="card-content">
                    <div class="card-title">
                        <h4>
                            <i class="material-icons left"><?php echo e(isset($paymentPlan) ? 'edit' : 'add'); ?></i>
                            <?php echo e(isset($paymentPlan) ? 'Editar Plano de Pagamento' : 'Novo Plano de Pagamento'); ?>

                        </h4>
                    </div>

                    <form action="<?php echo e(isset($paymentPlan) ? 
                        route('financial.registration.payment-plans.update', $paymentPlan) : 
                        route('financial.registration.payment-plans.store')); ?>" 
                        method="POST" id="payment-plan-form">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($paymentPlan)): ?>
                            <?php echo method_field('PUT'); ?>
                        <?php endif; ?>

                        <div class="row">
                            <!-- Informações Básicas -->
                            <div class="col s12">
                                <h5>Informações Básicas</h5>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="text" id="nome" name="nome" value="<?php echo e(old('nome', $paymentPlan->nome ?? '')); ?>" required>
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

                            <div class="input-field col s12 m6">
                                <select name="tipo" id="tipo" required>
                                    <option value="venda" <?php echo e(old('tipo', $paymentPlan->tipo ?? '') == 'venda' ? 'selected' : ''); ?>>Venda</option>
                                    <option value="compra" <?php echo e(old('tipo', $paymentPlan->tipo ?? '') == 'compra' ? 'selected' : ''); ?>>Compra</option>
                                </select>
                                <label for="tipo">Tipo</label>
                                <?php $__errorArgs = ['tipo'];
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
                                <textarea id="descricao" name="descricao" class="materialize-textarea"><?php echo e(old('descricao', $paymentPlan->descricao ?? '')); ?></textarea>
                                <label for="descricao">Descrição</label>
                            </div>

                            <!-- Configurações de Parcelas -->
                            <div class="col s12">
                                <h5>Configurações de Parcelas</h5>
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="number" id="parcelas" name="parcelas" min="1" value="<?php echo e(old('parcelas', $paymentPlan->parcelas ?? '1')); ?>" required>
                                <label for="parcelas">Número de Parcelas</label>
                                <?php $__errorArgs = ['parcelas'];
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

                            <div class="input-field col s12 m3">
                                <input type="number" id="intervalo_dias" name="intervalo_dias" min="1" value="<?php echo e(old('intervalo_dias', $paymentPlan->intervalo_dias ?? '30')); ?>" required>
                                <label for="intervalo_dias">Intervalo (dias)</label>
                                <?php $__errorArgs = ['intervalo_dias'];
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

                            <div class="input-field col s12 m3">
                                <input type="number" id="carencia_dias" name="carencia_dias" min="0" value="<?php echo e(old('carencia_dias', $paymentPlan->carencia_dias ?? '0')); ?>" required>
                                <label for="carencia_dias">Carência (dias)</label>
                                <?php $__errorArgs = ['carencia_dias'];
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

                            <div class="input-field col s12 m3">
                                <input type="number" id="taxa" name="taxa" step="0.01" min="0" value="<?php echo e(old('taxa', $paymentPlan->taxa ?? '0')); ?>" required>
                                <label for="taxa">Taxa (%)</label>
                                <?php $__errorArgs = ['taxa'];
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

                            <!-- Configurações Financeiras -->
                            <div class="col s12">
                                <h5>Configurações Financeiras</h5>
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="number" id="multa_atraso" name="multa_atraso" step="0.01" min="0" value="<?php echo e(old('multa_atraso', $paymentPlan->multa_atraso ?? '0')); ?>" required>
                                <label for="multa_atraso">Multa por Atraso (%)</label>
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="number" id="juros_atraso" name="juros_atraso" step="0.01" min="0" value="<?php echo e(old('juros_atraso', $paymentPlan->juros_atraso ?? '0')); ?>" required>
                                <label for="juros_atraso">Juros por Atraso (%)</label>
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="number" id="valor_minimo_parcela" name="valor_minimo_parcela" step="0.01" min="0" value="<?php echo e(old('valor_minimo_parcela', $paymentPlan->valor_minimo_parcela ?? '0')); ?>" required>
                                <label for="valor_minimo_parcela">Valor Mínimo da Parcela</label>
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="number" id="limite_credito" name="limite_credito" step="0.01" min="0" value="<?php echo e(old('limite_credito', $paymentPlan->limite_credito ?? '0')); ?>">
                                <label for="limite_credito">Limite de Crédito</label>
                            </div>

                            <!-- Configurações de Entrada -->
                            <div class="col s12">
                                <h5>Configurações de Entrada</h5>
                            </div>

                            <div class="col s12 m6">
                                <label>
                                    <input type="checkbox" name="permite_entrada" value="1" class="filled-in" 
                                        <?php echo e(old('permite_entrada', $paymentPlan->permite_entrada ?? false) ? 'checked' : ''); ?>>
                                    <span>Permite Entrada</span>
                                </label>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="number" id="percentual_minimo_entrada" name="percentual_minimo_entrada" 
                                    step="0.01" min="0" max="100" 
                                    value="<?php echo e(old('percentual_minimo_entrada', $paymentPlan->percentual_minimo_entrada ?? '0')); ?>" required>
                                <label for="percentual_minimo_entrada">Percentual Mínimo de Entrada (%)</label>
                            </div>

                            <!-- Configurações de Integração -->
                            <div class="col s12">
                                <h5>Configurações de Integração</h5>
                            </div>

                            <div class="input-field col s12 m6">
                                <select name="payment_method_id" required>
                                    <option value="">Selecione uma forma de pagamento</option>
                                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($method->id); ?>" 
                                            <?php echo e(old('payment_method_id', $paymentPlan->payment_method_id ?? '') == $method->id ? 'selected' : ''); ?>>
                                            <?php echo e($method->nome); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <label>Forma de Pagamento</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="text" id="codigo_xml" name="codigo_xml" value="<?php echo e(old('codigo_xml', $paymentPlan->codigo_xml ?? '')); ?>">
                                <label for="codigo_xml">Código XML (NFe)</label>
                            </div>

                            <!-- Configurações PDV -->
                            <div class="col s12">
                                <h5>Configurações PDV</h5>
                            </div>

                            <div class="col s12 m6">
                                <label>
                                    <input type="checkbox" name="disponivel_pdv" value="1" class="filled-in" 
                                        <?php echo e(old('disponivel_pdv', $paymentPlan->disponivel_pdv ?? true) ? 'checked' : ''); ?>>
                                    <span>Disponível no PDV</span>
                                </label>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="number" id="ordem_exibicao" name="ordem_exibicao" min="0" 
                                    value="<?php echo e(old('ordem_exibicao', $paymentPlan->ordem_exibicao ?? '0')); ?>" required>
                                <label for="ordem_exibicao">Ordem de Exibição</label>
                            </div>

                            <!-- Controles -->
                            <div class="col s12">
                                <h5>Controles</h5>
                            </div>

                            <div class="col s12 m6">
                                <label>
                                    <input type="checkbox" name="ativo" value="1" class="filled-in" 
                                        <?php echo e(old('ativo', $paymentPlan->ativo ?? true) ? 'checked' : ''); ?>>
                                    <span>Ativo</span>
                                </label>
                            </div>

                            <div class="col s12 m6">
                                <label>
                                    <input type="checkbox" name="requer_aprovacao" value="1" class="filled-in" 
                                        <?php echo e(old('requer_aprovacao', $paymentPlan->requer_aprovacao ?? false) ? 'checked' : ''); ?>>
                                    <span>Requer Aprovação</span>
                                </label>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="row">
                            <div class="col s12 center-align">
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="material-icons left">save</i>
                                    Salvar
                                </button>
                                <a href="<?php echo e(route('financial.registration.payment-plans.index')); ?>" class="btn waves-effect waves-light red">
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
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);

        var textareas = document.querySelectorAll('.materialize-textarea');
        M.textareaAutoResize(textareas);
        
        // Adicionar debug no envio do formulário
        const form = document.getElementById('payment-plan-form');
        
        form.addEventListener('submit', function(e) {
            console.log('Tentando enviar formulário');
            
            // Verifique se todos os campos obrigatórios estão preenchidos
            const requiredFields = form.querySelectorAll('[required]');
            let allValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    console.error('Campo obrigatório não preenchido:', field.name);
                    allValid = false;
                    field.classList.add('invalid');
                }
            });
            
            if (!allValid) {
                e.preventDefault();
                M.toast({html: 'Por favor, preencha todos os campos obrigatórios'});
                return false;
            }
            
            // Adicione um feedback visual
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="material-icons left">hourglass_empty</i> Salvando...';
            submitBtn.disabled = true;
            
            // Prossiga com o envio
            return true;
        });
    });
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/registration/payment-plans/form.blade.php ENDPATH**/ ?>
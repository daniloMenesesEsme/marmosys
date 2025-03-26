<?php $__env->startSection('title', isset($supplier) ? 'Editar Fornecedor' : 'Novo Fornecedor'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col s12">
            <h4 class="header">
                <i class="material-icons left">business</i>
                <?php echo e(isset($supplier) ? 'Editar Fornecedor' : 'Novo Fornecedor'); ?>

            </h4>
        </div>
    </div>

<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                    <form method="POST" action="<?php echo e(isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php if(isset($supplier)): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>

                    <div class="row">
                            <!-- CNPJ -->
                        <div class="input-field col s12 m6">
                                <i class="material-icons prefix">business</i>
                                <input type="text" name="cnpj" id="cnpj" value="<?php echo e(old('cnpj', isset($supplier) ? $supplier->cnpj : '')); ?>" class="cnpj" required>
                                <label for="cnpj">CNPJ</label>
                                <?php $__errorArgs = ['cnpj'];
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

                            <!-- Inscrição Estadual -->
                        <div class="input-field col s12 m6">
                                <i class="material-icons prefix">description</i>
                                <input type="text" name="inscricao_estadual" id="inscricao_estadual" value="<?php echo e(old('inscricao_estadual', isset($supplier) ? $supplier->inscricao_estadual : '')); ?>">
                                <label for="inscricao_estadual">Inscrição Estadual</label>
                                <?php $__errorArgs = ['inscricao_estadual'];
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

                            <!-- Inscrição Municipal -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">description</i>
                                <input type="text" name="inscricao_municipal" id="inscricao_municipal" value="<?php echo e(old('inscricao_municipal', isset($supplier) ? $supplier->inscricao_municipal : '')); ?>">
                                <label for="inscricao_municipal">Inscrição Municipal</label>
                                <?php $__errorArgs = ['inscricao_municipal'];
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

                            <!-- Razão Social -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">business</i>
                                <input type="text" name="razao_social" id="razao_social" value="<?php echo e(old('razao_social', isset($supplier) ? $supplier->razao_social : '')); ?>" required>
                                <label for="razao_social">Razão Social</label>
                                <?php $__errorArgs = ['razao_social'];
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

                            <!-- Nome Fantasia -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">store</i>
                                <input type="text" name="nome_fantasia" id="nome_fantasia" value="<?php echo e(old('nome_fantasia', isset($supplier) ? $supplier->nome_fantasia : '')); ?>" required>
                                <label for="nome_fantasia">Nome Fantasia</label>
                                <?php $__errorArgs = ['nome_fantasia'];
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

                            <!-- Telefone -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">phone</i>
                                <input type="text" name="telefone" id="telefone" value="<?php echo e(old('telefone', isset($supplier) ? $supplier->telefone : '')); ?>" class="phone" required>
                                <label for="telefone">Telefone</label>
                                <?php $__errorArgs = ['telefone'];
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

                            <!-- Email -->
                        <div class="input-field col s12 m6">
                                <i class="material-icons prefix">email</i>
                                <input type="email" name="email" id="email" value="<?php echo e(old('email', isset($supplier) ? $supplier->email : '')); ?>" required>
                                <label for="email">Email</label>
                                <?php $__errorArgs = ['email'];
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

                            <!-- CEP -->
                        <div class="input-field col s12 m6">
                                <i class="material-icons prefix">location_on</i>
                                <input type="text" name="cep" id="cep" value="<?php echo e(old('cep', isset($supplier) ? $supplier->cep : '')); ?>" class="cep" required>
                                <label for="cep">CEP</label>
                                <?php $__errorArgs = ['cep'];
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

                            <!-- Endereço -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">home</i>
                                <input type="text" name="endereco" id="endereco" value="<?php echo e(old('endereco', isset($supplier) ? $supplier->endereco : '')); ?>" required>
                                <label for="endereco">Endereço</label>
                                <?php $__errorArgs = ['endereco'];
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

                            <!-- Número -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">looks_one</i>
                                <input type="text" name="numero" id="numero" value="<?php echo e(old('numero', isset($supplier) ? $supplier->numero : '')); ?>" required>
                                <label for="numero">Número</label>
                                <?php $__errorArgs = ['numero'];
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

                            <!-- Complemento -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">add_location</i>
                                <input type="text" name="complemento" id="complemento" value="<?php echo e(old('complemento', isset($supplier) ? $supplier->complemento : '')); ?>">
                                <label for="complemento">Complemento</label>
                                <?php $__errorArgs = ['complemento'];
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

                            <!-- Bairro -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">location_city</i>
                                <input type="text" name="bairro" id="bairro" value="<?php echo e(old('bairro', isset($supplier) ? $supplier->bairro : '')); ?>" required>
                                <label for="bairro">Bairro</label>
                                <?php $__errorArgs = ['bairro'];
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

                            <!-- Cidade -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">location_city</i>
                                <input type="text" name="cidade" id="cidade" value="<?php echo e(old('cidade', isset($supplier) ? $supplier->cidade : '')); ?>" required>
                                <label for="cidade">Cidade</label>
                                <?php $__errorArgs = ['cidade'];
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

                            <!-- Estado -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">location_city</i>
                            <select name="estado" id="estado" required>
                                    <option value="">Selecione um estado</option>
                                    <option value="AC" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'AC' ? 'selected' : ''); ?>>Acre</option>
                                    <option value="AL" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'AL' ? 'selected' : ''); ?>>Alagoas</option>
                                    <option value="AP" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'AP' ? 'selected' : ''); ?>>Amapá</option>
                                    <option value="AM" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'AM' ? 'selected' : ''); ?>>Amazonas</option>
                                    <option value="BA" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'BA' ? 'selected' : ''); ?>>Bahia</option>
                                    <option value="CE" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'CE' ? 'selected' : ''); ?>>Ceará</option>
                                    <option value="DF" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'DF' ? 'selected' : ''); ?>>Distrito Federal</option>
                                    <option value="ES" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'ES' ? 'selected' : ''); ?>>Espírito Santo</option>
                                    <option value="GO" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'GO' ? 'selected' : ''); ?>>Goiás</option>
                                    <option value="MA" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'MA' ? 'selected' : ''); ?>>Maranhão</option>
                                    <option value="MT" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'MT' ? 'selected' : ''); ?>>Mato Grosso</option>
                                    <option value="MS" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'MS' ? 'selected' : ''); ?>>Mato Grosso do Sul</option>
                                    <option value="MG" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'MG' ? 'selected' : ''); ?>>Minas Gerais</option>
                                    <option value="PA" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'PA' ? 'selected' : ''); ?>>Pará</option>
                                    <option value="PB" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'PB' ? 'selected' : ''); ?>>Paraíba</option>
                                    <option value="PR" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'PR' ? 'selected' : ''); ?>>Paraná</option>
                                    <option value="PE" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'PE' ? 'selected' : ''); ?>>Pernambuco</option>
                                    <option value="PI" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'PI' ? 'selected' : ''); ?>>Piauí</option>
                                    <option value="RJ" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'RJ' ? 'selected' : ''); ?>>Rio de Janeiro</option>
                                    <option value="RN" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'RN' ? 'selected' : ''); ?>>Rio Grande do Norte</option>
                                    <option value="RS" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'RS' ? 'selected' : ''); ?>>Rio Grande do Sul</option>
                                    <option value="RO" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'RO' ? 'selected' : ''); ?>>Rondônia</option>
                                    <option value="RR" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'RR' ? 'selected' : ''); ?>>Roraima</option>
                                    <option value="SC" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'SC' ? 'selected' : ''); ?>>Santa Catarina</option>
                                    <option value="SP" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'SP' ? 'selected' : ''); ?>>São Paulo</option>
                                    <option value="SE" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'SE' ? 'selected' : ''); ?>>Sergipe</option>
                                    <option value="TO" <?php echo e(old('estado', isset($supplier) ? $supplier->estado : '') === 'TO' ? 'selected' : ''); ?>>Tocantins</option>
                            </select>
                                <label>Estado</label>
                                <?php $__errorArgs = ['estado'];
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

                            <!-- Observações -->
                            <div class="input-field col s12">
                                <i class="material-icons prefix">note</i>
                                <textarea name="observacoes" id="observacoes" class="materialize-textarea"><?php echo e(old('observacoes', isset($supplier) ? $supplier->observacoes : '')); ?></textarea>
                                <label for="observacoes">Observações</label>
                                <?php $__errorArgs = ['observacoes'];
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

                            <!-- Status -->
                        <div class="input-field col s12">
                                <label>
                                    <input type="checkbox" name="ativo" value="1" <?php echo e(old('ativo', isset($supplier) ? $supplier->ativo : true) ? 'checked' : ''); ?>>
                                    <span>Ativo</span>
                                </label>
                                <?php $__errorArgs = ['ativo'];
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
                            <div class="col s12 center-align">
                                <button type="submit" class="btn waves-effect waves-light green">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                                <a href="<?php echo e(route('suppliers.index')); ?>" class="btn waves-effect waves-light red">
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

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
        // Inicializa os selects do Materialize
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);

    // Máscaras
        $('.cnpj').mask('00.000.000/0000-00');
        $('.phone').mask('(00) 00000-0000');
        $('.cep').mask('00000-000');

        // Busca de CEP
        $('#cep').on('blur', function() {
            var cep = $(this).val().replace(/\D/g, '');
        if (cep.length === 8) {
                $.get(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                    if (!data.erro) {
                        $('#endereco').val(data.logradouro);
                        $('#bairro').val(data.bairro);
                        $('#cidade').val(data.localidade);
                        $('#estado').val(data.uf);
                        M.updateTextFields();
                        $('select').formSelect();
                    }
                });
            }
        });

        // Busca de CNPJ
        $('#cnpj').on('blur', function() {
            var cnpj = $(this).val().replace(/\D/g, '');
            if (cnpj.length === 14) {
                $.get(`<?php echo e(route('suppliers.find', '')); ?>/${cnpj}`, function(data) {
                    if (data) {
                        M.toast({html: 'CNPJ já cadastrado!', classes: 'red'});
                    }
                });
        }
    });
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/suppliers/form.blade.php ENDPATH**/ ?>
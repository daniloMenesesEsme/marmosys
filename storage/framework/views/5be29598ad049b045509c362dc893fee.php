

<?php $__env->startSection('title', 'Novo Orçamento'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .input-field {
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    .card-panel {
        padding: 1rem;
        margin: 1rem 0;
    }
    .btn-floating {
        margin: 0.5rem;
    }
    .ambiente-container {
        margin-bottom: 2rem;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .item-row {
        margin-bottom: 1rem;
    }
    label {
        font-size: 0.9rem;
    }
    input[type="number"] {
        height: 2.5rem;
    }
    /* Estilos do datepicker */
    .datepicker-date-display {
        background-color: #2196f3;
    }
    .datepicker-table td.is-selected {
        background-color: #2196f3;
    }
    .datepicker-table td.is-today {
        color: #2196f3;
    }
    .datepicker-cancel, 
    .datepicker-clear, 
    .datepicker-today, 
    .datepicker-done {
        color: #2196f3;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Novo Orçamento</span>

                <form action="<?php echo e(route('financial.budgets.store')); ?>" method="POST" id="budget-form">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <select name="client_id" id="client_id" required>
                                <option value="" disabled selected>Selecione o cliente</option>
                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id') == $client->id ? 'selected' : ''); ?>>
                                        <?php echo e($client->nome); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="client_id">Cliente*</label>
                            <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="red-text"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="previsao_entrega" name="previsao_entrega" class="datepicker" value="<?php echo e(old('previsao_entrega')); ?>" required>
                            <label for="previsao_entrega">Previsão de Entrega*</label>
                            <?php $__errorArgs = ['previsao_entrega'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="red-text"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div id="ambientes-container">
                        <!-- Os ambientes serão adicionados aqui dinamicamente -->
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="button" id="add-ambiente" class="btn waves-effect waves-light green">
                                <i class="material-icons left">add</i>
                                Adicionar Ambiente
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar Orçamento
                            </button>
                            <a href="<?php echo e(route('financial.budgets.index')); ?>" class="btn waves-effect waves-light red">
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

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicialização do select e datepicker
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    var datepicker = document.querySelectorAll('.datepicker');
    M.Datepicker.init(datepicker, {
        format: 'dd/mm/yyyy',
        i18n: {
            months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            today: 'Hoje',
            clear: 'Limpar',
            cancel: 'Cancelar',
            done: 'OK'
        }
    });

    // Função para criar linha de item
    function createItemRow(ambienteIndex, itemIndex) {
        return `
            <div class="row item-row">
                <input type="hidden" name="rooms[${ambienteIndex}][items][${itemIndex}][id]" value="">
                
                <div class="input-field col s12 m3">
                    <select name="rooms[${ambienteIndex}][items][${itemIndex}][material_id]" required>
                        <option value="" disabled selected>Selecione o material</option>
                        <?php $__currentLoopData = $materiais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($material['id']); ?>" data-preco="<?php echo e($material['preco_padrao']); ?>">
                                <?php echo e($material['nome']); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <label>Material*</label>
                </div>

                <div class="input-field col s6 m2">
                    <input type="number" name="rooms[${ambienteIndex}][items][${itemIndex}][largura]" 
                           step="0.01" min="0" required class="validate">
                    <label>Largura (m)*</label>
                </div>

                <div class="input-field col s6 m2">
                    <input type="number" name="rooms[${ambienteIndex}][items][${itemIndex}][altura]" 
                           step="0.01" min="0" required class="validate">
                    <label>Altura (m)*</label>
                </div>

                <div class="input-field col s6 m2">
                    <input type="number" name="rooms[${ambienteIndex}][items][${itemIndex}][quantidade]" 
                           min="1" value="1" required class="validate">
                    <label>Quantidade*</label>
                </div>

                <div class="input-field col s6 m2">
                    <input type="number" name="rooms[${ambienteIndex}][items][${itemIndex}][valor_unitario]" 
                           step="0.01" min="0" required class="validate">
                    <label>Valor Unitário (R$)*</label>
                </div>

                <div class="col s12 m1">
                    <button type="button" class="btn-floating waves-effect waves-light red remove-item">
                        <i class="material-icons">remove</i>
                    </button>
                </div>
            </div>
        `;
    }

    // Função para criar linha de ambiente
    function createAmbienteRow() {
        const ambienteDiv = document.createElement('div');
        ambienteDiv.className = 'ambiente-container card-panel';
        ambienteDiv.innerHTML = `
            <div class="row">
                <div class="input-field col s12 m6">
                    <input type="text" name="rooms[${ambienteCount}][nome]" required class="validate">
                    <label>Nome do Ambiente*</label>
                </div>
                <div class="col s12 m6 right-align">
                    <button type="button" class="btn-floating waves-effect waves-light green add-item">
                        <i class="material-icons">add</i>
                    </button>
                    <button type="button" class="btn-floating waves-effect waves-light red remove-ambiente">
                        <i class="material-icons">delete</i>
                    </button>
                </div>
            </div>
            <div class="items-container"></div>
        `;

        const itemsContainer = ambienteDiv.querySelector('.items-container');
        let itemCount = 0;

        ambienteDiv.querySelector('.add-item').addEventListener('click', function() {
            const itemHtml = createItemRow(ambienteCount, itemCount);
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = itemHtml;
            itemsContainer.appendChild(tempDiv.firstElementChild);
            
            // Inicializar novos selects
            M.FormSelect.init(tempDiv.querySelectorAll('select'));
            itemCount++;
        });

        ambienteDiv.querySelector('.remove-ambiente').addEventListener('click', function() {
            if (confirm('Tem certeza que deseja remover este ambiente?')) {
                ambienteDiv.remove();
            }
        });

        return ambienteDiv;
    }

    // Adicionar ambiente
    const addAmbienteButton = document.getElementById('add-ambiente');
    const ambientesContainer = document.getElementById('ambientes-container');
    let ambienteCount = 0;

    addAmbienteButton.addEventListener('click', function() {
        const ambienteDiv = createAmbienteRow();
        ambientesContainer.appendChild(ambienteDiv);
        
        // Adicionar primeiro item automaticamente
        ambienteDiv.querySelector('.add-item').click();
        
        ambienteCount++;
    });

    // Adicionar primeiro ambiente automaticamente
    addAmbienteButton.click();

    // Validação do formulário antes de enviar
    document.getElementById('budget-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Verificar se há pelo menos um ambiente
        if (document.querySelectorAll('.ambiente-container').length === 0) {
            M.toast({html: 'Adicione pelo menos um ambiente!'});
            return false;
        }

        // Verificar se todos os campos obrigatórios estão preenchidos
        const requiredFields = this.querySelectorAll('[required]');
        let valid = true;

        requiredFields.forEach(field => {
            if (!field.value) {
                valid = false;
                field.classList.add('invalid');
                M.toast({html: 'Preencha todos os campos obrigatórios!'});
            }
        });

        if (valid) {
            this.submit();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/budgets/create.blade.php ENDPATH**/ ?>
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
    .ambiente-total {
        font-size: 1.2rem;
        font-weight: bold;
        margin-right: 1rem;
    }
    .remove-item:hover, .remove-ambiente:hover {
        background-color: #f44336 !important;
    }
    .material-select {
        width: 100% !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Novo Orçamento</span>

                <?php
                    \Log::info('Dados do formulário:', request()->all());
                ?>

                <form id="budget-form" action="<?php echo e(route('financial.budgets.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <div class="input-field col s12 m3">
                            <input type="text" id="numero" name="numero" value="<?php echo e($numero); ?>" readonly>
                            <label for="numero">Número do Orçamento</label>
                        </div>

                        <div class="input-field col s12 m3">
                            <input type="date" id="data" name="data" value="<?php echo e(date('Y-m-d')); ?>" required>
                            <label for="data">Data do Orçamento</label>
                        </div>

                        <div class="input-field col s12 m3">
                            <input type="date" id="previsao_entrega" name="previsao_entrega" required>
                            <label for="previsao_entrega">Previsão de Entrega</label>
                        </div>

                        <div class="input-field col s12 m3">
                            <select name="client_id" id="client_id" required>
                                <option value="" disabled selected>Selecione o cliente</option>
                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($client->id); ?>" 
                                            data-endereco="<?php echo e($client->endereco); ?>"
                                            data-telefone="<?php echo e($client->telefone); ?>"
                                            <?php echo e(old('client_id') == $client->id ? 'selected' : ''); ?>>
                                        <?php echo e($client->nome); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="client_id">Cliente*</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div id="client-info" style="display: none;">
                                <p><strong>Endereço:</strong> <span id="client-endereco"></span></p>
                                <p><strong>Telefone:</strong> <span id="client-telefone"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="rooms-container"></div>

                    <div class="row">
                        <div class="col s12">
                            <button type="button" class="btn waves-effect waves-light blue add-room">
                                <i class="material-icons left">add</i>
                                Adicionar Ambiente
                            </button>
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn waves-effect waves-light">
                            <i class="material-icons left">save</i>
                            Salvar Orçamento
                        </button>
                        
                        <a href="<?php echo e(route('financial.budgets.index')); ?>" class="btn waves-effect waves-light red">
                            <i class="material-icons left">cancel</i>
                            Cancelar
                        </a>
                    </div>
                </form>

                <button type="button" onclick="debugForm()">Debug Form</button>
            </div>
        </div>
    </div>
</div>

<!-- Adicione isso logo após o início do form -->
<template id="item-template">
    <div class="item-row">
        <div class="row">
            <div class="input-field col s12 m3">
                <select name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][material_id]" class="material-select" required>
                    <option value="" disabled selected>Selecione o material</option>
                    <?php $__currentLoopData = $materiais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($material->id); ?>" data-preco="<?php echo e($material->preco_venda); ?>">
                            <?php echo e($material->nome); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <label>Material*</label>
            </div>

            <div class="input-field col s6 m2">
                <input type="number" name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][quantidade]" step="0.001" min="0.001" required>
                <label>Quantidade*</label>
            </div>

            <div class="input-field col s6 m1">
                <select name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][unidade]" required>
                    <option value="m²">m²</option>
                    <option value="ml">ml</option>
                    <option value="pç">pç</option>
                </select>
                <label>Unid.*</label>
            </div>

            <div class="input-field col s6 m2">
                <input type="number" name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][largura]" step="0.001" min="0" required>
                <label>Largura (m)*</label>
            </div>

            <div class="input-field col s6 m2">
                <input type="number" name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][altura]" step="0.001" min="0" required>
                <label>Altura (m)*</label>
            </div>

            <div class="col s12 m2">
                <button type="button" class="btn-floating red remove-item">
                    <i class="material-icons">remove</i>
                </button>
            </div>
        </div>
    </div>
</template>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let roomIndex = 0;
    
    // Adiciona ambiente
    document.querySelector('.add-room').addEventListener('click', function() {
        const roomTemplate = `
            <div class="room-card card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" name="rooms[${roomIndex}][nome]" class="room-name" required>
                            <label>Nome do Ambiente*</label>
                        </div>
                    </div>
                    <div class="items-container"></div>
                    <button type="button" class="btn-floating green add-item">
                        <i class="material-icons">add</i>
                    </button>
                </div>
                <div class="card-action">
                    <button type="button" class="btn-floating red remove-room">
                        <i class="material-icons">delete</i>
                    </button>
                </div>
            </div>
        `;
        
        document.querySelector('.rooms-container').insertAdjacentHTML('beforeend', roomTemplate);
        M.updateTextFields();
        roomIndex++;
    });

    // Adiciona item
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-item')) {
            const roomCard = e.target.closest('.room-card');
            const roomIndex = Array.from(document.querySelectorAll('.room-card')).indexOf(roomCard);
            const itemsContainer = roomCard.querySelector('.items-container');
            const itemIndex = itemsContainer.querySelectorAll('.item-row').length;
            
            const template = document.getElementById('item-template').innerHTML
                .replace(/{ROOM_INDEX}/g, roomIndex)
                .replace(/{ITEM_INDEX}/g, itemIndex);
            
            itemsContainer.insertAdjacentHTML('beforeend', template);
            
            // Inicializa os selects do novo item
            M.FormSelect.init(itemsContainer.querySelectorAll('select'));
            M.updateTextFields();
        }
    });

    // Remove item/ambiente
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.item-row').remove();
        } else if (e.target.closest('.remove-room')) {
            e.target.closest('.room-card').remove();
        }
    });

    // Submit do formulário
    document.getElementById('budget-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const rooms = document.querySelectorAll('.room-card');
        if (rooms.length === 0) {
            M.toast({html: 'Adicione pelo menos um ambiente'});
            return;
        }

        let valid = true;
        rooms.forEach(room => {
            const items = room.querySelectorAll('.item-row');
            if (items.length === 0) {
                M.toast({html: `O ambiente ${room.querySelector('.room-name').value} precisa ter pelo menos um item`});
                valid = false;
            }
        });

        if (!valid) return;
        if (!this.checkValidity()) {
            M.toast({html: 'Preencha todos os campos obrigatórios'});
            return;
        }

        this.submit();
    });

    // Adiciona primeiro ambiente automaticamente
    document.querySelector('.add-room').click();
});

function debugForm() {
    const form = document.getElementById('budget-form');
    console.log('Form Data:', new FormData(form));
    console.log('Rooms:', document.querySelectorAll('.room-card').length);
    console.log('Items:', document.querySelectorAll('.item-row').length);
    // Tenta enviar o formulário diretamente
    form.submit();
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/budgets/create.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Novo Orçamento'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="material-icons left">add_circle</i>
                    Novo Orçamento
                </span>

                <form id="budget-form" action="<?php echo e(route('financial.budgets.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">tag</i>
                            <input type="text" id="numero" name="numero" value="<?php echo e($numero); ?>" readonly>
                            <label for="numero">Número do Orçamento</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">event</i>
                            <input type="date" id="data" name="data" value="<?php echo e(date('Y-m-d')); ?>" required>
                            <label for="data">Data do Orçamento</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <i class="material-icons prefix">event_available</i>
                            <input type="date" id="previsao_entrega" name="previsao_entrega" value="<?php echo e(date('Y-m-d', strtotime('+7 days'))); ?>" required>
                            <label for="previsao_entrega">Previsão de Entrega</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <select name="client_id" id="client_id" required>
                                <option value="" disabled selected>Selecione um cliente</option>
                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($client->id); ?>"><?php echo e($client->nome); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="client_id">Cliente</label>
                        </div>
                    </div>

                    <div id="rooms-container">
                        <!-- Os ambientes serão adicionados aqui via JavaScript -->
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="button" class="btn waves-effect waves-light blue add-room">
                                <i class="material-icons left">add</i>
                                Adicionar Ambiente
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">notes</i>
                            <textarea id="observacoes" name="observacoes" class="materialize-textarea"></textarea>
                            <label for="observacoes">Observações</label>
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn waves-effect waves-light green">
                            <i class="material-icons left">save</i>
                            Salvar Orçamento
                        </button>
                        
                        <a href="<?php echo e(route('financial.budgets.index')); ?>" class="btn waves-effect waves-light red">
                            <i class="material-icons left">cancel</i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="room-template">
    <div class="room-card card">
        <div class="card-content">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">room</i>
                    <input type="text" name="rooms[{ROOM_INDEX}][nome]" class="room-name" required>
                    <label>Nome do Ambiente</label>
                </div>
            </div>
            <div class="items-container"></div>
            <button type="button" class="btn waves-effect waves-light green add-item">
                <i class="material-icons left">add_shopping_cart</i>
                Adicionar Item
            </button>
        </div>
        <div class="card-action">
            <button type="button" class="btn waves-effect waves-light red remove-room">
                <i class="material-icons left">delete</i>
                Remover Ambiente
            </button>
        </div>
    </div>
</template>

<template id="item-template">
    <div class="item-row">
        <div class="row">
            <div class="input-field col s12 m3">
                <select name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][material_id]" required>
                    <option value="" disabled selected>Selecione um material</option>
                    <?php $__currentLoopData = $materiais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($material->id); ?>"><?php echo e($material->nome); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <label>Material</label>
            </div>
            <div class="input-field col s6 m2">
                <input type="number" name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][quantidade]" step="0.001" min="0.001" value="1" required>
                <label>Quantidade</label>
            </div>
            <div class="input-field col s6 m2">
                <select name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][unidade]" required>
                    <option value="m²">m²</option>
                    <option value="ml">ml</option>
                    <option value="pç">pç</option>
                    <option value="un">un</option>
                </select>
                <label>Unidade</label>
            </div>
            <div class="input-field col s6 m2">
                <input type="number" name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][largura]" step="0.001" min="0" value="1" required>
                <label>Largura (m)</label>
            </div>
            <div class="input-field col s6 m2">
                <input type="number" name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][altura]" step="0.001" min="0" value="1" required>
                <label>Altura (m)</label>
            </div>
            <div class="col s12 m1">
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
    
    // Inicializa componentes do Materialize
    M.AutoInit();
    
    // Adiciona ambiente
    document.querySelector('.add-room').addEventListener('click', function() {
        const template = document.getElementById('room-template').innerHTML
            .replace(/{ROOM_INDEX}/g, roomIndex);
        
        document.getElementById('rooms-container').insertAdjacentHTML('beforeend', template);
        
        // Inicializa os selects do novo ambiente
        const newRoom = document.querySelector(`.room-card:nth-child(${roomIndex + 1})`);
        M.FormSelect.init(newRoom.querySelectorAll('select'));
        M.updateTextFields();
        
        // Adiciona automaticamente o primeiro item
        const addItemButton = newRoom.querySelector('.add-item');
        addItemButton.click();
        
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
        const rooms = document.querySelectorAll('.room-card');
        
        // Verifica se tem pelo menos um ambiente
        if (rooms.length === 0) {
            e.preventDefault();
            M.toast({html: 'Adicione pelo menos um ambiente'});
            return false;
        }
        
        // Verifica se cada ambiente tem pelo menos um item
        let valid = true;
        
        rooms.forEach((room) => {
            const items = room.querySelectorAll('.item-row');
            const roomName = room.querySelector('.room-name').value || 'Ambiente sem nome';
            
            if (items.length === 0) {
                e.preventDefault();
                M.toast({html: `O ambiente "${roomName}" precisa ter pelo menos um item`});
                valid = false;
            }
        });
        
        if (!valid) {
            return false;
        }
    });
    
    // Adiciona primeiro ambiente automaticamente
    document.querySelector('.add-room').click();
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/budgets/create.blade.php ENDPATH**/ ?>
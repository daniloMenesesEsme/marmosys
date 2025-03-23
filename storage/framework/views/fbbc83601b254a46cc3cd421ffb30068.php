

<?php $__env->startSection('title', 'Editar Orçamento'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Editar Orçamento <?php echo e($budget->numero); ?></span>

                <form action="<?php echo e(route('financial.budgets.update', $budget)); ?>" method="POST" id="budget-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="numero" name="numero" value="<?php echo e($budget->numero); ?>" readonly>
                            <label for="numero">Número do Orçamento</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="date" id="data" name="data" value="<?php echo e($budget->data->format('Y-m-d')); ?>" required>
                            <label for="data">Data do Orçamento</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <select name="client_id" id="client_id" required>
                                <option value="" disabled>Selecione o cliente</option>
                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($client->id); ?>" 
                                            data-endereco="<?php echo e($client->endereco); ?>"
                                            data-telefone="<?php echo e($client->telefone); ?>"
                                            <?php echo e(old('client_id', $budget->client_id) == $client->id ? 'selected' : ''); ?>>
                                        <?php echo e($client->nome); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="client_id">Cliente*</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div id="client-info">
                                <p><strong>Endereço:</strong> <span id="client-endereco"><?php echo e($budget->client->endereco); ?></span></p>
                                <p><strong>Telefone:</strong> <span id="client-telefone"><?php echo e($budget->client->telefone); ?></span></p>
                            </div>
                        </div>
                    </div>

                    <div id="rooms-container">
                        <?php $__currentLoopData = $budget->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomIndex => $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="room-card card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input type="text" 
                                                   name="rooms[<?php echo e($roomIndex); ?>][nome]" 
                                                   value="<?php echo e($room->nome); ?>" 
                                                   class="room-name" 
                                                   required>
                                            <label>Nome do Ambiente*</label>
                                        </div>
                                    </div>

                                    <div class="items-container">
                                        <?php $__currentLoopData = $room->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="row item-row">
                                                <div class="input-field col s12 m3">
                                                    <select name="rooms[<?php echo e($roomIndex); ?>][items][<?php echo e($itemIndex); ?>][material_id]" 
                                                            class="material-select" required>
                                                        <option value="" disabled>Selecione o material</option>
                                                        <?php $__currentLoopData = $materiais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($material->id); ?>" 
                                                                    data-preco="<?php echo e($material->preco_padrao); ?>"
                                                                    data-unidade="<?php echo e($material->unidade_medida); ?>"
                                                                    <?php echo e($item->material_id == $material->id ? 'selected' : ''); ?>>
                                                                <?php echo e($material->nome); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <label>Material*</label>
                                                </div>

                                                <div class="input-field col s6 m2">
                                                    <input type="number" 
                                                           step="0.001" 
                                                           min="0.001"
                                                           name="rooms[<?php echo e($roomIndex); ?>][items][<?php echo e($itemIndex); ?>][quantidade]"
                                                           value="<?php echo e($item->quantidade); ?>"
                                                           class="quantidade" 
                                                           required>
                                                    <label>Quantidade*</label>
                                                </div>

                                                <div class="input-field col s6 m1">
                                                    <input type="text" 
                                                           name="rooms[<?php echo e($roomIndex); ?>][items][<?php echo e($itemIndex); ?>][unidade]"
                                                           value="<?php echo e($item->unidade); ?>"
                                                           class="unidade" 
                                                           readonly>
                                                    <label>Unid.</label>
                                                </div>

                                                <div class="input-field col s6 m2">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           min="0"
                                                           name="rooms[<?php echo e($roomIndex); ?>][items][<?php echo e($itemIndex); ?>][largura]"
                                                           value="<?php echo e($item->largura); ?>"
                                                           class="largura" 
                                                           required>
                                                    <label>Largura (m)*</label>
                                                </div>

                                                <div class="input-field col s6 m2">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           min="0"
                                                           name="rooms[<?php echo e($roomIndex); ?>][items][<?php echo e($itemIndex); ?>][altura]"
                                                           value="<?php echo e($item->altura); ?>"
                                                           class="altura" 
                                                           required>
                                                    <label>Altura (m)*</label>
                                                </div>

                                                <div class="input-field col s6 m2">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           min="0"
                                                           name="rooms[<?php echo e($roomIndex); ?>][items][<?php echo e($itemIndex); ?>][valor_unitario]"
                                                           value="<?php echo e($item->valor_unitario); ?>"
                                                           class="valor-unitario" 
                                                           required>
                                                    <label>Valor Unit.*</label>
                                                </div>

                                                <div class="col s12">
                                                    <button type="button" class="btn-floating red remove-item">
                                                        <i class="material-icons">remove</i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <div class="row">
                                        <div class="col s12">
                                            <button type="button" class="btn-floating green add-item">
                                                <i class="material-icons">add</i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-action">
                                    <button type="button" class="btn-floating red remove-room">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="button" class="btn waves-effect waves-light blue add-room">
                                <i class="material-icons left">add</i>
                                Adicionar Ambiente
                            </button>
                        </div>
                    </div>

                    <!-- Seção de Pagamento -->
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">
                                <i class="material-icons left">payment</i>
                                Informações de Pagamento
                            </span>
                            
                            <div class="row">
                                <div class="input-field col s12 m6">
                                    <i class="material-icons prefix">credit_card</i>
                                    <select name="payment_method_id" id="payment_method_id">
                                        <option value="">Selecione uma forma de pagamento</option>
                                        <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($method->id); ?>" 
                                                    data-parcelas="<?php echo e($method->parcelas_padrao); ?>" 
                                                    data-taxa="<?php echo e($method->taxa_padrao); ?>"
                                                    <?php echo e($budget->payment_method_id == $method->id ? 'selected' : ''); ?>>
                                                <?php echo e($method->nome); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <label for="payment_method_id">Forma de Pagamento</label>
                                </div>
                                
                                <div class="input-field col s12 m6">
                                    <i class="material-icons prefix">description</i>
                                    <input type="text" id="payment_condition" name="payment_condition" value="<?php echo e($budget->payment_condition); ?>">
                                    <label for="payment_condition">Condição de Pagamento</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="input-field col s12 m4">
                                    <i class="material-icons prefix">date_range</i>
                                    <input type="date" id="first_installment_date" name="first_installment_date" 
                                           value="<?php echo e($budget->first_installment_date ? $budget->first_installment_date->format('Y-m-d') : date('Y-m-d', strtotime('+7 days'))); ?>">
                                    <label for="first_installment_date">Data da Primeira Parcela</label>
                                </div>
                                
                                <div class="input-field col s12 m4">
                                    <i class="material-icons prefix">view_week</i>
                                    <input type="number" id="payment_installments" name="payment_installments" min="1" 
                                           value="<?php echo e($budget->payment_installments ?? 1); ?>">
                                    <label for="payment_installments">Número de Parcelas</label>
                                </div>
                                
                                <div class="input-field col s12 m4">
                                    <i class="material-icons prefix">attach_money</i>
                                    <input type="number" id="payment_fee" name="payment_fee" step="0.01" min="0" 
                                           value="<?php echo e($budget->payment_fee ?? 0); ?>">
                                    <label for="payment_fee">Taxa (%)</label>
                                </div>
                            </div>
                            
                            <?php if($budget->converted_to_receivable): ?>
                                <div class="row">
                                    <div class="col s12">
                                        <div class="card-panel green lighten-4">
                                            <i class="material-icons left">info</i>
                                            <span>Este orçamento já foi convertido em contas a receber. Alterar as condições de pagamento irá gerar novas contas a receber.</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">notes</i>
                            <textarea id="observacoes" name="observacoes" class="materialize-textarea"><?php echo e(old('observacoes', $budget->observacoes)); ?></textarea>
                            <label for="observacoes">Observações</label>
                            <?php $__errorArgs = ['observacoes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="red-text"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn waves-effect waves-light">
                            <i class="material-icons left">save</i>
                            Atualizar Orçamento
                        </button>
                        
                        <a href="<?php echo e(route('financial.budgets.show', $budget)); ?>" class="btn waves-effect waves-light red">
                            <i class="material-icons left">cancel</i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Template para novo ambiente -->
<?php echo $__env->make('financial.budgets._room_template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar componentes Materialize
    M.AutoInit();

    let roomIndex = <?php echo e($budget->rooms->count()); ?>;
    
    // Adicionar item a um ambiente
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-item')) {
            const roomCard = e.target.closest('.room-card');
            const roomIndex = Array.from(document.querySelectorAll('.room-card')).indexOf(roomCard);
            const itemsContainer = roomCard.querySelector('.items-container');
            const itemIndex = itemsContainer.querySelectorAll('.item-row').length;
            
            const newItemRow = `
                <div class="row item-row">
                    <div class="input-field col s12 m3">
                        <select name="rooms[${roomIndex}][items][${itemIndex}][material_id]" class="material-select" required>
                            <option value="" disabled selected>Selecione o material</option>
                            <?php $__currentLoopData = $materiais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($material->id); ?>" 
                                        data-preco="<?php echo e($material->preco_padrao); ?>"
                                        data-unidade="<?php echo e($material->unidade_medida); ?>">
                                    <?php echo e($material->nome); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <label>Material*</label>
                    </div>

                    <div class="input-field col s6 m2">
                        <input type="number" 
                               step="0.001" 
                               min="0.001"
                               name="rooms[${roomIndex}][items][${itemIndex}][quantidade]" 
                               class="quantidade" 
                               required>
                        <label>Quantidade*</label>
                    </div>

                    <div class="input-field col s6 m1">
                        <input type="text" 
                               name="rooms[${roomIndex}][items][${itemIndex}][unidade]" 
                               class="unidade" 
                               readonly>
                        <label>Unid.</label>
                    </div>

                    <div class="input-field col s6 m2">
                        <input type="number" 
                               step="0.01" 
                               min="0"
                               name="rooms[${roomIndex}][items][${itemIndex}][largura]" 
                               class="largura" 
                               required>
                        <label>Largura (m)*</label>
                    </div>

                    <div class="input-field col s6 m2">
                        <input type="number" 
                               step="0.01" 
                               min="0"
                               name="rooms[${roomIndex}][items][${itemIndex}][altura]" 
                               class="altura" 
                               required>
                        <label>Altura (m)*</label>
                    </div>

                    <div class="input-field col s6 m2">
                        <input type="number" 
                               step="0.01" 
                               min="0"
                               name="rooms[${roomIndex}][items][${itemIndex}][valor_unitario]" 
                               class="valor-unitario" 
                               required>
                        <label>Valor Unit.*</label>
                    </div>

                    <div class="col s12">
                        <button type="button" class="btn-floating red remove-item">
                            <i class="material-icons">remove</i>
                        </button>
                    </div>
                </div>
            `;
            
            itemsContainer.insertAdjacentHTML('beforeend', newItemRow);
            
            // Inicializar select do novo item
            const newSelects = itemsContainer.querySelectorAll('select');
            M.FormSelect.init(newSelects[newSelects.length - 1]);
        }
    });

    // Adicionar novo ambiente
    document.querySelector('.add-room').addEventListener('click', function() {
        const newRoomCard = `
            <div class="room-card card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" name="rooms[${roomIndex}][nome]" class="room-name" required>
                            <label>Nome do Ambiente*</label>
                        </div>
                    </div>
                    <div class="items-container"></div>
                    <button type="button" class="btn green add-item">
                        <i class="material-icons left">add</i>
                        Adicionar Item
                    </button>
                </div>
                <div class="card-action">
                    <button type="button" class="btn red remove-room">
                        <i class="material-icons left">delete</i>
                        Remover Ambiente
                    </button>
                </div>
            </div>
        `;
        
        document.getElementById('rooms-container').insertAdjacentHTML('beforeend', newRoomCard);
        M.updateTextFields();
        
        roomIndex++;
    });

    // Remover item
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });

    // Remover ambiente
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-room')) {
            e.target.closest('.room-card').remove();
        }
    });

    // Submit do formulário
    document.getElementById('budget-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('Validando formulário antes do envio');
        
        // Verifica se há ambientes
        const rooms = document.querySelectorAll('.room-card');
        if (rooms.length === 0) {
            M.toast({html: 'Adicione pelo menos um ambiente'});
            return false;
        }
        
        // Verifica e preenche nomes vazios de ambientes
        rooms.forEach((room, index) => {
            const roomNameInput = room.querySelector('.room-name');
            if (!roomNameInput.value.trim()) {
                roomNameInput.value = `Ambiente ${index + 1}`;
                console.log(`Nome automático atribuído: Ambiente ${index + 1}`);
            }
        });
        
        // Verifica se cada ambiente tem pelo menos um item
        let valid = true;
        
        rooms.forEach((room, roomIndex) => {
            const roomName = room.querySelector('.room-name').value || `Ambiente ${roomIndex + 1}`;
            const items = room.querySelectorAll('.item-row');
            
            if (items.length === 0) {
                M.toast({html: `O ambiente ${roomName} precisa ter pelo menos um item`});
                valid = false;
            }
        });
        
        if (!valid) {
            return false;
        }
        
        // Se chegou até aqui, envia o formulário
        this.submit();
    });

    // Atualizar informações do cliente
    document.getElementById('client_id').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        document.getElementById('client-endereco').textContent = option.dataset.endereco || 'Não informado';
        document.getElementById('client-telefone').textContent = option.dataset.telefone || 'Não informado';
    });

    // Atualizar parcelas e taxa quando mudar o método de pagamento
    document.getElementById('payment_method_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            document.getElementById('payment_installments').value = selectedOption.dataset.parcelas || 1;
            document.getElementById('payment_fee').value = selectedOption.dataset.taxa || 0;
        } else {
            document.getElementById('payment_installments').value = 1;
            document.getElementById('payment_fee').value = 0;
        }
    });
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/budgets/edit.blade.php ENDPATH**/ ?>
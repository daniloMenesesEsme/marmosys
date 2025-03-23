@extends('layouts.app')

@section('title', 'Editar Orçamento')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Editar Orçamento {{ $budget->numero }}</span>

                <form action="{{ route('financial.budgets.update', $budget) }}" method="POST" id="budget-form">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="numero" name="numero" value="{{ $budget->numero }}" readonly>
                            <label for="numero">Número do Orçamento</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="date" id="data" name="data" value="{{ $budget->data->format('Y-m-d') }}" required>
                            <label for="data">Data do Orçamento</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <select name="client_id" id="client_id" required>
                                <option value="" disabled>Selecione o cliente</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" 
                                            data-endereco="{{ $client->endereco }}"
                                            data-telefone="{{ $client->telefone }}"
                                            {{ old('client_id', $budget->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="client_id">Cliente*</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div id="client-info">
                                <p><strong>Endereço:</strong> <span id="client-endereco">{{ $budget->client->endereco }}</span></p>
                                <p><strong>Telefone:</strong> <span id="client-telefone">{{ $budget->client->telefone }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div id="rooms-container">
                        @foreach($budget->rooms as $roomIndex => $room)
                            <div class="room-card card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input type="text" 
                                                   name="rooms[{{ $roomIndex }}][nome]" 
                                                   value="{{ $room->nome }}" 
                                                   class="room-name" 
                                                   required>
                                            <label>Nome do Ambiente*</label>
                                        </div>
                                    </div>

                                    <div class="items-container">
                                        @foreach($room->items as $itemIndex => $item)
                                            <div class="row item-row">
                                                <div class="input-field col s12 m3">
                                                    <select name="rooms[{{ $roomIndex }}][items][{{ $itemIndex }}][material_id]" 
                                                            class="material-select" required>
                                                        <option value="" disabled>Selecione o material</option>
                                                        @foreach($materiais as $material)
                                                            <option value="{{ $material->id }}" 
                                                                    data-preco="{{ $material->preco_padrao }}"
                                                                    data-unidade="{{ $material->unidade_medida }}"
                                                                    {{ $item->material_id == $material->id ? 'selected' : '' }}>
                                                                {{ $material->nome }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label>Material*</label>
                                                </div>

                                                <div class="input-field col s6 m2">
                                                    <input type="number" 
                                                           step="0.001" 
                                                           min="0.001"
                                                           name="rooms[{{ $roomIndex }}][items][{{ $itemIndex }}][quantidade]"
                                                           value="{{ $item->quantidade }}"
                                                           class="quantidade" 
                                                           required>
                                                    <label>Quantidade*</label>
                                                </div>

                                                <div class="input-field col s6 m1">
                                                    <input type="text" 
                                                           name="rooms[{{ $roomIndex }}][items][{{ $itemIndex }}][unidade]"
                                                           value="{{ $item->unidade }}"
                                                           class="unidade" 
                                                           readonly>
                                                    <label>Unid.</label>
                                                </div>

                                                <div class="input-field col s6 m2">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           min="0"
                                                           name="rooms[{{ $roomIndex }}][items][{{ $itemIndex }}][largura]"
                                                           value="{{ $item->largura }}"
                                                           class="largura" 
                                                           required>
                                                    <label>Largura (m)*</label>
                                                </div>

                                                <div class="input-field col s6 m2">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           min="0"
                                                           name="rooms[{{ $roomIndex }}][items][{{ $itemIndex }}][altura]"
                                                           value="{{ $item->altura }}"
                                                           class="altura" 
                                                           required>
                                                    <label>Altura (m)*</label>
                                                </div>

                                                <div class="input-field col s6 m2">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           min="0"
                                                           name="rooms[{{ $roomIndex }}][items][{{ $itemIndex }}][valor_unitario]"
                                                           value="{{ $item->valor_unitario }}"
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
                                        @endforeach
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
                        @endforeach
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
                                        @foreach($paymentMethods as $method)
                                            <option value="{{ $method->id }}" 
                                                    data-parcelas="{{ $method->parcelas_padrao }}" 
                                                    data-taxa="{{ $method->taxa_padrao }}"
                                                    {{ $budget->payment_method_id == $method->id ? 'selected' : '' }}>
                                                {{ $method->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="payment_method_id">Forma de Pagamento</label>
                                </div>
                                
                                <div class="input-field col s12 m6">
                                    <i class="material-icons prefix">description</i>
                                    <input type="text" id="payment_condition" name="payment_condition" value="{{ $budget->payment_condition }}">
                                    <label for="payment_condition">Condição de Pagamento</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="input-field col s12 m4">
                                    <i class="material-icons prefix">date_range</i>
                                    <input type="date" id="first_installment_date" name="first_installment_date" 
                                           value="{{ $budget->first_installment_date ? $budget->first_installment_date->format('Y-m-d') : date('Y-m-d', strtotime('+7 days')) }}">
                                    <label for="first_installment_date">Data da Primeira Parcela</label>
                                </div>
                                
                                <div class="input-field col s12 m4">
                                    <i class="material-icons prefix">view_week</i>
                                    <input type="number" id="payment_installments" name="payment_installments" min="1" 
                                           value="{{ $budget->payment_installments ?? 1 }}">
                                    <label for="payment_installments">Número de Parcelas</label>
                                </div>
                                
                                <div class="input-field col s12 m4">
                                    <i class="material-icons prefix">attach_money</i>
                                    <input type="number" id="payment_fee" name="payment_fee" step="0.01" min="0" 
                                           value="{{ $budget->payment_fee ?? 0 }}">
                                    <label for="payment_fee">Taxa (%)</label>
                                </div>
                            </div>
                            
                            @if($budget->converted_to_receivable)
                                <div class="row">
                                    <div class="col s12">
                                        <div class="card-panel green lighten-4">
                                            <i class="material-icons left">info</i>
                                            <span>Este orçamento já foi convertido em contas a receber. Alterar as condições de pagamento irá gerar novas contas a receber.</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">notes</i>
                            <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes', $budget->observacoes) }}</textarea>
                            <label for="observacoes">Observações</label>
                            @error('observacoes') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn waves-effect waves-light">
                            <i class="material-icons left">save</i>
                            Atualizar Orçamento
                        </button>
                        
                        <a href="{{ route('financial.budgets.show', $budget) }}" class="btn waves-effect waves-light red">
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
@include('financial.budgets._room_template')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar componentes Materialize
    M.AutoInit();

    let roomIndex = {{ $budget->rooms->count() }};
    
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
                            @foreach($materiais as $material)
                                <option value="{{ $material->id }}" 
                                        data-preco="{{ $material->preco_padrao }}"
                                        data-unidade="{{ $material->unidade_medida }}">
                                    {{ $material->nome }}
                                </option>
                            @endforeach
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
@endpush 
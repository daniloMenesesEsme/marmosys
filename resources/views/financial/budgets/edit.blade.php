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
    // Inicializa os selects do Materialize
    M.FormSelect.init(document.querySelectorAll('select'));
    
    // Mostra informações do cliente ao selecionar
    const clientSelect = document.getElementById('client_id');
    clientSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('client-endereco').textContent = selectedOption.dataset.endereco || 'Não informado';
        document.getElementById('client-telefone').textContent = selectedOption.dataset.telefone || 'Não informado';
    });

    // Adiciona ambiente
    let roomIndex = {{ count($budget->rooms) }};
    document.querySelector('.add-room').addEventListener('click', function() {
        const template = document.querySelector('.room-template').innerHTML;
        const newRoom = template.replace(/{ROOM_INDEX}/g, roomIndex++);
        document.getElementById('rooms-container').insertAdjacentHTML('beforeend', newRoom);
        M.FormSelect.init(document.querySelectorAll('select'));
        M.updateTextFields();
    });

    // Remove ambiente
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-room')) {
            e.target.closest('.room-card').remove();
        }
    });

    // Adiciona item
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-item')) {
            const roomCard = e.target.closest('.room-card');
            const itemTemplate = document.querySelector('.item-template').innerHTML;
            const itemsContainer = roomCard.querySelector('.items-container');
            const itemIndex = itemsContainer.querySelectorAll('.item-row').length;
            const newItem = itemTemplate
                .replace(/{ROOM_INDEX}/g, roomCard.dataset.roomIndex)
                .replace(/{ITEM_INDEX}/g, itemIndex);
            itemsContainer.insertAdjacentHTML('beforeend', newItem);
            M.FormSelect.init(itemsContainer.querySelectorAll('select'));
            M.updateTextFields();
        }
    });

    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });

    // Atualiza unidade e valor unitário ao selecionar material
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('material-select')) {
            const row = e.target.closest('.item-row');
            const selectedOption = e.target.options[e.target.selectedIndex];
            row.querySelector('.unidade').value = selectedOption.dataset.unidade;
            row.querySelector('.valor-unitario').value = selectedOption.dataset.preco;
            M.updateTextFields();
        }
    });
});
</script>
@endpush 
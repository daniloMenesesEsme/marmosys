@extends('layouts.app')

@section('title', 'Novo Orçamento')

@push('styles')
<style>
    /* Espaçamento geral */
    .row {
        margin-bottom: 20px;
    }

    /* Ajuste dos campos superiores */
    .header-fields {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background-color: #f5f5f5;
        border-radius: 4px;
        margin-bottom: 30px;
    }

    .header-fields .input-field {
        margin: 0 15px;
        flex: 1;
    }

    .header-fields .input-field:first-child {
        margin-left: 0;
    }

    .header-fields .input-field:last-child {
        margin-right: 0;
    }

    /* Ajuste do campo de cliente */
    .client-field {
        padding: 20px;
        background-color: #fff;
        border-radius: 4px;
        box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14);
    }

    /* Ajuste dos inputs */
    .input-field input, .input-field select {
        height: 3rem;
    }

    .input-field label {
        transform: translateY(-14px) scale(0.8);
    }

    /* Ícones */
    .material-icons.prefix {
        line-height: 3rem;
    }

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
        padding: 10px 0;
        margin-bottom: 10px;
    }
    label {
        font-size: 0.9rem;
    }
    input[type="number"] {
        height: 2.5rem;
    }
    /* Estilos do datepicker */
    .datepicker-date-display {
        background-color: #031e34;
    }
    .datepicker-table td.is-selected {
        background-color: #033c6b;
    }
    .datepicker-table td.is-today {
        color: #051f33;
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

    /* Ajuste do espaçamento do select de cliente sem ícone */
    .select-wrapper input.select-dropdown {
        margin-left: 0 !important;
        width: 100% !important;
    }

    /* Ajuste do dropdown */
    .dropdown-content {
        margin-left: 0;
        width: 100% !important;
    }

    /* Estilo para os botões */
    .btn {
        margin: 5px;
        text-transform: none;
        height: 36px;
        line-height: 36px;
        padding: 0 16px;
    }
    
    .btn i.material-icons {
        line-height: 36px;
    }

    /* Ajuste do espaçamento entre colunas */
    .row .col {
        padding: 0 0.75rem;
    }

    /* Alinhamento vertical do botão remover */
    .btn-floating {
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="material-icons left">add_circle</i>
                    Novo Orçamento
                </span>

                @php
                    \Log::info('Dados do formulário:', request()->all());
                @endphp

                <form id="budget-form" action="{{ route('financial.budgets.store') }}" method="POST">
                    @csrf
                    
                    <div class="header-fields">
                        <div class="input-field">
                            <i class="material-icons prefix">tag</i>
                            <input type="text" id="numero" name="numero" value="{{ $numero }}" readonly>
                            <label for="numero">Número do Orçamento</label>
                        </div>

                        <div class="input-field">
                            <i class="material-icons prefix">event</i>
                            <input type="date" id="data" name="data" value="{{ date('Y-m-d') }}" required>
                            <label for="data">Data do Orçamento</label>
                        </div>

                        <div class="input-field">
                            <i class="material-icons prefix">event_available</i>
                            <input type="date" id="previsao_entrega" name="previsao_entrega" required>
                            <label for="previsao_entrega">Previsão de Entrega</label>
                        </div>
                    </div>

                    <div class="client-field">
                        <div class="input-field">
                            <select name="client_id" id="client_id" class="browser-default" required>
                                <option value="" disabled selected>Digite para buscar o cliente</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" 
                                            data-endereco="{{ $client->endereco }}"
                                            data-telefone="{{ $client->telefone }}"
                                            {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="client_id">Cliente*</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div id="client-info" class="card-panel" style="display: none;">
                                <i class="material-icons left">info</i>
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

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">notes</i>
                            <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes') }}</textarea>
                            <label for="observacoes">Observações</label>
                            @error('observacoes') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn waves-effect waves-light green">
                            <i class="material-icons left">save</i>
                            Salvar Orçamento
                        </button>
                        
                        <a href="{{ route('financial.budgets.index') }}" class="btn waves-effect waves-light red">
                            <i class="material-icons left">cancel</i>
                            Cancelar
                        </a>
                    </div>
                </form>

                <!--<button type="button" onclick="debugForm()">Debug Form</button>-->
            </div>
        </div>
    </div>
</div>

<!-- Adicione isso logo após o início do form -->
<template id="item-template">
    <div class="item-row">
        <div class="row" style="margin-bottom: 0;">
            <div class="input-field col s12 m3">
                <input type="text" 
                       class="autocomplete-material" 
                       placeholder="Digite código ou nome"
                       autocomplete="off">
                <input type="hidden" 
                       name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][material_id]" 
                       class="material-id-input" 
                       required>
                <label>Material*</label>
            </div>

            <div class="input-field col s6 m2">
                <input type="number" 
                       name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][quantidade]" 
                       step="0.001" 
                       min="0.001" 
                       required>
                <label>Quantidade*</label>
            </div>

            <div class="input-field col s6 m2">
                <select name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][unidade]" required>
                    <option value="m²">m²</option>
                    <option value="ml">ml</option>
                    <option value="pç">pç</option>
                </select>
                <label>Unid.*</label>
            </div>

            <div class="input-field col s6 m2">
                <input type="number" 
                       name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][largura]" 
                       step="0.001" 
                       min="0" 
                       required>
                <label>Largura (m)*</label>
            </div>

            <div class="input-field col s6 m2">
                <input type="number" 
                       name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][altura]" 
                       step="0.001" 
                       min="0" 
                       required>
                <label>Altura (m)*</label>
            </div>

            <div class="col s12 m1 center-align" style="padding-top: 10px;">
                <button type="button" class="btn-floating red remove-item tooltipped" data-position="top" data-tooltip="Remover Item">
                    <i class="material-icons">remove</i>
                </button>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let roomIndex = 0;
    
    // Inicializa componentes do Materialize
    M.AutoInit();
    
    // Adiciona ambiente
    document.querySelector('.add-room').addEventListener('click', function() {
        const roomTemplate = `
            <div class="room-card card">
                <div class="card-content">
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">room</i>
                            <input type="text" name="rooms[${roomIndex}][nome]" class="room-name" required>
                            <label>Nome do Ambiente*</label>
                        </div>
                    </div>
                    <div class="items-container"></div>
                    <button type="button" class="btn waves-effect waves-light green add-item tooltipped" data-position="top" data-tooltip="Adicionar Item">
                        <i class="material-icons left">add_shopping_cart</i>
                        Adicionar Item
                    </button>
                </div>
                <div class="card-action">
                    <button type="button" class="btn waves-effect waves-light red remove-room tooltipped" data-position="top" data-tooltip="Remover Ambiente">
                        <i class="material-icons left">delete</i>
                        Remover Ambiente
                    </button>
                </div>
            </div>
        `;

        document.querySelector('.rooms-container').insertAdjacentHTML('beforeend', roomTemplate);
        M.updateTextFields();
        M.Tooltip.init(document.querySelectorAll('.tooltipped'));
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

    // Inicializa o select com funcionalidade de busca
    M.FormSelect.init(document.querySelector('#client_id'), {
        dropdownOptions: {
            search: true, // Habilita a busca
            searchText: 'Digite o nome do cliente',
        }
    });

    // Preparar dados dos materiais para autocomplete
    const materiais = {
        @foreach($materiais as $material)
            "{{ $material->codigo }} - {{ $material->nome }}": {
                id: {{ $material->id }},
                preco: {{ $material->preco_venda }}
            },
        @endforeach
    };

    // Inicializar autocomplete em campos existentes e novos
    function initAutocomplete(element) {
        M.Autocomplete.init(element, {
            data: materiais,
            minLength: 1,
            onAutocomplete: function(text) {
                // Quando um item é selecionado, atualiza o input hidden
                const materialId = materiais[text].id;
                element.nextElementSibling.value = materialId;
            }
        });
    }

    // Inicializar autocomplete para campos existentes
    document.querySelectorAll('.autocomplete-material').forEach(initAutocomplete);

    // Observer para inicializar autocomplete em novos campos
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Elemento
                    const newInputs = node.querySelectorAll('.autocomplete-material');
                    newInputs.forEach(initAutocomplete);
                }
            });
        });
    });

    // Observar adições de novos campos
    observer.observe(document.querySelector('.rooms-container'), {
        childList: true,
        subtree: true
    });
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
@endpush 
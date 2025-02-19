@extends('layouts.app')

@section('title', 'Novo Orçamento')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Novo Orçamento</span>

                <form action="{{ route('budgets.store') }}" method="POST" id="budgetForm">
                    @csrf
                    
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <select name="client_id" id="client_id" required>
                                <option value="" disabled selected>Selecione o cliente</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="client_id">Cliente*</label>
                            @error('client_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="date" id="data_validade" name="data_validade" value="{{ old('data_validade', date('Y-m-d', strtotime('+30 days'))) }}" required>
                            <label for="data_validade">Data de Validade*</label>
                            @error('data_validade') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <h5>Itens do Orçamento</h5>
                            <div id="items-container">
                                <!-- Items will be added here -->
                            </div>
                            
                            <button type="button" class="btn-floating waves-effect waves-light green" id="add-item">
                                <i class="material-icons">add</i>
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes') }}</textarea>
                            <label for="observacoes">Observações</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="{{ route('budgets.index') }}" class="btn waves-effect waves-light grey">
                                <i class="material-icons left">arrow_back</i>
                                Voltar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    const itemsContainer = document.getElementById('items-container');
    const addItemButton = document.getElementById('add-item');
    let itemCount = 0;

    function createItemRow() {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'row item-row';
        itemDiv.innerHTML = `
            <div class="col s12">
                <div class="card-panel">
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" name="items[${itemCount}][descricao]" required>
                            <label>Descrição*</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input type="text" name="items[${itemCount}][ambiente]" required>
                            <label>Ambiente*</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6 m3">
                            <input type="number" step="0.01" name="items[${itemCount}][largura]" required>
                            <label>Largura (m)*</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <input type="number" step="0.01" name="items[${itemCount}][altura]" required>
                            <label>Altura (m)*</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <input type="number" step="0.01" name="items[${itemCount}][quantidade]" required>
                            <label>Quantidade*</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <input type="number" step="0.01" name="items[${itemCount}][valor_unitario]" required>
                            <label>Valor Unitário (R$)*</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea name="items[${itemCount}][observacoes]" class="materialize-textarea"></textarea>
                            <label>Observações do Item</label>
                        </div>
                    </div>
                    <button type="button" class="btn-floating waves-effect waves-light red remove-item">
                        <i class="material-icons">remove</i>
                    </button>
                </div>
            </div>
        `;

        itemsContainer.appendChild(itemDiv);
        itemCount++;

        // Initialize new textareas
        M.textareaAutoResize(itemDiv.querySelector('textarea'));

        // Add remove handler
        itemDiv.querySelector('.remove-item').addEventListener('click', function() {
            itemDiv.remove();
        });
    }

    addItemButton.addEventListener('click', createItemRow);

    // Add first item row
    createItemRow();
});
</script>
@endpush 
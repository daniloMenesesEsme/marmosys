@extends('layouts.app')

@section('title', 'Novo Orçamento')

@push('styles')
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
@endpush

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Novo Orçamento</span>

                <form action="{{ route('financial.budgets.store') }}" method="POST" id="budget-form">
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
                            <input type="text" id="previsao_entrega" name="previsao_entrega" class="datepicker" value="{{ old('previsao_entrega') }}" required>
                            <label for="previsao_entrega">Previsão de Entrega*</label>
                            @error('previsao_entrega') <span class="red-text">{{ $message }}</span> @enderror
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
                            <a href="{{ route('financial.budgets.index') }}" class="btn waves-effect waves-light red">
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
@endsection

@push('scripts')
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
                        @foreach($materiais as $material)
                            <option value="{{ $material->id }}" data-preco="{{ $material->preco_padrao }}">
                                {{ $material->nome }}
                            </option>
                        @endforeach
                    </select>
                    <label>Material*</label>
                </div>

                <div class="input-field col s6 m2">
                    <input type="number" name="rooms[${ambienteIndex}][items][${itemIndex}][largura]" 
                           step="0.01" min="0" required class="validate largura">
                    <label>Largura (m)*</label>
                </div>

                <div class="input-field col s6 m2">
                    <input type="number" name="rooms[${ambienteIndex}][items][${itemIndex}][altura]" 
                           step="0.01" min="0" required class="validate altura">
                    <label>Altura (m)*</label>
                </div>

                <div class="input-field col s6 m2">
                    <input type="number" name="rooms[${ambienteIndex}][items][${itemIndex}][quantidade]" 
                           min="1" value="1" required class="validate quantidade">
                    <label>Quantidade*</label>
                </div>

                <div class="input-field col s6 m2">
                    <input type="number" name="rooms[${ambienteIndex}][items][${itemIndex}][valor_unitario]" 
                           step="0.01" min="0" required class="validate valor-unitario">
                    <label>Valor Unitário (R$)*</label>
                </div>

                <div class="col s12 m1">
                    <button type="button" class="btn-floating waves-effect waves-light red remove-item">
                        <i class="material-icons">delete</i>
                    </button>
                </div>
            </div>
        `;
    }

    // Função para atualizar preço quando material é selecionado
    function setupMaterialSelect(select) {
        select.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const row = this.closest('.item-row');
            if (option && option.dataset.preco) {
                row.querySelector('.valor-unitario').value = option.dataset.preco;
                M.updateTextFields(); // Atualiza os labels do Materialize
            }
        });
    }

    // Função para remover item
    function setupRemoveItem(button) {
        button.addEventListener('click', function() {
            if (confirm('Tem certeza que deseja remover este item?')) {
                const row = this.closest('.item-row');
                row.remove();
            }
        });
    }

    // Função para calcular total do ambiente
    function updateTotalAmbiente(ambiente) {
        let total = 0;
        ambiente.querySelectorAll('.item-row').forEach(row => {
            const quantidade = parseFloat(row.querySelector('.quantidade').value) || 0;
            const valorUnitario = parseFloat(row.querySelector('.valor-unitario').value) || 0;
            const largura = parseFloat(row.querySelector('.largura').value) || 0;
            const altura = parseFloat(row.querySelector('.altura').value) || 0;
            
            total += quantidade * valorUnitario * largura * altura;
        });
        
        // Se você tiver um elemento para mostrar o total do ambiente
        const totalElement = ambiente.querySelector('.ambiente-total');
        if (totalElement) {
            totalElement.textContent = `R$ ${total.toFixed(2)}`;
        }
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

        // Configurar evento de adição de item
        ambienteDiv.querySelector('.add-item').addEventListener('click', function() {
            const itemHtml = createItemRow(ambienteCount, itemCount);
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = itemHtml;
            itemsContainer.appendChild(tempDiv.firstElementChild);
            
            // Configurar evento de remoção para o novo item
            const removeButton = tempDiv.querySelector('.remove-item');
            if (removeButton) {
                setupRemoveItem(removeButton);
            }
            
            // Inicializar novos selects
            M.FormSelect.init(tempDiv.querySelectorAll('select'));
            itemCount++;
        });

        // Configurar evento de remoção do ambiente
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

    // Adicionar evento para calcular totais quando valores são alterados
    document.addEventListener('input', function(e) {
        if (e.target.matches('.quantidade, .valor-unitario, .largura, .altura')) {
            updateTotalAmbiente(e.target.closest('.ambiente-container'));
        }
    });
});
</script>
@endpush
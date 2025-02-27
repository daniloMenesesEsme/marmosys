@extends('layouts.app')

@section('title', isset($budget) ? 'Editar Orçamento' : 'Novo Orçamento')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ isset($budget) ? 'Editar Orçamento' : 'Novo Orçamento' }}</span>

                <form action="{{ isset($budget) ? route('financial.budgets.update', $budget) : route('financial.budgets.store') }}" method="POST">
                    @csrf
                    @if(isset($budget))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <select name="client_id" id="client_id" required>
                                <option value="" disabled selected>Selecione o cliente</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ (old('client_id', $budget->client_id ?? '') == $client->id) ? 'selected' : '' }}>
                                        {{ $client->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="client_id">Cliente*</label>
                            @error('client_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="previsao_entrega" name="previsao_entrega" class="datepicker" 
                                value="{{ old('previsao_entrega', isset($budget) ? $budget->previsao_entrega->format('d/m/Y') : '') }}" required>
                            <label for="previsao_entrega">Previsão de Entrega*</label>
                            @error('previsao_entrega') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <h6>Itens do Orçamento</h6>
                            <table class="striped" id="items-table">
                                <thead>
                                    <tr>
                                        <th>Produto/Serviço</th>
                                        <th>Quantidade</th>
                                        <th>Unidade</th>
                                        <th>Valor Unitário</th>
                                        <th>Subtotal</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="item-template" style="display: none;">
                                        <td>
                                            <input type="text" name="items[{INDEX}][descricao]" class="item-descricao" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{INDEX}][quantidade]" class="item-quantidade" step="0.01" min="0.01" required>
                                        </td>
                                        <td>
                                            <select name="items[{INDEX}][unidade]" class="item-unidade" required>
                                                <option value="M²">M²</option>
                                                <option value="ML">ML</option>
                                                <option value="UN">UN</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{INDEX}][valor_unitario]" class="item-valor" step="0.01" min="0.01" required>
                                        </td>
                                        <td>
                                            <span class="item-subtotal">R$ 0,00</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn-small red remove-item">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="right-align" style="margin-top: 10px;">
                                <button type="button" class="btn-small blue" id="add-item">
                                    <i class="material-icons left">add</i>Adicionar Item
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m8">
                            <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes', $budget->observacoes ?? '') }}</textarea>
                            <label for="observacoes">Observações</label>
                        </div>
                        <div class="col s12 m4">
                            <div class="card blue-grey darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Total do Orçamento</span>
                                    <h4 id="total-orcamento">R$ 0,00</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="{{ route('financial.budgets.index') }}" class="btn waves-effect waves-light grey">
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

    var itemCount = 0;
    const template = document.getElementById('item-template');

    // Adicionar item
    document.getElementById('add-item').addEventListener('click', function() {
        const newRow = template.cloneNode(true);
        newRow.style.display = '';
        newRow.id = '';
        
        // Atualiza os índices
        const html = newRow.innerHTML.replace(/{INDEX}/g, itemCount);
        newRow.innerHTML = html;
        
        template.parentNode.appendChild(newRow);
        
        // Inicializa os selects do novo item
        M.FormSelect.init(newRow.querySelectorAll('select'));
        
        // Adiciona listeners para cálculos
        addCalculationListeners(newRow);
        
        itemCount++;
    });

    // Remover item
    document.querySelector('tbody').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') || e.target.parentElement.classList.contains('remove-item')) {
            const row = e.target.closest('tr');
            if (row && row.id !== 'item-template') {
                row.remove();
                calculateTotal();
            }
        }
    });

    function addCalculationListeners(row) {
        const quantidade = row.querySelector('.item-quantidade');
        const valor = row.querySelector('.item-valor');
        
        [quantidade, valor].forEach(input => {
            input.addEventListener('input', function() {
                calculateSubtotal(row);
                calculateTotal();
            });
        });
    }

    function calculateSubtotal(row) {
        const quantidade = parseFloat(row.querySelector('.item-quantidade').value) || 0;
        const valor = parseFloat(row.querySelector('.item-valor').value) || 0;
        const subtotal = quantidade * valor;
        row.querySelector('.item-subtotal').textContent = `R$ ${subtotal.toFixed(2)}`;
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('tr:not(#item-template) .item-subtotal').forEach(span => {
            total += parseFloat(span.textContent.replace('R$ ', '')) || 0;
        });
        document.getElementById('total-orcamento').textContent = `R$ ${total.toFixed(2)}`;
    }

    // Adiciona primeiro item automaticamente
    document.getElementById('add-item').click();

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
            cancel: 'Sair',
            done: 'Confirmar'
        }
    });
});
</script>
@endpush
@endsection 
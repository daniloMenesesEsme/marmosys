@extends('layouts.app')

@section('title', isset($account) ? 'Editar Conta' : 'Nova Conta')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ isset($account) ? 'Editar Conta' : 'Nova Conta' }}</span>

                <form action="{{ isset($account) ? route('financial.accounts.update', $account) : route('financial.accounts.store') }}" method="POST">
                    @csrf
                    @if(isset($account))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <select name="category_id" id="category_id" required>
                                <option value="" disabled selected>Selecione a categoria</option>
                                <optgroup label="Receitas">
                                    @foreach($categories->where('tipo', 'receita') as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id', $account->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                            {{ $category->nome }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Despesas">
                                    @foreach($categories->where('tipo', 'despesa') as $category)
                                        <option value="{{ $category->id }}" {{ (old('category_id', $account->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                            {{ $category->nome }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <label for="category_id">Categoria*</label>
                            @error('category_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="descricao" name="descricao" value="{{ old('descricao', $account->descricao ?? '') }}" required>
                            <label for="descricao">Descrição*</label>
                            @error('descricao') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="number" id="valor" name="valor" step="0.01" min="0.01" value="{{ old('valor', $account->valor ?? '') }}" required>
                            <label for="valor">Valor*</label>
                            @error('valor') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="date" id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento', isset($account) ? $account->data_vencimento->format('Y-m-d') : date('Y-m-d')) }}" required>
                            <label for="data_vencimento">Data de Vencimento*</label>
                            @error('data_vencimento') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <select name="status" id="status" required>
                                <option value="" disabled selected>Selecione o status</option>
                                <option value="pendente" {{ (old('status', $account->status ?? '') == 'pendente') ? 'selected' : '' }}>Pendente</option>
                                <option value="pago" {{ (old('status', $account->status ?? '') == 'pago') ? 'selected' : '' }}>Pago</option>
                                <option value="cancelado" {{ (old('status', $account->status ?? '') == 'cancelado') ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            <label for="status">Status*</label>
                            @error('status') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div id="payment-fields" style="{{ (old('status', $account->status ?? 'pendente') == 'pago') ? 'display:block' : 'display:none' }}">
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input type="date" id="data_pagamento" name="data_pagamento" value="{{ old('data_pagamento', isset($account) && $account->data_pagamento ? $account->data_pagamento->format('Y-m-d') : date('Y-m-d')) }}">
                                <label for="data_pagamento">Data do Pagamento</label>
                                @error('data_pagamento') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <select name="forma_pagamento" id="forma_pagamento">
                                    <option value="" disabled selected>Selecione</option>
                                    <option value="dinheiro" {{ (old('forma_pagamento', $account->forma_pagamento ?? '') == 'dinheiro') ? 'selected' : '' }}>Dinheiro</option>
                                    <option value="pix" {{ (old('forma_pagamento', $account->forma_pagamento ?? '') == 'pix') ? 'selected' : '' }}>PIX</option>
                                    <option value="cartao" {{ (old('forma_pagamento', $account->forma_pagamento ?? '') == 'cartao') ? 'selected' : '' }}>Cartão</option>
                                    <option value="transferencia" {{ (old('forma_pagamento', $account->forma_pagamento ?? '') == 'transferencia') ? 'selected' : '' }}>Transferência</option>
                                    <option value="boleto" {{ (old('forma_pagamento', $account->forma_pagamento ?? '') == 'boleto') ? 'selected' : '' }}>Boleto</option>
                                </select>
                                <label for="forma_pagamento">Forma de Pagamento</label>
                                @error('forma_pagamento') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes', $account->observacoes ?? '') }}</textarea>
                            <label for="observacoes">Observações</label>
                            @error('observacoes') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" id="documento" name="documento" value="{{ old('documento', $account->documento ?? '') }}">
                            <label for="documento">Número do Documento</label>
                            @error('documento') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <select name="budget_id" id="budget_id">
                                <option value="">Selecione um orçamento (opcional)</option>
                                @foreach($budgets as $budget)
                                    <option value="{{ $budget->id }}" {{ (old('budget_id', $account->budget_id ?? '') == $budget->id) ? 'selected' : '' }}>
                                        #{{ $budget->id }} - {{ $budget->client->nome }} - R$ {{ number_format($budget->valor_total, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="budget_id">Orçamento Relacionado</label>
                            @error('budget_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <select name="cost_center_id" id="cost_center_id">
                                <option value="">Selecione um centro de custo (opcional)</option>
                                @foreach($costCenters as $center)
                                    <option value="{{ $center->id }}" 
                                        {{ (old('cost_center_id', $account->cost_center_id ?? '') == $center->id) ? 'selected' : '' }}>
                                        {{ $center->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="cost_center_id">Centro de Custo</label>
                            @error('cost_center_id')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="{{ route('financial.accounts.index') }}" class="btn waves-effect waves-light grey">
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

    var textareas = document.querySelectorAll('textarea');
    M.textareaAutoResize(textareas);

    // Mostrar/ocultar campos de pagamento
    var statusSelect = document.getElementById('status');
    var paymentFields = document.getElementById('payment-fields');

    statusSelect.addEventListener('change', function() {
        if (this.value === 'pago') {
            paymentFields.style.display = 'block';
            document.getElementById('data_pagamento').required = true;
            document.getElementById('forma_pagamento').required = true;
        } else {
            paymentFields.style.display = 'none';
            document.getElementById('data_pagamento').required = false;
            document.getElementById('forma_pagamento').required = false;
        }
    });
});
</script>
@endpush
@endsection 
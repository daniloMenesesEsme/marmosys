@extends('layouts.app')

@section('title', 'Nova Transação')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Nova Transação</span>

                <form action="{{ route('financial.transactions.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="input-field col s12 m4">
                            <select name="tipo" id="tipo" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="receita" {{ old('tipo') == 'receita' ? 'selected' : '' }}>Receita</option>
                                <option value="despesa" {{ old('tipo') == 'despesa' ? 'selected' : '' }}>Despesa</option>
                                <option value="transferencia" {{ old('tipo') == 'transferencia' ? 'selected' : '' }}>Transferência</option>
                            </select>
                            <label for="tipo">Tipo*</label>
                            @error('tipo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <select name="category_id" id="category_id" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-tipo="{{ $category->tipo }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="category_id">Categoria*</label>
                            @error('category_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <select name="account_id" id="account_id" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="account_id">Conta*</label>
                            @error('account_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="descricao" name="descricao" value="{{ old('descricao') }}" required>
                            <label for="descricao">Descrição*</label>
                            @error('descricao') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="number" step="0.01" id="valor" name="valor" value="{{ old('valor') }}" required>
                            <label for="valor">Valor (R$)*</label>
                            @error('valor') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="date" id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento', date('Y-m-d')) }}" required>
                            <label for="data_vencimento">Data de Vencimento*</label>
                            @error('data_vencimento') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="date" id="data_pagamento" name="data_pagamento" value="{{ old('data_pagamento') }}">
                            <label for="data_pagamento">Data de Pagamento</label>
                            @error('data_pagamento') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="number" id="parcelas" name="parcelas" value="{{ old('parcelas', '1') }}" min="1">
                            <label for="parcelas">Número de Parcelas</label>
                            @error('parcelas') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="documento" name="documento" value="{{ old('documento') }}">
                            <label for="documento">Número do Documento</label>
                            @error('documento') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <select name="client_id" id="client_id">
                                <option value="">Selecione um cliente</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="client_id">Cliente</label>
                            @error('client_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes') }}</textarea>
                            <label for="observacoes">Observações</label>
                            @error('observacoes') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="{{ route('financial.transactions.index') }}" class="btn waves-effect waves-light grey">
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

    // Filtra categorias baseado no tipo de transação
    var tipoSelect = document.getElementById('tipo');
    var categorySelect = document.getElementById('category_id');
    var categoryOptions = Array.from(categorySelect.options);

    tipoSelect.addEventListener('change', function() {
        var tipo = this.value;
        categorySelect.innerHTML = '<option value="" disabled selected>Selecione</option>';
        
        categoryOptions.forEach(function(option) {
            if (option.value === '') return;
            if (tipo === 'transferencia' || option.dataset.tipo === tipo) {
                categorySelect.appendChild(option.cloneNode(true));
            }
        });
        
        M.FormSelect.init(categorySelect);
    });
});
</script>
@endpush
@endsection 
@extends('layouts.app')

@section('title', isset($agent) ? 'Editar Agente Financeiro' : 'Novo Agente Financeiro')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    <div class="row mb-0">
                        <div class="col s12">
                            <h4>
                                <i class="material-icons left">{{ isset($agent) ? 'edit' : 'add' }}</i>
                                {{ isset($agent) ? 'Editar Agente Financeiro' : 'Novo Agente Financeiro' }}
                            </h4>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel red lighten-4 red-text text-darken-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <form action="{{ isset($agent) ? route('financial.registration.agents.update', $agent) : route('financial.registration.agents.store') }}" method="POST">
                        @csrf
                        @if(isset($agent))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $agent->codigo ?? '') }}" required>
                                <label for="codigo">Código</label>
                            </div>
                            <div class="input-field col s12 m8">
                                <input type="text" id="nome" name="nome" value="{{ old('nome', $agent->nome ?? '') }}" required>
                                <label for="nome">Nome</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <select id="tipo" name="tipo" class="select2" required>
                                    <option value="" disabled {{ old('tipo', $agent->tipo ?? '') ? '' : 'selected' }}>Selecione o tipo</option>
                                    @foreach($tipos as $value => $label)
                                        <option value="{{ $value }}" {{ old('tipo', $agent->tipo ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="tipo">Tipo</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <select id="financial_category_id" name="financial_category_id" class="select2">
                                    <option value="" {{ old('financial_category_id', $agent->financial_category_id ?? '') ? '' : 'selected' }}>Selecione a categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('financial_category_id', $agent->financial_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="financial_category_id">Categoria Financeira</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <select id="cost_center_id" name="cost_center_id" class="select2">
                                    <option value="" {{ old('cost_center_id', $agent->cost_center_id ?? '') ? '' : 'selected' }}>Selecione o centro de custo</option>
                                    @foreach($costCenters as $center)
                                        <option value="{{ $center->id }}" {{ old('cost_center_id', $agent->cost_center_id ?? '') == $center->id ? 'selected' : '' }}>
                                            {{ $center->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="cost_center_id">Centro de Custo</label>
                            </div>
                        </div>

                        <div class="row bank-fields" style="{{ old('tipo', $agent->tipo ?? '') == 'banco' ? '' : 'display: none;' }}">
                            <div class="input-field col s12 m3">
                                <input type="text" id="codigo_banco" name="codigo_banco" value="{{ old('codigo_banco', $agent->codigo_banco ?? '') }}">
                                <label for="codigo_banco">Código do Banco</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="agencia" name="agencia" value="{{ old('agencia', $agent->agencia ?? '') }}">
                                <label for="agencia">Agência</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="conta" name="conta" value="{{ old('conta', $agent->conta ?? '') }}">
                                <label for="conta">Conta</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="digito" name="digito" value="{{ old('digito', $agent->digito ?? '') }}">
                                <label for="digito">Dígito</label>
                            </div>
                        </div>

                        <div class="row financial-fields" style="{{ old('tipo', $agent->tipo ?? '') == 'financeira' ? '' : 'display: none;' }}">
                            <div class="input-field col s12 m6">
                                <input type="text" id="cnpj" name="cnpj" class="cnpj-mask" value="{{ old('cnpj', $agent->cnpj ?? '') }}">
                                <label for="cnpj">CNPJ</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes', $agent->observacoes ?? '') }}</textarea>
                                <label for="observacoes">Observações</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <div class="switch">
                                    <label>
                                        Inativo
                                        <input type="checkbox" name="status" id="status" {{ old('status', $agent->status ?? true) ? 'checked' : '' }}>
                                        <span class="lever"></span>
                                        Ativo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 right-align">
                                <button type="submit" class="btn waves-effect waves-light green">
                                    <i class="material-icons left">save</i>
                                    Salvar
                                </button>
                                <a href="{{ route('financial.registration.agents.index') }}" class="btn waves-effect waves-light grey">
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
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Configura o campo select2
        $('.select2').select2({
            placeholder: 'Selecione uma opção'
        });

        // Exibe/oculta campos específicos de acordo com o tipo
        $('#tipo').change(function() {
            var tipo = $(this).val();
            
            if (tipo === 'banco') {
                $('.bank-fields').show();
                $('.financial-fields').hide();
            } else if (tipo === 'financeira') {
                $('.bank-fields').hide();
                $('.financial-fields').show();
            } else {
                $('.bank-fields').hide();
                $('.financial-fields').hide();
            }
        });

        // Máscara para o CNPJ
        $('.cnpj-mask').mask('00.000.000/0000-00');
    });
</script>
@endsection 
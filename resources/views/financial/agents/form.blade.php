@extends('layouts.app')

@section('title', isset($agent) ? 'Editar Agente Financeiro' : 'Novo Agente Financeiro')

@section('content')
<style>
/* Estilos específicos para corrigir os selects */
.select-field {
    margin-bottom: 0 !important;
}
.select-field .select-wrapper input.select-dropdown {
    height: 2rem !important;
    line-height: 2rem !important;
    font-size: 14px !important;
    border-bottom: 1px solid #9e9e9e !important;
}
.select-field .select-wrapper .dropdown-content {
    top: auto !important;
}
.select-field .select-wrapper .dropdown-content li > span {
    font-size: 14px;
    padding: 8px 16px;
}
.select-field .helper-text {
    margin-top: 0;
    font-size: 12px;
}
</style>

<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        {{ isset($agent) ? 'Editar Agente Financeiro' : 'Novo Agente Financeiro' }}
                    </div>

                    <form action="{{ isset($agent) ? route('financial.registration.agents.update', $agent) : route('financial.registration.agents.store') }}" method="POST">
                        @csrf
                        @if(isset($agent))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">badge</i>
                                <input type="text" name="nome" id="nome" value="{{ old('nome', $agent->nome ?? '') }}" required>
                                <label for="nome">Nome</label>
                                @error('nome')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">code</i>
                                <input type="text" name="codigo" id="codigo" value="{{ old('codigo', $agent->codigo ?? '') }}" required>
                                <label for="codigo">Código</label>
                                @error('codigo')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6 select-field">
                                <select name="tipo" id="tipo" required>
                                    <option value="" disabled {{ !isset($agent) ? 'selected' : '' }}>Selecione o tipo</option>
                                    @foreach(\App\Models\FinancialAgent::TIPOS as $key => $value)
                                        <option value="{{ $key }}" {{ (old('tipo', $agent->tipo ?? '') == $key) ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="tipo">Tipo*</label>
                                @error('tipo') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m6 select-field">
                                <select name="financial_category_id" id="financial_category_id">
                                    <option value="" disabled {{ !isset($agent) ? 'selected' : '' }}>Selecione a categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old('financial_category_id', $agent->financial_category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                            {{ $category->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="financial_category_id">Categoria Financeira</label>
                                @error('financial_category_id') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6 select-field">
                                <select name="cost_center_id" id="cost_center_id">
                                    <option value="" disabled {{ !isset($agent) ? 'selected' : '' }}>Selecione o centro de custo</option>
                                    @foreach($costCenters as $costCenter)
                                        <option value="{{ $costCenter->id }}" {{ (old('cost_center_id', $agent->cost_center_id ?? '') == $costCenter->id) ? 'selected' : '' }}>
                                            {{ $costCenter->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="cost_center_id">Centro de Custo</label>
                                @error('cost_center_id') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">notes</i>
                                <textarea name="observacoes" id="observacoes" class="materialize-textarea">{{ old('observacoes', $agent->observacoes ?? '') }}</textarea>
                                <label for="observacoes">Observações</label>
                                @error('observacoes')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <label>
                                    <input type="checkbox" name="status" value="1" {{ old('status', $agent->status ?? true) ? 'checked' : '' }}>
                                    <span>Ativo</span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn waves-effect waves-light blue">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicialização melhorada dos selects
    var selects = document.querySelectorAll('select');
    var selectInstances = M.FormSelect.init(selects, {
        dropdownOptions: {
            container: document.body,
            constrainWidth: false
        }
    });

    // Atualização forçada após a seleção para todos os selects
    selects.forEach(function(select) {
        select.addEventListener('change', function() {
            var selectedValue = this.value;
            var selectedText = this.options[this.selectedIndex].text;
            
            setTimeout(function() {
                var instance = M.FormSelect.init(select);
                // Garante que o valor selecionado permaneça
                select.value = selectedValue;
                instance.input.value = selectedText;
            }, 100);
        });
    });

    var textareas = document.querySelectorAll('.materialize-textarea');
    M.textareaAutoResize(textareas);
});
</script>
@endpush
@endsection
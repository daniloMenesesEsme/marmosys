@extends('layouts.app')

@section('title', isset($category) ? 'Editar Categoria' : 'Nova Categoria')

@section('content')
<style>
.select-field {
    margin-bottom: 0 !important;
}
.select-field .select-wrapper input.select-dropdown {
    height: 2rem;
    line-height: 2rem;
    font-size: 14px;
}
.select-field .helper-text {
    margin-top: 0;
    font-size: 12px;
}
</style>

<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    <h5>
                        <i class="material-icons left">{{ isset($category) ? 'edit' : 'add' }}</i>
                        {{ isset($category) ? 'Editar Categoria' : 'Nova Categoria' }}
                    </h5>
                </div>

                <form action="{{ isset($category) ? route('financial.categories.update', $category) : route('financial.categories.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($category))
                        @method('PUT')
                    @endif

                    <div class="row" style="margin-bottom: 0;">
                        <div class="input-field col s12 m6">
                            <input type="text" id="nome" name="nome" 
                                   value="{{ old('nome', $category->nome ?? '') }}" required>
                            <label for="nome">Nome da Categoria*</label>
                            @error('nome') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="codigo_contabil_externo" name="codigo_contabil_externo" 
                                   value="{{ old('codigo_contabil_externo', $category->codigo_contabil_externo ?? '') }}"
                                   maxlength="20">
                            <label for="codigo_contabil_externo">Código Contábil</label>
                            <span class="helper-text">Código para integração contábil</span>
                            @error('codigo_contabil_externo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="input-field col s12 m6 select-field">
                            <select name="tipo" id="tipo" required>
                                <option value="" disabled selected>Selecione o tipo</option>
                                <option value="analitica" {{ (old('tipo', $category->tipo ?? '') == 'analitica') ? 'selected' : '' }}>Analítica</option>
                                <option value="sintetica" {{ (old('tipo', $category->tipo ?? '') == 'sintetica') ? 'selected' : '' }}>Sintética</option>
                            </select>
                            <label for="tipo">Tipo da Categoria*</label>
                            <span class="helper-text">
                                Analítica: Permite lançamentos diretos<br>
                                Sintética: Agrupa outras categorias
                            </span>
                            @error('tipo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6 select-field">
                            <select name="natureza" id="natureza" required>
                                <option value="" disabled selected>Selecione a natureza</option>
                                <option value="receita" {{ (old('natureza', $category->natureza ?? '') == 'receita') ? 'selected' : '' }}>Receita</option>
                                <option value="despesa" {{ (old('natureza', $category->natureza ?? '') == 'despesa') ? 'selected' : '' }}>Despesa</option>
                            </select>
                            <label for="natureza">Natureza da Categoria*</label>
                            <span class="helper-text">&nbsp;</span>
                            @error('natureza') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Campos ocultos com valores padrão -->
                    <input type="hidden" name="cor" value="#2196F3">
                    <input type="hidden" name="icone" value="attach_money">
                    
                    <div class="row" style="margin-top: 20px;">
                        <div class="col s12">
                            <label>
                                <input type="checkbox" name="ativo" class="filled-in" 
                                       {{ old('ativo', $category->ativo ?? true) ? 'checked' : '' }}>
                                <span>Categoria Ativa</span>
                            </label>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="{{ route('financial.categories.index') }}" class="btn waves-effect waves-light grey">
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
});
</script>
@endpush
@endsection 
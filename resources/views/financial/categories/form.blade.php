@extends('layouts.app')

@section('title', isset($category) ? 'Editar Categoria' : 'Nova Categoria')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ isset($category) ? 'Editar Categoria' : 'Nova Categoria' }}</span>

                <form action="{{ isset($category) ? route('financial.categories.update', $category) : route('financial.categories.store') }}" method="POST">
                    @csrf
                    @if(isset($category))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="nome" name="nome" value="{{ old('nome', $category->nome ?? '') }}" required>
                            <label for="nome">Nome*</label>
                            @error('nome') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <select name="tipo" id="tipo" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="receita" {{ (old('tipo', $category->tipo ?? '') == 'receita') ? 'selected' : '' }}>Receita</option>
                                <option value="despesa" {{ (old('tipo', $category->tipo ?? '') == 'despesa') ? 'selected' : '' }}>Despesa</option>
                            </select>
                            <label for="tipo">Tipo*</label>
                            @error('tipo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="descricao" name="descricao" class="materialize-textarea">{{ old('descricao', $category->descricao ?? '') }}</textarea>
                            <label for="descricao">Descrição</label>
                            @error('descricao') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
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

    var textareas = document.querySelectorAll('textarea');
    M.textareaAutoResize(textareas);
});
</script>
@endpush
@endsection 
@extends('layouts.app')

@section('title', isset($goal) ? 'Editar Meta Financeira' : 'Nova Meta Financeira')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    {{ isset($goal) ? 'Editar Meta Financeira' : 'Nova Meta Financeira' }}
                </span>

                <form action="{{ isset($goal) 
                    ? route('financial.goals.update', $goal) 
                    : route('financial.goals.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($goal))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" id="titulo" name="titulo" 
                                   value="{{ old('titulo', $goal->titulo ?? '') }}" required>
                            <label for="titulo">Título</label>
                            @error('titulo')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field col s12">
                            <textarea id="descricao" name="descricao" 
                                      class="materialize-textarea">{{ old('descricao', $goal->descricao ?? '') }}</textarea>
                            <label for="descricao">Descrição</label>
                            @error('descricao')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="number" id="valor_meta" name="valor_meta" step="0.01" 
                                   value="{{ old('valor_meta', $goal->valor_meta ?? '') }}" required>
                            <label for="valor_meta">Valor da Meta</label>
                            @error('valor_meta')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <select name="tipo" id="tipo" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="receita" {{ (old('tipo', $goal->tipo ?? '') == 'receita') ? 'selected' : '' }}>
                                    Meta de Receita
                                </option>
                                <option value="economia" {{ (old('tipo', $goal->tipo ?? '') == 'economia') ? 'selected' : '' }}>
                                    Meta de Economia
                                </option>
                            </select>
                            <label for="tipo">Tipo da Meta</label>
                            @error('tipo')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="date" id="data_inicial" name="data_inicial" 
                                   value="{{ old('data_inicial', isset($goal) ? $goal->data_inicial->format('Y-m-d') : '') }}" required>
                            <label for="data_inicial">Data Inicial</label>
                            @error('data_inicial')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="date" id="data_final" name="data_final" 
                                   value="{{ old('data_final', isset($goal) ? $goal->data_final->format('Y-m-d') : '') }}" required>
                            <label for="data_final">Data Final</label>
                            @error('data_final')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field col s12">
                            <select name="categoria_id" id="categoria_id" required>
                                <option value="" disabled selected>Selecione</option>
                                <optgroup label="Receitas">
                                    @foreach($categories->get('receita', collect()) as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ (old('categoria_id', $goal->categoria_id ?? '') == $category->id) ? 'selected' : '' }}>
                                            {{ $category->nome }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Despesas">
                                    @foreach($categories->get('despesa', collect()) as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ (old('categoria_id', $goal->categoria_id ?? '') == $category->id) ? 'selected' : '' }}>
                                            {{ $category->nome }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <label for="categoria_id">Categoria</label>
                            @error('categoria_id')
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
                            
                            <a href="{{ route('financial.goals.index') }}" 
                               class="btn waves-effect waves-light red">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});
</script>
@endpush
@endsection 
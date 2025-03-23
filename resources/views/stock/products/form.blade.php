@extends('layouts.app')

@section('title', isset($product) ? 'Editar Produto' : 'Novo Produto')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}</span>

                <form action="{{ isset($product) ? route('stock.products.update', $product) : route('stock.products.store') }}" method="POST">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $product->codigo ?? '') }}" required>
                            <label for="codigo">Código*</label>
                            @error('codigo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m8">
                            <input type="text" id="nome" name="nome" value="{{ old('nome', $product->nome ?? '') }}" required>
                            <label for="nome">Nome*</label>
                            @error('nome') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <select name="category_id" id="category_id" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="category_id">Categoria*</label>
                            @error('category_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <select name="unidade" id="unidade" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="UN" {{ old('unidade', $product->unidade ?? '') == 'UN' ? 'selected' : '' }}>Unidade</option>
                                <option value="M2" {{ old('unidade', $product->unidade ?? '') == 'M2' ? 'selected' : '' }}>Metro Quadrado</option>
                                <option value="M" {{ old('unidade', $product->unidade ?? '') == 'M' ? 'selected' : '' }}>Metro</option>
                                <option value="KG" {{ old('unidade', $product->unidade ?? '') == 'KG' ? 'selected' : '' }}>Quilograma</option>
                            </select>
                            <label for="unidade">Unidade de Medida*</label>
                            @error('unidade') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="number" step="0.01" id="valor_unitario" name="valor_unitario" value="{{ old('valor_unitario', $product->valor_unitario ?? '') }}" required>
                            <label for="valor_unitario">Valor Unitário (R$)*</label>
                            @error('valor_unitario') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="number" step="0.01" id="estoque_minimo" name="estoque_minimo" value="{{ old('estoque_minimo', $product->estoque_minimo ?? '0') }}" required>
                            <label for="estoque_minimo">Estoque Mínimo*</label>
                            @error('estoque_minimo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        @if(!isset($product))
                        <div class="input-field col s12 m6">
                            <input type="number" step="0.01" id="estoque_inicial" name="estoque_inicial" value="{{ old('estoque_inicial', '0') }}" required>
                            <label for="estoque_inicial">Estoque Inicial*</label>
                            @error('estoque_inicial') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="descricao" name="descricao" class="materialize-textarea">{{ old('descricao', $product->descricao ?? '') }}</textarea>
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
                            <a href="{{ route('stock.products.index') }}" class="btn waves-effect waves-light grey">
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
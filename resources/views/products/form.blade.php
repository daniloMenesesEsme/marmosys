@extends('layouts.app')

@section('title', isset($product) ? 'Editar Produto' : 'Novo Produto')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}</span>

                <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="nome" name="nome" value="{{ old('nome', $product->nome ?? '') }}" required>
                            <label for="nome">Nome*</label>
                            @error('nome') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $product->codigo ?? '') }}">
                            <label for="codigo">Código</label>
                            @error('codigo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="descricao" name="descricao" class="materialize-textarea">{{ old('descricao', $product->descricao ?? '') }}</textarea>
                            <label for="descricao">Descrição</label>
                            @error('descricao') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m3">
                            <input type="number" id="preco_custo" name="preco_custo" step="0.01" min="0" value="{{ old('preco_custo', $product->preco_custo ?? '0.00') }}" required>
                            <label for="preco_custo">Preço de Custo*</label>
                            @error('preco_custo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m3">
                            <input type="number" id="preco_venda" name="preco_venda" step="0.01" min="0" value="{{ old('preco_venda', $product->preco_venda ?? '0.00') }}" required>
                            <label for="preco_venda">Preço de Venda*</label>
                            @error('preco_venda') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m3">
                            <input type="number" id="estoque_atual" name="estoque_atual" step="0.01" min="0" value="{{ old('estoque_atual', $product->estoque_atual ?? '0.00') }}" required>
                            <label for="estoque_atual">Estoque Atual*</label>
                            @error('estoque_atual') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m3">
                            <input type="number" id="estoque_minimo" name="estoque_minimo" step="0.01" min="0" value="{{ old('estoque_minimo', $product->estoque_minimo ?? '0.00') }}" required>
                            <label for="estoque_minimo">Estoque Mínimo*</label>
                            @error('estoque_minimo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <select name="unidade_medida" id="unidade_medida" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach(['M²', 'ML', 'UN', 'KG'] as $unidade)
                                    <option value="{{ $unidade }}" {{ (old('unidade_medida', $product->unidade_medida ?? '') == $unidade) ? 'selected' : '' }}>
                                        {{ $unidade }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="unidade_medida">Unidade de Medida*</label>
                            @error('unidade_medida') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="categoria" name="categoria" value="{{ old('categoria', $product->categoria ?? '') }}">
                            <label for="categoria">Categoria</label>
                            @error('categoria') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="fornecedor" name="fornecedor" value="{{ old('fornecedor', $product->fornecedor ?? '') }}">
                            <label for="fornecedor">Fornecedor</label>
                            @error('fornecedor') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="{{ route('products.index') }}" class="btn waves-effect waves-light grey">
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
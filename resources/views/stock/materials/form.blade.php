@extends('layouts.app')

@section('title', isset($material) ? 'Editar Material' : 'Novo Material')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ isset($material) ? 'Editar Material' : 'Novo Material' }}</span>

                <form action="{{ isset($material) ? route('stock.materials.update', $material) : route('stock.materials.store') }}" method="POST">
                    @csrf
                    @if(isset($material))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <select name="category_id" id="category_id" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $material->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                        {{ $category->nome }} ({{ ucfirst($category->tipo) }})
                                    </option>
                                @endforeach
                            </select>
                            <label for="category_id">Categoria*</label>
                            @error('category_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="nome" name="nome" value="{{ old('nome', $material->nome ?? '') }}" required>
                            <label for="nome">Nome*</label>
                            @error('nome') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <select name="unidade_medida" id="unidade_medida" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="m2" {{ (old('unidade_medida', $material->unidade_medida ?? '') == 'm2') ? 'selected' : '' }}>Metro Quadrado (m²)</option>
                                <option value="unidade" {{ (old('unidade_medida', $material->unidade_medida ?? '') == 'unidade') ? 'selected' : '' }}>Unidade (un)</option>
                                <option value="kg" {{ (old('unidade_medida', $material->unidade_medida ?? '') == 'kg') ? 'selected' : '' }}>Quilograma (kg)</option>
                                <option value="litro" {{ (old('unidade_medida', $material->unidade_medida ?? '') == 'litro') ? 'selected' : '' }}>Litro (l)</option>
                            </select>
                            <label for="unidade_medida">Unidade de Medida*</label>
                            @error('unidade_medida') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="number" step="0.01" id="estoque_minimo" name="estoque_minimo" value="{{ old('estoque_minimo', $material->estoque_minimo ?? '0.00') }}" required>
                            <label for="estoque_minimo">Estoque Mínimo*</label>
                            @error('estoque_minimo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="number" step="0.01" id="preco_custo" name="preco_custo" value="{{ old('preco_custo', $material->preco_custo ?? '0.00') }}" required>
                            <label for="preco_custo">Preço de Custo (R$)*</label>
                            @error('preco_custo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="number" step="0.01" id="preco_venda" name="preco_venda" value="{{ old('preco_venda', $material->preco_venda ?? '0.00') }}" required>
                            <label for="preco_venda">Preço de Venda (R$)*</label>
                            @error('preco_venda') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if(!isset($material))
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="number" step="0.01" id="estoque_atual" name="estoque_atual" value="{{ old('estoque_atual', '0.00') }}" required>
                            <label for="estoque_atual">Estoque Inicial*</label>
                            @error('estoque_atual') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="descricao" name="descricao" class="materialize-textarea">{{ old('descricao', $material->descricao ?? '') }}</textarea>
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
                            <a href="{{ route('stock.materials.index') }}" class="btn waves-effect waves-light grey">
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
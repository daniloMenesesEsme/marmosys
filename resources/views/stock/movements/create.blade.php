@extends('layouts.app')

@section('title', 'Nova Movimentação de Estoque')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Nova Movimentação de Estoque</span>

                <form action="{{ route('stock.movements.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <select name="material_id" id="material_id" required>
                                <option value="" disabled selected>Selecione o material</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" data-estoque="{{ $material->estoque_atual }}" data-unidade="{{ $material->unidade_medida }}">
                                        {{ $material->codigo }} - {{ $material->nome }} 
                                        ({{ number_format($material->estoque_atual, 2, ',', '.') }} {{ $material->unidade_medida }})
                                    </option>
                                @endforeach
                            </select>
                            <label for="material_id">Material*</label>
                            @error('material_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <select name="tipo" id="tipo" required>
                                <option value="" disabled selected>Selecione o tipo</option>
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saída</option>
                            </select>
                            <label for="tipo">Tipo de Movimentação*</label>
                            @error('tipo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="number" step="0.01" id="quantidade" name="quantidade" value="{{ old('quantidade') }}" required>
                            <label for="quantidade">Quantidade*</label>
                            @error('quantidade') <span class="red-text">{{ $message }}</span> @enderror
                            <span class="helper-text" id="unidade-medida"></span>
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="number" step="0.01" id="valor_unitario" name="valor_unitario" value="{{ old('valor_unitario') }}" required>
                            <label for="valor_unitario">Valor Unitário (R$)*</label>
                            @error('valor_unitario') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="numero_nota" name="numero_nota" value="{{ old('numero_nota') }}">
                            <label for="numero_nota">Número da Nota</label>
                            @error('numero_nota') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="fornecedor" name="fornecedor" value="{{ old('fornecedor') }}">
                            <label for="fornecedor">Fornecedor</label>
                            @error('fornecedor') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="observacao" name="observacao" class="materialize-textarea">{{ old('observacao') }}</textarea>
                            <label for="observacao">Observações</label>
                            @error('observacao') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="{{ route('stock.movements.index') }}" class="btn waves-effect waves-light grey">
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

    // Atualiza a unidade de medida quando o material é selecionado
    var materialSelect = document.getElementById('material_id');
    var unidadeMedidaSpan = document.getElementById('unidade-medida');
    var tipoSelect = document.getElementById('tipo');
    var quantidadeInput = document.getElementById('quantidade');

    materialSelect.addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var unidade = selectedOption.dataset.unidade;
        var estoque = parseFloat(selectedOption.dataset.estoque);
        unidadeMedidaSpan.textContent = unidade;
    });

    // Validação de quantidade para saída
    document.querySelector('form').addEventListener('submit', function(e) {
        if (tipoSelect.value === 'saida') {
            var selectedOption = materialSelect.options[materialSelect.selectedIndex];
            var estoque = parseFloat(selectedOption.dataset.estoque);
            var quantidade = parseFloat(quantidadeInput.value);

            if (quantidade > estoque) {
                e.preventDefault();
                alert('Quantidade de saída não pode ser maior que o estoque atual!');
            }
        }
    });
});
</script>
@endpush
@endsection 
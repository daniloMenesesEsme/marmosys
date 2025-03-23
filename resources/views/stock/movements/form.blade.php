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
                        <div class="input-field col s12 m4">
                            <select name="tipo" id="tipo" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                <option value="saida" {{ old('tipo') == 'saida' ? 'selected' : '' }}>Saída</option>
                            </select>
                            <label for="tipo">Tipo de Movimentação*</label>
                            @error('tipo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m8">
                            <select name="product_id" id="product_id" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                        {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}
                                        data-estoque="{{ $product->estoque_atual }}"
                                        data-unidade="{{ $product->unidade }}">
                                        {{ $product->codigo }} - {{ $product->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="product_id">Produto*</label>
                            @error('product_id') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="number" step="0.01" id="quantidade" name="quantidade" value="{{ old('quantidade') }}" required>
                            <label for="quantidade">Quantidade*</label>
                            @error('quantidade') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <select name="motivo" id="motivo" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="compra" {{ old('motivo') == 'compra' ? 'selected' : '' }}>Compra</option>
                                <option value="venda" {{ old('motivo') == 'venda' ? 'selected' : '' }}>Venda</option>
                                <option value="ajuste" {{ old('motivo') == 'ajuste' ? 'selected' : '' }}>Ajuste de Estoque</option>
                                <option value="perda" {{ old('motivo') == 'perda' ? 'selected' : '' }}>Perda/Quebra</option>
                            </select>
                            <label for="motivo">Motivo*</label>
                            @error('motivo') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="documento" name="documento" value="{{ old('documento') }}">
                            <label for="documento">Documento (NF/Pedido)</label>
                            @error('documento') <span class="red-text">{{ $message }}</span> @enderror
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
                            <div class="card-panel grey lighten-4">
                                <p>Estoque Atual: <span id="estoque-atual">0</span> <span id="unidade-medida">UN</span></p>
                                <p>Estoque Após Movimentação: <span id="estoque-final">0</span> <span id="unidade-medida-final">UN</span></p>
                            </div>
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

    // Atualiza informações do estoque
    function atualizarEstoque() {
        var productSelect = document.getElementById('product_id');
        var tipoSelect = document.getElementById('tipo');
        var quantidadeInput = document.getElementById('quantidade');
        
        var selectedOption = productSelect.options[productSelect.selectedIndex];
        var estoqueAtual = parseFloat(selectedOption?.dataset.estoque || 0);
        var unidade = selectedOption?.dataset.unidade || 'UN';
        var quantidade = parseFloat(quantidadeInput.value || 0);
        var tipo = tipoSelect.value;

        document.getElementById('estoque-atual').textContent = estoqueAtual.toFixed(2);
        document.getElementById('unidade-medida').textContent = unidade;
        document.getElementById('unidade-medida-final').textContent = unidade;

        var estoqueFinal = tipo === 'entrada' ? estoqueAtual + quantidade : estoqueAtual - quantidade;
        document.getElementById('estoque-final').textContent = estoqueFinal.toFixed(2);
        
        // Alerta visual se estoque ficar negativo
        var estoqueInfo = document.getElementById('estoque-final');
        if (estoqueFinal < 0) {
            estoqueInfo.classList.add('red-text');
        } else {
            estoqueInfo.classList.remove('red-text');
        }
    }

    // Eventos para atualizar o estoque
    document.getElementById('product_id').addEventListener('change', atualizarEstoque);
    document.getElementById('tipo').addEventListener('change', atualizarEstoque);
    document.getElementById('quantidade').addEventListener('input', atualizarEstoque);

    // Atualiza inicialmente
    atualizarEstoque();
});
</script>
@endpush
@endsection 
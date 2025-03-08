@extends('layouts.app')

@section('title', isset($product) ? 'Editar Produto' : 'Novo Produto')

@section('content')
<div class="row">
    <div class="card">
        <div class="card-content">
            <span class="card-title">
                <i class="material-icons left">inventory_2</i>
                {{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}
            </span>

            <form action="{{ $product->id ? route('products.update', $product) : route('products.store') }}" method="POST">
                @csrf
                @if($product->id)
                    @method('PUT')
                @endif

                <!-- Debug temporário -->
                @if ($errors->any())
                    <div class="card-panel red lighten-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col s12">
                        <div class="row" style="display: flex; align-items: center;">
                            <div class="col s12">
                                <h6>Tipo do Item*</h6>
                                <div class="tipo-item-group">
                                    @foreach(\App\Enums\ProductType::cases() as $type)
                                    <p>
                                        <label>
                                            <input type="checkbox" name="tipo" value="{{ $type->value }}" 
                                                   class="tipo-checkbox" {{ old('tipo') == $type->value ? 'checked' : '' }}/>
                                            <span>{{ $type->label() }}</span>
                                        </label>
                                    </p>
                                    @endforeach
                                </div>
                            </div>

                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">qr_code</i>
                                <input type="text" id="codigo" name="codigo" readonly>
                                <label for="codigo">Código*</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">label</i>
                                <input type="text" id="nome" name="nome" value="{{ old('nome') }}"
                                       style="width: calc(100% - 3rem);">
                                <label for="nome">Nome*</label>
                                @error('nome') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">description</i>
                                <textarea id="descricao" name="descricao" class="materialize-textarea">{{ old('descricao', $product->descricao ?? '') }}</textarea>
                                <label>Descrição</label>
                                @error('descricao') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">attach_money</i>
                                <input type="number" id="preco_custo" name="preco_custo" step="0.01" min="0" value="{{ old('preco_custo', $product->preco_custo ?? '0.00') }}" required>
                                <label>Preço de Custo*</label>
                                @error('preco_custo') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">monetization_on</i>
                                <input type="number" id="preco_venda" name="preco_venda" step="0.01" min="0" value="{{ old('preco_venda', $product->preco_venda ?? '0.00') }}" required>
                                <label>Preço de Venda*</label>
                                @error('preco_venda') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">inventory</i>
                                <input type="number" id="estoque" name="estoque" min="0" 
                                       value="{{ old('estoque', $product->estoque ?? '0') }}">
                                <label>Estoque Atual</label>
                                @error('estoque') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">low_priority</i>
                                <input type="number" id="estoque_minimo" name="estoque_minimo" min="0" 
                                       value="{{ old('estoque_minimo', $product->estoque_minimo ?? '0') }}">
                                <label>Estoque Mínimo</label>
                                @error('estoque_minimo') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">straighten</i>
                                <select name="unidade_medida" id="unidade_medida" required>
                                    <option value="" disabled selected>Selecione</option>
                                    @foreach(['M²', 'ML', 'UN', 'KG'] as $unidade)
                                        <option value="{{ $unidade }}" {{ (old('unidade_medida', $product->unidade_medida ?? '') == $unidade) ? 'selected' : '' }}>
                                            {{ $unidade }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Unidade de Medida*</label>
                                @error('unidade_medida') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">folder</i>
                                <input type="text" id="categoria" name="categoria" value="{{ old('categoria', $product->categoria ?? '') }}">
                                <label>Categoria</label>
                                @error('categoria') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">business</i>
                                <input type="text" id="fornecedor" name="fornecedor" value="{{ old('fornecedor', $product->fornecedor ?? '') }}">
                                <label>Fornecedor</label>
                                @error('fornecedor') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12" style="text-align: right; margin-bottom: 20px;">
                        <label style="margin-right: 20px;">
                            <input type="checkbox" class="filled-in" name="ativo" value="1" 
                                   {{ old('ativo', $product->ativo ?? true) ? 'checked' : '' }}>
                            <span>Ativar</span>
                        </label>
                        <label>
                            <input type="checkbox" class="filled-in" name="inativo" value="0" 
                                   {{ old('inativo', !($product->ativo ?? true)) ? 'checked' : '' }}>
                            <span>Desativar</span>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <button type="submit" class="btn waves-effect waves-light">
                            <i class="material-icons left">save</i>
                            SALVAR
                        </button>
                        <a href="{{ route('products.index') }}" class="btn waves-effect waves-light grey">
                            VOLTAR
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="servico-fields" style="display: none;">
    <div class="row">
        <div class="input-field col s12">
            <textarea id="descricao_servico" name="descricao_servico" class="materialize-textarea">{{ old('descricao_servico', $product->descricao ?? '') }}</textarea>
            <label for="descricao_servico">Descrição do Serviço</label>
            @error('descricao_servico') <span class="red-text">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.tipo-checkbox');
    const codigoInput = document.getElementById('codigo');

    // Função para gerar o código
    async function updateCode(tipo) {
        try {
            const response = await fetch(`/products/generate-code/${tipo}`);
            if (response.ok) {
                const newCode = await response.text();
                codigoInput.value = newCode;
                M.updateTextFields();
            }
        } catch (error) {
            console.error('Erro ao gerar código:', error);
        }
    }

    // Gerencia os checkboxes para funcionar como radio buttons
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Desmarca os outros checkboxes
                checkboxes.forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
                // Gera o novo código
                updateCode(this.value);
            } else {
                // Se desmarcar, limpa o código
                codigoInput.value = '';
            }
        });
    });

    // Se já tiver um tipo selecionado, gera o código inicial
    const checkedBox = document.querySelector('.tipo-checkbox:checked');
    if (checkedBox) {
        updateCode(checkedBox.value);
    }
});
</script>
@endpush

<style>
/* Ajuste específico para o texto do select */
.select-wrapper input.select-dropdown {
    position: relative !important;
    cursor: pointer !important;
    background-color: transparent !important;
    border: none !important;
    border-bottom: 1px solid #9e9e9e !important;
    outline: none !important;
    height: 3rem !important;
    line-height: 3rem !important;
    width: 100% !important;
    font-size: 1rem !important;
    margin: 0 0 0 3rem !important;
    padding: 0 !important;
    display: block !important;
    text-align: left !important;
    color: rgba(0,0,0,0.87) !important;
}

.tipo-item-group {
    display: flex;
    gap: 2rem;
    margin: 1rem 0;
}

.tipo-item-group p {
    margin: 0;
}
</style>
@endsection 
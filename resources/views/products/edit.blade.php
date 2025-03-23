<div class="row">
    <div class="card">
        <div class="card-content">
            <span class="card-title">
                <i class="material-icons left">edit</i>
                Editar Produto
            </span>

            <form action="{{ route('products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Status do Produto -->
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col s12">
                        <div class="status-section" style="background-color: #f5f5f5; padding: 15px; border-radius: 4px;">
                            <label style="display: flex; align-items: center; color: rgba(0,0,0,0.87);">
                                <input type="checkbox" name="ativo" class="filled-in" value="1" 
                                       {{ $product->ativo ? 'checked' : '' }}>
                                <span style="font-size: 1.1rem;">
                                    <i class="material-icons left" style="margin-right: 8px;">power_settings_new</i>
                                    Status do Produto: <strong>{{ $product->ativo ? 'Ativo' : 'Inativo' }}</strong>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Tipo do Item -->
                <div class="row">
                    <div class="col s12">
                        <h6>Tipo do Item*</h6>
                        <div class="tipo-item-group">
                            @foreach(\App\Enums\ProductType::cases() as $type)
                            <p>
                                <label>
                                    <input type="checkbox" name="tipo" value="{{ $type->value }}" 
                                           class="tipo-checkbox" 
                                           {{ $product->tipo->value == $type->value ? 'checked' : '' }}/>
                                    <span>{{ $type->label() }}</span>
                                </label>
                            </p>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Campos básicos -->
                <div class="row">
                    <div class="input-field col s12 m6">
                        <i class="material-icons prefix">qr_code</i>
                        <input type="text" id="codigo" name="codigo" value="{{ $product->codigo }}" readonly>
                        <label for="codigo">Código*</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <i class="material-icons prefix">label</i>
                        <input type="text" id="nome" name="nome" value="{{ $product->nome }}">
                        <label for="nome">Nome*</label>
                    </div>
                </div>

                <!-- Outros campos -->
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">description</i>
                        <textarea id="descricao" name="descricao" class="materialize-textarea">{{ $product->descricao }}</textarea>
                        <label for="descricao">Descrição</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m6">
                        <i class="material-icons prefix">attach_money</i>
                        <input type="number" step="0.01" id="preco_custo" name="preco_custo" value="{{ $product->preco_custo }}">
                        <label for="preco_custo">Preço de Custo*</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <i class="material-icons prefix">monetization_on</i>
                        <input type="number" step="0.01" id="preco_venda" name="preco_venda" value="{{ $product->preco_venda }}">
                        <label for="preco_venda">Preço de Venda*</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m6">
                        <i class="material-icons prefix">inventory</i>
                        <input type="number" step="0.01" id="estoque" name="estoque" value="{{ $product->estoque }}">
                        <label for="estoque">Estoque Atual*</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <i class="material-icons prefix">low_priority</i>
                        <input type="number" step="0.01" id="estoque_minimo" name="estoque_minimo" value="{{ $product->estoque_minimo }}">
                        <label for="estoque_minimo">Estoque Mínimo*</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m4">
                        <i class="material-icons prefix">straighten</i>
                        <input type="text" id="unidade_medida" name="unidade_medida" value="{{ $product->unidade_medida }}">
                        <label for="unidade_medida">Unidade de Medida*</label>
                    </div>

                    <div class="input-field col s12 m4">
                        <i class="material-icons prefix">category</i>
                        <input type="text" id="categoria" name="categoria" value="{{ $product->categoria }}">
                        <label for="categoria">Categoria</label>
                    </div>

                    <div class="input-field col s12 m4">
                        <i class="material-icons prefix">business</i>
                        <input type="text" id="fornecedor" name="fornecedor" value="{{ $product->fornecedor }}">
                        <label for="fornecedor">Fornecedor</label>
                    </div>
                </div>

                <!-- Botões e Status do Produto -->
                <div class="row">
                    <div class="col s6">
                        <!-- Botões à esquerda -->
                        <button type="submit" class="btn waves-effect waves-light">
                            <i class="material-icons left">save</i>
                            Salvar
                        </button>
                        <a href="{{ route('products.index') }}" class="btn waves-effect waves-light grey">
                            <i class="material-icons left">arrow_back</i>
                            Voltar
                        </a>
                    </div>
                    
                    <div class="col s6" style="text-align: right;">
                        <!-- Status do Produto à direita -->
                        <label style="margin-left: 20px;">
                            <input type="checkbox" name="ativo" class="filled-in" value="1" 
                                   {{ $product->ativo ? 'checked' : '' }}>
                            <span style="color: #26a69a;">Ativar</span>
                        </label>
                        <label style="margin-left: 20px;">
                            <input type="checkbox" name="inativo" class="filled-in" value="0" 
                                   {{ !$product->ativo ? 'checked' : '' }}>
                            <span style="color: #ef5350;">Desativar</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.status-switch {
    background-color: #f5f5f5;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.tipo-item-group {
    display: flex;
    gap: 2rem;
    margin: 1rem 0;
}

.tipo-item-group p {
    margin: 0;
}

.switch label {
    font-weight: 500;
}

.switch label input[type=checkbox]:checked + .lever {
    background-color: #64b5f6;
}

.switch label input[type=checkbox]:checked + .lever:after {
    background-color: #2196f3;
}

/* Estilo para os checkboxes de status */
[type="checkbox"].filled-in:checked + span:not(.lever):after {
    border: 2px solid #26a69a;
    background-color: #26a69a;
}

[type="checkbox"].filled-in:not(:checked) + span:not(.lever):after {
    border: 2px solid #ef5350;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Garante que apenas um checkbox pode estar marcado por vez
    const ativoCheckbox = document.querySelector('input[name="ativo"]');
    const inativoCheckbox = document.querySelector('input[name="inativo"]');
    
    ativoCheckbox.addEventListener('change', function() {
        if(this.checked) {
            inativoCheckbox.checked = false;
        }
    });

    inativoCheckbox.addEventListener('change', function() {
        if(this.checked) {
            ativoCheckbox.checked = false;
        }
    });
});
</script> 
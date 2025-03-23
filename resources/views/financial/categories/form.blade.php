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
.input-field input[type=text],
.input-field input[type=number],
.input-field textarea {
    height: 2rem;
    font-size: 14px;
}
.input-field label {
    font-size: 0.9rem;
}
.input-field .helper-text {
    font-size: 12px;
    margin-top: 0;
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

                <form id="categoryForm" action="{{ isset($category) ? '/financial/categories/'.$category->id : '/financial/categories' }}" 
                      method="POST" class="col s12">
                    @csrf
                    @if(isset($category))
                        @method('PUT')
                    @endif
                    
                    <!-- Debug Info - Será removido depois -->
                    <div class="row">
                        <div class="col s12 grey lighten-4 p-2" style="padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                            <small>
                                <strong>DEBUG:</strong> Enviando para: {{ isset($category) ? '/financial/categories/'.$category->id : '/financial/categories' }}
                            </small>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 0;">
                        <div class="input-field col s12">
                            <input type="text" id="nome" name="nome" 
                                   value="{{ old('nome', $category->nome ?? '') }}" required>
                            <label for="nome">Nome da Categoria*</label>
                            @error('nome') <span class="red-text helper-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="input-field col s12 m6 select-field">
                            <select name="tipo" id="tipo" required>
                                <option value="" disabled {{ old('tipo', $category->tipo ?? '') ? '' : 'selected' }}>Selecione o tipo</option>
                                <option value="ANALITICA" {{ (old('tipo', $category->tipo ?? '') == 'ANALITICA') ? 'selected' : '' }}>Analítica</option>
                                <option value="SINTETICA" {{ (old('tipo', $category->tipo ?? '') == 'SINTETICA') ? 'selected' : '' }}>Sintética</option>
                            </select>
                            <label for="tipo">Tipo da Categoria*</label>
                            <span class="helper-text">
                                Analítica: Permite lançamentos diretos<br>
                                Sintética: Agrupa outras categorias
                            </span>
                            @error('tipo') <span class="red-text helper-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6 select-field">
                            <select name="natureza" id="natureza" required>
                                <option value="" disabled {{ old('natureza', $category->natureza ?? '') ? '' : 'selected' }}>Selecione a natureza</option>
                                <option value="receita" {{ (old('natureza', $category->natureza ?? '') == 'receita') ? 'selected' : '' }}>Receita</option>
                                <option value="despesa" {{ (old('natureza', $category->natureza ?? '') == 'despesa') ? 'selected' : '' }}>Despesa</option>
                            </select>
                            <label for="natureza">Natureza da Categoria*</label>
                            @error('natureza') <span class="red-text helper-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="descricao" name="descricao" class="materialize-textarea">{{ old('descricao', $category->descricao ?? '') }}</textarea>
                            <label for="descricao">Descrição</label>
                            @error('descricao') <span class="red-text helper-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Campos ocultos com valores padrão -->
                    <input type="hidden" name="cor" value="#2196F3">
                    <input type="hidden" name="icone" value="attach_money">
                    <input type="hidden" name="ativo" value="1">
                    <input type="hidden" name="form_type" value="financial_category">
                    
                    <div class="row" style="margin-top: 20px;">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="/financial/categories" class="btn waves-effect waves-light grey">
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
    // Inicializa os selects do Materialize
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    // Inicializa os textareas
    var textareas = document.querySelectorAll('.materialize-textarea');
    M.textareaAutoResize(textareas);

    // Validação do formulário
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        var nome = document.getElementById('nome').value;
        var tipo = document.getElementById('tipo').value;
        var natureza = document.getElementById('natureza').value;
        var isValid = true;
        var errorMessages = [];

        // Verifica se o formulário está sendo enviado para a URL correta
        var actionUrl = this.getAttribute('action');
        if (!actionUrl.includes('/financial/categories')) {
            e.preventDefault();
            M.toast({html: 'Erro: URL de envio incorreta. Por favor, atualize a página.', classes: 'red'});
            console.error('URL incorreta:', actionUrl);
            return false;
        }

        if (!nome) {
            errorMessages.push('O nome da categoria é obrigatório');
            isValid = false;
        }

        if (!tipo) {
            errorMessages.push('O tipo da categoria é obrigatório');
            isValid = false;
        }

        if (!natureza) {
            errorMessages.push('A natureza da categoria é obrigatória');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            M.toast({html: errorMessages.join('<br>'), classes: 'red'});
        }
    });
});
</script>
@endpush

@endsection 
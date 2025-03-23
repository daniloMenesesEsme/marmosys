@extends('layouts.app')

@section('title', 'Importar Extrato Bancário')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Importar Arquivo OFX</span>

                <form action="{{ route('financial.reconciliation.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="file-field input-field col s12">
                            <div class="btn blue">
                                <span>Arquivo</span>
                                <input type="file" name="arquivo_ofx" accept=".ofx" required>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" 
                                       placeholder="Selecione o arquivo OFX do banco">
                            </div>
                            @error('arquivo_ofx')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <select name="categoria_receita" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach($categories->get('receita', collect()) as $category)
                                    <option value="{{ $category->id }}">{{ $category->nome }}</option>
                                @endforeach
                            </select>
                            <label>Categoria para Receitas</label>
                            @error('categoria_receita')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <select name="categoria_despesa" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach($categories->get('despesa', collect()) as $category)
                                    <option value="{{ $category->id }}">{{ $category->nome }}</option>
                                @endforeach
                            </select>
                            <label>Categoria para Despesas</label>
                            @error('categoria_despesa')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn-large waves-effect waves-light blue">
                                <i class="material-icons left">cloud_upload</i>
                                Importar Transações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col s12">
        <div class="card blue-grey lighten-5">
            <div class="card-content">
                <span class="card-title">Instruções</span>
                <ul class="browser-default">
                    <li>Exporte o arquivo OFX do seu banco</li>
                    <li>Selecione as categorias padrão para receitas e despesas</li>
                    <li>As transações serão importadas automaticamente</li>
                    <li>Valores positivos serão considerados receitas</li>
                    <li>Valores negativos serão considerados despesas</li>
                    <li>Todas as transações serão marcadas como pagas</li>
                </ul>
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
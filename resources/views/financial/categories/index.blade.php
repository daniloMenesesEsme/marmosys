@extends('layouts.app')

@section('title', 'Categorias Financeiras')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    <div class="row">
                        <div class="col s6">
                            <h5>
                                <i class="material-icons left">category</i>
                                Categorias Financeiras
                            </h5>
                        </div>
                        <div class="col s6 right-align">
                            <a href="{{ route('financial.categories.create') }}" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">add</i>
                                Nova Categoria
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('financial.categories.index') }}" method="GET" class="row">
                    <div class="col s12 m4">
                        <div class="input-field">
                            <input type="text" name="nome" id="nome" value="{{ request('nome') }}">
                            <label for="nome">Nome</label>
                        </div>
                    </div>
                    <div class="col s12 m4">
                        <div class="input-field">
                            <select name="natureza" id="natureza">
                                <option value="">Todas as Naturezas</option>
                                <option value="receita" {{ request('natureza') === 'receita' ? 'selected' : '' }}>Receita</option>
                                <option value="despesa" {{ request('natureza') === 'despesa' ? 'selected' : '' }}>Despesa</option>
                            </select>
                            <label>Natureza</label>
                        </div>
                    </div>
                    <div class="col s12 m4">
                        <div class="input-field">
                            <select name="status" id="status">
                                <option value="">Todos os Status</option>
                                <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <label>Status</label>
                        </div>
                    </div>
                    <div class="col s12 right-align">
                        <a href="{{ route('financial.categories.index') }}" class="btn waves-effect waves-light grey">
                            <i class="material-icons left">clear</i>
                            Limpar
                        </a>
                        <button type="submit" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">search</i>
                            Filtrar
                        </button>
                    </div>
                </form>

                <table class="striped highlight responsive-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Natureza</th>
                            <th>Código Contábil</th>
                            <th>Status</th>
                            <th class="center-align">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>
                                <i class="material-icons left">{{ $category->icone }}</i>
                                {{ $category->nome }}
                            </td>
                            <td>
                                <span class="chip">
                                    {{ $category->tipo_formatado }}
                                </span>
                            </td>
                            <td>
                                <span class="chip {{ $category->natureza == 'receita' ? 'light-green' : 'deep-orange' }} white-text">
                                    {{ $category->natureza == 'receita' ? 'Receita' : 'Despesa' }}
                                </span>
                            </td>
                            <td>{{ $category->codigo_contabil_externo ?: '-' }}</td>
                            <td>
                                <span class="chip {{ $category->ativo ? 'green' : 'red' }} white-text">
                                    {{ $category->ativo ? 'Ativa' : 'Inativa' }}
                                </span>
                            </td>
                            <td class="center-align">
                                <a href="{{ route('financial.categories.edit', $category) }}" 
                                   class="btn-small waves-effect waves-light orange tooltipped"
                                   data-position="top" 
                                   data-tooltip="Editar">
                                    <i class="material-icons">edit</i>
                                </a>
                                <form action="{{ route('financial.categories.destroy', $category) }}" 
                                      method="POST" 
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-small waves-effect waves-light {{ $category->ativo ? 'red' : 'green' }} tooltipped"
                                            data-position="top"
                                            data-tooltip="{{ $category->ativo ? 'Inativar' : 'Ativar' }}"
                                            onclick="return confirm('Tem certeza que deseja {{ $category->ativo ? 'inativar' : 'ativar' }} esta categoria?')">
                                        <i class="material-icons">{{ $category->ativo ? 'delete' : 'restore' }}</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="center-align">
                                <i class="material-icons medium grey-text">info</i>
                                <p class="grey-text">Nenhuma categoria encontrada.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="row" style="margin-top: 20px;">
                    <div class="col s12">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltips = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(tooltips);

    // Inicializa os selects do Materialize
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});
</script>
@endpush
@endsection 
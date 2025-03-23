@extends('layouts.app')

@section('title', 'Agentes Financeiros')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    <div class="row mb-0">
                        <div class="col s12 m6">
                            <h4>
                                <i class="material-icons left">account_balance</i>
                                Agentes Financeiros
                            </h4>
                        </div>
                        <div class="col s12 m6 right-align">
                            <a href="{{ route('financial.registration.agents.create') }}" 
                               class="btn waves-effect waves-light blue">
                                <i class="material-icons left">add</i>
                                Novo Agente
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="row">
                    <form id="filter-form" method="GET" class="col s12">
                        <div class="row mb-0">
                            <div class="input-field col s12 m3">
                                <input type="text" id="filter_codigo" name="codigo" 
                                       value="{{ request('codigo') }}">
                                <label for="filter_codigo">Código</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="text" id="filter_nome" name="nome" 
                                       value="{{ request('nome') }}">
                                <label for="filter_nome">Nome</label>
                            </div>

                            <div class="input-field col s12 m3 select-field">
                                <select name="tipo" id="filter_tipo">
                                    <option value="">Todos</option>
                                    @foreach(\App\Models\FinancialAgent::TIPOS as $key => $value)
                                        <option value="{{ $key }}" {{ request('tipo') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="filter_tipo">Tipo</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="material-icons left">search</i>
                                    Filtrar
                                </button>
                                <a href="{{ route('financial.registration.agents.index') }}" 
                                   class="btn waves-effect waves-light grey">
                                    <i class="material-icons left">clear</i>
                                    Limpar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabela -->
                <div class="row">
                    <div class="col s12">
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ route('financial.registration.agents.index', 
                                            array_merge(request()->all(), ['sort' => 'codigo', 'direction' => $direction])) }}" 
                                           class="sort-link">
                                            Código
                                            @if(request('sort') == 'codigo')
                                                <i class="material-icons tiny">{{ $direction == 'asc' ? 'arrow_upward' : 'arrow_downward' }}</i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('financial.registration.agents.index', 
                                            array_merge(request()->all(), ['sort' => 'nome', 'direction' => $direction])) }}" 
                                           class="sort-link">
                                            Nome
                                            @if(request('sort') == 'nome')
                                                <i class="material-icons tiny">{{ $direction == 'asc' ? 'arrow_upward' : 'arrow_downward' }}</i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Tipo</th>
                                    <th>Categoria</th>
                                    <th>Centro de Custo</th>
                                    <th>Status</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agents as $agent)
                                    <tr>
                                        <td>{{ $agent->codigo }}</td>
                                        <td>{{ $agent->nome }}</td>
                                        <td>{{ \App\Models\FinancialAgent::TIPOS[$agent->tipo] ?? '' }}</td>
                                        <td>{{ $agent->category->nome ?? '-' }}</td>
                                        <td>{{ $agent->costCenter->nome ?? '-' }}</td>
                                        <td>
                                            <span class="chip {{ $agent->status ? 'green' : 'red' }} white-text">
                                                {{ $agent->status ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('financial.registration.agents.edit', $agent) }}" 
                                               class="btn-small waves-effect waves-light blue" 
                                               title="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <button type="button" 
                                                    class="btn-small waves-effect waves-light red" 
                                                    onclick="confirmDelete('{{ $agent->id }}')"
                                                    title="Excluir">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="center-align">Nenhum agente financeiro encontrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Paginação -->
                        <div class="row">
                            <div class="col s12">
                                {{ $agents->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="modal-delete" class="modal">
    <div class="modal-content">
        <h4>Confirmar Exclusão</h4>
        <p>Tem certeza que deseja excluir este agente financeiro?</p>
    </div>
    <div class="modal-footer">
        <form id="delete-form" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
        <button type="submit" form="delete-form" class="waves-effect waves-light btn red">
            Confirmar
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa os selects do Materialize
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects, {
        dropdownOptions: {
            container: document.body,
            constrainWidth: false
        }
    });

    // Inicializa o modal
    var modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);
});

function confirmDelete(id) {
    var modal = M.Modal.getInstance(document.getElementById('modal-delete'));
    var form = document.getElementById('delete-form');
    form.action = `/financial/agents/${id}`;
    modal.open();
}
</script>
@endpush

@push('styles')
<style>
.sort-link {
    color: inherit;
    display: inline-flex;
    align-items: center;
}
.sort-link:hover {
    color: #2196F3;
}
.chip {
    height: 24px;
    line-height: 24px;
    padding: 0 12px;
}
.mb-0 {
    margin-bottom: 0;
}
</style>
@endpush
@endsection 
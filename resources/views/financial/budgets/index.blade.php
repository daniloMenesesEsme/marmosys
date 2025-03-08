@extends('layouts.app')

@section('title', 'Orçamentos')

@push('styles')
<style>
    .action-buttons .btn-floating {
        margin: 0 3px;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Orçamentos</span>
                
                <!-- Filtro -->
                <div class="row">
                    <form action="{{ route('financial.budgets.index') }}" method="GET" class="col s12">
                        <div class="row mb-0">
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">search</i>
                                <input type="text" id="search" name="search" value="{{ request('search') }}">
                                <label for="search">Buscar por Número, Cliente, CPF/CNPJ ou Telefone</label>
                            </div>
                            <div class="input-field col s6 m2">
                                <select name="status">
                                    <option value="">Todos os Status</option>
                                    <option value="aguardando_aprovacao" {{ request('status') == 'aguardando_aprovacao' ? 'selected' : '' }}>Aguardando Aprovação</option>
                                    <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                    <option value="reprovado" {{ request('status') == 'reprovado' ? 'selected' : '' }}>Reprovado</option>
                                </select>
                                <label>Status</label>
                            </div>
                            <div class="input-field col s6 m2">
                                <input type="date" name="data_inicio" value="{{ request('data_inicio') }}">
                                <label>Data Início</label>
                            </div>
                            <div class="input-field col s6 m2">
                                <input type="date" name="data_fim" value="{{ request('data_fim') }}">
                                <label>Data Fim</label>
                            </div>
                            <div class="col s12" style="margin-top: 10px;">
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="material-icons left">search</i>
                                    Filtrar
                                </button>
                                <a href="{{ route('financial.budgets.index') }}" class="btn waves-effect waves-light red">
                                    <i class="material-icons left">clear</i>
                                    Limpar
                                </a>
                                <div style="float: right;">
                                    <a href="{{ route('financial.budgets.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> + NOVO ORÇAMENTO
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Telefone</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                            <tr>
                                <td>{{ $budget->numero }}</td>
                                <td>{{ $budget->data->format('d/m/Y') }}</td>
                                <td>{{ $budget->client->nome }}</td>
                                <td>{{ $budget->client->telefone }}</td>
                                <td>R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    <span class="chip {{ $budget->status_class }}">
                                        {{ $budget->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('financial.budgets.show', $budget) }}"
                                       class="btn-floating waves-effect waves-light blue tooltipped"
                                       data-position="top" 
                                       data-tooltip="Visualizar">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    
                                    <a href="{{ route('financial.budgets.edit', $budget) }}"
                                       class="btn-floating waves-effect waves-light green tooltipped"
                                       data-position="top" 
                                       data-tooltip="Editar">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    
                                    <a href="{{ route('financial.budgets.pdf', $budget->id) }}" 
                                       target="_blank" 
                                       class="btn btn-info btn-sm" 
                                       title="Gerar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    
                                    <form action="{{ route('financial.budgets.destroy', $budget) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este orçamento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-floating waves-effect waves-light red tooltipped"
                                                data-position="top"
                                                data-tooltip="Excluir">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="center">Nenhum orçamento encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginação -->
                <div class="row">
                    <div class="col s12">
                        <ul class="pagination center-align">
                            {{-- Link Previous --}}
                            @if ($budgets->onFirstPage())
                                <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
                            @else
                                <li class="waves-effect"><a href="{{ $budgets->previousPageUrl() }}"><i class="material-icons">chevron_left</i></a></li>
                            @endif

                            {{-- Números das Páginas --}}
                            @foreach ($budgets->getUrlRange(1, $budgets->lastPage()) as $page => $url)
                                @if ($page == $budgets->currentPage())
                                    <li class="active blue"><a href="#!">{{ $page }}</a></li>
                                @else
                                    <li class="waves-effect"><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Link Next --}}
                            @if ($budgets->hasMorePages())
                                <li class="waves-effect"><a href="{{ $budgets->nextPageUrl() }}"><i class="material-icons">chevron_right</i></a></li>
                            @else
                                <li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir este orçamento?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

function printBudget(id) {
    window.open(`/financial/budgets/${id}/print`, '_blank');
}

document.addEventListener('DOMContentLoaded', function() {
    var tooltips = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(tooltips);
});

document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});
</script>
@endpush
@endsection 
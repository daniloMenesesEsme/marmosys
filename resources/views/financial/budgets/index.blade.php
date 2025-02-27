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
                                    
                                    <a href="{{ route('financial.budgets.pdf', $budget) }}"
                                       class="btn-floating waves-effect waves-light purple tooltipped"
                                       data-position="top" 
                                       data-tooltip="Gerar PDF">
                                        <i class="material-icons">picture_as_pdf</i>
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

                {{ $budgets->links() }}
            </div>
            <div class="card-action">
                <a href="{{ route('financial.budgets.create') }}" 
                   class="btn waves-effect waves-light">
                    <i class="material-icons left">add</i>
                    Novo Orçamento
                </a>
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
</script>
@endpush
@endsection 
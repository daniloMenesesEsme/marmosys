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
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Orçamentos</span>
                    </div>
                    <div class="col s6 right-align no-print">
                        <a href="{{ route('financial.budgets.create') }}" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>
                            Novo Orçamento
                        </a>
                    </div>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Valor Total</th>
                            <th class="no-print">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                            <tr>
                                <td>{{ $budget->client->nome }}</td>
                                <td>{{ $budget->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="chip {{ $budget->status_class }}">
                                        {{ $budget->status_text }}
                                    </span>
                                </td>
                                <td>R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</td>
                                <td class="action-buttons no-print">
                                    <a href="{{ route('financial.budgets.show', $budget) }}" 
                                       class="btn-floating waves-effect waves-light blue tooltipped"
                                       data-position="top" 
                                       data-tooltip="Visualizar">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    
                                    <a href="{{ route('financial.budgets.edit', $budget) }}" 
                                       class="btn-floating waves-effect waves-light orange tooltipped"
                                       data-position="top" 
                                       data-tooltip="Editar">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    
                                    <a href="#" onclick="printBudget({{ $budget->id }})"
                                       class="btn-floating waves-effect waves-light green tooltipped"
                                       data-position="top" 
                                       data-tooltip="Imprimir">
                                        <i class="material-icons">print</i>
                                    </a>
                                    
                                    <a href="{{ route('financial.budgets.pdf', $budget) }}"
                                       class="btn-floating waves-effect waves-light purple tooltipped"
                                       data-position="top" 
                                       data-tooltip="Gerar PDF">
                                        <i class="material-icons">picture_as_pdf</i>
                                    </a>
                                    
                                    <a href="mailto:?subject=Orçamento {{ $budget->id }}&body=Segue em anexo o orçamento solicitado."
                                       class="btn-floating waves-effect waves-light blue-grey tooltipped"
                                       data-position="top" 
                                       data-tooltip="Enviar por E-mail">
                                        <i class="material-icons">email</i>
                                    </a>

                                    <button type="button" 
                                            onclick="confirmDelete('{{ $budget->id }}')" 
                                            class="btn-floating waves-effect waves-light red tooltipped"
                                            data-position="top" 
                                            data-tooltip="Excluir">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="center-align">Nenhum orçamento encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $budgets->links() }}
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
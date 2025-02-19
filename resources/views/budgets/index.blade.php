@extends('layouts.app')

@section('title', 'Orçamentos')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Orçamentos</span>

                <div class="row">
                    <div class="col s12">
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
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                            <tr>
                                <td>{{ $budget->client->nome ?? 'N/A' }}</td>
                                <td>{{ $budget->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @switch($budget->status)
                                        @case('aguardando_aprovacao')
                                            <span class="orange-text">Aguardando Aprovação</span>
                                            @break
                                        @case('producao')
                                            <span class="blue-text">Em Produção</span>
                                            @break
                                        @case('entregue')
                                            <span class="green-text">Entregue</span>
                                            @break
                                        @default
                                            <span class="grey-text">{{ $budget->status }}</span>
                                    @endswitch
                                </td>
                                <td>R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('financial.budgets.show', $budget) }}" class="btn-floating waves-effect waves-light blue">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    <a href="{{ route('financial.budgets.edit', $budget) }}" class="btn-floating waves-effect waves-light orange">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <button type="button" onclick="confirmDelete('{{ $budget->id }}')" class="btn-floating waves-effect waves-light red">
                                        <i class="material-icons">delete</i>
                                    </button>
                                    <form id="delete-form-{{ $budget->id }}" action="{{ route('financial.budgets.destroy', $budget) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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
</script>
@endpush
@endsection 
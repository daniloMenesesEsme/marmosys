@extends('layouts.app')

@section('title', 'Formas de Pagamento')

@section('content')
<div class="container-fluid" style="padding: 0 30px;">
    <div class="row">
        <div class="col s12">
            <div class="card" style="margin: 15px 0;">
                <div class="card-content">
                    <div class="card-title">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Formas de Pagamento</h4>
                            <a href="{{ route('financial.registration.payment-methods.create') }}" 
                               class="btn-floating btn-large waves-effect waves-light green tooltipped"
                               data-position="left" 
                               data-tooltip="Nova Forma de Pagamento">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-0">
                        <form id="filter-form" class="col s12">
                            <div class="row mb-0">
                                <div class="input-field col s12 m3">
                                    <select name="tipo" id="tipo" onchange="this.form.submit()">
                                        <option value="">Todos os Tipos</option>
                                        @foreach(App\Models\PaymentMethod::TIPOS as $valor => $label)
                                            <option value="{{ $valor }}" {{ request('tipo') == $valor ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="tipo">Tipo</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <select name="status" id="status" onchange="this.form.submit()">
                                        <option value="">Todos os Status</option>
                                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                                <div class="input-field col s12 m4">
                                    <input type="text" name="search" id="search" value="{{ request('search') }}">
                                    <label for="search">Buscar</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <button type="submit" class="btn waves-effect waves-light">
                                        <i class="material-icons">search</i>
                                    </button>
                                    <a href="{{ route('financial.registration.payment-methods.index') }}" 
                                       class="btn waves-effect waves-light red">
                                        <i class="material-icons">clear</i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela -->
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Parcelas Padrão</th>
                                <th>Taxa Padrão</th>
                                <th>Status</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentMethods as $method)
                                <tr>
                                    <td>{{ $method->nome }}</td>
                                    <td>
                                        <span class="chip {{ $method->isAvistaOnly() ? 'green' : 'blue' }} white-text">
                                            {{ $method->tipo_formatado }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($method->isAvistaOnly())
                                            <span class="chip grey white-text">À Vista</span>
                                        @else
                                            {{ $method->parcelas_padrao }}x
                                        @endif
                                    </td>
                                    <td>
                                        @if($method->isAvistaOnly())
                                            <span class="chip grey white-text">Sem Taxa</span>
                                        @else
                                            {{ number_format($method->taxa_padrao, 2) }}%
                                        @endif
                                    </td>
                                    <td>
                                        <span class="chip {{ $method->ativo ? 'green' : 'red' }} white-text">
                                            {{ $method->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="buttons-actions">
                                            <a href="{{ route('financial.registration.payment-methods.edit', $method) }}" 
                                               class="btn-floating waves-effect waves-light amber"
                                               title="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <button type="button"
                                                    onclick="deleteItem('{{ route('financial.registration.payment-methods.destroy', $method) }}')"
                                                    class="btn-floating waves-effect waves-light red"
                                                    title="Excluir">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="center-align">Nenhuma forma de pagamento encontrada</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="row">
                        <div class="col s12">
                            {{ $paymentMethods->links('vendor.pagination.materialize') }}
                        </div>
                    </div>

                    <!-- Botão Adicionar -->
                    <div class="fixed-action-btn">
                        <a href="{{ route('financial.registration.payment-methods.create') }}" 
                           class="btn-floating btn-large waves-effect waves-light teal">
                            <i class="material-icons">add</i>
                        </a>
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
        <p>Tem certeza que deseja excluir esta forma de pagamento?</p>
    </div>
    <div class="modal-footer">
        <form id="delete-form" method="POST">
            @csrf
            @method('DELETE')
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            <button type="submit" class="modal-close waves-effect waves-green btn-flat">Confirmar</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa os selects
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    // Inicializa os tooltips
    var tooltips = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(tooltips);

    // Inicializa os modais
    var modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);
});

function deleteItem(url) {
    const form = document.getElementById('delete-form');
    form.action = url;
    var modal = M.Modal.getInstance(document.getElementById('modal-delete'));
    modal.open();
}

@if(session('success'))
    M.toast({html: '{{ session("success") }}', classes: 'green'});
@endif

@if(session('error'))
    M.toast({html: '{{ session("error") }}', classes: 'red'});
@endif
</script>
@endpush

@push('styles')
<style>
.d-flex {
    display: flex !important;
}
.justify-content-between {
    justify-content: space-between !important;
}
.align-items-center {
    align-items: center !important;
}
.buttons-actions {
    display: flex;
    gap: 8px;
}
.chip {
    height: 24px;
    padding: 0 12px;
    border-radius: 12px;
    line-height: 24px;
}
.card-title {
    padding-bottom: 20px;
}
</style>
@endpush 
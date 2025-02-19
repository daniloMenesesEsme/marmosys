@extends('layouts.app')

@section('title', 'Transações Financeiras')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Transações Financeiras</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="{{ route('financial.transactions.create') }}" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>Nova Transação
                        </a>
                    </div>
                </div>

                <div class="row">
                    <form action="{{ route('financial.transactions.index') }}" method="GET">
                        <div class="input-field col s12 m3">
                            <select name="tipo" id="tipo">
                                <option value="">Todos os Tipos</option>
                                <option value="receita" {{ request('tipo') == 'receita' ? 'selected' : '' }}>Receitas</option>
                                <option value="despesa" {{ request('tipo') == 'despesa' ? 'selected' : '' }}>Despesas</option>
                                <option value="transferencia" {{ request('tipo') == 'transferencia' ? 'selected' : '' }}>Transferências</option>
                            </select>
                            <label for="tipo">Tipo</label>
                        </div>

                        <div class="input-field col s12 m3">
                            <select name="status" id="status">
                                <option value="">Todos os Status</option>
                                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendentes</option>
                                <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pagos</option>
                                <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelados</option>
                            </select>
                            <label for="status">Status</label>
                        </div>

                        <div class="input-field col s12 m2">
                            <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}">
                            <label for="data_inicio">Data Inicial</label>
                        </div>

                        <div class="input-field col s12 m2">
                            <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}">
                            <label for="data_fim">Data Final</label>
                        </div>

                        <div class="input-field col s12 m2">
                            <button type="submit" class="btn waves-effect waves-light">
                                <i class="material-icons">search</i>
                            </button>
                            <a href="{{ route('financial.transactions.index') }}" class="btn waves-effect waves-light red">
                                <i class="material-icons">clear</i>
                            </a>
                        </div>
                    </form>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Conta</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->data_vencimento->format('d/m/Y') }}</td>
                            <td>{{ $transaction->descricao }}</td>
                            <td>{{ $transaction->category->nome }}</td>
                            <td>{{ $transaction->account->nome }}</td>
                            <td class="{{ $transaction->tipo === 'receita' ? 'green-text' : 'red-text' }}">
                                R$ {{ number_format($transaction->valor, 2, ',', '.') }}
                            </td>
                            <td>
                                <span class="chip {{ $transaction->status }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('financial.transactions.show', $transaction) }}" class="btn-small waves-effect waves-light">
                                    <i class="material-icons">visibility</i>
                                </a>
                                @if($transaction->status === 'pendente')
                                <a href="#modal-payment-{{ $transaction->id }}" class="btn-small waves-effect waves-light green modal-trigger">
                                    <i class="material-icons">payments</i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($transactions as $transaction)
    @if($transaction->status === 'pendente')
    <div id="modal-payment-{{ $transaction->id }}" class="modal">
        <form action="{{ route('financial.transactions.pay', $transaction) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <h4>Registrar Pagamento</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="date" id="data_pagamento" name="data_pagamento" value="{{ date('Y-m-d') }}" required>
                        <label for="data_pagamento">Data do Pagamento</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="number" step="0.01" id="valor_pago" name="valor_pago" value="{{ $transaction->valor }}" required>
                        <label for="valor_pago">Valor Pago (R$)</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="modal-close waves-effect waves-green btn-flat">Confirmar</button>
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            </div>
        </form>
    </div>
    @endif
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    var modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);
});
</script>
@endpush
@endsection 
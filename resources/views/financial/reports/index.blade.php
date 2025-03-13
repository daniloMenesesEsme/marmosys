@extends('layouts.app')

@section('title', 'Relatório Financeiro')

@php
    $meses = [
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    ];
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col s12">
            <div class="page-title">
                <i class="material-icons">assessment</i>
                <h4>Relatório Financeiro</h4>
            </div>

            <!-- Filtros -->
            <div class="card">
                <div class="card-content">
                    <form action="{{ route('financial.reports.index') }}" method="GET">
                        <div class="row mb-0">
                            <div class="input-field col s12 m2">
                                <i class="material-icons prefix">event</i>
                                <input type="text" class="datepicker" name="data_inicio" value="{{ request('data_inicio') }}" id="data_inicio">
                                <label for="data_inicio">Data Início</label>
                            </div>

                            <div class="input-field col s12 m2">
                                <i class="material-icons prefix">event</i>
                                <input type="text" class="datepicker" name="data_fim" value="{{ request('data_fim') }}" id="data_fim">
                                <label for="data_fim">Data Fim</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">swap_vert</i>
                                <input type="text" id="tipo_display" class="dropdown-trigger" data-target="dropdown_tipo" 
                                    value="{{ request('tipo') ? ucfirst($tipos[request('tipo')]) : 'Todos' }}" readonly>
                                <input type="hidden" name="tipo" id="tipo_value" value="{{ request('tipo') }}">
                                <label for="tipo_display" class="active">Tipo</label>
                                
                                <ul id="dropdown_tipo" class="dropdown-content">
                                    <li><a href="#!" data-value="">Todos</a></li>
                                    @foreach($tipos as $value => $label)
                                        <li><a href="#!" data-value="{{ $value }}">{{ $label }}</a></li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">flag</i>
                                <input type="text" id="status_display" class="dropdown-trigger" data-target="dropdown_status" 
                                    value="{{ request('status') ? ucfirst($status[request('status')]) : 'Todos' }}" readonly>
                                <input type="hidden" name="status" id="status_value" value="{{ request('status') }}">
                                <label for="status_display" class="active">Status</label>
                                
                                <ul id="dropdown_status" class="dropdown-content">
                                    <li><a href="#!" data-value="">Todos</a></li>
                                    @foreach($status as $value => $label)
                                        <li><a href="#!" data-value="{{ $value }}">{{ $label }}</a></li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="input-field col s12 m2">
                                <button type="submit" class="btn waves-effect waves-light teal">
                                    <i class="material-icons">search</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Botões de Exportação e Tabela -->
            <div class="card">
                <div class="card-content">
                    <div class="card-title d-flex">
                        <div class="flex-grow-1">Resultados</div>
                        <div>
                            <a href="{{ route('financial.reports.export.pdf') }}" 
                               class="btn-floating waves-effect waves-light red tooltipped" 
                               data-position="bottom" 
                               data-tooltip="Exportar PDF"
                               target="_blank">
                                <i class="material-icons">picture_as_pdf</i>
                            </a>
                            <a href="{{ route('financial.reports.export.excel') }}" 
                               class="btn-floating waves-effect waves-light green tooltipped" 
                               data-position="bottom" 
                               data-tooltip="Exportar Excel">
                                <i class="material-icons">grid_on</i>
                            </a>
                        </div>
                    </div>

                    <table class="highlight responsive-table">
                        <thead>
                            <tr>
                                <th>Data Vencimento</th>
                                <th>Descrição</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Data Pagamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->data_vencimento->format('d/m/Y') }}</td>
                                    <td>{{ $transaction->descricao }}</td>
                                    <td>
                                        <i class="material-icons tiny {{ $transaction->tipo === 'receita' ? 'green-text' : 'red-text' }}">
                                            {{ $transaction->tipo === 'receita' ? 'arrow_upward' : 'arrow_downward' }}
                                        </i>
                                        {{ ucfirst($transaction->tipo) }}
                                    </td>
                                    <td>R$ {{ number_format($transaction->valor, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $transaction->status === 'pago' ? 'green' : ($transaction->status === 'cancelado' ? 'red' : 'orange') }} white-text">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->data_pagamento ? $transaction->data_pagamento->format('d/m/Y') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="center-align">Nenhum registro encontrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Totalizadores -->
                    @if($transactions->isNotEmpty())
                        <div class="row mt-4">
                            <div class="col s12">
                                <div class="card-panel grey lighten-4">
                                    <h5 class="m-0"><i class="material-icons left">calculate</i> Totais</h5>
                                    <div class="row mb-0">
                                        <div class="col s12 m4">
                                            <p class="green-text">
                                                <i class="material-icons tiny">arrow_upward</i>
                                                <strong>Total Receitas:</strong> R$ {{ number_format($transactions->where('tipo', 'receita')->sum('valor'), 2, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="col s12 m4">
                                            <p class="red-text">
                                                <i class="material-icons tiny">arrow_downward</i>
                                                <strong>Total Despesas:</strong> R$ {{ number_format($transactions->where('tipo', 'despesa')->sum('valor'), 2, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="col s12 m4">
                                            <p class="{{ $transactions->where('tipo', 'receita')->sum('valor') - $transactions->where('tipo', 'despesa')->sum('valor') >= 0 ? 'green-text' : 'red-text' }}">
                                                <i class="material-icons tiny">account_balance</i>
                                                <strong>Saldo:</strong> R$ {{ number_format($transactions->where('tipo', 'receita')->sum('valor') - $transactions->where('tipo', 'despesa')->sum('valor'), 2, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content {
    padding: 15px;
}
.page-title {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}
.page-title i {
    margin-right: 10px;
}
.page-title h4 {
    margin: 0;
}
.card-title.d-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.mt-4 {
    margin-top: 2rem !important;
}
.mb-0 {
    margin-bottom: 0 !important;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa datepickers com opção de limpar
    var elems = document.querySelectorAll('.datepicker');
    M.Datepicker.init(elems, {
        format: 'dd/mm/yyyy',
        showClearBtn: true,
        i18n: {
            months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            cancel: 'Cancelar',
            clear: 'Limpar',
            done: 'OK'
        }
    });

    // Inicializa dropdowns
    var elems = document.querySelectorAll('.dropdown-trigger');
    M.Dropdown.init(elems, {
        constrainWidth: false,
        coverTrigger: false
    });

    // Handlers para os dropdowns
    document.querySelectorAll('#dropdown_tipo li a').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var value = this.getAttribute('data-value');
            var text = this.textContent;
            document.getElementById('tipo_display').value = text;
            document.getElementById('tipo_value').value = value;
        });
    });

    document.querySelectorAll('#dropdown_status li a').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var value = this.getAttribute('data-value');
            var text = this.textContent;
            document.getElementById('status_display').value = text;
            document.getElementById('status_value').value = value;
        });
    });
});
</script>
@endpush 
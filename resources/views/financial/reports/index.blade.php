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
<div class="row">
    <!-- Filtros -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <form action="{{ route('financial.reports.index') }}" method="GET">
                    <div class="row mb-0">
                        <div class="input-field col s12 m4">
                            <select name="mes" id="mes">
                                @foreach($meses as $num => $nome)
                                    <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>
                                        {{ $nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="mes">Mês</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <select name="ano" id="ano">
                                @foreach(range(date('Y')-2, date('Y')+1) as $a)
                                    <option value="{{ $a }}" {{ $ano == $a ? 'selected' : '' }}>
                                        {{ $a }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="ano">Ano</label>
                        </div>

                        <div class="input-field col s12 m4">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">search</i>
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Após o formulário de filtro -->
    <div class="col s12 right-align" style="margin-top: -20px; margin-bottom: 20px;">
        <a href="{{ route('financial.reports.excel', ['mes' => $mes, 'ano' => $ano]) }}" 
           class="btn waves-effect waves-light green" style="margin-right: 10px;">
            <i class="material-icons left">table_chart</i>
            Exportar Excel
        </a>
        
        <a href="{{ route('financial.reports.pdf', ['mes' => $mes, 'ano' => $ano]) }}" 
           class="btn waves-effect waves-light red" target="_blank">
            <i class="material-icons left">picture_as_pdf</i>
            Baixar PDF
        </a>
    </div>

    <!-- Cards de Totais -->
    <div class="col s12 m4">
        <div class="card green lighten-1">
            <div class="card-content white-text">
                <span class="card-title">Receitas</span>
                <h4>R$ {{ number_format($totais['receitas'], 2, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="col s12 m4">
        <div class="card red lighten-1">
            <div class="card-content white-text">
                <span class="card-title">Despesas</span>
                <h4>R$ {{ number_format($totais['despesas'], 2, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="col s12 m4">
        <div class="card {{ $totais['saldo'] >= 0 ? 'blue' : 'orange' }} lighten-1">
            <div class="card-content white-text">
                <span class="card-title">Saldo</span>
                <h4>R$ {{ number_format($totais['saldo'], 2, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Fluxo de Caixa Diário</span>
                <canvas id="fluxoCaixa" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabelas -->
    <div class="col s12 m6">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Receitas por Categoria</span>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th class="right-align">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias->where('tipo', 'receita') as $categoria)
                        <tr>
                            <td>{{ $categoria->nome }}</td>
                            <td class="right-align">R$ {{ number_format($categoria->total, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th class="right-align">R$ {{ number_format($totais['receitas'], 2, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col s12 m6">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Despesas por Categoria</span>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th class="right-align">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias->where('tipo', 'despesa') as $categoria)
                        <tr>
                            <td>{{ $categoria->nome }}</td>
                            <td class="right-align">R$ {{ number_format($categoria->total, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th class="right-align">R$ {{ number_format($totais['despesas'], 2, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Fluxo Diário -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Fluxo de Caixa Diário</span>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th class="right-align">Receitas</th>
                            <th class="right-align">Despesas</th>
                            <th class="right-align">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fluxoDiario as $dia)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($dia->data_pagamento)->format('d/m/Y') }}</td>
                            <td class="right-align">R$ {{ number_format($dia->receitas, 2, ',', '.') }}</td>
                            <td class="right-align">R$ {{ number_format($dia->despesas, 2, ',', '.') }}</td>
                            <td class="right-align">R$ {{ number_format($dia->receitas - $dia->despesas, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    // Dados para o gráfico
    var fluxoDiario = @json($fluxoDiario);
    var labels = fluxoDiario.map(dia => {
        return new Date(dia.data_pagamento).toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit'
        });
    });
    var receitas = fluxoDiario.map(dia => dia.receitas);
    var despesas = fluxoDiario.map(dia => dia.despesas);

    // Configuração do gráfico
    var ctx = document.getElementById('fluxoCaixa').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Receitas',
                data: receitas,
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                fill: true
            }, {
                label: 'Despesas',
                data: despesas,
                borderColor: '#F44336',
                backgroundColor: 'rgba(244, 67, 54, 0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw.toLocaleString('pt-BR', {
                                style: 'currency',
                                currency: 'BRL'
                            });
                            return context.dataset.label + ': ' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 
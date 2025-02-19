@extends('layouts.app')

@section('title', 'Projeção de Fluxo de Caixa')

@section('content')
<div class="row">
    <!-- Saldo Atual -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Saldo Atual</span>
                <h4 class="{{ $saldoAtual >= 0 ? 'green-text' : 'red-text' }}">
                    R$ {{ number_format($saldoAtual, 2, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>

    <!-- Filtro de Meses -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <form action="{{ route('financial.projections.index') }}" method="GET">
                    <div class="row" style="margin-bottom: 0;">
                        <div class="input-field col s12 m6">
                            <select name="meses" onchange="this.form.submit()">
                                @foreach([3, 6, 12, 24] as $option)
                                    <option value="{{ $option }}" {{ $meses == $option ? 'selected' : '' }}>
                                        {{ $option }} meses
                                    </option>
                                @endforeach
                            </select>
                            <label>Período de Projeção</label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Gráfico de Projeção -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Projeção de Fluxo de Caixa</span>
                <canvas id="graficoProjecao" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabela de Projeção -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Detalhamento Mensal</span>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Período</th>
                            <th class="right-align">Receitas</th>
                            <th class="right-align">Despesas</th>
                            <th class="right-align">Saldo do Mês</th>
                            <th class="right-align">Saldo Projetado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projecao as $mes)
                        <tr>
                            <td>{{ $mes['nome'] }}</td>
                            <td class="right-align green-text">
                                R$ {{ number_format($mes['receitas'], 2, ',', '.') }}
                            </td>
                            <td class="right-align red-text">
                                R$ {{ number_format($mes['despesas'], 2, ',', '.') }}
                            </td>
                            <td class="right-align {{ $mes['saldo_mes'] >= 0 ? 'green-text' : 'red-text' }}">
                                R$ {{ number_format($mes['saldo_mes'], 2, ',', '.') }}
                            </td>
                            <td class="right-align {{ $mes['saldo_projetado'] >= 0 ? 'green-text' : 'red-text' }}">
                                R$ {{ number_format($mes['saldo_projetado'], 2, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detalhamento por Categoria -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Detalhamento por Categoria</span>
                
                <div class="row">
                    <!-- Receitas -->
                    <div class="col s12 m6">
                        <h5>Receitas Previstas</h5>
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th class="right-align">Valor Total</th>
                                    <th class="center-align">Qtd. Contas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categorias->where('tipo', 'receita') as $categoria)
                                <tr>
                                    <td>{{ $categoria->nome }}</td>
                                    <td class="right-align">
                                        R$ {{ number_format($categoria->valor_total, 2, ',', '.') }}
                                    </td>
                                    <td class="center-align">{{ $categoria->total_contas }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Despesas -->
                    <div class="col s12 m6">
                        <h5>Despesas Previstas</h5>
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th class="right-align">Valor Total</th>
                                    <th class="center-align">Qtd. Contas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categorias->where('tipo', 'despesa') as $categoria)
                                <tr>
                                    <td>{{ $categoria->nome }}</td>
                                    <td class="right-align">
                                        R$ {{ number_format($categoria->valor_total, 2, ',', '.') }}
                                    </td>
                                    <td class="center-align">{{ $categoria->total_contas }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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

    var projecao = @json($projecao);
    var ctx = document.getElementById('graficoProjecao').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: projecao.map(p => p.nome),
            datasets: [{
                label: 'Receitas',
                data: projecao.map(p => p.receitas),
                backgroundColor: 'rgba(76, 175, 80, 0.5)',
                borderColor: '#4CAF50',
                borderWidth: 1
            }, {
                label: 'Despesas',
                data: projecao.map(p => p.despesas),
                backgroundColor: 'rgba(244, 67, 54, 0.5)',
                borderColor: '#F44336',
                borderWidth: 1
            }, {
                label: 'Saldo Projetado',
                data: projecao.map(p => p.saldo_projetado),
                type: 'line',
                borderColor: '#2196F3',
                backgroundColor: 'rgba(33, 150, 243, 0.1)',
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
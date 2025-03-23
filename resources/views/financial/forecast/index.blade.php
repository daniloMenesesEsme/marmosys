@extends('layouts.app')

@section('title', 'Previsão Financeira')

@section('content')
<div class="row">
    <!-- Contas Vencidas -->
    <div class="col s12">
        <div class="card red lighten-4">
            <div class="card-content">
                <span class="card-title">Contas Vencidas</span>
                
                <div class="row">
                    <div class="col s12 m6">
                        <h5>Receitas Vencidas: R$ {{ number_format($totaisVencidos['receitas'], 2, ',', '.') }}</h5>
                        @if($contasVencidas->has('receita'))
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Vencimento</th>
                                        <th>Descrição</th>
                                        <th class="right-align">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contasVencidas['receita'] as $conta)
                                    <tr>
                                        <td>{{ $conta->data_vencimento->format('d/m/Y') }}</td>
                                        <td>{{ $conta->descricao }}</td>
                                        <td class="right-align">R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Não há receitas vencidas.</p>
                        @endif
                    </div>

                    <div class="col s12 m6">
                        <h5>Despesas Vencidas: R$ {{ number_format($totaisVencidos['despesas'], 2, ',', '.') }}</h5>
                        @if($contasVencidas->has('despesa'))
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Vencimento</th>
                                        <th>Descrição</th>
                                        <th class="right-align">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contasVencidas['despesa'] as $conta)
                                    <tr>
                                        <td>{{ $conta->data_vencimento->format('d/m/Y') }}</td>
                                        <td>{{ $conta->descricao }}</td>
                                        <td class="right-align">R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Não há despesas vencidas.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Previsão Futura -->
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Previsão para os Próximos 6 Meses</span>
                
                <canvas id="graficoPrevisao" style="width: 100%; height: 300px;"></canvas>

                <table class="striped mt-4">
                    <thead>
                        <tr>
                            <th>Período</th>
                            <th class="right-align">Receitas Previstas</th>
                            <th class="right-align">Despesas Previstas</th>
                            <th class="right-align">Saldo Previsto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previsao as $periodo)
                        <tr>
                            <td>{{ $periodo['nome'] }}</td>
                            <td class="right-align">R$ {{ number_format($periodo['receitas'], 2, ',', '.') }}</td>
                            <td class="right-align">R$ {{ number_format($periodo['despesas'], 2, ',', '.') }}</td>
                            <td class="right-align">
                                <span class="{{ $periodo['saldo'] >= 0 ? 'green-text' : 'red-text' }}">
                                    R$ {{ number_format($periodo['saldo'], 2, ',', '.') }}
                                </span>
                            </td>
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
    var previsao = @json($previsao);
    var labels = previsao.map(p => p.nome);
    var receitas = previsao.map(p => p.receitas);
    var despesas = previsao.map(p => p.despesas);
    var saldos = previsao.map(p => p.saldo);

    var ctx = document.getElementById('graficoPrevisao').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Receitas',
                data: receitas,
                backgroundColor: 'rgba(76, 175, 80, 0.5)',
                borderColor: '#4CAF50',
                borderWidth: 1
            }, {
                label: 'Despesas',
                data: despesas,
                backgroundColor: 'rgba(244, 67, 54, 0.5)',
                borderColor: '#F44336',
                borderWidth: 1
            }, {
                label: 'Saldo',
                data: saldos,
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
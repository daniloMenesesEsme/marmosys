@extends('layouts.app')

@section('title', 'Dashboard de Tipos de Estabelecimento')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Dashboard de Tipos de Estabelecimento</h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content blue lighten-1 white-text">
                    <p class="card-stats-title"><i class="material-icons">business</i> Total de Tipos</p>
                    <h4 class="card-stats-number">{{ $totalTypes }}</h4>
                    <p class="card-stats-compare">
                        <i class="material-icons">check_circle</i>
                        {{ $activeCount }} ativos, {{ $inactiveCount }} inativos
                    </p>
                </div>
                <div class="card-action blue">
                    <div>
                        <a href="{{ route('establishment-types.index') }}" class="white-text">Ver Todos</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content green lighten-1 white-text">
                    <p class="card-stats-title"><i class="material-icons">group</i> Clientes por Tipo</p>
                    <h4 class="card-stats-number">{{ number_format($avgClientsPerType, 1) }}</h4>
                    <p class="card-stats-compare">
                        <i class="material-icons">person</i>
                        Total: {{ $totalClients }} clientes
                    </p>
                </div>
                <div class="card-action green">
                    <div>
                        <a href="{{ route('clientes.index') }}" class="white-text">Ver Clientes</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content purple lighten-1 white-text">
                    <p class="card-stats-title"><i class="material-icons">attach_money</i> Ticket Médio Geral</p>
                    <h4 class="card-stats-number">R$ {{ number_format($avgTicket, 2, ',', '.') }}</h4>
                    <p class="card-stats-compare">
                        <i class="material-icons">trending_up</i>
                        Baseado em {{ $salesCount }} vendas
                    </p>
                </div>
                <div class="card-action purple">
                    <div>
                        <a href="{{ route('budgets.index') }}" class="white-text">Ver Vendas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content orange lighten-1 white-text">
                    <p class="card-stats-title"><i class="material-icons">trending_up</i> Total de Vendas</p>
                    <h4 class="card-stats-number">R$ {{ number_format($totalSales, 2, ',', '.') }}</h4>
                    <p class="card-stats-compare">
                        <i class="material-icons">assignment_turned_in</i>
                        Média: R$ {{ number_format($avgSalesPerType, 2, ',', '.') }} por tipo
                    </p>
                </div>
                <div class="card-action orange">
                    <div>
                        <a href="{{ route('service-areas.dashboard') }}" class="white-text">Ver Áreas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Distribuição de Clientes por Tipo</span>
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="clientChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m6">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Distribuição de Vendas por Tipo</span>
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Tipos de Estabelecimento por Desempenho</span>
                    <table class="striped responsive-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Nível de Potencial</th>
                                <th>Ticket Médio Esperado</th>
                                <th>Ticket Médio Real</th>
                                <th>Clientes</th>
                                <th>Total de Vendas</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topTypes as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="determinate" style="width: {{ $type->potential_level * 10 }}%;"></div>
                                    </div>
                                    <span>{{ $type->potential_level }}/10</span>
                                </td>
                                <td>R$ {{ number_format($type->avg_ticket, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($type->real_avg_ticket, 2, ',', '.') }}</td>
                                <td>{{ $type->clients_count }}</td>
                                <td>R$ {{ number_format($type->total_sales, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('establishment-types.show', $type) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Detalhes">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Tipos de Estabelecimento sem Áreas de Atendimento</span>
                    <table class="striped responsive-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Nível de Potencial</th>
                                <th>Ticket Médio Esperado</th>
                                <th>Clientes</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($typesWithoutServiceAreas as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="determinate" style="width: {{ $type->potential_level * 10 }}%;"></div>
                                    </div>
                                    <span>{{ $type->potential_level }}/10</span>
                                </td>
                                <td>R$ {{ number_format($type->avg_ticket, 2, ',', '.') }}</td>
                                <td>{{ $type->clients_count }}</td>
                                <td>
                                    <a href="{{ route('service-areas.create') }}?establishment_type_id={{ $type->id }}" class="btn-floating btn-small waves-effect waves-light green" title="Criar Área">
                                        <i class="material-icons">add</i>
                                    </a>
                                    <a href="{{ route('establishment-types.show', $type) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Detalhes">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="center-align">Todos os tipos de estabelecimento possuem áreas de atendimento.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dados para o gráfico de clientes por tipo
        let clientCtx = document.getElementById('clientChart').getContext('2d');
        let clientChart = new Chart(clientCtx, {
            type: 'pie',
            data: {
                labels: @json($clientsChartData['labels']),
                datasets: [{
                    data: @json($clientsChartData['data']),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(199, 199, 199, 0.5)',
                        'rgba(83, 102, 255, 0.5)',
                        'rgba(40, 159, 64, 0.5)',
                        'rgba(210, 199, 199, 0.5)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)',
                        'rgba(83, 102, 255, 1)',
                        'rgba(40, 159, 64, 1)',
                        'rgba(210, 199, 199, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} clientes (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Dados para o gráfico de vendas por tipo
        let salesCtx = document.getElementById('salesChart').getContext('2d');
        let salesChart = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: @json($salesChartData['labels']),
                datasets: [{
                    label: 'Total de Vendas (R$)',
                    data: @json($salesChartData['data']),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection 
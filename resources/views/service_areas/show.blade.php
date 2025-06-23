@extends('layouts.app')

@section('title', 'Detalhes da Área de Atendimento')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>
                Área de Atendimento: 
                {{ $serviceArea->location->name }}
                @if($serviceArea->establishmentType)
                    - {{ $serviceArea->establishmentType->name }}
                @endif
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m4">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Informações Gerais</span>
                    <table class="striped responsive-table">
                        <tbody>
                            <tr>
                                <th>Localidade:</th>
                                <td>
                                    <a href="{{ route('locations.show', $serviceArea->location) }}">
                                        {{ $serviceArea->location->name }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Tipo de Estabelecimento:</th>
                                <td>
                                    @if($serviceArea->establishmentType)
                                        <a href="{{ route('establishment-types.show', $serviceArea->establishmentType) }}">
                                            {{ $serviceArea->establishmentType->name }}
                                        </a>
                                    @else
                                        <span class="grey-text">Todos</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Vendedor:</th>
                                <td>
                                    @if($serviceArea->seller)
                                        <a href="{{ route('sellers.show', $serviceArea->seller) }}">
                                            {{ $serviceArea->seller->nome }}
                                        </a>
                                    @else
                                        <span class="red-text">Não atribuído</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Meta Anual:</th>
                                <td>{{ $serviceArea->goal_amount ? 'R$ ' . number_format($serviceArea->goal_amount, 2, ',', '.') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($serviceArea->status === 'active')
                                        <span class="chip green white-text">Ativo</span>
                                    @else
                                        <span class="chip grey white-text">Inativo</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Data de Criação:</th>
                                <td>{{ $serviceArea->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Última Atualização:</th>
                                <td>{{ $serviceArea->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-action">
                    <a href="{{ route('service-areas.edit', $serviceArea) }}" class="btn waves-effect waves-light orange">
                        <i class="material-icons left">edit</i>Editar
                    </a>
                    <a href="{{ route('service-areas.index') }}" class="btn-flat waves-effect">
                        <i class="material-icons left">arrow_back</i>Voltar
                    </a>
                </div>
            </div>

            @if($serviceArea->notes)
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Observações</span>
                    <p>{{ $serviceArea->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col s12 m8">
            <!-- Métricas de Desempenho -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Desempenho do Ano Atual</span>
                    
                    @if($serviceArea->seller_id)
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="card-panel teal lighten-2 white-text center-align">
                                    <h5>Vendas Realizadas</h5>
                                    <h4>R$ {{ number_format($currentYearStats['total_sales'], 2, ',', '.') }}</h4>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="card-panel blue lighten-2 white-text center-align">
                                    <h5>Progresso da Meta</h5>
                                    <div class="progress white">
                                        <div class="determinate blue darken-4" style="width: {{ min($currentYearStats['goal_percentage'], 100) }}%;"></div>
                                    </div>
                                    <h4>{{ number_format($currentYearStats['goal_percentage'], 1) }}%</h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col s12">
                                <span class="card-title">Comparativo com Ano Anterior</span>
                                <table class="striped centered">
                                    <thead>
                                        <tr>
                                            <th>Ano</th>
                                            <th>Total de Vendas</th>
                                            <th>Quantidade</th>
                                            <th>Meta</th>
                                            <th>Progresso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ date('Y') }}</td>
                                            <td>R$ {{ number_format($currentYearStats['total_sales'], 2, ',', '.') }}</td>
                                            <td>{{ $currentYearStats['sale_count'] }}</td>
                                            <td>R$ {{ number_format($currentYearStats['goal_amount'], 2, ',', '.') }}</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="determinate blue" style="width: {{ min($currentYearStats['goal_percentage'], 100) }}%;"></div>
                                                </div>
                                                {{ number_format($currentYearStats['goal_percentage'], 1) }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ date('Y') - 1 }}</td>
                                            <td>R$ {{ number_format($lastYearStats['total_sales'], 2, ',', '.') }}</td>
                                            <td>{{ $lastYearStats['sale_count'] }}</td>
                                            <td>R$ {{ number_format($lastYearStats['goal_amount'], 2, ',', '.') }}</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="determinate green" style="width: {{ min($lastYearStats['goal_percentage'], 100) }}%;"></div>
                                                </div>
                                                {{ number_format($lastYearStats['goal_percentage'], 1) }}%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col s12">
                                <span class="card-title">Vendas Mensais</span>
                                <div class="chart-container" style="position: relative; height:300px;">
                                    <canvas id="monthlyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="center-align">
                            <p>Não há dados de desempenho disponíveis porque não há vendedor atribuído a esta área.</p>
                            <a href="{{ route('service-areas.edit', $serviceArea) }}" class="btn waves-effect waves-light">
                                <i class="material-icons left">person_add</i>Atribuir Vendedor
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Clientes -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Clientes na Área ({{ $clientCount }})</span>
                    @if($clientCount > 0)
                        <div class="row">
                            <div class="col s12">
                                <a href="{{ route('clientes.index') }}?location_id={{ $serviceArea->location_id }}{{ $serviceArea->establishment_type_id ? '&establishment_type_id=' . $serviceArea->establishment_type_id : '' }}" class="btn waves-effect waves-light">
                                    <i class="material-icons left">group</i>Ver Todos os Clientes
                                </a>
                            </div>
                        </div>
                    @else
                        <p>Não há clientes cadastrados para esta área de atendimento.</p>
                    @endif
                </div>
            </div>

            <!-- Localização no Mapa -->
            @if($serviceArea->location->latitude && $serviceArea->location->longitude)
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Localização</span>
                    <div id="map" style="height: 300px;"></div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($serviceArea->seller_id)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dados para o gráfico de vendas mensais
        let monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        
        let monthNames = @json(array_map(function($item) { return $item['month']; }, $monthlyStats));
        let salesData = @json(array_map(function($item) { return $item['total_sales']; }, $monthlyStats));
        let goalData = @json(array_map(function($item) { return $item['monthly_goal']; }, $monthlyStats));
        
        let monthlyChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [
                    {
                        label: 'Vendas',
                        data: salesData,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Meta Mensal',
                        data: goalData,
                        type: 'line',
                        fill: false,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderDash: [5, 5],
                        pointRadius: 0
                    }
                ]
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
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw || 0;
                                return label + ': R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif

@if($serviceArea->location->latitude && $serviceArea->location->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
<script>
    function initMap() {
        let location = {
            lat: {{ $serviceArea->location->latitude }},
            lng: {{ $serviceArea->location->longitude }}
        };
        
        let map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: location,
        });
        
        let marker = new google.maps.Marker({
            position: location,
            map: map,
            title: "{{ $serviceArea->location->name }}"
        });
    }
</script>
@endif
@endsection 
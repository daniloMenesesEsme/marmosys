@extends('layouts.app')

@section('title', 'Dashboard de Áreas de Atendimento')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Dashboard de Áreas de Atendimento</h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content blue lighten-1 white-text">
                    <p class="card-stats-title"><i class="material-icons">place</i> Áreas sem Vendedor</p>
                    <h4 class="card-stats-number">{{ $unassignedCount }}</h4>
                    <p class="card-stats-compare">
                        <i class="material-icons">warning</i>
                        Áreas que precisam de atribuição
                    </p>
                </div>
                <div class="card-action blue">
                    <div>
                        <a href="{{ route('service-areas.index') }}?seller_id=null" class="white-text">Ver Áreas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content orange lighten-1 white-text">
                    <p class="card-stats-title"><i class="material-icons">location_city</i> Áreas sem Clientes</p>
                    <h4 class="card-stats-number">{{ $areasWithoutClients }}</h4>
                    <p class="card-stats-compare">
                        <i class="material-icons">people_outline</i>
                        Áreas sem clientes cadastrados
                    </p>
                </div>
                <div class="card-action orange">
                    <div>
                        <a href="{{ route('service-areas.map') }}" class="white-text">Ver no Mapa</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content green lighten-1 white-text">
                    <p class="card-stats-title"><i class="material-icons">person</i> Vendedores Ativos</p>
                    <h4 class="card-stats-number">{{ $topSellers->count() }}</h4>
                    <p class="card-stats-compare">
                        <i class="material-icons">trending_up</i>
                        Com áreas de atendimento atribuídas
                    </p>
                </div>
                <div class="card-action green">
                    <div>
                        <a href="{{ route('sellers.index') }}" class="white-text">Ver Vendedores</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content purple lighten-1 white-text">
                    <p class="card-stats-title"><i class="material-icons">business</i> Tipos de Estabelecimento</p>
                    <h4 class="card-stats-number">{{ $typePerformance->count() }}</h4>
                    <p class="card-stats-compare">
                        <i class="material-icons">store</i>
                        Diferentes tipos de estabelecimentos
                    </p>
                </div>
                <div class="card-action purple">
                    <div>
                        <a href="{{ route('establishment-types.index') }}" class="white-text">Ver Tipos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m6">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Melhores Vendedores por Áreas</span>
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Vendedor</th>
                                <th>Áreas Atribuídas</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSellers as $sellerData)
                                <tr>
                                    <td>{{ $sellerData->seller->nome }}</td>
                                    <td>{{ $sellerData->area_count }}</td>
                                    <td>
                                        <a href="{{ route('service-areas.index') }}?seller_id={{ $sellerData->seller_id }}" class="btn-floating btn-small waves-effect waves-light blue" title="Ver Áreas">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                        <a href="{{ route('sellers.show', $sellerData->seller_id) }}" class="btn-floating btn-small waves-effect waves-light green" title="Ver Vendedor">
                                            <i class="material-icons">person</i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="center-align">Não há vendedores com áreas atribuídas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col s12 m6">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Desempenho por Tipo de Estabelecimento</span>
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Áreas</th>
                                <th>Vendas</th>
                                <th>Total (R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($typePerformance as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->area_count }}</td>
                                    <td>{{ $type->sale_count }}</td>
                                    <td>R$ {{ number_format($type->total_sales, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="center-align">Não há dados de desempenho disponíveis.</td>
                                </tr>
                            @endforelse
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
                    <span class="card-title">Distribuição de Áreas de Atendimento</span>
                    <div class="chart-container" style="position: relative; height:400px;">
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-action">
                    <a href="{{ route('service-areas.map') }}" class="btn waves-effect waves-light">
                        <i class="material-icons left">map</i>Ver Mapa de Cobertura
                    </a>
                    <a href="{{ route('service-areas.index') }}" class="btn-flat waves-effect">
                        <i class="material-icons left">list</i>Listar Áreas
                    </a>
                    <a href="{{ route('service-areas.create') }}" class="btn green waves-effect waves-light">
                        <i class="material-icons left">add</i>Nova Área
                    </a>
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
        // Preparar dados para o gráfico de distribuição
        let sellerNames = [];
        let areaCounts = [];
        let backgroundColors = [];
        
        @foreach($topSellers as $index => $sellerData)
            sellerNames.push("{{ $sellerData->seller->nome }}");
            areaCounts.push({{ $sellerData->area_count }});
            backgroundColors.push(`hsl(${({{ $index }} * 50) % 360}, 70%, 50%)`);
        @endforeach
        
        // Adicionar áreas não atribuídas
        sellerNames.push("Não atribuído");
        areaCounts.push({{ $unassignedCount }});
        backgroundColors.push('#9e9e9e'); // Cinza para áreas não atribuídas
        
        // Criar o gráfico
        let ctx = document.getElementById('distributionChart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: sellerNames,
                datasets: [{
                    data: areaCounts,
                    backgroundColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} áreas (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection 
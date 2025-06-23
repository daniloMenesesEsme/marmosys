@extends('layouts.app')

@section('title', 'Regiões de Vendedores')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Regiões de Vendedores</span>
                    
                    <div class="row">
                        <div class="col s12">
                            <a href="{{ route('regions.create') }}" class="btn waves-effect waves-light blue right">
                                <i class="material-icons left">add</i>Nova Região
                            </a>
                        </div>
                    </div>
                    
                    <ul class="tabs">
                        <li class="tab col s6"><a class="active" href="#tab-states">Por Estado</a></li>
                        <li class="tab col s6"><a href="#tab-regions">Por Região Cadastrada</a></li>
                    </ul>
                    
                    <div id="tab-states" class="col s12">
                        <div class="row">
                            <div class="col s12 m6">
                                <h5>Distribuição por Estado</h5>
                                <div style="height: 300px; margin-top: 20px;">
                                    <canvas id="stateChart"></canvas>
                                </div>
                            </div>
                            
                            <div class="col s12 m6">
                                <h5>Detalhes por Estado</h5>
                                <table class="striped">
                                    <thead>
                                        <tr>
                                            <th>Estado</th>
                                            <th>Total de Vendedores</th>
                                            <th>Porcentagem</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalVendedoresEstado = $stateRegions->sum('total');
                                        @endphp
                                        
                                        @forelse($stateRegions as $region)
                                            <tr>
                                                <td>{{ $region->estado }}</td>
                                                <td>{{ $region->total }}</td>
                                                <td>{{ number_format(($region->total / ($totalVendedoresEstado ?: 1)) * 100, 1) }}%</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="center-align">Nenhuma região por estado encontrada</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th>{{ $totalVendedoresEstado }}</th>
                                            <th>100%</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab-regions" class="col s12">
                        <div class="row">
                            <div class="col s12 m6">
                                <h5>Distribuição por Região Cadastrada</h5>
                                <div style="height: 300px; margin-top: 20px;">
                                    <canvas id="regionChart"></canvas>
                                </div>
                            </div>
                            
                            <div class="col s12 m6">
                                <h5>Detalhes por Região Cadastrada</h5>
                                <table class="striped">
                                    <thead>
                                        <tr>
                                            <th>Região</th>
                                            <th>Estado</th>
                                            <th>Total de Vendedores</th>
                                            <th>Porcentagem</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalVendedoresRegiao = $customRegions->sum('total');
                                        @endphp
                                        
                                        @forelse($customRegions as $region)
                                            <tr>
                                                <td>{{ $region->name }}</td>
                                                <td>{{ $region->state }}</td>
                                                <td>{{ $region->total }}</td>
                                                <td>{{ number_format(($region->total / ($totalVendedoresRegiao ?: 1)) * 100, 1) }}%</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="center-align">Nenhuma região cadastrada com vendedores</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th>{{ $totalVendedoresRegiao }}</th>
                                            <th>100%</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col s12">
                            <h5>Vendedores por Região</h5>
                            <table class="striped responsive-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Região Cadastrada</th>
                                        <th>Estado</th>
                                        <th>Cidade</th>
                                        <th>Telefone</th>
                                        <th>Email</th>
                                        <th>Comissão (%)</th>
                                        <th>Meta Mensal</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sellers as $seller)
                                        <tr>
                                            <td>{{ $seller->nome }}</td>
                                            <td>{{ $seller->region ? $seller->region->name : '-' }}</td>
                                            <td>{{ $seller->estado ?: '-' }}</td>
                                            <td>{{ $seller->cidade ?: '-' }}</td>
                                            <td>{{ $seller->telefone ?: $seller->celular }}</td>
                                            <td>{{ $seller->email }}</td>
                                            <td>{{ number_format($seller->percentual_comissao, 2) }}%</td>
                                            <td>R$ {{ number_format($seller->meta_mensal, 2, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('sellers.show', $seller) }}" class="btn-floating btn-small waves-effect waves-light blue">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="center-align">Nenhum vendedor encontrado</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            {{ $sellers->links() }}
                        </div>
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
        // Inicializar tabs
        var tabs = document.querySelectorAll('.tabs');
        var instance = M.Tabs.init(tabs, {});
        
        // Dados para o gráfico de estados
        var estados = [
            @foreach($stateRegions as $region)
                '{{ $region->estado }}',
            @endforeach
        ];
        
        var totaisEstado = [
            @foreach($stateRegions as $region)
                {{ $region->total }},
            @endforeach
        ];
        
        // Cores aleatórias para o gráfico de estados
        var coresEstado = [];
        for (var i = 0; i < estados.length; i++) {
            coresEstado.push(
                'hsl(' + (i * 360 / estados.length) + ', 70%, 60%)'
            );
        }
        
        // Criar o gráfico de estados
        var ctxState = document.getElementById('stateChart').getContext('2d');
        var stateChart = new Chart(ctxState, {
            type: 'pie',
            data: {
                labels: estados,
                datasets: [{
                    data: totaisEstado,
                    backgroundColor: coresEstado
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' vendedores (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
        
        // Dados para o gráfico de regiões cadastradas
        var regioes = [
            @foreach($customRegions as $region)
                '{{ $region->name }} ({{ $region->state }})',
            @endforeach
        ];
        
        var totaisRegiao = [
            @foreach($customRegions as $region)
                {{ $region->total }},
            @endforeach
        ];
        
        // Cores aleatórias para o gráfico de regiões
        var coresRegiao = [];
        for (var i = 0; i < regioes.length; i++) {
            coresRegiao.push(
                'hsl(' + (i * 360 / regioes.length) + ', 70%, 60%)'
            );
        }
        
        // Criar o gráfico de regiões cadastradas
        var ctxRegion = document.getElementById('regionChart').getContext('2d');
        var regionChart = new Chart(ctxRegion, {
            type: 'pie',
            data: {
                labels: regioes,
                datasets: [{
                    data: totaisRegiao,
                    backgroundColor: coresRegiao
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' vendedores (' + percentage + '%)';
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
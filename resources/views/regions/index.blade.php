@extends('layouts.app')

@section('title', 'Regiões')

@section('content')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Regiões</span>
                        
                        <div class="row">
                            <div class="col s12">
                                <a href="{{ route('regions.create') }}" class="btn waves-effect waves-light blue">
                                    <i class="material-icons left">add</i>Nova Região
                                </a>
                            </div>
                        </div>
                        
                        <!-- Gráficos -->
                        <div class="row">
                            <div class="col s12">
                                <ul class="tabs">
                                    <li class="tab col s6"><a class="active" href="#tab-regions">Vendedores por Região</a></li>
                                    <li class="tab col s6"><a href="#tab-states">Vendedores por Estado</a></li>
                                </ul>
                            </div>
                            
                            <!-- Gráfico de Vendedores por Região -->
                            <div id="tab-regions" class="col s12">
                                <div class="row">
                                    <div class="col s12 m6">
                                        <div style="height: 300px; margin-top: 20px; position: relative;">
                                            <canvas id="regionsChart"></canvas>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m6">
                                        <h5>Detalhes por Região</h5>
                                        <table class="striped">
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Estado</th>
                                                    <th>Vendedores</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($regionsChart as $region)
                                                    <tr>
                                                        <td>{{ $region->name }}</td>
                                                        <td>{{ $region->state }}</td>
                                                        <td>{{ $region->sellers_count }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="center-align">Nenhuma região encontrada</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Gráfico de Vendedores por Estado -->
                            <div id="tab-states" class="col s12">
                                <div class="row">
                                    <div class="col s12 m6">
                                        <div style="height: 300px; margin-top: 20px; position: relative;">
                                            <canvas id="statesChart"></canvas>
                                        </div>
                                    </div>
                                    
                                    <div class="col s12 m6">
                                        <h5>Detalhes por Estado</h5>
                                        <table class="striped">
                                            <thead>
                                                <tr>
                                                    <th>Estado</th>
                                                    <th>Regiões</th>
                                                    <th>Vendedores</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($stateData as $state)
                                                    <tr>
                                                        <td>{{ $state->state }}</td>
                                                        <td>{{ $state->total_regions }}</td>
                                                        <td>{{ $state->total_sellers }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="center-align">Nenhum estado encontrado</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tabela de Regiões -->
                        <div class="row">
                            <div class="col s12">
                                <h5>Lista de Regiões</h5>
                                <table class="striped responsive-table">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Estado</th>
                                            <th>Status</th>
                                            <th>Vendedores</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($regions as $region)
                                            <tr>
                                                <td>{{ $region->name }}</td>
                                                <td>{{ $region->state }}</td>
                                                <td>
                                                    @if($region->status == 'active')
                                                        <span class="new badge green" data-badge-caption="Ativo"></span>
                                                    @else
                                                        <span class="new badge grey" data-badge-caption="Inativo"></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $region->sellers_count }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('regions.show', $region) }}" class="btn-small blue">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    <a href="{{ route('regions.edit', $region) }}" class="btn-small amber">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                    <form action="{{ route('regions.destroy', $region) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-small red" onclick="return confirm('Tem certeza que deseja excluir esta região?')">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('regions.toggle-status', $region) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn-small {{ $region->status == 'active' ? 'grey' : 'green' }}" 
                                                                title="{{ $region->status == 'active' ? 'Desativar' : 'Ativar' }} região">
                                                            <i class="material-icons">{{ $region->status == 'active' ? 'toggle_off' : 'toggle_on' }}</i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                {{ $regions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tabs
        var tabs = document.querySelectorAll('.tabs');
        var instances = M.Tabs.init(tabs, {});
        
        // Delay para garantir que os elementos do DOM estejam prontos
        setTimeout(function() {
            // Dados para o gráfico de regiões
            var regionLabels = [
                @foreach($regionsChart as $region)
                    "{{ $region->name }} ({{ $region->state }})",
                @endforeach
            ];
            
            var regionData = [
                @foreach($regionsChart as $region)
                    {{ $region->sellers_count ?: 0 }},
                @endforeach
            ];
            
            // Cores para o gráfico de regiões
            var regionColors = [];
            for (var i = 0; i < regionLabels.length; i++) {
                regionColors.push(
                    'hsl(' + (i * 360 / Math.max(1, regionLabels.length)) + ', 70%, 60%)'
                );
            }
            
            // Criar o gráfico de regiões
            var ctxRegions = document.getElementById('regionsChart').getContext('2d');
            if (regionLabels.length > 0) {
                var regionsChart = new Chart(ctxRegions, {
                    type: 'pie',
                    data: {
                        labels: regionLabels,
                        datasets: [{
                            data: regionData,
                            backgroundColor: regionColors
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                display: true
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.raw || 0;
                                        var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        var percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return label + ': ' + value + ' vendedor(es) (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                // Se não houver dados, exibir mensagem
                document.getElementById('regionsChart').parentNode.innerHTML = '<p class="center-align">Não há dados para exibir</p>';
            }
            
            // Dados para o gráfico de estados
            var stateLabels = [
                @foreach($stateData as $state)
                    "{{ $state->state }}",
                @endforeach
            ];
            
            var stateData = [
                @foreach($stateData as $state)
                    {{ $state->total_sellers ?: 0 }},
                @endforeach
            ];
            
            // Cores para o gráfico de estados
            var stateColors = [];
            for (var i = 0; i < stateLabels.length; i++) {
                stateColors.push(
                    'hsl(' + (i * 360 / Math.max(1, stateLabels.length)) + ', 70%, 60%)'
                );
            }
            
            // Criar o gráfico de estados
            var ctxStates = document.getElementById('statesChart').getContext('2d');
            if (stateLabels.length > 0) {
                var statesChart = new Chart(ctxStates, {
                    type: 'pie',
                    data: {
                        labels: stateLabels,
                        datasets: [{
                            data: stateData,
                            backgroundColor: stateColors
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                display: true
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.raw || 0;
                                        var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        var percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return label + ': ' + value + ' vendedor(es) (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                // Se não houver dados, exibir mensagem
                document.getElementById('statesChart').parentNode.innerHTML = '<p class="center-align">Não há dados para exibir</p>';
            }
        }, 300); // Pequeno atraso para garantir que o DOM esteja pronto
    });
</script>
@endpush
@endsection 
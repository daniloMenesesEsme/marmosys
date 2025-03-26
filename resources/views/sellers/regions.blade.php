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
                        <div class="col s12 m6">
                            <h5>Distribuição por Estado</h5>
                            <div style="height: 300px; margin-top: 20px;">
                                <canvas id="regionChart"></canvas>
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
                                        $totalVendedores = $regions->sum('total');
                                    @endphp
                                    
                                    @forelse($regions as $region)
                                        <tr>
                                            <td>{{ $region->estado }}</td>
                                            <td>{{ $region->total }}</td>
                                            <td>{{ number_format(($region->total / $totalVendedores) * 100, 1) }}%</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="center-align">Nenhuma região encontrada</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th>{{ $totalVendedores }}</th>
                                        <th>100%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col s12">
                            <h5>Vendedores por Região</h5>
                            <table class="striped responsive-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
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
                                            <td colspan="8" class="center-align">Nenhum vendedor encontrado</td>
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
        // Dados para o gráfico
        var estados = [
            @foreach($regions as $region)
                '{{ $region->estado }}',
            @endforeach
        ];
        
        var totais = [
            @foreach($regions as $region)
                {{ $region->total }},
            @endforeach
        ];
        
        // Cores aleatórias para o gráfico
        var cores = [];
        for (var i = 0; i < estados.length; i++) {
            cores.push(
                'hsl(' + (i * 360 / estados.length) + ', 70%, 60%)'
            );
        }
        
        // Criar o gráfico
        var ctx = document.getElementById('regionChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: estados,
                datasets: [{
                    data: totais,
                    backgroundColor: cores
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
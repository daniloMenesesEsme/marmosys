@extends('layouts.app')

@section('title', 'Regiões de Fornecedores')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <h4 class="header">
                <i class="material-icons left">place</i>
                Regiões de Fornecedores
            </h4>
        </div>
    </div>

    <div class="row">
        <!-- Mapa do Brasil -->
        <div class="col s12 m6">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Distribuição por Estado</span>
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>

        <!-- Tabela de Distribuição -->
        <div class="col s12 m6">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Quantidade por Estado</span>
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Quantidade</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = $regions->sum('total');
                            @endphp
                            @foreach($regions as $region)
                                <tr>
                                    <td>{{ $region->estado }}</td>
                                    <td>{{ $region->total }}</td>
                                    <td>{{ number_format(($region->total / $total) * 100, 1) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>{{ $total }}</th>
                                <th>100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Resumo por Região -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Resumo por Região</span>
                    @php
                        $regioes = [
                            'Norte' => ['AC', 'AM', 'AP', 'PA', 'RO', 'RR', 'TO'],
                            'Nordeste' => ['AL', 'BA', 'CE', 'MA', 'PB', 'PE', 'PI', 'RN', 'SE'],
                            'Centro-Oeste' => ['DF', 'GO', 'MT', 'MS'],
                            'Sudeste' => ['ES', 'MG', 'RJ', 'SP'],
                            'Sul' => ['PR', 'RS', 'SC']
                        ];

                        $totaisPorRegiao = [];
                        foreach ($regioes as $regiao => $estados) {
                            $totaisPorRegiao[$regiao] = $regions->whereIn('estado', $estados)->sum('total');
                        }
                    @endphp

                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Região</th>
                                <th>Quantidade</th>
                                <th>Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totaisPorRegiao as $regiao => $totalRegiao)
                                <tr>
                                    <td>{{ $regiao }}</td>
                                    <td>{{ $totalRegiao }}</td>
                                    <td>{{ number_format(($totalRegiao / $total) * 100, 1) }}%</td>
                                </tr>
                            @endforeach
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
    // Dados para o mapa
    const regions = @json($regions);
    const total = {{ $total }};

    // Criar gráfico de pizza para distribuição por região
    const ctx = document.getElementById('map').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: regions.map(r => r.estado),
            datasets: [{
                data: regions.map(r => r.total),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56',
                    '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#36A2EB',
                    '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384',
                    '#36A2EB', '#FFCE56'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                title: {
                    display: true,
                    text: 'Distribuição de Fornecedores por Estado'
                }
            }
        }
    });
});
</script>
@endsection 
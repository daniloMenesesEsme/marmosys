@extends('layouts.app')

@section('title', 'Detalhes da Região')

@section('content')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">{{ $region->name }}</span>
                        
                        <div class="row">
                            <div class="col s12 m6">
                                <ul class="collection">
                                    <li class="collection-item">
                                        <strong>Estado:</strong> {{ $region->state }}
                                    </li>
                                    <li class="collection-item">
                                        <strong>Descrição:</strong> {{ $region->description ?? 'Não informado' }}
                                    </li>
                                    <li class="collection-item">
                                        <strong>Status:</strong>
                                        @if($region->status == 'active')
                                            <span class="new badge green" data-badge-caption="Ativo"></span>
                                        @else
                                            <span class="new badge grey" data-badge-caption="Inativo"></span>
                                        @endif
                                    </li>
                                    <li class="collection-item">
                                        <strong>Data de Criação:</strong> {{ $region->created_at->format('d/m/Y H:i') }}
                                    </li>
                                    <li class="collection-item">
                                        <strong>Última Atualização:</strong> {{ $region->updated_at->format('d/m/Y H:i') }}
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="col s12 m6">
                                <div style="height: 200px;">
                                    <canvas id="sellerStatusChart"></canvas>
                                </div>
                                <p class="center-align">Status dos vendedores nesta região</p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col s12">
                                <h5>Vendedores nesta região</h5>
                                
                                @if($sellers->count() > 0)
                                    <table class="striped">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Telefone</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sellers as $seller)
                                                <tr>
                                                    <td>{{ $seller->name }}</td>
                                                    <td>{{ $seller->email }}</td>
                                                    <td>{{ $seller->phone }}</td>
                                                    <td>
                                                        @if($seller->status == 'active')
                                                            <span class="new badge green" data-badge-caption="Ativo"></span>
                                                        @else
                                                            <span class="new badge grey" data-badge-caption="Inativo"></span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('sellers.show', $seller) }}" class="btn-small blue">
                                                            <i class="material-icons">visibility</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>Nenhum vendedor associado a esta região.</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col s12">
                                <a href="{{ route('regions.edit', $region) }}" class="btn waves-effect waves-light amber">
                                    <i class="material-icons left">edit</i>Editar
                                </a>
                                <a href="{{ route('regions.index') }}" class="btn waves-effect waves-light grey">
                                    <i class="material-icons left">arrow_back</i>Voltar
                                </a>
                                <form action="{{ route('regions.toggle-status', $region) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn waves-effect waves-light {{ $region->status == 'active' ? 'red' : 'green' }}">
                                        <i class="material-icons left">{{ $region->status == 'active' ? 'toggle_off' : 'toggle_on' }}</i>
                                        {{ $region->status == 'active' ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>
                            </div>
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
        // Gráfico de status dos vendedores
        var activeCount = {{ $sellers->where('status', 'active')->count() }};
        var inactiveCount = {{ $sellers->where('status', '!=', 'active')->count() }};
        
        var ctx = document.getElementById('sellerStatusChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Ativos', 'Inativos'],
                datasets: [{
                    data: [activeCount, inactiveCount],
                    backgroundColor: [
                        '#4CAF50', // Verde
                        '#9E9E9E'  // Cinza
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = activeCount + inactiveCount;
                                var percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return label + ': ' + value + ' vendedor(es) (' + percentage + '%)';
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
@extends('layouts.app')

@section('title', 'Detalhes do Tipo de Estabelecimento')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Detalhes do Tipo de Estabelecimento: {{ $establishmentType->name }}</h4>
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
                                <th>Nome:</th>
                                <td>{{ $establishmentType->name }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($establishmentType->status === 'active')
                                        <span class="chip green white-text">Ativo</span>
                                    @else
                                        <span class="chip grey white-text">Inativo</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ticket Médio:</th>
                                <td>
                                    @if($establishmentType->avg_ticket)
                                        R$ {{ number_format($establishmentType->avg_ticket, 2, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Nível de Potencial:</th>
                                <td>
                                    @if($establishmentType->potential_level)
                                        <div class="progress">
                                            <div class="determinate" style="width: {{ $establishmentType->potential_level * 10 }}%;"></div>
                                        </div>
                                        <span>{{ $establishmentType->potential_level }} / 10</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Data de Criação:</th>
                                <td>{{ $establishmentType->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Última Atualização:</th>
                                <td>{{ $establishmentType->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-action">
                    <a href="{{ route('establishment-types.edit', $establishmentType) }}" class="btn waves-effect waves-light orange">
                        <i class="material-icons left">edit</i>Editar
                    </a>
                    <a href="{{ route('establishment-types.index') }}" class="btn-flat waves-effect">
                        <i class="material-icons left">arrow_back</i>Voltar
                    </a>
                </div>
            </div>

            @if($establishmentType->description)
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Descrição</span>
                    <p>{{ $establishmentType->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col s12 m8">
            <!-- Estatísticas de Vendas -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Estatísticas de Vendas</span>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="card-panel teal lighten-2 white-text center-align">
                                <h5>Total de Vendas</h5>
                                <h4>R$ {{ number_format($salesStats['total'], 2, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="card-panel blue lighten-2 white-text center-align">
                                <h5>Ticket Médio Real</h5>
                                <h4>R$ {{ number_format($salesStats['avg_ticket'], 2, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="card-panel orange lighten-2 white-text center-align">
                                <h5>Quantidade de Vendas</h5>
                                <h4>{{ $salesStats['count'] }}</h4>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="card-panel purple lighten-2 white-text center-align">
                                <h5>Vendas / Cliente</h5>
                                <h4>{{ number_format($salesStats['sales_per_client'], 1) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Áreas de Atendimento -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Áreas de Atendimento ({{ $serviceAreas->count() }})</span>
                    @if($serviceAreas->count() > 0)
                        <table class="striped responsive-table">
                            <thead>
                                <tr>
                                    <th>Localidade</th>
                                    <th>Vendedor</th>
                                    <th>Meta</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceAreas as $area)
                                <tr>
                                    <td>{{ $area->location->name ?? 'N/A' }}</td>
                                    <td>{{ $area->seller->nome ?? 'Não atribuído' }}</td>
                                    <td>{{ $area->goal_amount ? 'R$ ' . number_format($area->goal_amount, 2, ',', '.') : '-' }}</td>
                                    <td>
                                        @if($area->status === 'active')
                                            <span class="chip green white-text">Ativo</span>
                                        @else
                                            <span class="chip grey white-text">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('service-areas.show', $area) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Detalhes">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Não há áreas de atendimento cadastradas para este tipo de estabelecimento.</p>
                    @endif
                </div>
                <div class="card-action">
                    <a href="{{ route('service-areas.create') }}?establishment_type_id={{ $establishmentType->id }}" class="btn waves-effect waves-light">
                        <i class="material-icons left">add</i>Nova Área de Atendimento
                    </a>
                </div>
            </div>

            <!-- Clientes -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Clientes ({{ $clients->count() }})</span>
                    @if($clients->count() > 0)
                        <table class="striped responsive-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Localidade</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                <tr>
                                    <td>{{ $client->nome }}</td>
                                    <td>{{ $client->location->name ?? '-' }}</td>
                                    <td>
                                        @if($client->status === 'Ativo')
                                            <span class="chip green white-text">Ativo</span>
                                        @else
                                            <span class="chip grey white-text">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('clientes.show', $client) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Detalhes">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Não há clientes cadastrados para este tipo de estabelecimento.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
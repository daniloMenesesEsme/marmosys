@extends('layouts.app')

@section('title', 'Detalhes da Localidade')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Detalhes da Localidade: {{ $location->name }}</h4>
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
                                <td>{{ $location->name }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($location->status === 'active')
                                        <span class="chip green white-text">Ativo</span>
                                    @else
                                        <span class="chip grey white-text">Inativo</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Nível:</th>
                                <td>
                                    @if($location->parent_id)
                                        <span class="chip">Subnível</span>
                                    @else
                                        <span class="chip blue white-text">Principal</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Localidade Superior:</th>
                                <td>
                                    @if($location->parent)
                                        <a href="{{ route('locations.show', $location->parent) }}">
                                            {{ $location->parent->name }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>{{ $location->state ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Cidade:</th>
                                <td>{{ $location->city ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Coordenadas:</th>
                                <td>
                                    @if($location->latitude && $location->longitude)
                                        {{ $location->latitude }}, {{ $location->longitude }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Data de Criação:</th>
                                <td>{{ $location->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Última Atualização:</th>
                                <td>{{ $location->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-action">
                    <a href="{{ route('locations.edit', $location) }}" class="btn waves-effect waves-light orange">
                        <i class="material-icons left">edit</i>Editar
                    </a>
                    <a href="{{ route('locations.index') }}" class="btn-flat waves-effect">
                        <i class="material-icons left">arrow_back</i>Voltar
                    </a>
                </div>
            </div>

            @if($location->description)
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Descrição</span>
                    <p>{{ $location->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col s12 m8">
            @if($location->latitude && $location->longitude)
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Mapa</span>
                    <div id="map" style="height: 300px;"></div>
                </div>
            </div>
            @endif

            <!-- Sublocações -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Sublocações ({{ $location->children->count() }})</span>
                    @if($location->children->count() > 0)
                        <table class="striped responsive-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Estado/Cidade</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($location->children as $child)
                                <tr>
                                    <td>{{ $child->name }}</td>
                                    <td>
                                        @if($child->state)
                                            {{ $child->state }}
                                            @if($child->city)
                                                - {{ $child->city }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($child->status === 'active')
                                            <span class="chip green white-text">Ativo</span>
                                        @else
                                            <span class="chip grey white-text">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('locations.show', $child) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Detalhes">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Não há sublocações cadastradas.</p>
                    @endif
                </div>
                <div class="card-action">
                    <a href="{{ route('locations.create') }}?parent_id={{ $location->id }}" class="btn waves-effect waves-light">
                        <i class="material-icons left">add</i>Adicionar Sublocação
                    </a>
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
                                    <th>Tipo de Estabelecimento</th>
                                    <th>Vendedor</th>
                                    <th>Meta</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceAreas as $area)
                                <tr>
                                    <td>{{ $area->establishmentType->name ?? 'Todos' }}</td>
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
                        <p>Não há áreas de atendimento cadastradas para esta localidade.</p>
                    @endif
                </div>
                <div class="card-action">
                    <a href="{{ route('service-areas.create') }}?location_id={{ $location->id }}" class="btn waves-effect waves-light">
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
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                <tr>
                                    <td>{{ $client->nome }}</td>
                                    <td>{{ $client->establishmentType->name ?? '-' }}</td>
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
                        <p>Não há clientes cadastrados para esta localidade.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($location->latitude && $location->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
<script>
    function initMap() {
        let location = {
            lat: {{ $location->latitude }},
            lng: {{ $location->longitude }}
        };
        
        let map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: location,
        });
        
        let marker = new google.maps.Marker({
            position: location,
            map: map,
            title: "{{ $location->name }}"
        });
    }
</script>
@endif
@endsection 
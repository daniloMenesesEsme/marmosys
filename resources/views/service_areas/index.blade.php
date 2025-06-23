@extends('layouts.app')

@section('title', 'Áreas de Atendimento')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Áreas de Atendimento</h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row mb-0">
                        <form action="{{ route('service-areas.index') }}" method="GET" class="col s12">
                            <div class="row mb-0">
                                <div class="input-field col s12 m3">
                                    <input id="search" type="text" name="search" value="{{ request('search') }}">
                                    <label for="search">Buscar</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <select name="location_id" id="location_id">
                                        <option value="">Todas as localidades</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="location_id">Localidade</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <select name="establishment_type_id" id="establishment_type_id">
                                        <option value="">Todos os tipos</option>
                                        @foreach($establishmentTypes as $type)
                                            <option value="{{ $type->id }}" {{ request('establishment_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="establishment_type_id">Tipo de Estab.</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <select name="seller_id" id="seller_id">
                                        <option value="">Todos os vendedores</option>
                                        <option value="null" {{ request('seller_id') === 'null' ? 'selected' : '' }}>Sem vendedor</option>
                                        @foreach($sellers as $seller)
                                            <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                                                {{ $seller->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="seller_id">Vendedor</label>
                                </div>
                                <div class="input-field col s6 m1">
                                    <select name="status" id="status">
                                        <option value="">Todos</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                                <div class="input-field col s6 m1">
                                    <button class="btn waves-effect waves-light" type="submit">
                                        <i class="material-icons">search</i>
                                    </button>
                                    <a href="{{ route('service-areas.index') }}" class="btn-flat waves-effect">Limpar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-0">
        <div class="col s12 m6">
            <a href="{{ route('service-areas.create') }}" class="btn waves-effect waves-light">
                <i class="material-icons left">add</i>Nova Área
            </a>
            <a href="{{ route('service-areas.map') }}" class="btn blue waves-effect waves-light">
                <i class="material-icons left">map</i>Mapa
            </a>
            <a href="{{ route('service-areas.dashboard') }}" class="btn teal waves-effect waves-light">
                <i class="material-icons left">dashboard</i>Dashboard
            </a>
        </div>
        <div class="col s12 m6 right-align">
            <p>{{ $serviceAreas->total() }} área(s) encontrada(s)</p>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content p-0">
                    <table class="striped responsive-table">
                        <thead>
                            <tr>
                                <th>Localidade</th>
                                <th>Tipo de Estabelecimento</th>
                                <th>Vendedor</th>
                                <th>Meta</th>
                                <th>Clientes</th>
                                <th>Status</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($serviceAreas as $area)
                                <tr>
                                    <td>
                                        <a href="{{ route('locations.show', $area->location) }}">
                                            {{ $area->location->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($area->establishmentType)
                                            <a href="{{ route('establishment-types.show', $area->establishmentType) }}">
                                                {{ $area->establishmentType->name }}
                                            </a>
                                        @else
                                            <span class="grey-text">Todos</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($area->seller)
                                            <a href="{{ route('sellers.show', $area->seller) }}">
                                                {{ $area->seller->nome }}
                                            </a>
                                        @else
                                            <span class="red-text">Não atribuído</span>
                                        @endif
                                    </td>
                                    <td>{{ $area->goal_amount ? 'R$ ' . number_format($area->goal_amount, 2, ',', '.') : '-' }}</td>
                                    <td>
                                        @php
                                            $clientCount = \App\Models\Client::where('location_id', $area->location_id)
                                                ->when($area->establishment_type_id, function($query) use ($area) {
                                                    return $query->where('establishment_type_id', $area->establishment_type_id);
                                                })
                                                ->count();
                                        @endphp
                                        {{ $clientCount }}
                                    </td>
                                    <td>
                                        @if($area->status === 'active')
                                            <span class="chip green white-text">Ativo</span>
                                        @else
                                            <span class="chip grey white-text">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('service-areas.show', $area) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Detalhes">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="{{ route('service-areas.edit', $area) }}" class="btn-floating btn-small waves-effect waves-light orange" title="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <form action="{{ route('service-areas.destroy', $area) }}" method="POST" style="display:inline" onsubmit="return confirm('Tem certeza que deseja excluir esta área de atendimento?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-floating btn-small waves-effect waves-light red" title="Excluir">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="center-align">Nenhuma área de atendimento encontrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{ $serviceAreas->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);
    });
</script>
@endsection 
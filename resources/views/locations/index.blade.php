@extends('layouts.app')

@section('title', 'Localidades')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Localidades</h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row mb-0">
                        <form action="{{ route('locations.index') }}" method="GET" class="col s12">
                            <div class="row mb-0">
                                <div class="input-field col s12 m4">
                                    <input id="search" type="text" name="search" value="{{ request('search') }}">
                                    <label for="search">Buscar por nome, estado ou cidade</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <select name="parent_id">
                                        <option value="">Todos os níveis</option>
                                        <option value="0" {{ request('parent_id') === '0' ? 'selected' : '' }}>Apenas níveis principais</option>
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Nível hierárquico</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <select name="status">
                                        <option value="">Todos os status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                                    </select>
                                    <label>Status</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <button class="btn waves-effect waves-light" type="submit">
                                        <i class="material-icons left">search</i>Filtrar
                                    </button>
                                    <a href="{{ route('locations.index') }}" class="btn-flat waves-effect">Limpar</a>
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
            <a href="{{ route('locations.create') }}" class="btn waves-effect waves-light">
                <i class="material-icons left">add</i>Nova Localidade
            </a>
            <a href="{{ route('locations.dashboard') }}" class="btn blue waves-effect waves-light">
                <i class="material-icons left">dashboard</i>Dashboard
            </a>
        </div>
        <div class="col s12 m6 right-align">
            <p>{{ $locations->total() }} localidade(s) encontrada(s)</p>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content p-0">
                    <table class="striped responsive-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Nível</th>
                                <th>Hierarquia</th>
                                <th>Estado/Cidade</th>
                                <th>Áreas de Atendimento</th>
                                <th>Clientes</th>
                                <th>Status</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($locations as $location)
                                <tr>
                                    <td>{{ $location->name }}</td>
                                    <td>
                                        @if($location->parent_id)
                                            <span class="chip">Subnível</span>
                                        @else
                                            <span class="chip blue white-text">Principal</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($location->parent)
                                            {{ $location->parent->name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($location->state)
                                            {{ $location->state }}
                                            @if($location->city)
                                                - {{ $location->city }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $location->service_areas_count ?? 0 }}</td>
                                    <td>{{ $location->clients_count ?? 0 }}</td>
                                    <td>
                                        @if($location->status === 'active')
                                            <span class="chip green white-text">Ativo</span>
                                        @else
                                            <span class="chip grey white-text">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('locations.show', $location) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Detalhes">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="{{ route('locations.edit', $location) }}" class="btn-floating btn-small waves-effect waves-light orange" title="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <form action="{{ route('locations.destroy', $location) }}" method="POST" style="display:inline" onsubmit="return confirm('Tem certeza que deseja excluir esta localidade?')">
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
                                    <td colspan="8" class="center-align">Nenhuma localidade encontrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{ $locations->links() }}
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
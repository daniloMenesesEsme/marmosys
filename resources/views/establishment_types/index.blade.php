@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Tipos de Estabelecimento')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Tipos de Estabelecimento</h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row mb-0">
                        <form action="{{ route('establishment-types.index') }}" method="GET" class="col s12">
                            <div class="row mb-0">
                                <div class="input-field col s12 m6">
                                    <input id="search" type="text" name="search" value="{{ request('search') }}">
                                    <label for="search">Buscar por nome</label>
                                </div>
                                <div class="input-field col s12 m4">
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
                                    <a href="{{ route('establishment-types.index') }}" class="btn-flat waves-effect">Limpar</a>
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
            <a href="{{ route('establishment-types.create') }}" class="btn waves-effect waves-light">
                <i class="material-icons left">add</i>Novo Tipo
            </a>
            <a href="{{ route('establishment-types.dashboard') }}" class="btn blue waves-effect waves-light">
                <i class="material-icons left">dashboard</i>Dashboard
            </a>
        </div>
        <div class="col s12 m6 right-align">
            <p>{{ $establishmentTypes->total() }} tipo(s) encontrado(s)</p>
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
                                <th>Descrição</th>
                                <th>Clientes</th>
                                <th>Áreas de Atendimento</th>
                                <th>Status</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($establishmentTypes as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ Str::limit($type->description, 50) }}</td>
                                    <td>{{ $type->clients_count ?? 0 }}</td>
                                    <td>{{ $type->service_areas_count ?? 0 }}</td>
                                    <td>
                                        @if($type->status === 'active')
                                            <span class="chip green white-text">Ativo</span>
                                        @else
                                            <span class="chip grey white-text">Inativo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('establishment-types.show', $type) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Detalhes">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="{{ route('establishment-types.edit', $type) }}" class="btn-floating btn-small waves-effect waves-light orange" title="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <form action="{{ route('establishment-types.destroy', $type) }}" method="POST" style="display:inline" onsubmit="return confirm('Tem certeza que deseja excluir este tipo de estabelecimento?')">
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
                                    <td colspan="6" class="center-align">Nenhum tipo de estabelecimento encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{ $establishmentTypes->links() }}
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
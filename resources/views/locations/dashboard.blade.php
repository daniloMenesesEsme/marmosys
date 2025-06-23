@extends('layouts.app')

@section('title', 'Dashboard de Localidades')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card-panel">
                <h4 class="header">Dashboard de Localidades</h4>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="row">
        <div class="col s12">
            <div class="card-panel red white-text">
                {{ session('error') }}
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Estatísticas Gerais -->
        <div class="col s12 m4">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Total de Localidades</span>
                    <p class="center-align" style="font-size: 2em;">{{ $totalLocations ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col s12 m4">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Localidades Ativas</span>
                    <p class="center-align" style="font-size: 2em;">{{ $activeLocations ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col s12 m4">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Localidades Inativas</span>
                    <p class="center-align" style="font-size: 2em;">{{ $inactiveLocations ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Últimas Localidades -->
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Últimas Localidades Cadastradas</span>
                    <table class="striped highlight responsive-table">
                        <thead>
                            <tr>
                                <th>Bairro</th>
                                <th>Cidade</th>
                                <th>Estado</th>
                                <th>Status</th>
                                <th>Data de Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentLocations as $location)
                            <tr>
                                <td>{{ $location['name'] }}</td>
                                <td>{{ $location['city'] }}</td>
                                <td>{{ $location['state'] }}</td>
                                <td>
                                    @if($location['status'] === 'active')
                                        <span class="new badge green" data-badge-caption="">Ativo</span>
                                    @else
                                        <span class="new badge grey" data-badge-caption="">Inativo</span>
                                    @endif
                                </td>
                                <td>{{ $location['created_at']->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('locations.show', $location['id']) }}" class="btn-floating btn-small waves-effect waves-light blue" title="Detalhes">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="center-align">Nenhuma localidade cadastrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicialização de componentes do Materialize
    M.AutoInit();
});
</script>
@endpush 
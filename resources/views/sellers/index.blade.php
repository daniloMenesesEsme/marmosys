@extends('layouts.app')

@section('title', 'Vendedores')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        <div class="row">
                            <div class="col s12 m6">
                                <h4><i class="material-icons left">people</i> Vendedores</h4>
                            </div>
                            <div class="col s12 m6 right-align">
                                <a href="{{ route('sellers.create') }}" class="btn-floating btn-large waves-effect waves-light green tooltipped" data-position="left" data-tooltip="Novo Vendedor">
                                    <i class="material-icons">add</i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row">
                        <form action="{{ route('sellers.index') }}" method="GET" class="col s12">
                            <div class="row">
                                <div class="input-field col s12 m5">
                                    <i class="material-icons prefix">search</i>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}">
                                    <label for="search">Buscar por Nome, CPF, Email ou Telefone</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <select name="status" id="status">
                                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Todos</option>
                                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativos</option>
                                        <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativos</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                                <div class="input-field col s12 m4">
                                    <button type="submit" class="btn waves-effect waves-light">
                                        <i class="material-icons left">search</i>
                                        Filtrar
                                    </button>
                                    <a href="{{ route('sellers.index') }}" class="btn waves-effect waves-light red">
                                        <i class="material-icons left">clear</i>
                                        Limpar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela de Vendedores -->
                    <div class="row">
                        <div class="col s12">
                            @if($sellers->count() > 0)
                                <table class="striped responsive-table">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Contato</th>
                                            <th>Comissão (%)</th>
                                            <th>Meta Mensal</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sellers as $seller)
                                            <tr>
                                                <td>{{ $seller->nome }}</td>
                                                <td>
                                                    {{ $seller->telefone }}<br>
                                                    {{ $seller->email }}
                                                </td>
                                                <td>{{ number_format($seller->percentual_comissao, 2) }}%</td>
                                                <td>R$ {{ number_format($seller->meta_mensal, 2, ',', '.') }}</td>
                                                <td>
                                                    <span class="chip {{ $seller->ativo ? 'green white-text' : 'red white-text' }}">
                                                        {{ $seller->ativo ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                </td>
                                                <td class="action-buttons">
                                                    <a href="{{ route('sellers.show', $seller) }}" class="btn-floating waves-effect waves-light blue tooltipped" data-position="top" data-tooltip="Visualizar">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    
                                                    <a href="{{ route('sellers.edit', $seller) }}" class="btn-floating waves-effect waves-light amber tooltipped" data-position="top" data-tooltip="Editar">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                    
                                                    <form action="{{ route('sellers.destroy', $seller) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir este vendedor?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-floating waves-effect waves-light red tooltipped" data-position="top" data-tooltip="Excluir">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <!-- Paginação -->
                                <div class="row">
                                    <div class="col s12">
                                        {{ $sellers->appends(request()->query())->links('vendor.pagination.materialize') }}
                                    </div>
                                </div>
                            @else
                                <div class="card-panel blue-grey lighten-4">
                                    <span class="blue-text text-darken-2">
                                        <i class="material-icons left">info</i>
                                        Nenhum vendedor encontrado. 
                                        <a href="{{ route('sellers.create') }}" class="btn-flat blue-text text-darken-2 waves-effect">Cadastrar novo vendedor</a>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa os selects
        var selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);
        
        // Inicializa os tooltips
        var tooltips = document.querySelectorAll('.tooltipped');
        M.Tooltip.init(tooltips);
        
        // Mensagens de feedback
        @if(session('success'))
            M.toast({html: '{{ session("success") }}', classes: 'green'});
        @endif
        
        @if(session('error'))
            M.toast({html: '{{ session("error") }}', classes: 'red'});
        @endif
    });
</script>
@endpush

@push('styles')
<style>
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    
    .card-title h4 {
        margin-top: 0;
        margin-bottom: 0;
    }
</style>
@endpush 
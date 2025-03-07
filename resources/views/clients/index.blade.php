@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Clientes</span>
                    
                    @if(session('success'))
                        <div class="chip green white-text">
                            {{ session('success') }}
                            <i class="close material-icons">close</i>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col s12">
                            <a href="{{ route('clients.create') }}" class="btn-floating btn-large waves-effect waves-light blue right">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                    </div>

                    <table class="striped responsive-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CPF/CNPJ</th>
                                <th>Telefone</th>
                                <th>E-mail</th>
                                <th>Cidade/UF</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td>{{ $client->nome }}</td>
                                    <td>{{ $client->formatted_cpf_cnpj }}</td>
                                    <td>{{ $client->formatted_telefone }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->cidade }}/{{ $client->estado }}</td>
                                    <td>
                                        <span class="chip {{ $client->ativo ? 'green' : 'grey' }} white-text">
                                            {{ $client->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="row" style="margin-bottom: 0;">
                                            <a href="{{ route('clients.show', $client) }}" class="btn-floating waves-effect waves-light blue">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="{{ route('clients.edit', $client) }}" class="btn-floating waves-effect waves-light orange">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-floating waves-effect waves-light red">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="center-align">Nenhum cliente cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col s12">
                            {{ $clients->links('vendor.pagination.materialize') }}
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
        var chips = document.querySelectorAll('.chip');
        chips.forEach(function(chip) {
            chip.querySelector('.close')?.addEventListener('click', function() {
                chip.remove();
            });
        });
    });
</script>
@endpush 
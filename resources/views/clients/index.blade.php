@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Clientes
                    <a href="#modal-novo" class="btn-floating btn waves-effect waves-light right modal-trigger">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>CPF/CNPJ</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>{{ $client->nome }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->telefone }}</td>
                                <td>{{ $client->cpf_cnpj }}</td>
                                <td>
                                    @if($client->ativo)
                                        <span class="new badge green" data-badge-caption="">Ativo</span>
                                    @else
                                        <span class="new badge grey" data-badge-caption="">Inativo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#modal-editar-{{ $client->id }}" class="btn-small waves-effect waves-light modal-trigger">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    @if($client->ativo)
                                        <a href="#modal-deletar-{{ $client->id }}" class="btn-small red waves-effect waves-light modal-trigger">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="center">Nenhum cliente cadastrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $clients->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Cliente -->
<div id="modal-novo" class="modal">
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <h4>Novo Cliente</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="nome" name="nome" required>
                    <label for="nome">Nome</label>
                </div>
                <div class="input-field col s12">
                    <input type="email" id="email" name="email">
                    <label for="email">Email</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" id="telefone" name="telefone">
                    <label for="telefone">Telefone</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" id="cpf_cnpj" name="cpf_cnpj">
                    <label for="cpf_cnpj">CPF/CNPJ</label>
                </div>
                <div class="input-field col s12">
                    <textarea id="endereco" name="endereco" class="materialize-textarea"></textarea>
                    <label for="endereco">Endereço</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
        </div>
    </form>
</div>

<!-- Modais de Edição -->
@foreach($clients as $client)
    <div id="modal-editar-{{ $client->id }}" class="modal">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <h4>Editar Cliente</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="nome-{{ $client->id }}" name="nome" value="{{ $client->nome }}" required>
                        <label for="nome-{{ $client->id }}">Nome</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="email" id="email-{{ $client->id }}" name="email" value="{{ $client->email }}">
                        <label for="email-{{ $client->id }}">Email</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" id="telefone-{{ $client->id }}" name="telefone" value="{{ $client->telefone }}">
                        <label for="telefone-{{ $client->id }}">Telefone</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" id="cpf_cnpj-{{ $client->id }}" name="cpf_cnpj" value="{{ $client->cpf_cnpj }}">
                        <label for="cpf_cnpj-{{ $client->id }}">CPF/CNPJ</label>
                    </div>
                    <div class="input-field col s12">
                        <textarea id="endereco-{{ $client->id }}" name="endereco" class="materialize-textarea">{{ $client->endereco }}</textarea>
                        <label for="endereco-{{ $client->id }}">Endereço</label>
                    </div>
                    <div class="input-field col s12">
                        <label>
                            <input type="checkbox" name="ativo" value="1" {{ $client->ativo ? 'checked' : '' }}>
                            <span>Ativo</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
            </div>
        </form>
    </div>

    <!-- Modal de Exclusão -->
    <div id="modal-deletar-{{ $client->id }}" class="modal">
        <form action="{{ route('clients.destroy', $client) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja desativar o cliente "{{ $client->nome }}"?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Confirmar</button>
            </div>
        </form>
    </div>
@endforeach

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);
    });
</script>
@endpush
@endsection 
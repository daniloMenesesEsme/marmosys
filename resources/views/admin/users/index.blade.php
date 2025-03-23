@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Usuários</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="{{ route('admin.users.create') }}" class="btn waves-effect waves-light">
                            <i class="material-icons left">add</i>
                            Novo Usuário
                        </a>
                    </div>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-small orange">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-small red" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
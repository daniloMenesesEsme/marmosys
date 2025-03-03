@extends('layouts.app')

@section('title', 'Empresas')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Empresas
                    <a href="{{ route('companies.create') }}" class="btn-floating btn-large waves-effect waves-light right">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                @if(session('success'))
                    <div class="card-panel green white-text">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Razão Social</th>
                            <th>Nome Fantasia</th>
                            <th>CNPJ</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>{{ $company->razao_social }}</td>
                                <td>{{ $company->nome_fantasia }}</td>
                                <td>{{ $company->cnpj }}</td>
                                <td>{{ $company->telefone }}</td>
                                <td>{{ $company->email }}</td>
                                <td>
                                    <span class="chip {{ $company->ativo ? 'green' : 'red' }} white-text">
                                        {{ $company->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('companies.edit', $company) }}" class="btn-small waves-effect waves-light orange">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <form action="{{ route('companies.destroy', $company) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-small waves-effect waves-light red" onclick="return confirm('Tem certeza que deseja excluir esta empresa?')">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="center">Nenhuma empresa cadastrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $companies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
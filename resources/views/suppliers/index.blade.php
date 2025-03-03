@extends('layouts.app')

@section('title', 'Fornecedores')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Fornecedores</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="{{ route('suppliers.create') }}" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>Novo Fornecedor
                        </a>
                    </div>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Razão Social</th>
                            <th>Nome Fantasia</th>
                            <th>CNPJ</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Cidade/UF</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->razao_social }}</td>
                            <td>{{ $supplier->nome_fantasia }}</td>
                            <td>{{ $supplier->cnpj }}</td>
                            <td>{{ $supplier->telefone }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>{{ $supplier->cidade }}/{{ $supplier->estado }}</td>
                            <td>
                                <span class="chip {{ $supplier->ativo ? 'green white-text' : 'red white-text' }}">
                                    {{ $supplier->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('suppliers.show', $supplier) }}" class="btn-small waves-effect waves-light">
                                    <i class="material-icons">visibility</i>
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-small waves-effect waves-light orange">
                                    <i class="material-icons">edit</i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small waves-effect waves-light red" onclick="return confirm('Tem certeza que deseja {{ $supplier->ativo ? 'inativar' : 'ativar' }} este fornecedor?')">
                                        <i class="material-icons">{{ $supplier->ativo ? 'delete' : 'restore' }}</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="center-align">Nenhum fornecedor cadastrado</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginação -->
                <div class="row">
                    <div class="col s12">
                        {{ $suppliers->links('vendor.pagination.materialize') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@extends('layouts.app')

@section('title', 'Detalhes do Fornecedor')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <h4 class="header">
                <i class="material-icons left">business</i>
                Detalhes do Fornecedor
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">
                            <span class="chip {{ $supplier->ativo ? 'green' : 'red' }} white-text right">
                                {{ $supplier->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Dados Principais -->
                        <div class="col s12 m6">
                            <h5>Dados Principais</h5>
                            <table class="striped">
                                <tbody>
                                    <tr>
                                        <th>CNPJ:</th>
                                        <td>{{ $supplier->formatted_cnpj }}</td>
                                    </tr>
                                    <tr>
                                        <th>Razão Social:</th>
                                        <td>{{ $supplier->razao_social }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nome Fantasia:</th>
                                        <td>{{ $supplier->nome_fantasia }}</td>
                                    </tr>
                                    <tr>
                                        <th>Inscrição Estadual:</th>
                                        <td>{{ $supplier->inscricao_estadual ?: 'Não informado' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Inscrição Municipal:</th>
                                        <td>{{ $supplier->inscricao_municipal ?: 'Não informado' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Contato -->
                        <div class="col s12 m6">
                            <h5>Contato</h5>
                            <table class="striped">
                                <tbody>
                                    <tr>
                                        <th>Telefone:</th>
                                        <td>{{ $supplier->formatted_telefone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $supplier->email }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Endereço -->
                        <div class="col s12">
                            <h5>Endereço</h5>
                            <table class="striped">
                                <tbody>
                                    <tr>
                                        <th width="200">CEP:</th>
                                        <td>{{ $supplier->formatted_cep }}</td>
                                    </tr>
                                    <tr>
                                        <th>Endereço:</th>
                                        <td>{{ $supplier->endereco }}, {{ $supplier->numero }}{{ $supplier->complemento ? ' - '.$supplier->complemento : '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bairro:</th>
                                        <td>{{ $supplier->bairro }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cidade/UF:</th>
                                        <td>{{ $supplier->cidade }}/{{ $supplier->estado }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($supplier->observacoes)
                    <div class="row">
                        <!-- Observações -->
                        <div class="col s12">
                            <h5>Observações</h5>
                            <div class="card-panel grey lighten-4">
                                {{ $supplier->observacoes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col s12 center-align">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn waves-effect waves-light orange">
                                <i class="material-icons left">edit</i>
                                Editar
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja {{ $supplier->ativo ? 'inativar' : 'ativar' }} este fornecedor?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn waves-effect waves-light {{ $supplier->ativo ? 'red' : 'green' }}">
                                    <i class="material-icons left">{{ $supplier->ativo ? 'block' : 'check' }}</i>
                                    {{ $supplier->ativo ? 'Inativar' : 'Ativar' }}
                                </button>
                            </form>
                            <a href="{{ route('suppliers.index') }}" class="btn waves-effect waves-light grey">
                                <i class="material-icons left">arrow_back</i>
                                Voltar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
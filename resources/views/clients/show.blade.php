@extends('layouts.app')

@section('title', 'Detalhes do Cliente')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">{{ $client->nome }}</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="{{ route('clients.edit', $client) }}" class="btn waves-effect waves-light orange">
                            <i class="material-icons left">edit</i>Editar
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6">
                        <h6>Informações Pessoais</h6>
                        <table class="striped">
                            <tbody>
                                <tr>
                                    <th>Nome/Razão Social:</th>
                                    <td>{{ $client->nome }}</td>
                                </tr>
                                <tr>
                                    <th>Tipo:</th>
                                    <td>{{ $client->tipo_pessoa == 'F' ? 'Pessoa Física' : 'Pessoa Jurídica' }}</td>
                                </tr>
                                <tr>
                                    <th>CPF/CNPJ:</th>
                                    <td>{{ $client->documento }}</td>
                                </tr>
                                <tr>
                                    <th>Telefone:</th>
                                    <td>{{ $client->telefone }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $client->email ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="chip {{ $client->ativo ? 'green white-text' : 'red white-text' }}">
                                            {{ $client->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col s12 m6">
                        <h6>Endereço</h6>
                        <table class="striped">
                            <tbody>
                                <tr>
                                    <th>CEP:</th>
                                    <td>{{ $client->cep }}</td>
                                </tr>
                                <tr>
                                    <th>Endereço:</th>
                                    <td>{{ $client->endereco }}, {{ $client->numero }}</td>
                                </tr>
                                <tr>
                                    <th>Complemento:</th>
                                    <td>{{ $client->complemento ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Bairro:</th>
                                    <td>{{ $client->bairro }}</td>
                                </tr>
                                <tr>
                                    <th>Cidade/UF:</th>
                                    <td>{{ $client->cidade }}/{{ $client->estado }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($client->observacoes)
                <div class="row">
                    <div class="col s12">
                        <h6>Observações</h6>
                        <p class="grey-text">{{ $client->observacoes }}</p>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col s12">
                        <h6>Últimos Orçamentos</h6>
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Número</th>
                                    <th>Valor Total</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($client->budgets()->latest()->take(5)->get() as $budget)
                                <tr>
                                    <td>{{ $budget->created_at->format('d/m/Y') }}</td>
                                    <td>#{{ str_pad($budget->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="chip {{ $budget->status_class }}">
                                            {{ $budget->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('budgets.show', $budget) }}" class="btn-small waves-effect waves-light">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="center-align">Nenhum orçamento encontrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="right-align" style="margin-top: 10px;">
                            <a href="{{ route('financial.budgets.create', ['client_id' => $client->id]) }}" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">add</i>Novo Orçamento
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <a href="{{ route('clients.index') }}" class="btn waves-effect waves-light grey">
                            <i class="material-icons left">arrow_back</i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
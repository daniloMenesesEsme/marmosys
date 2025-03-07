@extends('layouts.app')

@section('title', 'Detalhes do Cliente')

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Detalhes do Cliente</span>

                    <div class="row">
                        <div class="col s12">
                            <h5 class="blue-text">{{ $client->nome }}</h5>
                            <span class="chip {{ $client->ativo ? 'green' : 'grey' }} white-text">
                                {{ $client->status }}
                            </span>

                            <div class="section">
                                <h6 class="blue-text">Informações Pessoais</h6>
                                <div class="row">
                                    <div class="col s12 m4">
                                        <p>
                                            <i class="material-icons tiny">pin</i>
                                            <strong>CPF/CNPJ:</strong><br>
                                            {{ $client->formatted_cpf_cnpj ?: 'Não informado' }}
                                        </p>
                                    </div>
                                    <div class="col s12 m4">
                                        <p>
                                            <i class="material-icons tiny">badge</i>
                                            <strong>RG:</strong><br>
                                            {{ $client->rg ?: 'Não informado' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="section">
                                <h6 class="blue-text">Contato</h6>
                                <div class="row">
                                    <div class="col s12 m6">
                                        <p>
                                            <i class="material-icons tiny">phone</i>
                                            <strong>Telefone:</strong><br>
                                            {{ $client->formatted_telefone ?: 'Não informado' }}
                                        </p>
                                    </div>
                                    <div class="col s12 m6">
                                        <p>
                                            <i class="material-icons tiny">email</i>
                                            <strong>E-mail:</strong><br>
                                            {{ $client->email ? '<a href="mailto:' . $client->email . '" class="blue-text">' . $client->email . '</a>' : 'Não informado' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="section">
                                <h6 class="blue-text">Endereço</h6>
                                <p>
                                    <i class="material-icons tiny">location_on</i>
                                    {{ $client->endereco }}
                                    @if($client->numero), {{ $client->numero }}@endif
                                    @if($client->complemento) - {{ $client->complemento }}@endif
                                    @if($client->bairro), {{ $client->bairro }}@endif<br>
                                    @if($client->cidade){{ $client->cidade }}@endif
                                    @if($client->estado) - {{ $client->estado }}@endif
                                    @if($client->cep)<br>CEP: {{ $client->cep }}@endif
                                </p>
                            </div>

                            @if($client->observacoes)
                                <div class="section">
                                    <h6 class="blue-text">Observações</h6>
                                    <p class="grey-text text-darken-2">{{ $client->observacoes }}</p>
                                </div>
                            @endif

                            <div class="section">
                                <h6 class="blue-text">Informações do Sistema</h6>
                                <div class="row">
                                    <div class="col s12 m4">
                                        <p>
                                            <strong>Cadastrado em:</strong><br>
                                            {{ $client->created_at->format('d/m/Y H:i:s') }}
                                        </p>
                                    </div>
                                    <div class="col s12 m4">
                                        <p>
                                            <strong>Última atualização:</strong><br>
                                            {{ $client->updated_at->format('d/m/Y H:i:s') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <a href="{{ route('clients.edit', $client) }}" class="btn waves-effect waves-light orange">
                                <i class="material-icons left">edit</i>
                                Editar
                            </a>
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn waves-effect waves-light red">
                                    <i class="material-icons left">delete</i>
                                    Excluir
                                </button>
                            </form>
                            <a href="{{ route('clients.index') }}" class="btn waves-effect waves-light grey right">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa os componentes do Materialize
        M.AutoInit();
    });
</script>
@endpush 
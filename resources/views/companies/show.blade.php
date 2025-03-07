@extends('layouts.app')

@section('title', 'Detalhes da Empresa')

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Detalhes da Empresa</span>

                    <div class="row">
                        <div class="col s12 m8">
                            <h5 class="blue-text">{{ $company->nome_fantasia }}</h5>
                            @if($company->razao_social)
                                <p class="grey-text">{{ $company->razao_social }}</p>
                            @endif

                            <div class="section">
                                <h6 class="blue-text">Informações Fiscais</h6>
                                <div class="row">
                                    <div class="col s12 m4">
                                        <p><i class="material-icons tiny">pin</i> <strong>CNPJ:</strong><br>
                                        {{ $company->formatted_cnpj ?: 'Não informado' }}</p>
                                    </div>
                                    <div class="col s12 m4">
                                        <p><i class="material-icons tiny">badge</i> <strong>Inscrição Estadual:</strong><br>
                                        {{ $company->inscricao_estadual ?: 'Não informado' }}</p>
                                    </div>
                                    <div class="col s12 m4">
                                        <p><i class="material-icons tiny">badge</i> <strong>Inscrição Municipal:</strong><br>
                                        {{ $company->inscricao_municipal ?: 'Não informado' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="section">
                                <h6 class="blue-text">Contato</h6>
                                <div class="row">
                                    <div class="col s12 m4">
                                        <p><i class="material-icons tiny">phone</i> <strong>Telefone:</strong><br>
                                        {{ $company->formatted_telefone ?: 'Não informado' }}</p>
                                    </div>
                                    <div class="col s12 m4">
                                        <p><i class="material-icons tiny">smartphone</i> <strong>Celular:</strong><br>
                                        {{ $company->celular ?: 'Não informado' }}</p>
                                    </div>
                                    <div class="col s12 m4">
                                        <p><i class="material-icons tiny">email</i> <strong>E-mail:</strong><br>
                                        {{ $company->email ?: 'Não informado' }}</p>
                                    </div>
                                </div>
                                @if($company->website)
                                    <p><i class="material-icons tiny">language</i> <strong>Website:</strong><br>
                                    <a href="{{ $company->website }}" target="_blank" class="blue-text">{{ $company->website }}</a></p>
                                @endif
                            </div>

                            <div class="section">
                                <h6 class="blue-text">Endereço</h6>
                                <p><i class="material-icons tiny">location_on</i> 
                                    {{ $company->logradouro }}
                                    @if($company->numero), {{ $company->numero }}@endif
                                    @if($company->complemento) - {{ $company->complemento }}@endif
                                    @if($company->bairro), {{ $company->bairro }}@endif<br>
                                    @if($company->cidade){{ $company->cidade }}@endif
                                    @if($company->estado) - {{ $company->estado }}@endif
                                    @if($company->cep)<br>CEP: {{ $company->cep }}@endif
                                </p>
                            </div>

                            @if($company->observacoes)
                                <div class="section">
                                    <h6 class="blue-text">Observações</h6>
                                    <p class="grey-text text-darken-2">{{ $company->observacoes }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="col s12 m4">
                            <div class="card">
                                <div class="card-content center-align">
                                    @if($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="responsive-img materialboxed" style="max-height: 200px;">
                                    @else
                                        <i class="material-icons grey-text" style="font-size: 8rem;">business</i>
                                        <p class="grey-text">Sem logo</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <a href="{{ route('companies.edit', $company) }}" class="btn waves-effect waves-light orange">
                                <i class="material-icons left">edit</i>
                                Editar
                            </a>
                            <form action="{{ route('companies.destroy', $company) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta empresa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn waves-effect waves-light red">
                                    <i class="material-icons left">delete</i>
                                    Excluir
                                </button>
                            </form>
                            <a href="{{ route('companies.index') }}" class="btn waves-effect waves-light grey right">
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
        // Inicializa o efeito de zoom na imagem
        $('.materialboxed').materialbox();
    });
</script>
@endpush 
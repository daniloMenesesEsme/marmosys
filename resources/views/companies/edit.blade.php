@extends('layouts.app')

@section('title', 'Editar Empresa')

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Editar Empresa</span>

                    @if($errors->any())
                        <div class="card-panel red lighten-4 red-text text-darken-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('companies.update', $company) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">business</i>
                                <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia', $company->nome_fantasia) }}" required>
                                <label for="nome_fantasia">Nome Fantasia *</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">description</i>
                                <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social', $company->razao_social) }}">
                                <label for="razao_social">Razão Social</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">pin</i>
                                <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj', $company->formatted_cnpj) }}" class="cnpj">
                                <label for="cnpj">CNPJ</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">badge</i>
                                <input type="text" id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual', $company->inscricao_estadual) }}">
                                <label for="inscricao_estadual">Inscrição Estadual</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">badge</i>
                                <input type="text" id="inscricao_municipal" name="inscricao_municipal" value="{{ old('inscricao_municipal', $company->inscricao_municipal) }}">
                                <label for="inscricao_municipal">Inscrição Municipal</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">phone</i>
                                <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $company->formatted_telefone) }}" class="phone">
                                <label for="telefone">Telefone</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">smartphone</i>
                                <input type="text" id="celular" name="celular" value="{{ old('celular', $company->celular) }}" class="celphone">
                                <label for="celular">Celular</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">email</i>
                                <input type="email" id="email" name="email" value="{{ old('email', $company->email) }}">
                                <label for="email">E-mail</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">language</i>
                                <input type="text" id="website" name="website" value="{{ old('website', $company->website) }}">
                                <label for="website">Website</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">location_on</i>
                                <input type="text" id="cep" name="cep" value="{{ old('cep', $company->cep) }}" class="cep">
                                <label for="cep">CEP</label>
                            </div>
                            <div class="input-field col s12 m7">
                                <i class="material-icons prefix">home</i>
                                <input type="text" id="logradouro" name="logradouro" value="{{ old('logradouro', $company->logradouro) }}">
                                <label for="logradouro">Logradouro</label>
                            </div>
                            <div class="input-field col s12 m2">
                                <input type="text" id="numero" name="numero" value="{{ old('numero', $company->numero) }}">
                                <label for="numero">Número</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <input type="text" id="complemento" name="complemento" value="{{ old('complemento', $company->complemento) }}">
                                <label for="complemento">Complemento</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <input type="text" id="bairro" name="bairro" value="{{ old('bairro', $company->bairro) }}">
                                <label for="bairro">Bairro</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $company->cidade) }}">
                                <label for="cidade">Cidade</label>
                            </div>
                            <div class="input-field col s12 m1">
                                <input type="text" id="estado" name="estado" value="{{ old('estado', $company->estado) }}" maxlength="2">
                                <label for="estado">UF</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">notes</i>
                                <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes', $company->observacoes) }}</textarea>
                                <label for="observacoes">Observações</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                @if($company->logo)
                                    <div class="card-panel center-align">
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo atual" style="max-height: 100px;" class="materialboxed">
                                        <p class="caption">Logo atual</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="file-field input-field col s12">
                                <div class="btn blue">
                                    <span>Alterar Logo</span>
                                    <input type="file" name="logo">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Selecione uma nova imagem">
                                </div>
                                <span class="helper-text">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn waves-effect waves-light blue right">
                                    <i class="material-icons left">save</i>
                                    Salvar Alterações
                                </button>
                                <a href="{{ route('companies.index') }}" class="btn waves-effect waves-light grey">
                                    <i class="material-icons left">arrow_back</i>
                                    Voltar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Máscaras
        $('.cnpj').mask('00.000.000/0000-00');
        $('.phone').mask('(00) 0000-0000');
        $('.celphone').mask('(00) 00000-0000');
        $('.cep').mask('00000-000');

        // Busca de CEP
        $('#cep').blur(function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep.length === 8) {
                $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                    if (!data.erro) {
                        $('#logradouro').val(data.logradouro);
                        M.updateTextFields();
                        $('#bairro').val(data.bairro);
                        M.updateTextFields();
                        $('#cidade').val(data.localidade);
                        M.updateTextFields();
                        $('#estado').val(data.uf);
                        M.updateTextFields();
                        $('#numero').focus();
                    }
                });
            }
        });

        // Inicializa os componentes do Materialize
        M.updateTextFields();
        M.textareaAutoResize($('#observacoes'));
        $('.materialboxed').materialbox(); // Para zoom na imagem do logo
    });
</script>
@endpush 
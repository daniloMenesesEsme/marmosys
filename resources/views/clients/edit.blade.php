@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Editar Cliente</span>

                    @if($errors->any())
                        <div class="card-panel red lighten-4 red-text text-darken-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('clients.update', $client) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">person</i>
                                <input type="text" id="nome" name="nome" value="{{ old('nome', $client->nome) }}" required>
                                <label for="nome">Nome *</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">email</i>
                                <input type="email" id="email" name="email" value="{{ old('email', $client->email) }}">
                                <label for="email">E-mail</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">phone</i>
                                <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $client->telefone) }}" class="phone">
                                <label for="telefone">Telefone</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">pin</i>
                                <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', $client->formatted_cpf_cnpj) }}" class="cpf_cnpj">
                                <label for="cpf_cnpj">CPF/CNPJ</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">badge</i>
                                <input type="text" id="rg_ie" name="rg_ie" value="{{ old('rg_ie', $client->rg_ie) }}">
                                <label for="rg_ie">RG/IE</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m3">
                                <i class="material-icons prefix">location_on</i>
                                <input type="text" id="cep" name="cep" value="{{ old('cep', $client->cep) }}" class="cep">
                                <label for="cep">CEP</label>
                            </div>
                            <div class="input-field col s12 m7">
                                <i class="material-icons prefix">home</i>
                                <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $client->endereco) }}">
                                <label for="endereco">Endereço</label>
                            </div>
                            <div class="input-field col s12 m2">
                                <input type="text" id="numero" name="numero" value="{{ old('numero', $client->numero) }}">
                                <label for="numero">Número</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <input type="text" id="complemento" name="complemento" value="{{ old('complemento', $client->complemento) }}">
                                <label for="complemento">Complemento</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <input type="text" id="bairro" name="bairro" value="{{ old('bairro', $client->bairro) }}">
                                <label for="bairro">Bairro</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $client->cidade) }}">
                                <label for="cidade">Cidade</label>
                            </div>
                            <div class="input-field col s12 m1">
                                <input type="text" id="estado" name="estado" value="{{ old('estado', $client->estado) }}" maxlength="2">
                                <label for="estado">UF</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">notes</i>
                                <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes', $client->observacoes) }}</textarea>
                                <label for="observacoes">Observações</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <label>
                                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $client->ativo) ? 'checked' : '' }}>
                                    <span>Cliente Ativo</span>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn waves-effect waves-light blue right">
                                    <i class="material-icons left">save</i>
                                    Salvar Alterações
                                </button>
                                <a href="{{ route('clients.index') }}" class="btn waves-effect waves-light grey">
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
        $('.phone').mask('(00) 00000-0000');
        $('.cep').mask('00000-000');
        
        // Máscara dinâmica para CPF/CNPJ
        var cpfCnpjMask = function (val) {
            return val.replace(/\D/g, '').length <= 11 ? 
                '000.000.000-00' : '00.000.000/0000-00';
        },
        cpfCnpjOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(cpfCnpjMask.apply({}, arguments), options);
            },
            onChange: function(val, e, field, options) {
                // Remove formatação para validação
                field.val(val.replace(/[^\d]/g, ''));
            }
        };
        
        $('#cpf_cnpj').mask(cpfCnpjMask, cpfCnpjOptions);

        // Busca de CEP
        $('#cep').on('input blur', function() {
            var cep = $(this).val().replace(/\D/g, '');
            
            if (cep.length === 8) {
                // Mostra loader
                M.toast({html: 'Buscando CEP...', classes: 'blue'});
                
                $.get(`https://viacep.com.br/ws/${cep}/json/`)
                    .done(function(data) {
                        if (!data.erro) {
                            preencheEndereco(data);
                            M.toast({html: 'CEP encontrado!', classes: 'green'});
                            console.log('CEP encontrado:', data); // Debug
                        } else {
                            limpaFormularioCep();
                            M.toast({html: 'CEP não encontrado!', classes: 'red'});
                        }
                    })
                    .fail(function(error) {
                        console.error('Erro:', error); // Debug
                        limpaFormularioCep();
                        M.toast({html: 'Erro ao buscar CEP!', classes: 'red'});
                    });
            }
        });

        function limpaFormularioCep() {
            $('#endereco').val('');
            $('#bairro').val('');
            $('#cidade').val('');
            $('#estado').val('');
            M.updateTextFields();
        }
        
        function preencheEndereco(data) {
            $('#endereco').val(data.logradouro);
            $('#bairro').val(data.bairro);
            $('#cidade').val(data.localidade);
            $('#estado').val(data.uf);
            
            // Atualiza as labels do Materialize
            M.updateTextFields();
            
            // Foca no campo número
            $('#numero').focus();
        }

        // Máscara dinâmica para telefone
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        
        $('#telefone').mask(SPMaskBehavior, spOptions);

        // Inicializa os componentes do Materialize
        M.updateTextFields();
        M.textareaAutoResize($('#observacoes'));
    });
</script>
@endpush 
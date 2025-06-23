@extends('layouts.app')

@section('title', 'Novo Cliente')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <h4>Cadastro de Cliente</h4>
            
            <form method="POST" action="{{ route('clients.store') }}" id="formCliente">
                @csrf
                
                <!-- Dados Principais -->
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Dados Principais</span>
                        
                        <div class="row mb-0">
                            <div class="input-field col s12 m6">
                                <div class="row mb-0">
                                    <div class="col s12" style="margin-bottom: 15px;">
                                        <label style="color: #9e9e9e; font-size: 0.8rem;">Tipo de Documento *</label>
                                        <p>
                                            <label>
                                                <input name="tipo_documento" type="radio" value="cpf" id="radioCpf" checked />
                                                <span>CPF</span>
                                            </label>
                                            <label style="margin-left: 20px;">
                                                <input name="tipo_documento" type="radio" value="cnpj" id="radioCnpj" />
                                                <span>CNPJ</span>
                                            </label>
                                        </p>
                                    </div>
                                </div>
                                
                                <input type="text" id="cpf_cnpj" name="cpf_cnpj" class="validate" required 
                                    value="{{ old('cpf_cnpj') }}" maxlength="18">
                                <label for="cpf_cnpj" id="label_cpf_cnpj">CPF *</label>
                                <span class="helper-text">Digite apenas números</span>
                                @error('cpf_cnpj')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="text" id="nome" name="nome" class="validate" required 
                                    value="{{ old('nome') }}">
                                <label for="nome" id="label_nome">Nome/Razão Social *</label>
                                @error('nome')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="input-field col s12 m6">
                                <input type="text" id="empresa" name="empresa" value="{{ old('empresa') }}">
                                <label for="empresa" id="label_empresa">Nome Fantasia</label>
                                @error('empresa')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="email" id="email" name="email" class="validate" 
                                    value="{{ old('email') }}">
                                <label for="email">E-mail</label>
                                @error('email')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="input-field col s12 m6">
                                <input type="text" id="telefone" name="telefone" class="validate" required 
                                    value="{{ old('telefone') }}" maxlength="15">
                                <label for="telefone">Telefone *</label>
                                <span class="helper-text">Ex: (85) 99999-9999</span>
                                @error('telefone')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="text" id="contato" name="contato" value="{{ old('contato') }}">
                                <label for="contato">Contato</label>
                                @error('contato')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Endereço</span>

                        <div class="row mb-0">
                            <div class="input-field col s12 m4">
                                <input type="text" id="cep" name="cep" class="validate" required 
                                    value="{{ old('cep') }}" maxlength="9">
                                <label for="cep">CEP *</label>
                                <span class="helper-text">Ex: 60442-440</span>
                                @error('cep')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="text" id="endereco" name="endereco" class="validate" required 
                                    value="{{ old('endereco') }}">
                                <label for="endereco">Logradouro *</label>
                                @error('endereco')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m2">
                                <input type="text" id="numero" name="numero" class="validate" required 
                                    value="{{ old('numero') }}">
                                <label for="numero">Número *</label>
                                @error('numero')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="input-field col s12 m4">
                                <input type="text" id="complemento" name="complemento" value="{{ old('complemento') }}">
                                <label for="complemento">Complemento</label>
                                @error('complemento')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="text" id="bairro" name="bairro" class="validate" required 
                                    value="{{ old('bairro') }}">
                                <label for="bairro">Bairro *</label>
                                @error('bairro')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="text" id="cidade" name="cidade" class="validate" required 
                                    value="{{ old('cidade') }}">
                                <label for="cidade">Cidade *</label>
                                @error('cidade')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="input-field col s12 m4">
                                <input type="text" id="estado" name="estado" class="validate" required 
                                    value="{{ old('estado') }}" maxlength="2">
                                <label for="estado">Estado (UF) *</label>
                                @error('estado')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="text" id="latitude" name="latitude" class="validate" required 
                                    value="{{ old('latitude') }}" readonly>
                                <label for="latitude">Latitude *</label>
                                @error('latitude')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="text" id="longitude" name="longitude" class="validate" required 
                                    value="{{ old('longitude') }}" readonly>
                                <label for="longitude">Longitude *</label>
                                @error('longitude')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações Adicionais -->
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Informações Adicionais</span>

                        <div class="row mb-0">
                            <div class="input-field col s12 m6">
                                <select name="location_id" id="location_id" class="validate" required>
                                    <option value="">Selecione uma localidade</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="location_id">Localidade *</label>
                                @error('location_id')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <select name="establishment_type_id" id="establishment_type_id" class="validate" required>
                                    <option value="">Selecione um tipo</option>
                                    @foreach($establishmentTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('establishment_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="establishment_type_id">Tipo de Estabelecimento *</label>
                                @error('establishment_type_id')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="input-field col s12">
                                <textarea id="observacao" name="observacao" class="materialize-textarea">{{ old('observacao') }}</textarea>
                                <label for="observacao">Observações</label>
                                @error('observacao')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <button type="submit" class="btn waves-effect waves-light">
                            Cadastrar
                            <i class="material-icons right">send</i>
                        </button>
                        
                        <a href="{{ route('clients.index') }}" class="btn waves-effect waves-light red">
                            Cancelar
                            <i class="material-icons right">cancel</i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializações do Materialize
    $('select').formSelect();
    $('.materialize-textarea').each(function() {
        M.textareaAutoResize($(this)[0]);
    });
    
    // Configuração inicial - por padrão inicia com CPF
    $('#cpf_cnpj').mask('000.000.000-00');
    
    // Controle de alteração entre CPF e CNPJ
    $('#radioCpf').on('change', function() {
        if ($(this).is(':checked')) {
            $('#cpf_cnpj').val('');
            $('#label_cpf_cnpj').text('CPF *');
            $('#cpf_cnpj').mask('000.000.000-00');
            $('#cpf_cnpj').attr('placeholder', 'Digite o CPF');
        }
    });
    
    $('#radioCnpj').on('change', function() {
        if ($(this).is(':checked')) {
            $('#cpf_cnpj').val('');
            $('#label_cpf_cnpj').text('CNPJ *');
            $('#cpf_cnpj').mask('00.000.000/0000-00');
            $('#cpf_cnpj').attr('placeholder', 'Digite o CNPJ');
            $('#label_nome').text('Razão Social *');
            $('#label_empresa').text('Nome Fantasia');
        }
    });
    
    // Máscara para telefone
    $('#telefone').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');
    
    // Variáveis globais para controle do estado
    var cnpjSituacao = null;
    var cnpjVerificado = false;
    
    // Função para mostrar toasts de notificação
    function mostrarToast(mensagem, tipo = 'info') {
        var classes = {
            'success': 'green',
            'error': 'red',
            'warning': 'orange',
            'info': 'blue'
        };
        
        M.toast({
            html: mensagem,
            classes: classes[tipo],
            displayLength: 4000
        });
    }
    
    // Função para mostrar modal de alerta
    function mostrarModal(titulo, mensagem, tipo) {
        var corTitulo = '';
        switch(tipo) {
            case 'error':
                corTitulo = 'red-text';
                break;
            case 'warning':
                corTitulo = 'orange-text';
                break;
            case 'info':
                corTitulo = 'blue-text';
                break;
            case 'success':
                corTitulo = 'green-text';
                break;
            default:
                corTitulo = 'blue-text';
        }
        
        // Cria o modal dinamicamente
        var modalHTML = `
            <div id="modalAlerta" class="modal">
                <div class="modal-content">
                    <h4 class="${corTitulo}">${titulo}</h4>
                    <p>${mensagem}</p>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-light btn-flat">Entendi</a>
                </div>
            </div>
        `;
        
        // Remove qualquer modal antigo e adiciona o novo
        $('#modalAlerta').remove();
        $('body').append(modalHTML);
        
        // Inicializa e abre o modal
        var modalElem = document.querySelector('#modalAlerta');
        var modalInstance = M.Modal.init(modalElem, {
            dismissible: true,
            inDuration: 300,
            outDuration: 200
        });
        
        modalInstance.open();
    }
    
    // Função para limpar campos do endereço
    function limparEndereco() {
        $('#endereco').val('');
        $('#bairro').val('');
        $('#cidade').val('');
        $('#estado').val('');
        $('#latitude').val('');
        $('#longitude').val('');
        M.updateTextFields();
    }
    
    // Função para buscar coordenadas geográficas
    function buscarCoordenadas(endereco) {
        mostrarToast('Buscando coordenadas...', 'info');
        
        $.ajax({
            url: 'https://nominatim.openstreetmap.org/search',
            method: 'GET',
            dataType: 'json',
            data: {
                format: 'json',
                q: endereco,
                limit: 1
            },
            success: function(data) {
                console.log('Dados de coordenadas:', data);
                
                if (data && data.length > 0) {
                    $('#latitude').val(data[0].lat);
                    $('#longitude').val(data[0].lon);
                    M.updateTextFields();
                    mostrarToast('Coordenadas encontradas!', 'success');
                } else {
                    mostrarToast('Não foi possível encontrar as coordenadas.', 'warning');
                }
            },
            error: function(error) {
                console.error('Erro ao buscar coordenadas:', error);
                mostrarToast('Erro ao buscar coordenadas.', 'error');
            }
        });
    }
    
    // Função para buscar dados do CNPJ usando BrasilAPI
    function buscarCNPJ(cnpj) {
        mostrarToast('Buscando dados do CNPJ...', 'info');
        console.log('Buscando CNPJ:', cnpj);
        
        $.ajax({
            url: 'https://brasilapi.com.br/api/cnpj/v1/' + cnpj,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Dados do CNPJ:', data);
                
                // Armazena a situação do CNPJ
                cnpjSituacao = data.situacao || null;
                cnpjVerificado = true;
                
                // Verifica situação do CNPJ
                if (cnpjSituacao && cnpjSituacao !== 'ATIVA') {
                    mostrarModal(
                        'Atenção: CNPJ não está ativo', 
                        `O CNPJ ${cnpj} está com situação "${cnpjSituacao}" na Receita Federal.<br><br>
                        Isso pode impedir o cadastro no sistema. Deseja continuar mesmo assim?`,
                        'warning'
                    );
                }
                
                // Preenche os dados principais
                $('#nome').val(data.razao_social || '');
                $('#empresa').val(data.nome_fantasia || '');
                
                // Preenche telefone se disponível
                if (data.ddd_telefone_1) {
                    var fone = data.ddd_telefone_1.replace(/\D/g, '');
                    $('#telefone').val(fone);
                }
                
                // Preenche email se disponível
                $('#email').val(data.email || '');
                
                // Atualiza os campos do Materialize
                M.updateTextFields();
                
                // Se tiver CEP, busca o endereço
                if (data.cep) {
                    var cep = data.cep.replace(/\D/g, '');
                    $('#cep').val(cep);
                    buscarCEP(cep);
                } else {
                    // Preenche o endereço diretamente
                    $('#endereco').val(data.logradouro || '');
                    $('#numero').val(data.numero || '');
                    $('#complemento').val(data.complemento || '');
                    $('#bairro').val(data.bairro || '');
                    $('#cidade').val(data.municipio || '');
                    $('#estado').val(data.uf || '');
                    M.updateTextFields();
                    
                    // Busca coordenadas se tiver dados suficientes
                    if (data.logradouro && data.municipio && data.uf) {
                        var enderecoCompleto = data.logradouro + ', ' + 
                            (data.numero || 'S/N') + ', ' + 
                            data.bairro + ', ' + 
                            data.municipio + ', ' + 
                            data.uf + ', Brasil';
                            
                        buscarCoordenadas(enderecoCompleto);
                    }
                }
                
                mostrarToast('Dados do CNPJ carregados com sucesso!', 'success');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao buscar CNPJ:', error);
                console.log('Status da requisição:', status);
                console.log('Resposta:', xhr.responseText);
                mostrarToast('Erro ao buscar CNPJ. Verifique se o número está correto.', 'error');
                
                // Reseta flags
                cnpjSituacao = null;
                cnpjVerificado = false;
            }
        });
    }
    
    // Função para buscar dados do CNPJ de forma alternativa usando ReceitaWS
    function buscarCNPJAlternativo(cnpj) {
        mostrarToast('Verificando situação do CNPJ...', 'info');
        
        $.ajax({
            url: 'https://www.receitaws.com.br/v1/cnpj/' + cnpj,
            method: 'GET',
            dataType: 'jsonp', // Usa JSONP para evitar problemas de CORS
            success: function(data) {
                console.log('Dados do CNPJ (ReceitaWS):', data);
                
                // Armazena a situação do CNPJ
                cnpjSituacao = data.situacao || null;
                cnpjVerificado = true;
                
                if (cnpjSituacao && cnpjSituacao !== 'ATIVA') {
                    mostrarModal(
                        'Atenção: CNPJ não está ativo', 
                        `O CNPJ ${cnpj} está com situação "${cnpjSituacao}" na Receita Federal.<br><br>
                        Isso pode impedir o cadastro no sistema. Deseja continuar mesmo assim?`,
                        'warning'
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao verificar situação do CNPJ (ReceitaWS):', error);
            }
        });
    }
    
    // Função para buscar endereço por CEP
    function buscarCEP(cep) {
        mostrarToast('Buscando endereço pelo CEP...', 'info');
        console.log('Buscando CEP:', cep);
        
        $.ajax({
            url: 'https://viacep.com.br/ws/' + cep + '/json/',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Dados do CEP (ViaCEP):', data);
                
                if (!data.erro) {
                    // Preenche os campos do endereço
                    $('#endereco').val(data.logradouro || '');
                    $('#bairro').val(data.bairro || '');
                    $('#cidade').val(data.localidade || '');
                    $('#estado').val(data.uf || '');
                    
                    // Foca no campo número
                    $('#numero').focus();
                    
                    // Atualiza campos do Materialize
                    M.updateTextFields();
                    
                    mostrarToast('Endereço encontrado!', 'success');
                    
                    // Busca coordenadas
                    if (data.logradouro && data.localidade && data.uf) {
                        var enderecoCompleto = data.logradouro + ', ' + 
                            ($('#numero').val() || 'S/N') + ', ' + 
                            data.bairro + ', ' + 
                            data.localidade + ', ' + 
                            data.uf + ', Brasil';
                            
                        buscarCoordenadas(enderecoCompleto);
                    }
                } else {
                    mostrarToast('CEP não encontrado!', 'error');
                    limparEndereco();
                }
            },
            error: function(error) {
                console.error('Erro ao buscar CEP (ViaCEP):', error);
                
                // Tenta com BrasilAPI como fallback
                $.ajax({
                    url: 'https://brasilapi.com.br/api/cep/v2/' + cep,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('Dados do CEP (BrasilAPI):', data);
                        
                        // Preenche os campos do endereço
                        $('#endereco').val(data.street || '');
                        $('#bairro').val(data.neighborhood || '');
                        $('#cidade').val(data.city || '');
                        $('#estado').val(data.state || '');
                        
                        // Foca no campo número
                        $('#numero').focus();
                        
                        // Atualiza campos do Materialize
                        M.updateTextFields();
                        
                        mostrarToast('Endereço encontrado!', 'success');
                        
                        // Busca coordenadas
                        if (data.street && data.city && data.state) {
                            var enderecoCompleto = data.street + ', ' + 
                                ($('#numero').val() || 'S/N') + ', ' + 
                                data.neighborhood + ', ' + 
                                data.city + ', ' + 
                                data.state + ', Brasil';
                                
                            buscarCoordenadas(enderecoCompleto);
                        }
                    },
                    error: function(error) {
                        console.error('Erro ao buscar CEP (BrasilAPI):', error);
                        mostrarToast('CEP não encontrado em nenhuma API!', 'error');
                        limparEndereco();
                    }
                });
            }
        });
    }
    
    // Evento para buscar CNPJ ao sair do campo
    $('#cpf_cnpj').on('blur', function() {
        if ($('#radioCnpj').is(':checked')) {
            var valor = $(this).val().replace(/\D/g, '');
            console.log('Valor CNPJ no blur:', valor, 'Tamanho:', valor.length);
            
            if (valor.length === 14) {
                buscarCNPJ(valor);
                // Tenta também verificar o status no ReceitaWS
                buscarCNPJAlternativo(valor);
            }
        }
    });
    
    // Evento para buscar CEP ao sair do campo
    $('#cep').on('blur', function() {
        var cep = $(this).val().replace(/\D/g, '');
        console.log('Valor CEP no blur:', cep, 'Tamanho:', cep.length);
        
        if (cep.length === 8) {
            buscarCEP(cep);
        }
    });
    
    // Atualiza coordenadas quando os campos de endereço são alterados
    $('#endereco, #numero, #bairro, #cidade, #estado').on('blur', function() {
        // Verifica se todos os campos essenciais estão preenchidos
        var endereco = $('#endereco').val();
        var numero = $('#numero').val() || 'S/N';
        var bairro = $('#bairro').val();
        var cidade = $('#cidade').val();
        var estado = $('#estado').val();
        
        if (endereco && cidade && estado) {
            var enderecoCompleto = endereco + ', ' + numero + ', ' + bairro + ', ' + cidade + ', ' + estado + ', Brasil';
            buscarCoordenadas(enderecoCompleto);
        }
    });
    
    // Validação antes do envio do formulário
    $('#formCliente').on('submit', function(e) {
        var cpfCnpj = $('#cpf_cnpj').val().replace(/\D/g, '');
        var isCpf = $('#radioCpf').is(':checked');
        
        // Verifica se o CPF tem 11 dígitos
        if (isCpf && cpfCnpj.length !== 11) {
            e.preventDefault();
            mostrarToast('CPF inválido. O CPF deve ter 11 dígitos.', 'error');
            return false;
        }
        
        // Verifica se o CNPJ tem 14 dígitos
        if (!isCpf && cpfCnpj.length !== 14) {
            e.preventDefault();
            mostrarToast('CNPJ inválido. O CNPJ deve ter 14 dígitos.', 'error');
            return false;
        }
        
        // Alerta sobre CNPJ inativo
        if (!isCpf && cnpjVerificado && cnpjSituacao && cnpjSituacao !== 'ATIVA') {
            // Usa confirm nativo do navegador para garantir que o usuário veja a mensagem
            if (!confirm(`ATENÇÃO: O CNPJ informado possui situação "${cnpjSituacao}" na Receita Federal.\n\nIsso provavelmente impedirá o cadastro no sistema.\n\nDeseja continuar mesmo assim?`)) {
                e.preventDefault();
                return false;
            }
        }
        
        // Verifica se os campos obrigatórios estão preenchidos
        if (!$('#nome').val() || !$('#telefone').val() || !$('#cep').val() || 
            !$('#endereco').val() || !$('#numero').val() || !$('#bairro').val() || 
            !$('#cidade').val() || !$('#estado').val() || !$('#latitude').val() || 
            !$('#longitude').val()) {
            e.preventDefault();
            mostrarToast('Preencha todos os campos obrigatórios.', 'error');
            return false;
        }
        
        // Tudo OK, pode enviar o formulário
        return true;
    });
    
    // Previne que o formulário seja perdido quando ocorrer um erro no servidor
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // A página foi restaurada do cache (voltar do navegador)
            // Não faz nada, mantém os dados
        }
    });
    
    // Verifica se há mensagem de erro na página e exibe um modal
    @if(session('error'))
        mostrarModal('Erro ao salvar', '{{ session('error') }}', 'error');
    @endif
});
</script>
@endpush 
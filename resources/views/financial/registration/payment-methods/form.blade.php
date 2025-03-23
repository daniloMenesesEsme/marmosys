@extends('layouts.app')

@section('title', isset($paymentMethod) ? 'Editar Forma de Pagamento' : 'Nova Forma de Pagamento')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">
                        <i class="material-icons left">{{ isset($paymentMethod) ? 'edit' : 'add_circle' }}</i>
                        {{ isset($paymentMethod) ? 'Editar Forma de Pagamento' : 'Nova Forma de Pagamento' }}
                    </span>

                    <form action="{{ isset($paymentMethod) ? 
                        route('financial.registration.payment-methods.update', $paymentMethod) : 
                        route('financial.registration.payment-methods.store') }}" 
                        method="POST">
                        @csrf
                        @if(isset($paymentMethod))
                            @method('PUT')
                        @endif

                        <!-- Campos ocultos para dados obrigatórios -->
                        <input type="hidden" name="parcelas_padrao" value="{{ old('parcelas_padrao', $paymentMethod->parcelas_padrao ?? 1) }}">
                        <input type="hidden" name="taxa_padrao" value="{{ old('taxa_padrao', $paymentMethod->taxa_padrao ?? 0) }}">

                        <div class="row">
                            <div class="col s12">
                                <ul class="tabs tabs-fixed-width">
                                    <li class="tab">
                                        <a href="#principal" class="active">
                                            <i class="material-icons">description</i> Principal
                                        </a>
                                    </li>
                                    <li class="tab">
                                        <a href="#fiscal">
                                            <i class="material-icons">receipt</i> Fiscal
                                        </a>
                                    </li>
                                    <li class="tab">
                                        <a href="#contas-corrente">
                                            <i class="material-icons">account_balance</i> Contas
                                        </a>
                                    </li>
                                    <li class="tab">
                                        <a href="#frente-loja">
                                            <i class="material-icons">point_of_sale</i> PDV
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Aba Principal -->
                            <div id="principal" class="col s12" style="margin-top: 20px;">
                                <div class="row">
                                    <div class="input-field col s12 m3">
                                        <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $paymentMethod->codigo ?? '') }}" maxlength="10">
                                        <label for="codigo">Código</label>
                                        <span class="helper-text">Máximo 10 caracteres. Se não preenchido, será gerado automaticamente.</span>
                                        @error('codigo')
                                            <span class="red-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-field col s12 m9">
                                        <input type="text" id="nome" name="nome" value="{{ old('nome', $paymentMethod->nome ?? $paymentMethod->descricao ?? '') }}" required>
                                        <label for="nome">Nome</label>
                                        @error('nome')
                                            <span class="red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea id="descricao" name="descricao" class="materialize-textarea">{{ old('descricao', $paymentMethod->descricao ?? '') }}</textarea>
                                        <label for="descricao">Descrição</label>
                                        @error('descricao')
                                            <span class="red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12 m6">
                                        <select name="especie_documento" id="especie_documento" required>
                                            <option value="">Selecione a Espécie de Documento</option>
                                            @foreach(\App\Models\PaymentMethod::TIPOS as $key => $value)
                                                <option value="{{ $key }}" {{ old('especie_documento', $paymentMethod->especie_documento ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="especie_documento">Espécie de Documento (Recebimento)</label>
                                    </div>

                                    <!-- Campo tipo atualizado para receber valor do especie_documento -->
                                    <input type="hidden" id="tipo" name="tipo" value="{{ old('tipo', old('especie_documento', $paymentMethod->tipo ?? $paymentMethod->especie_documento ?? '')) }}">

                                    <div class="input-field col s12 m6">
                                        <select name="categoria_financeira_id" id="categoria_financeira_id" required>
                                            <option value="">Selecione a Categoria Financeira</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                    {{ old('categoria_financeira_id', $paymentMethod->categoria_financeira_id ?? '') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="categoria_financeira_id">Categoria Financeira (Receita)</label>
                                        @error('categoria_financeira_id')
                                            <span class="red-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s12">
                                        <label>
                                            <input type="checkbox" name="controle_cartao" class="filled-in" {{ old('controle_cartao', $paymentMethod->controle_cartao ?? '') ? 'checked' : '' }}>
                                            <span>Controle de Cartão</span>
                                        </label>
                                        <label style="margin-left: 20px;">
                                            <input type="checkbox" name="movimenta_conta_corrente" class="filled-in" {{ old('movimenta_conta_corrente', $paymentMethod->movimenta_conta_corrente ?? '') ? 'checked' : '' }}>
                                            <span>Movimenta conta corrente</span>
                                        </label>
                                        <label style="margin-left: 20px;">
                                            <input type="checkbox" name="emite_comprovantes_vinculados" class="filled-in" {{ old('emite_comprovantes_vinculados', $paymentMethod->emite_comprovantes_vinculados ?? '') ? 'checked' : '' }}>
                                            <span>Emite comprovantes vinculados</span>
                                        </label>
                                        <label style="margin-left: 20px;">
                                            <input type="checkbox" name="ativo" class="filled-in" {{ old('ativo', $paymentMethod->ativo ?? true) ? 'checked' : '' }}>
                                            <span>Ativo</span>
                                        </label>
                                        <label style="margin-left: 20px;">
                                            <input type="checkbox" name="gera_financeiro" class="filled-in" {{ old('gera_financeiro', $paymentMethod->gera_financeiro ?? true) ? 'checked' : '' }}>
                                            <span>Gera Financeiro</span>
                                        </label>
                                        <label style="margin-left: 20px;">
                                            <input type="checkbox" name="envia_pdv" class="filled-in" {{ old('envia_pdv', $paymentMethod->envia_pdv ?? '') ? 'checked' : '' }}>
                                            <span>Envia PDV</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12 m6 select-field">
                                        <select name="financial_agent_id" id="financial_agent_id">
                                            <option value="">Selecione o Agente Financeiro</option>
                                            @foreach($financialAgents as $agent)
                                                <option value="{{ $agent->id }}" 
                                                    {{ old('financial_agent_id', $paymentMethod->financial_agent_id ?? '') == $agent->id ? 'selected' : '' }}>
                                                    {{ $agent->nome }} ({{ \App\Models\FinancialAgent::TIPOS[$agent->tipo] }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="financial_agent_id">Agente Financeiro</label>
                                        @error('financial_agent_id') <span class="red-text">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Aba Fiscal -->
                            <div id="fiscal" class="col s12" style="margin-top: 20px;">
                                <div class="row">
                                    <div class="input-field col s12 m3">
                                        <input type="text" id="codigo_fiscal" name="codigo_fiscal" value="{{ old('codigo_fiscal', $paymentMethod->codigo_fiscal ?? '') }}">
                                        <label for="codigo_fiscal">Código Fiscal</label>
                                    </div>

                                    <div class="input-field col s12 m9">
                                        <input type="text" id="descricao_fiscal" name="descricao_fiscal" value="{{ old('descricao_fiscal', $paymentMethod->descricao_fiscal ?? '') }}">
                                        <label for="descricao_fiscal">Descrição Fiscal</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Aba Contas Corrente -->
                            <div id="contas-corrente" class="col s12" style="margin-top: 20px;">
                                <div class="card">
                                    <div class="card-content">
                                        <span class="card-title">Contas Correntes Vinculadas</span>
                                        <div class="row">
                                            <div class="col s12">
                                                <table class="striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Loja</th>
                                                            <th>Conta Corrente</th>
                                                            <th width="80">Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="contas-corrente-list">
                                                        <!-- Lista de contas corrente será carregada aqui -->
                                                    </tbody>
                                                </table>
                                                <div class="right-align" style="margin-top: 15px;">
                                                    <a class="btn-floating waves-effect waves-light blue" onclick="abrirModalContaCorrente()">
                                                        <i class="material-icons">add</i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row" style="margin-top: 20px;">
                                            <div class="col s12">
                                                <div class="switch">
                                                    <label>
                                                        <input type="checkbox" name="movimenta_conta_corrente" id="movimenta_conta_corrente" {{ old('movimenta_conta_corrente', $paymentMethod->movimenta_conta_corrente ?? 0) ? 'checked' : '' }} value="1">
                                                        <span class="lever"></span>
                                                        Movimenta Conta Corrente
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Aba Frente de Loja -->
                            <div id="frente-loja" class="col s12" style="margin-top: 20px;">
                                <div class="row">
                                    <div class="col s12">
                                        <h5>Espécie</h5>
                                        <div class="row">
                                            <div class="col s12">
                                                <label style="margin-right: 20px;">
                                                    <input name="especie_pdv" type="radio" value="dinheiro" {{ old('especie_pdv', $paymentMethod->especie_pdv ?? '') == 'dinheiro' ? 'checked' : '' }}>
                                                    <span>Dinheiro</span>
                                                </label>
                                                <label style="margin-right: 20px;">
                                                    <input name="especie_pdv" type="radio" value="cheque" {{ old('especie_pdv', $paymentMethod->especie_pdv ?? '') == 'cheque' ? 'checked' : '' }}>
                                                    <span>Cheque</span>
                                                </label>
                                                <label style="margin-right: 20px;">
                                                    <input name="especie_pdv" type="radio" value="cartao_credito" {{ old('especie_pdv', $paymentMethod->especie_pdv ?? '') == 'cartao_credito' ? 'checked' : '' }}>
                                                    <span>Cartão de Crédito</span>
                                                </label>
                                                <label style="margin-right: 20px;">
                                                    <input name="especie_pdv" type="radio" value="cartao_debito" {{ old('especie_pdv', $paymentMethod->especie_pdv ?? '') == 'cartao_debito' ? 'checked' : '' }}>
                                                    <span>Cartão de Débito</span>
                                                </label>
                                                <label style="margin-right: 20px;">
                                                    <input name="especie_pdv" type="radio" value="pix" {{ old('especie_pdv', $paymentMethod->especie_pdv ?? '') == 'pix' ? 'checked' : '' }}>
                                                    <span>PIX</span>
                                                </label>
                                                <label style="margin-right: 20px;">
                                                    <input name="especie_pdv" type="radio" value="outros" {{ old('especie_pdv', $paymentMethod->especie_pdv ?? '') == 'outros' ? 'checked' : '' }}>
                                                    <span>Outros</span>
                                                </label>
                                            </div>
                                        </div>

                                        <h5>Parametrização</h5>
                                        <div class="row">
                                            <div class="col s12">
                                                <label style="margin-right: 20px;">
                                                    <input type="checkbox" name="pagamento" class="filled-in" {{ old('pagamento', $paymentMethod->pagamento ?? '') ? 'checked' : '' }}>
                                                    <span>Pagamento</span>
                                                </label>
                                                <label style="margin-right: 20px;">
                                                    <input type="checkbox" name="permite_troco" class="filled-in" {{ old('permite_troco', $paymentMethod->permite_troco ?? true) ? 'checked' : '' }}>
                                                    <span>Permite Troco</span>
                                                </label>
                                                <label style="margin-right: 20px;">
                                                    <input type="checkbox" name="sangria_automatica" class="filled-in" {{ old('sangria_automatica', $paymentMethod->sangria_automatica ?? '') ? 'checked' : '' }}>
                                                    <span>Sangria automática</span>
                                                </label>
                                            </div>
                                        </div>

                                        <h5>Controle de Valores</h5>
                                        <div class="row">
                                            <div class="input-field col s12 m3">
                                                <input type="number" step="0.01" id="valor_minimo" name="valor_minimo" value="{{ old('valor_minimo', $paymentMethod->valor_minimo ?? '') }}">
                                                <label for="valor_minimo">Valor Mínimo</label>
                                            </div>
                                            <div class="input-field col s12 m3">
                                                <input type="number" step="0.01" id="valor_maximo" name="valor_maximo" value="{{ old('valor_maximo', $paymentMethod->valor_maximo ?? '') }}">
                                                <label for="valor_maximo">Valor Máximo</label>
                                            </div>
                                            <div class="input-field col s12 m3">
                                                <input type="number" step="0.01" id="troco_maximo" name="troco_maximo" value="{{ old('troco_maximo', $paymentMethod->troco_maximo ?? '') }}">
                                                <label for="troco_maximo">Troco Máximo</label>
                                            </div>
                                            <div class="input-field col s12 m3">
                                                <input type="number" step="0.01" id="valor_minimo_parcela" name="valor_minimo_parcela" value="{{ old('valor_minimo_parcela', $paymentMethod->valor_minimo_parcela ?? '') }}">
                                                <label for="valor_minimo_parcela">Valor Mínimo por Parcela</label>
                                            </div>
                                        </div>

                                        <h5>Controle de Parcelas</h5>
                                        <div class="row">
                                            <div class="input-field col s12 m4">
                                                <input type="number" id="parcelas_minimas" name="parcelas_minimas" value="{{ old('parcelas_minimas', $paymentMethod->parcelas_minimas ?? 1) }}">
                                                <label for="parcelas_minimas">Parcelas Mínimas</label>
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <input type="number" id="parcelas_maximas" name="parcelas_maximas" value="{{ old('parcelas_maximas', $paymentMethod->parcelas_maximas ?? 1) }}">
                                                <label for="parcelas_maximas">Parcelas Máximas</label>
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <input type="number" id="prazo" name="prazo" value="{{ old('prazo', $paymentMethod->prazo ?? 0) }}">
                                                <label for="prazo">Prazo (dias)</label>
                                            </div>
                                        </div>

                                        <h5>Controle de Horários</h5>
                                        <div class="row">
                                            <div class="input-field col s12 m6">
                                                <input type="time" id="horario_inicio" name="horario_inicio" value="{{ old('horario_inicio', $paymentMethod->horario_inicio ?? '') }}">
                                                <label for="horario_inicio">Horário Inicial</label>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <input type="time" id="horario_fim" name="horario_fim" value="{{ old('horario_fim', $paymentMethod->horario_fim ?? '') }}">
                                                <label for="horario_fim">Horário Final</label>
                                            </div>
                                        </div>

                                        <h5>TEF</h5>
                                        <div class="row">
                                            <div class="col s12">
                                                <label style="margin-right: 20px;">
                                                    <input type="checkbox" name="usa_tef" class="filled-in" {{ old('usa_tef', $paymentMethod->usa_tef ?? '') ? 'checked' : '' }}>
                                                    <span>Usa TEF</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s12 m6">
                                                <select name="bandeira_tef" id="bandeira_tef">
                                                    <option value="">Selecione a Bandeira</option>
                                                    @foreach(\App\Models\PaymentMethod::BANDEIRAS_TEF as $key => $value)
                                                        <option value="{{ $key }}" {{ old('bandeira_tef', $paymentMethod->bandeira_tef ?? '') == $key ? 'selected' : '' }}>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="bandeira_tef">Bandeira TEF</label>
                                            </div>
                                            <div class="input-field col s12 m6">
                                                <input type="text" id="nsu_tef" name="nsu_tef" value="{{ old('nsu_tef', $paymentMethod->nsu_tef ?? '') }}">
                                                <label for="nsu_tef">NSU TEF</label>
                                            </div>
                                        </div>

                                        <h5>Cliente</h5>
                                        <div class="row">
                                            <div class="col s12 m4">
                                                <label>Tipo de Cliente</label>
                                                <select name="tipo_cliente" class="browser-default">
                                                    <option value="codigo" {{ old('tipo_cliente', $paymentMethod->tipo_cliente ?? '') == 'codigo' ? 'selected' : '' }}>Código</option>
                                                    <option value="pin_pad" {{ old('tipo_cliente', $paymentMethod->tipo_cliente ?? '') == 'pin_pad' ? 'selected' : '' }}>Pin Pad</option>
                                                </select>
                                            </div>
                                            <div class="col s12 m8">
                                                <p>
                                                    <label>
                                                        <input type="checkbox" name="pin_pad" class="filled-in" {{ old('pin_pad', $paymentMethod->pin_pad ?? '') ? 'checked' : '' }}>
                                                        <span>Usa Pin Pad</span>
                                                    </label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 center-align" style="margin-top: 20px;">
                                <button type="submit" id="btn-salvar" class="btn waves-effect waves-light">
                                    <i class="material-icons left">save</i>
                                    Salvar
                                </button>
                                <a href="{{ route('financial.registration.payment-methods.index') }}" 
                                   class="btn waves-effect waves-light red">
                                    <i class="material-icons left">cancel</i>
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Conta Corrente -->
<div id="modalContaCorrente" class="modal">
    <div class="modal-content">
        <h4>Adicionar Conta Corrente</h4>
        <div class="row">
            <div class="input-field col s12">
                <select id="lojaSelect" name="lojaSelect">
                    <option value="" disabled selected>Selecione uma loja</option>
                </select>
                <label for="lojaSelect">Loja</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <select id="contaSelect" name="contaSelect">
                    <option value="" disabled selected>Selecione uma conta</option>
                </select>
                <label for="contaSelect">Conta Corrente</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
        <a href="#!" class="waves-effect waves-green btn" onclick="adicionarContaCorrente()">Adicionar</a>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa os selects do Materialize
        var selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);

        // Inicializa as tabs
        var tabs = document.querySelectorAll('.tabs');
        var tabsInstance = M.Tabs.init(tabs);
        
        // Evento para atualizar o campo tipo quando trocar de aba
        document.querySelectorAll('.tab a').forEach(function(tab) {
            tab.addEventListener('click', function() {
                atualizarCampoTipo();
                
                // Reinicializa os selects após trocar de aba
                setTimeout(function() {
                    var selects = document.querySelectorAll('select');
                    M.FormSelect.init(selects);
                }, 100);
            });
        });

        // Inicializa os modais
        var modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);

        // Define o valor inicial do campo tipo
        atualizarCampoTipo();

        // Sincroniza o campo tipo com a espécie de documento
        var especieDocumento = document.getElementById('especie_documento');
        if (especieDocumento) {
            especieDocumento.addEventListener('change', function() {
                var isAvistaOnly = ['dinheiro', 'pix'].includes(this.value);
                var parcelasMinimas = document.getElementById('parcelas_minimas');
                var parcelasMaximas = document.getElementById('parcelas_maximas');
                var valorMinimoParcela = document.getElementById('valor_minimo_parcela');
                
                // Sincroniza o campo tipo com o valor de especie_documento - CORRIGIDO
                document.getElementById('tipo').value = this.value;
                console.log('Tipo atualizado para:', this.value);

                if (isAvistaOnly) {
                    parcelasMinimas.value = 1;
                    parcelasMaximas.value = 1;
                    parcelasMinimas.disabled = true;
                    parcelasMaximas.disabled = true;
                    valorMinimoParcela.disabled = true;
                    
                    // Atualiza os campos ocultos
                    document.querySelector('input[name="parcelas_padrao"]').value = 1;
                    document.querySelector('input[name="taxa_padrao"]').value = 0;
                } else {
                    parcelasMinimas.disabled = false;
                    parcelasMaximas.disabled = false;
                    valorMinimoParcela.disabled = false;
                }
            });

            // Dispara o evento change para configurar o estado inicial
            especieDocumento.dispatchEvent(new Event('change'));
        }

        // Sincroniza especie_pdv com o campo tipo quando aquele for alterado
        document.querySelectorAll('input[name="especie_pdv"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('tipo').value = this.value;
                    console.log('Tipo atualizado para (especie_pdv):', this.value);
                }
            });
        });

        // Adiciona evento ao formulário para verificar os campos antes do envio
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o envio do formulário para fazer validações
            
            // Garante que o campo tipo seja preenchido corretamente
            var especieDocumento = document.getElementById('especie_documento');
            var tipoField = document.getElementById('tipo');
            
            if (especieDocumento && especieDocumento.value && !tipoField.value) {
                tipoField.value = especieDocumento.value;
                console.log('Tipo definido com especie_documento antes do envio:', tipoField.value);
            }
            
            // Debug - exibe valores importantes no console
            console.log('Valores do formulário:');
            console.log('Nome:', document.querySelector('input[name="nome"]').value);
            console.log('Código:', document.querySelector('input[name="codigo"]').value);
            console.log('Tipo:', document.getElementById('tipo').value);
            console.log('Categoria:', document.querySelector('select[name="categoria_financeira_id"]').value);
            console.log('Parcelas Padrão:', document.querySelector('input[name="parcelas_padrao"]').value);
            console.log('Taxa Padrão:', document.querySelector('input[name="taxa_padrao"]').value);
            
            // Verifica se todos os campos obrigatórios estão preenchidos
            var nome = document.querySelector('input[name="nome"]').value;
            var categoria = document.querySelector('select[name="categoria_financeira_id"]').value;
            var parcelas = document.querySelector('input[name="parcelas_padrao"]').value;
            var taxa = document.querySelector('input[name="taxa_padrao"]').value;
            
            if (!nome || !tipoField.value || !categoria || !parcelas || !taxa) {
                var camposFaltantes = [];
                if (!nome) camposFaltantes.push('Nome');
                if (!tipoField.value) camposFaltantes.push('Tipo');
                if (!categoria) camposFaltantes.push('Categoria Financeira');
                if (!parcelas) camposFaltantes.push('Parcelas Padrão');
                if (!taxa) camposFaltantes.push('Taxa Padrão');
                
                M.toast({html: 'Preencha os campos obrigatórios: ' + camposFaltantes.join(', '), classes: 'red'});
                return false;
            }
            
            // Adiciona as contas correntes temporárias ao formulário
            var contas = JSON.parse(localStorage.getItem('paymentMethodContas') || '[]');
            if (contas.length > 0) {
                // Adiciona um campo oculto com as contas em formato JSON
                var contasInput = document.createElement('input');
                contasInput.type = 'hidden';
                contasInput.name = 'contas_corrente';
                contasInput.value = JSON.stringify(contas);
                this.appendChild(contasInput);
            }
            
            // Se chegou até aqui, tudo está válido, pode enviar o formulário
            console.log('Formulário validado, enviando...');
            this.submit();
        });

        // Controle de campos baseado no uso de TEF
        var usaTef = document.querySelector('input[name="usa_tef"]');
        if (usaTef) {
            usaTef.addEventListener('change', function() {
                var controleCartao = document.querySelector('input[name="controle_cartao"]');
                if (this.checked) {
                    controleCartao.checked = true;
                    controleCartao.disabled = true;
                } else {
                    controleCartao.disabled = false;
                }
            });

            // Dispara o evento change para configurar o estado inicial
            usaTef.dispatchEvent(new Event('change'));
        }
        
        // Carrega as contas correntes existentes (se houver)
        carregarContasCorrentes();
        
        // Adiciona função específica para o botão salvar
        document.getElementById('btn-salvar').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Força atualização do campo tipo
            var especieDocumento = document.getElementById('especie_documento');
            var tipoField = document.getElementById('tipo');
            
            if (especieDocumento && especieDocumento.value) {
                tipoField.value = especieDocumento.value;
                console.log('Tipo definido pelo botão salvar:', tipoField.value);
            } else {
                // Se não tiver especie_documento, tenta outras opções
                atualizarCampoTipo();
            }
            
            document.querySelector('form').dispatchEvent(new Event('submit'));
        });
    });
    
    // Função para atualizar o campo tipo com base em outros campos
    function atualizarCampoTipo() {
        var tipoField = document.getElementById('tipo');
        
        // Prioridade 1: Espécie de documento
        var especieDocumento = document.getElementById('especie_documento');
        if (especieDocumento && especieDocumento.value) {
            tipoField.value = especieDocumento.value;
            console.log('Tipo definido pela espécie de documento:', tipoField.value);
            return;
        }
        
        // Prioridade 2: Espécie PDV
        var especiePdv = document.querySelector('input[name="especie_pdv"]:checked');
        if (especiePdv && especiePdv.value) {
            tipoField.value = especiePdv.value;
            console.log('Tipo definido pela espécie PDV:', tipoField.value);
            return;
        }
        
        // Se já tem um valor, mantém
        if (tipoField.value) {
            console.log('Mantendo valor existente do tipo:', tipoField.value);
            return;
        }
        
        // Prioridade 3: Valor padrão
        tipoField.value = 'outros';
        console.log('Tipo definido como padrão (outros)');
    }
    
    // Função para abrir o modal e carregar lojas e contas
    function abrirModalContaCorrente() {
        carregarLojas();
        $('#modalContaCorrente').modal('open');
    }

    // Função para carregar lojas
    function carregarLojas() {
        const selectLoja = $('#lojaSelect');
        selectLoja.empty();
        selectLoja.append('<option value="" disabled selected>Selecione uma loja</option>');
        
        // Carregar lojas via AJAX
        $.ajax({
            url: '{{ route("api.stores.list") }}',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    response.data.forEach(function(loja) {
                        selectLoja.append(`<option value="${loja.id}">${loja.nome}</option>`);
                    });
                } else {
                    // Exemplo de dados para teste caso não haja API
                    selectLoja.append('<option value="1">Matriz</option>');
                    selectLoja.append('<option value="2">Filial 1</option>');
                    selectLoja.append('<option value="3">Filial 2</option>');
                }
                selectLoja.formSelect();
            },
            error: function() {
                // Exemplo de dados para teste em caso de erro
                selectLoja.append('<option value="1">Matriz</option>');
                selectLoja.append('<option value="2">Filial 1</option>');
                selectLoja.append('<option value="3">Filial 2</option>');
                selectLoja.formSelect();
                M.toast({html: 'Erro ao carregar lojas. Usando dados de exemplo.', classes: 'red'});
            }
        });
    }

    // Função para carregar contas correntes baseada na loja selecionada
    function carregarContasCorrentes() {
        const lojaId = $('#lojaSelect').val();
        const selectConta = $('#contaSelect');
        selectConta.empty();
        selectConta.append('<option value="" disabled selected>Selecione uma conta</option>');
        
        if (!lojaId) {
            selectConta.formSelect();
            return;
        }
        
        // Carregar contas via AJAX
        $.ajax({
            url: '{{ route("api.current-accounts.by-store") }}',
            type: 'GET',
            data: { store_id: lojaId },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    response.data.forEach(function(conta) {
                        selectConta.append(`<option value="${conta.id}">${conta.nome}</option>`);
                    });
                } else {
                    // Exemplo de dados para teste caso não haja API
                    selectConta.append('<option value="1">Conta Principal</option>');
                    selectConta.append('<option value="2">Conta Secundária</option>');
                    selectConta.append('<option value="3">Conta Poupança</option>');
                }
                selectConta.formSelect();
            },
            error: function() {
                // Exemplo de dados para teste em caso de erro
                selectConta.append('<option value="1">Conta Principal</option>');
                selectConta.append('<option value="2">Conta Secundária</option>');
                selectConta.append('<option value="3">Conta Poupança</option>');
                selectConta.formSelect();
                M.toast({html: 'Erro ao carregar contas. Usando dados de exemplo.', classes: 'red'});
            }
        });
    }
    
    // Função para carregar a lista de contas correntes já adicionadas
    function carregarListaContasCorrentes() {
        const contasList = $('#contas-corrente-list');
        
        // Verifica se há contas salvas no storage local (temporário)
        const contas = JSON.parse(localStorage.getItem('paymentMethodContas') || '[]');
        
        // Limpa a lista
        contasList.empty();
        
        // Adiciona as contas à lista
        contas.forEach(function(conta, index) {
            const row = `
                <tr>
                    <td>${conta.lojaNome || '-'}</td>
                    <td>${conta.contaNome || '-'}</td>
                    <td>
                        <a class="btn-floating btn-small waves-effect waves-light red" onclick="removerContaCorrente(${index})">
                            <i class="material-icons">delete</i>
                        </a>
                    </td>
                </tr>
            `;
            contasList.append(row);
        });
        
        // Se não houver contas, exibe uma mensagem
        if (contas.length === 0) {
            contasList.append('<tr><td colspan="3" class="center-align">Nenhuma conta corrente adicionada</td></tr>');
        }
    }
    
    // Função para adicionar uma conta corrente à lista
    function adicionarContaCorrente() {
        const lojaId = $('#lojaSelect').val();
        const contaId = $('#contaSelect').val();
        
        if (!lojaId || !contaId) {
            M.toast({html: 'Selecione a loja e a conta corrente', classes: 'red'});
            return;
        }
        
        // Pega os textos selecionados
        const lojaNome = $('#lojaSelect option:selected').text();
        const contaNome = $('#contaSelect option:selected').text();
        
        // Carrega as contas existentes
        const contas = JSON.parse(localStorage.getItem('paymentMethodContas') || '[]');
        
        // Verifica se esta combinação já existe
        const jaExiste = contas.some(c => c.lojaId == lojaId && c.contaId == contaId);
        if (jaExiste) {
            M.toast({html: 'Esta conta já foi adicionada', classes: 'red'});
            return;
        }
        
        // Adiciona a nova conta
        contas.push({
            lojaId: lojaId,
            lojaNome: lojaNome,
            contaId: contaId,
            contaNome: contaNome
        });
        
        // Salva as contas no storage
        localStorage.setItem('paymentMethodContas', JSON.stringify(contas));
        
        // Atualiza a lista de contas
        carregarListaContasCorrentes();
        
        // Fecha o modal
        M.Modal.getInstance(document.getElementById('modalContaCorrente')).close();
        
        // Exibe uma mensagem de sucesso
        M.toast({html: 'Conta corrente adicionada com sucesso', classes: 'green'});
    }
    
    // Função para remover uma conta corrente da lista
    function removerContaCorrente(index) {
        // Carrega as contas existentes
        const contas = JSON.parse(localStorage.getItem('paymentMethodContas') || '[]');
        
        // Remove a conta no índice especificado
        contas.splice(index, 1);
        
        // Salva as contas no storage
        localStorage.setItem('paymentMethodContas', JSON.stringify(contas));
        
        // Atualiza a lista de contas
        carregarListaContasCorrentes();
        
        // Exibe uma mensagem
        M.toast({html: 'Conta corrente removida', classes: 'orange'});
    }

    // Adiciona evento para carregar contas quando a loja for selecionada
    $(document).on('change', '#lojaSelect', function() {
        carregarContasCorrentes();
    });

    // Inicializa a lista de contas ao carregar a página
    $(document).ready(function() {
        carregarListaContasCorrentes();
    });
</script>
@endsection

@push('styles')
<style>
/* Ajustes de layout */
.container {
    padding: 100px 20px 20px 380px;
    width: 95%;
    max-width: none;
}

/* Mantém as tabs como estavam */
.tabs .tab a {
    color: rgba(0,0,0,0.7);
}
.tabs .tab a:hover {
    color: #1976d2;
}
.tabs .tab a.active {
    color: #1976d2;
}
.tabs .indicator {
    background-color: #1976d2;
}

/* Ajusta o tamanho dos campos */
.input-field {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

/* Ajusta espaçamento das linhas */
.row {
    margin-bottom: 10px;
}

/* Mantém o modal como estava */
.modal {
    width: 80% !important;
    max-height: 85% !important;
}

/* Ajusta o card */
.card {
    margin: 0;
    width: 98%;
}

.card-content {
    padding: 20px;
}

/* Ajusta os checkboxes */
[type="checkbox"]+span {
    padding-left: 25px;
}

/* Ajusta os campos de texto */
input:not([type]), 
input[type=text], 
input[type=number],
input[type=time] {
    height: 2.5rem;
}

/* Ajusta os selects */
.select-wrapper input.select-dropdown {
    height: 2.5rem;
    line-height: 2.5rem;
}

@media only screen and (max-width: 992px) {
    .container {
        padding: 100px 20px 20px 20px;
        width: 100%;
    }
}
</style>
@endpush 
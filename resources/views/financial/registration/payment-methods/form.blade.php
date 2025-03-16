@extends('layouts.app')

@section('title', isset($paymentMethod) ? 'Editar Forma de Pagamento' : 'Nova Forma de Pagamento')

@section('content')
<div class="container-fluid" style="padding: 0 30px;">
    <div class="row">
        <div class="col s12">
            <div class="card" style="margin: 15px 0;">
                <div class="card-content">
                    <div class="card-title">
                        <h4>
                            <i class="material-icons left">{{ isset($paymentMethod) ? 'edit' : 'add' }}</i>
                            {{ isset($paymentMethod) ? 'Editar Forma de Pagamento' : 'Nova Forma de Pagamento' }}
                        </h4>
                    </div>

                    <form action="{{ isset($paymentMethod) ? 
                        route('financial.registration.payment-methods.update', $paymentMethod) : 
                        route('financial.registration.payment-methods.store') }}" 
                        method="POST">
                        @csrf
                        @if(isset($paymentMethod))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col s12">
                                <ul class="tabs">
                                    <li class="tab col s4"><a class="active" href="#principal">Principal</a></li>
                                    <li class="tab col s4"><a href="#contas-corrente">Contas Corrente</a></li>
                                    <li class="tab col s4"><a href="#frente-loja">Frente de Loja</a></li>
                                </ul>
                            </div>

                            <!-- Aba Principal -->
                            <div id="principal" class="col s12" style="margin-top: 20px;">
                                <div class="row">
                                    <div class="input-field col s12 m3">
                                        <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $paymentMethod->codigo ?? '') }}" required>
                                        <label for="codigo">Código</label>
                                        @error('codigo')
                                            <span class="red-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-field col s12 m9">
                                        <input type="text" id="descricao" name="descricao" value="{{ old('descricao', $paymentMethod->descricao ?? '') }}" required>
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
                                            <option value="dinheiro" {{ old('especie_documento', $paymentMethod->especie_documento ?? '') == 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                                            <option value="cartao" {{ old('especie_documento', $paymentMethod->especie_documento ?? '') == 'cartao' ? 'selected' : '' }}>Cartão</option>
                                            <option value="boleto" {{ old('especie_documento', $paymentMethod->especie_documento ?? '') == 'boleto' ? 'selected' : '' }}>Boleto</option>
                                            <option value="pix" {{ old('especie_documento', $paymentMethod->especie_documento ?? '') == 'pix' ? 'selected' : '' }}>PIX</option>
                                        </select>
                                        <label for="especie_documento">Espécie de Documento (Recebimento)</label>
                                    </div>

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
                                    <div class="input-field col s12">
                                        <input type="text" id="agente" name="agente" value="{{ old('agente', $paymentMethod->agente ?? '') }}">
                                        <label for="agente">Agente</label>
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
                                            <input type="checkbox" name="envia_pdv" class="filled-in" {{ old('envia_pdv', $paymentMethod->envia_pdv ?? '') ? 'checked' : '' }}>
                                            <span>Envia PDV</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Aba Contas Corrente -->
                            <div id="contas-corrente" class="col s12" style="margin-top: 20px;">
                                <div class="card">
                                    <div class="card-content">
                                        <span class="card-title">Lojas/Conta Corrente</span>
                                        <div class="row">
                                            <div class="col s12">
                                                <table class="striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Código</th>
                                                            <th>Descrição</th>
                                                            <th>Conta Corrente</th>
                                                            <th width="100">Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="contas-corrente-list">
                                                        <!-- Lista de contas corrente será carregada aqui -->
                                                    </tbody>
                                                </table>
                                                <div class="right-align" style="margin-top: 10px;">
                                                    <a class="btn-floating waves-effect waves-light green" onclick="abrirModalContaCorrente()">
                                                        <i class="material-icons">add</i>
                                                    </a>
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
                                                <!-- Adicione os outros tipos de espécie aqui -->
                                            </div>
                                        </div>

                                        <h5>Parametrização</h5>
                                        <div class="row">
                                            <div class="col s12">
                                                <label style="margin-right: 20px;">
                                                    <input type="checkbox" name="pagamento" class="filled-in">
                                                    <span>Pagamento</span>
                                                </label>
                                                <label style="margin-right: 20px;">
                                                    <input type="checkbox" name="sangria_automatica" class="filled-in">
                                                    <span>Sangria automática</span>
                                                </label>
                                                <!-- Adicione as outras opções de parametrização aqui -->
                                            </div>
                                        </div>

                                        <h5>Cliente</h5>
                                        <div class="row">
                                            <div class="col s3">
                                                <label>Tipo</label>
                                                <select name="tipo_cliente" class="browser-default">
                                                    <option value="codigo">Código</option>
                                                    <option value="pin_pad">Pin Pad</option>
                                                </select>
                                            </div>
                                            <!-- Adicione os outros campos de cliente aqui -->
                                        </div>

                                        <!-- Adicione as outras seções (TEF, Textos Livres) aqui -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 center-align" style="margin-top: 20px;">
                                <button type="submit" class="btn waves-effect waves-light">
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

<!-- Modal Conta Corrente -->
<div id="modal-conta-corrente" class="modal">
    <div class="modal-content">
        <h4>Loja/Conta Corrente</h4>
        <div class="row">
            <div class="input-field col s12">
                <select id="loja" name="loja">
                    <option value="">Selecione a Loja</option>
                    <!-- Opções de lojas serão carregadas aqui -->
                </select>
                <label for="loja">Loja</label>
            </div>
            <div class="input-field col s12">
                <select id="conta_corrente" name="conta_corrente">
                    <option value="">Selecione a Conta Corrente</option>
                    <!-- Opções de contas correntes serão carregadas aqui -->
                </select>
                <label for="conta_corrente">Conta Corrente</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
        <a href="#!" class="waves-effect waves-green btn" onclick="adicionarContaCorrente()">Adicionar</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa as tabs
    var tabs = document.querySelectorAll('.tabs');
    M.Tabs.init(tabs);

    // Inicializa os selects
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    // Inicializa os modais
    var modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);
});

function abrirModalContaCorrente() {
    var modal = M.Modal.getInstance(document.getElementById('modal-conta-corrente'));
    modal.open();
}

function adicionarContaCorrente() {
    // Implementar a lógica de adicionar conta corrente
    var modal = M.Modal.getInstance(document.getElementById('modal-conta-corrente'));
    modal.close();
}

// Mantém a função handleTipoChange para compatibilidade
function handleTipoChange(tipo) {
    const isAVista = ['dinheiro', 'pix'].includes(tipo);
    if (isAVista) {
        document.querySelector('input[name="parcelas_padrao"]').value = 'À Vista';
        document.querySelector('input[name="taxa_padrao"]').value = '0';
    }
}
</script>
@endpush

@push('styles')
<style>
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
.modal {
    width: 80% !important;
    max-height: 85% !important;
}
</style>
@endpush 
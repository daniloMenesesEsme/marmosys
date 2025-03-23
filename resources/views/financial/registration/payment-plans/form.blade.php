@extends('layouts.app')

@section('title', isset($paymentPlan) ? 'Editar Plano de Pagamento' : 'Novo Plano de Pagamento')

@section('content')
<div class="container-fluid" style="padding: 0 30px;">
    <div class="row">
        <div class="col s12">
            <div class="card" style="margin: 15px 0;">
                <div class="card-content">
                    <div class="card-title">
                        <h4>
                            <i class="material-icons left">{{ isset($paymentPlan) ? 'edit' : 'add' }}</i>
                            {{ isset($paymentPlan) ? 'Editar Plano de Pagamento' : 'Novo Plano de Pagamento' }}
                        </h4>
                    </div>

                    <form action="{{ isset($paymentPlan) ? 
                        route('financial.registration.payment-plans.update', $paymentPlan) : 
                        route('financial.registration.payment-plans.store') }}" 
                        method="POST" id="payment-plan-form">
                        @csrf
                        @if(isset($paymentPlan))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <!-- Informações Básicas -->
                            <div class="col s12">
                                <h5>Informações Básicas</h5>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="text" id="nome" name="nome" value="{{ old('nome', $paymentPlan->nome ?? '') }}" required>
                                <label for="nome">Nome</label>
                                @error('nome')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <select name="tipo" id="tipo" required>
                                    <option value="venda" {{ old('tipo', $paymentPlan->tipo ?? '') == 'venda' ? 'selected' : '' }}>Venda</option>
                                    <option value="compra" {{ old('tipo', $paymentPlan->tipo ?? '') == 'compra' ? 'selected' : '' }}>Compra</option>
                                </select>
                                <label for="tipo">Tipo</label>
                                @error('tipo')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12">
                                <textarea id="descricao" name="descricao" class="materialize-textarea">{{ old('descricao', $paymentPlan->descricao ?? '') }}</textarea>
                                <label for="descricao">Descrição</label>
                            </div>

                            <!-- Configurações de Parcelas -->
                            <div class="col s12">
                                <h5>Configurações de Parcelas</h5>
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="number" id="parcelas" name="parcelas" min="1" value="{{ old('parcelas', $paymentPlan->parcelas ?? '1') }}" required>
                                <label for="parcelas">Número de Parcelas</label>
                                @error('parcelas')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="number" id="intervalo_dias" name="intervalo_dias" min="1" value="{{ old('intervalo_dias', $paymentPlan->intervalo_dias ?? '30') }}" required>
                                <label for="intervalo_dias">Intervalo (dias)</label>
                                @error('intervalo_dias')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="number" id="carencia_dias" name="carencia_dias" min="0" value="{{ old('carencia_dias', $paymentPlan->carencia_dias ?? '0') }}" required>
                                <label for="carencia_dias">Carência (dias)</label>
                                @error('carencia_dias')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="number" id="taxa" name="taxa" step="0.01" min="0" value="{{ old('taxa', $paymentPlan->taxa ?? '0') }}" required>
                                <label for="taxa">Taxa (%)</label>
                                @error('taxa')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Configurações Financeiras -->
                            <div class="col s12">
                                <h5>Configurações Financeiras</h5>
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="number" id="multa_atraso" name="multa_atraso" step="0.01" min="0" value="{{ old('multa_atraso', $paymentPlan->multa_atraso ?? '0') }}" required>
                                <label for="multa_atraso">Multa por Atraso (%)</label>
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="number" id="juros_atraso" name="juros_atraso" step="0.01" min="0" value="{{ old('juros_atraso', $paymentPlan->juros_atraso ?? '0') }}" required>
                                <label for="juros_atraso">Juros por Atraso (%)</label>
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="number" id="valor_minimo_parcela" name="valor_minimo_parcela" step="0.01" min="0" value="{{ old('valor_minimo_parcela', $paymentPlan->valor_minimo_parcela ?? '0') }}" required>
                                <label for="valor_minimo_parcela">Valor Mínimo da Parcela</label>
                            </div>

                            <!-- Configurações de Entrada -->
                            <div class="col s12">
                                <h5>Configurações de Entrada</h5>
                            </div>

                            <div class="col s12 m6">
                                <label>
                                    <input type="checkbox" name="permite_entrada" value="1" class="filled-in" 
                                        {{ old('permite_entrada', $paymentPlan->permite_entrada ?? false) ? 'checked' : '' }}>
                                    <span>Permite Entrada</span>
                                </label>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="number" id="percentual_minimo_entrada" name="percentual_minimo_entrada" 
                                    step="0.01" min="0" max="100" 
                                    value="{{ old('percentual_minimo_entrada', $paymentPlan->percentual_minimo_entrada ?? '0') }}" required>
                                <label for="percentual_minimo_entrada">Percentual Mínimo de Entrada (%)</label>
                            </div>

                            <!-- Configurações de Integração -->
                            <div class="col s12">
                                <h5>Configurações de Integração</h5>
                            </div>

                            <div class="input-field col s12 m6">
                                <select name="payment_method_id" required>
                                    <option value="">Selecione uma forma de pagamento</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}" 
                                            {{ old('payment_method_id', $paymentPlan->payment_method_id ?? '') == $method->id ? 'selected' : '' }}>
                                            {{ $method->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Forma de Pagamento</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="text" id="codigo_xml" name="codigo_xml" value="{{ old('codigo_xml', $paymentPlan->codigo_xml ?? '') }}">
                                <label for="codigo_xml">Código XML (NFe)</label>
                            </div>

                            <!-- Configurações PDV -->
                            <div class="col s12">
                                <h5>Configurações PDV</h5>
                            </div>

                            <div class="col s12 m6">
                                <label>
                                    <input type="checkbox" name="disponivel_pdv" value="1" class="filled-in" 
                                        {{ old('disponivel_pdv', $paymentPlan->disponivel_pdv ?? true) ? 'checked' : '' }}>
                                    <span>Disponível no PDV</span>
                                </label>
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="number" id="ordem_exibicao" name="ordem_exibicao" min="0" 
                                    value="{{ old('ordem_exibicao', $paymentPlan->ordem_exibicao ?? '0') }}" required>
                                <label for="ordem_exibicao">Ordem de Exibição</label>
                            </div>

                            <!-- Controles -->
                            <div class="col s12">
                                <h5>Controles</h5>
                            </div>

                            <div class="col s12 m6">
                                <label>
                                    <input type="checkbox" name="ativo" value="1" class="filled-in" 
                                        {{ old('ativo', $paymentPlan->ativo ?? true) ? 'checked' : '' }}>
                                    <span>Ativo</span>
                                </label>
                            </div>

                            <div class="col s12 m6">
                                <label>
                                    <input type="checkbox" name="requer_aprovacao" value="1" class="filled-in" 
                                        {{ old('requer_aprovacao', $paymentPlan->requer_aprovacao ?? false) ? 'checked' : '' }}>
                                    <span>Requer Aprovação</span>
                                </label>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="row">
                            <div class="col s12 center-align">
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="material-icons left">save</i>
                                    Salvar
                                </button>
                                <a href="{{ route('financial.registration.payment-plans.index') }}" class="btn waves-effect waves-light red">
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);

        var textareas = document.querySelectorAll('.materialize-textarea');
        M.textareaAutoResize(textareas);
    });
</script>
@endpush 
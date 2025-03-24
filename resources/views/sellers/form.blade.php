@extends('layouts.app')

@section('title', isset($seller) ? 'Editar Vendedor' : 'Novo Vendedor')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        <div class="row">
                            <div class="col s12">
                                <h4>
                                    <i class="material-icons left">{{ isset($seller) ? 'edit' : 'person_add' }}</i> 
                                    {{ isset($seller) ? 'Editar Vendedor' : 'Novo Vendedor' }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <form action="{{ isset($seller) ? route('sellers.update', $seller) : route('sellers.store') }}" method="POST" class="col s12">
                        @csrf
                        @if(isset($seller))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col s12">
                                <div class="card-panel blue lighten-5">
                                    <span class="blue-text text-darken-4">
                                        <i class="material-icons left">info</i>
                                        Os campos marcados com <span class="red-text">*</span> são obrigatórios.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Dados Pessoais -->
                        <div class="row">
                            <div class="col s12">
                                <h5 class="header">Dados Pessoais</h5>
                            </div>
                            
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">person</i>
                                <input type="text" name="nome" id="nome" value="{{ old('nome', $seller->nome ?? '') }}" required>
                                <label for="nome">Nome Completo <span class="red-text">*</span></label>
                                @error('nome')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">badge</i>
                                <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $seller->cpf ?? '') }}" class="cpf" required>
                                <label for="cpf">CPF <span class="red-text">*</span></label>
                                @error('cpf')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">email</i>
                                <input type="email" name="email" id="email" value="{{ old('email', $seller->email ?? '') }}" required>
                                <label for="email">E-mail <span class="red-text">*</span></label>
                                @error('email')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">phone</i>
                                <input type="text" name="telefone" id="telefone" value="{{ old('telefone', $seller->telefone ?? '') }}" class="telefone" required>
                                <label for="telefone">Telefone <span class="red-text">*</span></label>
                                @error('telefone')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="row">
                            <div class="col s12">
                                <h5 class="header">Endereço</h5>
                            </div>
                            
                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">location_on</i>
                                <input type="text" name="cep" id="cep" value="{{ old('cep', $seller->cep ?? '') }}" class="cep">
                                <label for="cep">CEP</label>
                                @error('cep')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m8">
                                <i class="material-icons prefix">home</i>
                                <input type="text" name="endereco" id="endereco" value="{{ old('endereco', $seller->endereco ?? '') }}">
                                <label for="endereco">Logradouro</label>
                                @error('endereco')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">house</i>
                                <input type="text" name="numero" id="numero" value="{{ old('numero', $seller->numero ?? '') }}">
                                <label for="numero">Número</label>
                                @error('numero')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m8">
                                <i class="material-icons prefix">apartment</i>
                                <input type="text" name="complemento" id="complemento" value="{{ old('complemento', $seller->complemento ?? '') }}">
                                <label for="complemento">Complemento</label>
                                @error('complemento')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">domain</i>
                                <input type="text" name="bairro" id="bairro" value="{{ old('bairro', $seller->bairro ?? '') }}">
                                <label for="bairro">Bairro</label>
                                @error('bairro')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">location_city</i>
                                <input type="text" name="cidade" id="cidade" value="{{ old('cidade', $seller->cidade ?? '') }}">
                                <label for="cidade">Cidade</label>
                                @error('cidade')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <i class="material-icons prefix">map</i>
                                <input type="text" name="estado" id="estado" value="{{ old('estado', $seller->estado ?? '') }}">
                                <label for="estado">Estado</label>
                                @error('estado')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Dados Profissionais -->
                        <div class="row">
                            <div class="col s12">
                                <h5 class="header">Dados Profissionais</h5>
                            </div>
                            
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">trending_up</i>
                                <input type="number" name="percentual_comissao" id="percentual_comissao" step="0.01" min="0" max="100" value="{{ old('percentual_comissao', $seller->percentual_comissao ?? 5) }}" required>
                                <label for="percentual_comissao">Percentual de Comissão (%) <span class="red-text">*</span></label>
                                @error('percentual_comissao')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">monetization_on</i>
                                <input type="number" name="meta_mensal" id="meta_mensal" step="0.01" min="0" value="{{ old('meta_mensal', $seller->meta_mensal ?? 0) }}" required>
                                <label for="meta_mensal">Meta Mensal (R$) <span class="red-text">*</span></label>
                                @error('meta_mensal')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="input-field col s12">
                                <i class="material-icons prefix">note</i>
                                <textarea name="observacoes" id="observacoes" class="materialize-textarea">{{ old('observacoes', $seller->observacoes ?? '') }}</textarea>
                                <label for="observacoes">Observações</label>
                                @error('observacoes')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="input-field col s12">
                                <div class="switch">
                                    <label>
                                        Inativo
                                        <input type="checkbox" name="ativo" {{ old('ativo', $seller->ativo ?? true) ? 'checked' : '' }} value="1">
                                        <span class="lever"></span>
                                        Ativo
                                    </label>
                                </div>
                                @error('ativo')
                                    <span class="red-text text-darken-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="row">
                            <div class="col s12 center-align">
                                <button type="submit" class="btn waves-effect waves-light green">
                                    <i class="material-icons left">save</i>
                                    {{ isset($seller) ? 'Atualizar' : 'Salvar' }}
                                </button>
                                
                                <a href="{{ route('sellers.index') }}" class="btn waves-effect waves-light red">
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
        // Inicializa Materialize
        M.updateTextFields();
        
        // Máscaras para inputs
        $('.cpf').mask('000.000.000-00');
        $('.telefone').mask('(00) 00000-0000');
        $('.cep').mask('00000-000');
        
        // CEP Autocomplete
        $('#cep').on('blur', function() {
            const cep = $(this).val().replace(/\D/g, '');
            
            if (cep.length === 8) {
                $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                    if (!data.erro) {
                        $('#endereco').val(data.logradouro);
                        $('#bairro').val(data.bairro);
                        $('#cidade').val(data.localidade);
                        $('#estado').val(data.uf);
                        
                        // Atualiza os labels para o efeito do Materialize
                        M.updateTextFields();
                    }
                });
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    h5.header {
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 20px;
        color: #26a69a;
    }
    
    .row {
        margin-bottom: 20px;
    }
</style>
@endpush 
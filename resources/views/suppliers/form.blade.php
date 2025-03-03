@extends('layouts.app')

@section('title', isset($supplier) ? 'Editar Fornecedor' : 'Novo Fornecedor')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">{{ isset($supplier) ? 'Editar' : 'Novo' }} Fornecedor</span>

                    <form action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}" method="POST">
                        @csrf
                        @if(isset($supplier))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj', $supplier->cnpj ?? '') }}" class="cnpj-mask" required>
                                <label for="cnpj">CNPJ*</label>
                                @error('cnpj') <span class="red-text">{{ $message }}</span> @enderror
                                <button type="button" id="buscar-cnpj" class="btn waves-effect waves-light">
                                    Buscar CNPJ
                                    <i class="material-icons right">search</i>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social', $supplier->razao_social ?? '') }}" required>
                                <label for="razao_social">Razão Social*</label>
                                @error('razao_social') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia', $supplier->nome_fantasia ?? '') }}" required>
                                <label for="nome_fantasia">Nome Fantasia*</label>
                                @error('nome_fantasia') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <input type="text" id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual', $supplier->inscricao_estadual ?? '') }}">
                                <label for="inscricao_estadual">Inscrição Estadual</label>
                                @error('inscricao_estadual') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="text" id="inscricao_municipal" name="inscricao_municipal" value="{{ old('inscricao_municipal', $supplier->inscricao_municipal ?? '') }}">
                                <label for="inscricao_municipal">Inscrição Municipal</label>
                                @error('inscricao_municipal') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $supplier->telefone ?? '') }}" required>
                                <label for="telefone">Telefone*</label>
                                @error('telefone') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <input type="email" id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" required>
                                <label for="email">Email*</label>
                                @error('email') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m3">
                                <input type="text" id="cep" name="cep" value="{{ old('cep', $supplier->cep ?? '') }}" required>
                                <label for="cep">CEP*</label>
                                @error('cep') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m7">
                                <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $supplier->endereco ?? '') }}" required>
                                <label for="endereco">Endereço*</label>
                                @error('endereco') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m2">
                                <input type="text" id="numero" name="numero" value="{{ old('numero', $supplier->numero ?? '') }}" required>
                                <label for="numero">Número*</label>
                                @error('numero') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <input type="text" id="bairro" name="bairro" value="{{ old('bairro', $supplier->bairro ?? '') }}" required>
                                <label for="bairro">Bairro*</label>
                                @error('bairro') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $supplier->cidade ?? '') }}" required>
                                <label for="cidade">Cidade*</label>
                                @error('cidade') <span class="red-text">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <select name="estado" id="estado" required>
                                    <option value="" disabled selected>Selecione</option>
                                    @foreach(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $uf)
                                        <option value="{{ $uf }}" {{ old('estado', $supplier->estado ?? '') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                                    @endforeach
                                </select>
                                <label for="estado">Estado*</label>
                                @error('estado') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes', $supplier->observacoes ?? '') }}</textarea>
                                <label for="observacoes">Observações</label>
                                @error('observacoes') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn waves-effect waves-light">
                                Salvar
                                <i class="material-icons right">save</i>
                            </button>
                            <a href="{{ route('suppliers.index') }}" class="btn waves-effect waves-light red">
                                Cancelar
                                <i class="material-icons right">cancel</i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    $('.cnpj-mask').mask('00.000.000/0000-00');

    $('#buscar-cnpj').click(function() {
        const cnpj = $('#cnpj').val();
        if (!cnpj) {
            M.toast({html: 'Por favor, informe o CNPJ'});
            return;
        }

        $(this).attr('disabled', true).html('Buscando...');

        $.get(`/suppliers/find-cnpj/${cnpj}`)
            .done(function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#razao_social').val(data.razao_social).focus();
                    $('#nome_fantasia').val(data.nome_fantasia).focus();
                    $('#inscricao_estadual').val(data.inscricao_estadual).focus();
                    $('#logradouro').val(data.endereco.logradouro).focus();
                    $('#numero').val(data.endereco.numero).focus();
                    $('#complemento').val(data.endereco.complemento).focus();
                    $('#bairro').val(data.endereco.bairro).focus();
                    $('#municipio').val(data.endereco.municipio).focus();
                    $('#uf').val(data.endereco.uf).focus();
                    $('#cep').val(data.endereco.cep).focus();
                    $('#telefone').val(data.telefone).focus();
                    $('#email').val(data.email).focus();
                    
                    // Atualiza os labels do Materialize
                    M.updateTextFields();
                    M.toast({html: 'Dados do CNPJ carregados com sucesso!'});
                }
            })
            .fail(function(error) {
                M.toast({html: error.responseJSON?.message || 'Erro ao buscar CNPJ'});
            })
            .always(function() {
                $('#buscar-cnpj').attr('disabled', false).html('Buscar CNPJ <i class="material-icons right">search</i>');
            });
    });
});
</script>
@endpush
@endsection 
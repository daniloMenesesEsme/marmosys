@extends('layouts.app')

@section('title', isset($client) ? 'Editar Cliente' : 'Novo Cliente')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ isset($client) ? 'Editar Cliente' : 'Novo Cliente' }}</span>

                <form action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}" method="POST">
                    @csrf
                    @if(isset($client))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="nome" name="nome" value="{{ old('nome', $client->nome ?? '') }}" required>
                            <label for="nome">Nome/Razão Social*</label>
                            @error('nome') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <select name="tipo_pessoa" id="tipo_pessoa" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="F" {{ (old('tipo_pessoa', $client->tipo_pessoa ?? '') == 'F') ? 'selected' : '' }}>Pessoa Física</option>
                                <option value="J" {{ (old('tipo_pessoa', $client->tipo_pessoa ?? '') == 'J') ? 'selected' : '' }}>Pessoa Jurídica</option>
                            </select>
                            <label for="tipo_pessoa">Tipo de Pessoa*</label>
                            @error('tipo_pessoa') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="documento" name="documento" value="{{ old('documento', $client->documento ?? '') }}" required>
                            <label for="documento">CPF/CNPJ*</label>
                            @error('documento') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $client->telefone ?? '') }}" required>
                            <label for="telefone">Telefone*</label>
                            @error('telefone') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="email" id="email" name="email" value="{{ old('email', $client->email ?? '') }}">
                            <label for="email">Email</label>
                            @error('email') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m3">
                            <input type="text" id="cep" name="cep" value="{{ old('cep', $client->cep ?? '') }}" required>
                            <label for="cep">CEP*</label>
                            @error('cep') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m7">
                            <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $client->endereco ?? '') }}" required>
                            <label for="endereco">Endereço*</label>
                            @error('endereco') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m2">
                            <input type="text" id="numero" name="numero" value="{{ old('numero', $client->numero ?? '') }}" required>
                            <label for="numero">Número*</label>
                            @error('numero') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="complemento" name="complemento" value="{{ old('complemento', $client->complemento ?? '') }}">
                            <label for="complemento">Complemento</label>
                            @error('complemento') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="bairro" name="bairro" value="{{ old('bairro', $client->bairro ?? '') }}" required>
                            <label for="bairro">Bairro*</label>
                            @error('bairro') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $client->cidade ?? '') }}" required>
                            <label for="cidade">Cidade*</label>
                            @error('cidade') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <select name="estado" id="estado" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach(config('estados') as $uf => $nome)
                                    <option value="{{ $uf }}" {{ (old('estado', $client->estado ?? '') == $uf) ? 'selected' : '' }}>
                                        {{ $nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="estado">Estado*</label>
                            @error('estado') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m8">
                            <textarea id="observacoes" name="observacoes" class="materialize-textarea">{{ old('observacoes', $client->observacoes ?? '') }}</textarea>
                            <label for="observacoes">Observações</label>
                            @error('observacoes') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    var textareas = document.querySelectorAll('textarea');
    M.textareaAutoResize(textareas);

    // Máscaras
    $('#telefone').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');

    // Alterna máscara do documento baseado no tipo de pessoa
    $('#tipo_pessoa').change(function() {
        if (this.value === 'F') {
            $('#documento').mask('000.000.000-00');
        } else {
            $('#documento').mask('00.000.000/0000-00');
        }
    }).trigger('change');

    // Busca CEP
    document.getElementById('cep').addEventListener('blur', function() {
        var cep = this.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('endereco').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('estado').value = data.uf;
                        M.FormSelect.init(document.getElementById('estado'));
                        M.updateTextFields();
                    }
                });
        }
    });
});
</script>
@endpush
@endsection 
@extends('layouts.app')

@section('title', 'Novo Fornecedor')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Novo Fornecedor</span>

                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" required>
                            <label for="cnpj">CNPJ*</label>
                            @error('cnpj') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                        <div class="col s12 m2">
                            <button type="button" id="buscarCNPJ" class="btn waves-effect waves-light" style="margin-top: 20px;">
                                <i class="material-icons">search</i>
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social') }}" required>
                            <label for="razao_social">Razão Social*</label>
                            @error('razao_social') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia') }}">
                            <label for="nome_fantasia">Nome Fantasia</label>
                            @error('nome_fantasia') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual') }}">
                            <label for="inscricao_estadual">Inscrição Estadual</label>
                            @error('inscricao_estadual') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="inscricao_municipal" name="inscricao_municipal" value="{{ old('inscricao_municipal') }}">
                            <label for="inscricao_municipal">Inscrição Municipal</label>
                            @error('inscricao_municipal') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}">
                            <label for="telefone">Telefone</label>
                            @error('telefone') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="email" id="email" name="email" value="{{ old('email') }}">
                            <label for="email">E-mail</label>
                            @error('email') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="site" name="site" value="{{ old('site') }}">
                            <label for="site">Site</label>
                            @error('site') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" id="endereco" name="endereco" value="{{ old('endereco') }}">
                            <label for="endereco">Endereço</label>
                            @error('endereco') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="cep" name="cep" value="{{ old('cep') }}">
                            <label for="cep">CEP</label>
                            @error('cep') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="cidade" name="cidade" value="{{ old('cidade') }}">
                            <label for="cidade">Cidade</label>
                            @error('cidade') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <select id="estado" name="estado">
                                <option value="">Selecione</option>
                                @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                                    <option value="{{ $uf }}" {{ old('estado') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                                @endforeach
                            </select>
                            <label for="estado">Estado</label>
                            @error('estado') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <label>
                                <input type="checkbox" name="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }}>
                                <span>Ativo</span>
                            </label>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa o select do Materialize
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);

    // Busca CNPJ
    document.getElementById('buscarCNPJ').addEventListener('click', async function() {
        const cnpj = document.getElementById('cnpj').value.replace(/\D/g, '');
        
        if (cnpj.length !== 14) {
            M.toast({html: 'CNPJ inválido', classes: 'red'});
            return;
        }

        try {
            const response = await fetch(`/suppliers/find-cnpj/${cnpj}`);
            const result = await response.json();

            if (!result.success) {
                M.toast({html: result.message || 'CNPJ não encontrado', classes: 'red'});
                return;
            }

            const data = result.data;
            console.log('Dados recebidos:', data); // Para debug

            // Preenche os campos
            const campos = {
                'razao_social': data.razao_social,
                'nome_fantasia': data.nome_fantasia,
                'inscricao_estadual': data.inscricao_estadual,
                'email': data.email,
                'telefone': data.telefone,
                'endereco': data.endereco,
                'bairro': data.bairro,
                'cidade': data.cidade,
                'estado': data.estado,
                'cep': data.cep
            };

            // Preenche cada campo e atualiza o label
            Object.entries(campos).forEach(([id, valor]) => {
                const campo = document.getElementById(id);
                if (campo) {
                    campo.value = valor || '';
                    // Força o label a subir
                    campo.focus();
                    campo.blur();
                }
            });

            // Atualiza os labels do Materialize
            M.updateTextFields();
            
            // Se houver select de estado, atualiza
            const selectEstado = document.getElementById('estado');
            if (selectEstado) {
                selectEstado.value = data.estado;
                M.FormSelect.init(selectEstado);
            }
            
            M.toast({html: 'Dados carregados com sucesso!', classes: 'green'});
        } catch (error) {
            console.error('Erro:', error);
            M.toast({html: 'Erro ao buscar CNPJ', classes: 'red'});
        }
    });

    // Adiciona máscara ao CNPJ
    const cnpjInput = document.getElementById('cnpj');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 14) {
                value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    }
});
</script>
@endpush 
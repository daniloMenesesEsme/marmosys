@extends('layouts.app')

@section('title', isset($company) ? 'Editar Empresa' : 'Nova Empresa')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ isset($company) ? 'Editar Empresa' : 'Nova Empresa' }}</span>

                <form action="{{ isset($company) ? route('companies.update', $company) : route('companies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($company))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj', $company->cnpj ?? '') }}" required>
                            <label for="cnpj">CNPJ*</label>
                            @error('cnpj') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                        <div class="col s12 m2">
                            <button type="button" id="buscarCNPJ" class="btn waves-effect waves-light" style="margin-top: 20px;">
                                <i class="material-icons">search</i>
                            </button>
                        </div>
                    </div>

                    <!-- Logo da Empresa -->
                    <div class="row">
                        <div class="col s12 center-align">
                            @if(isset($company) && $company->logo_path)
                                <img src="{{ asset('storage/' . $company->logo_path) }}" 
                                     alt="Logo da Empresa" 
                                     style="max-width: 200px; margin-bottom: 20px;"
                                     class="responsive-img"
                                     onerror="this.src='{{ asset('images/default-logo.png') }}'">
                            @endif

                            <div class="file-field input-field">
                                <div class="btn">
                                    <span>Logo</span>
                                    <input type="file" name="logo" accept="image/jpeg,image/png,image/gif">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Selecione uma imagem para o logo">
                                </div>
                                @error('logo') <span class="red-text">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social', $company->razao_social ?? '') }}" required>
                            <label for="razao_social">Razão Social*</label>
                            @error('razao_social') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia', $company->nome_fantasia ?? '') }}">
                            <label for="nome_fantasia">Nome Fantasia</label>
                            @error('nome_fantasia') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual', $company->inscricao_estadual ?? '') }}">
                            <label for="inscricao_estadual">Inscrição Estadual</label>
                            @error('inscricao_estadual') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="inscricao_municipal" name="inscricao_municipal" value="{{ old('inscricao_municipal', $company->inscricao_municipal ?? '') }}">
                            <label for="inscricao_municipal">Inscrição Municipal</label>
                            @error('inscricao_municipal') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="email" id="email" name="email" value="{{ old('email', $company->email ?? '') }}">
                            <label for="email">Email</label>
                            @error('email') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m6">
                            <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $company->telefone ?? '') }}">
                            <label for="telefone">Telefone</label>
                            @error('telefone') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $company->endereco ?? '') }}">
                            <label for="endereco">Endereço</label>
                            @error('endereco') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input type="text" id="cep" name="cep" value="{{ old('cep', $company->cep ?? '') }}">
                            <label for="cep">CEP</label>
                            @error('cep') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $company->cidade ?? '') }}">
                            <label for="cidade">Cidade</label>
                            @error('cidade') <span class="red-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="input-field col s12 m4">
                            <input type="text" id="estado" name="estado" value="{{ old('estado', $company->estado ?? '') }}" maxlength="2">
                            <label for="estado">Estado (UF)</label>
                            @error('estado') <span class="red-text">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if(isset($company))
                        <div class="row">
                            <div class="input-field col s12">
                                <label>
                                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $company->ativo) ? 'checked' : '' }}>
                                    <span>Ativo</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            <a href="{{ route('companies.index') }}" class="btn waves-effect waves-light red">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscarCNPJ = document.getElementById('buscarCNPJ');
    const cnpjInput = document.getElementById('cnpj');

    buscarCNPJ.addEventListener('click', async function() {
        const cnpj = cnpjInput.value.replace(/[^\d]/g, '');
        
        if (cnpj.length !== 14) {
            M.toast({html: 'CNPJ inválido', classes: 'red'});
            return;
        }

        try {
            const response = await fetch(`/api/companies/find-cnpj/${cnpj}`);
            const result = await response.json();

            if (!result.success) {
                M.toast({html: result.message, classes: 'red'});
                return;
            }

            const data = result.data;
            
            // Preenche os campos
            document.getElementById('razao_social').value = data.razao_social;
            document.getElementById('nome_fantasia').value = data.nome_fantasia;
            document.getElementById('email').value = data.email || '';
            document.getElementById('telefone').value = data.telefone || '';
            document.getElementById('endereco').value = data.endereco;
            document.getElementById('cep').value = data.cep;
            document.getElementById('cidade').value = data.cidade;
            document.getElementById('estado').value = data.estado;
            document.getElementById('inscricao_estadual').value = data.inscricao_estadual || '';

            // Atualiza os labels do Materialize
            M.updateTextFields();
            
            M.toast({html: 'Dados carregados com sucesso!', classes: 'green'});
        } catch (error) {
            M.toast({html: 'Erro ao buscar CNPJ', classes: 'red'});
        }
    });
});
</script>
@endpush 
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Dados da Empresa</span>

                    <form action="{{ route('admin.company.update', $company) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="nome_empresa" type="text" name="nome_empresa" 
                                    value="{{ old('nome_empresa', $company->nome_empresa) }}" required>
                                <label for="nome_empresa">Nome da Empresa</label>
                            </div>

                            <div class="input-field col s6">
                                <input id="cnpj" type="text" name="cnpj" 
                                    value="{{ old('cnpj', $company->cnpj) }}" required>
                                <label for="cnpj">CNPJ</label>
                            </div>

                            <div class="input-field col s6">
                                <input id="telefone" type="text" name="telefone" 
                                    value="{{ old('telefone', $company->telefone) }}" required>
                                <label for="telefone">Telefone</label>
                            </div>

                            <div class="input-field col s12">
                                <input id="email" type="email" name="email" 
                                    value="{{ old('email', $company->email) }}" required>
                                <label for="email">E-mail</label>
                            </div>

                            <div class="input-field col s12">
                                <textarea id="endereco" name="endereco" class="materialize-textarea" required>{{ old('endereco', $company->endereco) }}</textarea>
                                <label for="endereco">Endereço</label>
                            </div>

                            <div class="file-field input-field col s12">
                                <div class="btn">
                                    <span>Logo</span>
                                    <input type="file" name="logo">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>

                            @if($company->logo_path)
                                <div class="col s12">
                                    <img src="{{ Storage::url($company->logo_path) }}" 
                                         alt="Logo atual" style="max-height: 100px">
                                </div>
                            @endif
                        </div>

                        <div class="card-action">
                            <button type="submit" class="btn waves-effect waves-light">
                                Salvar
                                <i class="material-icons right">save</i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
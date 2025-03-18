@extends('layouts.app')

@section('title', 'Importar Agentes Financeiros')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">
                        <i class="material-icons left">cloud_upload</i>
                        Importar Agentes Financeiros
                    </span>

                    <!-- Instrução -->
                    <div class="row">
                        <div class="col s12">
                            <div class="card blue lighten-5">
                                <div class="card-content blue-text">
                                    <span class="card-title">Instruções</span>
                                    <p>
                                        <b>1.</b> Faça o download do modelo clicando no botão abaixo.<br>
                                        <b>2.</b> Preencha os dados dos seus agentes financeiros no arquivo Excel.<br>
                                        <b>3.</b> Salve o arquivo e faça o upload abaixo.<br>
                                        <b>4.</b> Os campos obrigatórios são: <b>código</b>, <b>nome</b> e <b>tipo</b>.
                                    </p>
                                    <p>
                                        <b>Observações:</b>
                                        <ul>
                                            <li>• O <b>tipo</b> deve ser um dos seguintes: banco, financeira ou outros.</li>
                                            <li>• Caso informe a categoria financeira ou centro de custo que não exista, será criado automaticamente.</li>
                                            <li>• Para bancos, preencha os campos de código do banco, agência, conta e dígito.</li>
                                            <li>• Para financeiras, preencha o campo CNPJ.</li>
                                        </ul>
                                    </p>
                                </div>
                                <div class="card-action">
                                    <a href="{{ route('financial.reports.agents.template') }}" class="btn blue waves-effect waves-light">
                                        <i class="material-icons left">file_download</i>
                                        Baixar Modelo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário -->
                    <div class="row">
                        <form action="{{ route('financial.reports.agents.import') }}" method="POST" enctype="multipart/form-data" class="col s12">
                            @csrf
                            
                            <div class="row">
                                <div class="file-field input-field col s12">
                                    <div class="btn blue">
                                        <span>Arquivo</span>
                                        <input type="file" name="file" accept=".xlsx,.xls,.csv">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" placeholder="Selecione o arquivo Excel ou CSV">
                                    </div>
                                    @error('file')
                                        <span class="red-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <button type="submit" class="btn-large waves-effect waves-light green">
                                        <i class="material-icons left">cloud_upload</i>
                                        Importar
                                    </button>
                                    <a href="{{ route('financial.reports.agents') }}" class="btn-large waves-effect waves-light grey">
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
    </div>
</div>
@endsection 
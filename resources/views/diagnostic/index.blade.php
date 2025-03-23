@extends('layouts.app')

@section('title', 'Diagnóstico do Sistema')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">
                        <i class="material-icons left">bug_report</i> Diagnóstico do Sistema
                    </span>
                    
                    <div class="row">
                        <div class="col s12">
                            <ul class="tabs">
                                <li class="tab"><a href="#overview" class="active">Visão Geral</a></li>
                                <li class="tab"><a href="#migrations">Migrações</a></li>
                                <li class="tab"><a href="#models">Modelos</a></li>
                                <li class="tab"><a href="#tables">Tabelas</a></li>
                                <li class="tab"><a href="#errors">Erros</a></li>
                                <li class="tab"><a href="#system">Sistema</a></li>
                            </ul>
                        </div>
                        
                        <!-- Visão Geral -->
                        <div id="overview" class="col s12" style="margin-top: 20px;">
                            <div class="row">
                                <div class="col s12 m6 l3">
                                    <div class="card {{ $results['database']['status'] == 'success' ? 'green' : 'red' }} lighten-1">
                                        <div class="card-content white-text">
                                            <span class="card-title">Banco de Dados</span>
                                            <p>{{ $results['database']['message'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col s12 m6 l3">
                                    <div class="card {{ $results['migrations']['status'] == 'success' ? 'green' : ($results['migrations']['status'] == 'warning' ? 'orange' : 'red') }} lighten-1">
                                        <div class="card-content white-text">
                                            <span class="card-title">Migrações</span>
                                            <p>{{ $results['migrations']['count'] }} migrações encontradas</p>
                                            @if(isset($results['migrations']['duplicates']) && count($results['migrations']['duplicates']) > 0)
                                                <p>{{ count($results['migrations']['duplicates']) }} tabelas duplicadas!</p>
                                            @endif
                                        </div>
                                        <div class="card-action">
                                            <a href="#" class="white-text fix-action" data-type="migrations">Corrigir Migrações</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col s12 m6 l3">
                                    <div class="card {{ $results['models']['status'] == 'success' ? 'green' : 'red' }} lighten-1">
                                        <div class="card-content white-text">
                                            <span class="card-title">Modelos</span>
                                            <p>{{ $results['models']['count'] }} modelos encontrados</p>
                                        </div>
                                        <div class="card-action">
                                            <a href="#" class="white-text fix-action" data-type="models">Verificar Modelos</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col s12 m6 l3">
                                    <div class="card {{ $results['tables']['status'] == 'success' ? 'green' : 'red' }} lighten-1">
                                        <div class="card-content white-text">
                                            <span class="card-title">Tabelas</span>
                                            <p>{{ $results['tables']['count'] }} tabelas encontradas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col s12">
                                    <div class="card blue-grey darken-1">
                                        <div class="card-content white-text">
                                            <span class="card-title">Ações de Manutenção</span>
                                            <div class="row">
                                                <div class="col s6 m3">
                                                    <a href="#" class="btn blue-grey lighten-1 fix-action" data-type="cache">Limpar Cache</a>
                                                </div>
                                                <div class="col s6 m3">
                                                    <a href="#" class="btn blue-grey lighten-1 fix-action" data-type="logs">Limpar Logs</a>
                                                </div>
                                                <div class="col s6 m3">
                                                    <a href="#" class="btn blue-grey lighten-1" id="update-btn">Atualizar Página</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Migrações -->
                        <div id="migrations" class="col s12" style="margin-top: 20px;">
                            <h5>Migrações com Problemas</h5>
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Arquivo</th>
                                        <th>Data</th>
                                        <th>Problema</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['migrations']['details'] as $filename => $info)
                                        @if(isset($info['future_date']) && $info['future_date'])
                                            <tr class="orange lighten-4">
                                                <td>{{ $filename }}</td>
                                                <td>{{ $info['date'] ?? '' }}</td>
                                                <td>Data futura</td>
                                            </tr>
                                        @endif
                                        
                                        @if(isset($info['status']) && $info['status'] == 'warning')
                                            <tr class="orange lighten-4">
                                                <td>{{ $filename }}</td>
                                                <td>-</td>
                                                <td>{{ $info['message'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <h5>Tabelas Duplicadas</h5>
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Tabela</th>
                                        <th>Migrações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($results['migrations']['duplicates']) && count($results['migrations']['duplicates']) > 0)
                                        @foreach($results['migrations']['duplicates'] as $table => $migrations)
                                            <tr class="orange lighten-4">
                                                <td>{{ $table }}</td>
                                                <td>
                                                    <ul class="browser-default">
                                                        @foreach($migrations as $migration)
                                                            <li>{{ $migration }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="center-align">Nenhuma tabela duplicada encontrada</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Modelos -->
                        <div id="models" class="col s12" style="margin-top: 20px;">
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Modelo</th>
                                        <th>Tabela</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['models']['details'] as $model => $info)
                                        <tr class="{{ $info['status'] == 'success' ? '' : ($info['status'] == 'warning' ? 'orange lighten-4' : 'red lighten-4') }}">
                                            <td>{{ $model }}</td>
                                            <td>{{ $info['table'] ?? 'N/D' }}</td>
                                            <td>
                                                @if($info['status'] == 'success')
                                                    <i class="material-icons green-text">check_circle</i>
                                                @elseif($info['status'] == 'warning')
                                                    <i class="material-icons orange-text">warning</i> {{ $info['message'] ?? '' }}
                                                @else
                                                    <i class="material-icons red-text">error</i> {{ $info['message'] ?? '' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Tabelas -->
                        <div id="tables" class="col s12" style="margin-top: 20px;">
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>Tabela</th>
                                        <th>Colunas</th>
                                        <th>Registros</th>
                                        <th>ID</th>
                                        <th>Timestamps</th>
                                        <th>Soft Deletes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results['tables']['details'] as $table => $info)
                                        <tr>
                                            <td>{{ $table }}</td>
                                            <td>{{ $info['columns'] }}</td>
                                            <td>{{ $info['records'] }}</td>
                                            <td>{!! $info['has_id'] ? '<i class="material-icons green-text">check</i>' : '<i class="material-icons red-text">clear</i>' !!}</td>
                                            <td>{!! $info['has_timestamps'] ? '<i class="material-icons green-text">check</i>' : '<i class="material-icons red-text">clear</i>' !!}</td>
                                            <td>{!! $info['has_soft_deletes'] ? '<i class="material-icons green-text">check</i>' : '<i class="material-icons red-text">clear</i>' !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Erros -->
                        <div id="errors" class="col s12" style="margin-top: 20px;">
                            <h5>Erros Recentes</h5>
                            @if(isset($results['logs']['details']) && count($results['logs']['details']) > 0)
                                <ul class="collapsible">
                                    @foreach($results['logs']['details'] as $index => $error)
                                        <li>
                                            <div class="collapsible-header">
                                                <i class="material-icons red-text">error</i>
                                                <span class="grey-text">{{ $error['timestamp'] }}</span>
                                                <span class="truncate" style="max-width: 500px;">{{ \Illuminate\Support\Str::limit(strip_tags($error['message']), 100) }}</span>
                                            </div>
                                            <div class="collapsible-body">
                                                <pre style="white-space: pre-wrap;">{{ $error['message'] }}</pre>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Nenhum erro recente encontrado.</p>
                            @endif
                        </div>
                        
                        <!-- Sistema -->
                        <div id="system" class="col s12" style="margin-top: 20px;">
                            <table class="striped">
                                <tbody>
                                    <tr>
                                        <th width="200">Versão do PHP</th>
                                        <td>{{ $results['info']['php_version'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Versão do Laravel</th>
                                        <td>{{ $results['info']['laravel_version'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Servidor</th>
                                        <td>{{ $results['info']['server'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sistema</th>
                                        <td>{{ $results['info']['system'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Limite de Memória</th>
                                        <td>{{ $results['info']['memory_limit'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tempo Máximo de Execução</th>
                                        <td>{{ $results['info']['max_execution_time'] }} segundos</td>
                                    </tr>
                                    <tr>
                                        <th>Tamanho Máximo de POST</th>
                                        <td>{{ $results['info']['post_max_size'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tamanho Máximo de Upload</th>
                                        <td>{{ $results['info']['upload_max_filesize'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Resultado -->
    <div id="result-modal" class="modal">
        <div class="modal-content">
            <h4>Resultado da Operação</h4>
            <div id="result-content"></div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Fechar</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa as tabs
        var tabs = document.querySelectorAll('.tabs');
        M.Tabs.init(tabs);
        
        // Inicializa o collapsible
        var collapsibles = document.querySelectorAll('.collapsible');
        M.Collapsible.init(collapsibles);
        
        // Inicializa o modal
        var modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);
        
        // Ações de correção
        var fixButtons = document.querySelectorAll('.fix-action');
        fixButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                var type = this.getAttribute('data-type');
                performFix(type);
            });
        });
        
        // Botão de atualização
        var updateButton = document.getElementById('update-btn');
        updateButton.addEventListener('click', function(e) {
            e.preventDefault();
            location.reload();
        });
    });
    
    function performFix(type) {
        // Exibe um toast de carregamento
        M.toast({html: 'Executando... aguarde', classes: 'blue'});
        
        fetch('{{ route("diagnostic.fix") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({type: type})
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                M.toast({html: data.message, classes: 'green'});
                
                if (data.output) {
                    // Exibe o output detalhado no modal
                    var modal = M.Modal.getInstance(document.getElementById('result-modal'));
                    document.getElementById('result-content').innerHTML = '<pre>' + data.output + '</pre>';
                    modal.open();
                }
            } else {
                M.toast({html: data.message, classes: 'red'});
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            M.toast({html: 'Erro ao executar a operação', classes: 'red'});
        });
    }
</script>
@endsection 
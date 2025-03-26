

<?php $__env->startSection('title', 'Diagnóstico do Sistema'); ?>

<?php $__env->startSection('content'); ?>
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
                                    <div class="card <?php echo e($results['database']['status'] == 'success' ? 'green' : 'red'); ?> lighten-1">
                                        <div class="card-content white-text">
                                            <span class="card-title">Banco de Dados</span>
                                            <p><?php echo e($results['database']['message']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col s12 m6 l3">
                                    <div class="card <?php echo e($results['migrations']['status'] == 'success' ? 'green' : ($results['migrations']['status'] == 'warning' ? 'orange' : 'red')); ?> lighten-1">
                                        <div class="card-content white-text">
                                            <span class="card-title">Migrações</span>
                                            <p><?php echo e($results['migrations']['count']); ?> migrações encontradas</p>
                                            <?php if(isset($results['migrations']['duplicates']) && count($results['migrations']['duplicates']) > 0): ?>
                                                <p><?php echo e(count($results['migrations']['duplicates'])); ?> tabelas duplicadas!</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-action">
                                            <a href="#" class="white-text fix-action" data-type="migrations">Corrigir Migrações</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col s12 m6 l3">
                                    <div class="card <?php echo e($results['models']['status'] == 'success' ? 'green' : 'red'); ?> lighten-1">
                                        <div class="card-content white-text">
                                            <span class="card-title">Modelos</span>
                                            <p><?php echo e($results['models']['count']); ?> modelos encontrados</p>
                                        </div>
                                        <div class="card-action">
                                            <a href="#" class="white-text fix-action" data-type="models">Verificar Modelos</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col s12 m6 l3">
                                    <div class="card <?php echo e($results['tables']['status'] == 'success' ? 'green' : 'red'); ?> lighten-1">
                                        <div class="card-content white-text">
                                            <span class="card-title">Tabelas</span>
                                            <p><?php echo e($results['tables']['count']); ?> tabelas encontradas</p>
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
                                    <?php $__currentLoopData = $results['migrations']['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filename => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(isset($info['future_date']) && $info['future_date']): ?>
                                            <tr class="orange lighten-4">
                                                <td><?php echo e($filename); ?></td>
                                                <td><?php echo e($info['date'] ?? ''); ?></td>
                                                <td>Data futura</td>
                                            </tr>
                                        <?php endif; ?>
                                        
                                        <?php if(isset($info['status']) && $info['status'] == 'warning'): ?>
                                            <tr class="orange lighten-4">
                                                <td><?php echo e($filename); ?></td>
                                                <td>-</td>
                                                <td><?php echo e($info['message']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php if(isset($results['migrations']['duplicates']) && count($results['migrations']['duplicates']) > 0): ?>
                                        <?php $__currentLoopData = $results['migrations']['duplicates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table => $migrations): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="orange lighten-4">
                                                <td><?php echo e($table); ?></td>
                                                <td>
                                                    <ul class="browser-default">
                                                        <?php $__currentLoopData = $migrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $migration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li><?php echo e($migration); ?></li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="center-align">Nenhuma tabela duplicada encontrada</td>
                                        </tr>
                                    <?php endif; ?>
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
                                    <?php $__currentLoopData = $results['models']['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="<?php echo e($info['status'] == 'success' ? '' : ($info['status'] == 'warning' ? 'orange lighten-4' : 'red lighten-4')); ?>">
                                            <td><?php echo e($model); ?></td>
                                            <td><?php echo e($info['table'] ?? 'N/D'); ?></td>
                                            <td>
                                                <?php if($info['status'] == 'success'): ?>
                                                    <i class="material-icons green-text">check_circle</i>
                                                <?php elseif($info['status'] == 'warning'): ?>
                                                    <i class="material-icons orange-text">warning</i> <?php echo e($info['message'] ?? ''); ?>

                                                <?php else: ?>
                                                    <i class="material-icons red-text">error</i> <?php echo e($info['message'] ?? ''); ?>

                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php $__currentLoopData = $results['tables']['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($table); ?></td>
                                            <td><?php echo e($info['columns']); ?></td>
                                            <td><?php echo e($info['records']); ?></td>
                                            <td><?php echo $info['has_id'] ? '<i class="material-icons green-text">check</i>' : '<i class="material-icons red-text">clear</i>'; ?></td>
                                            <td><?php echo $info['has_timestamps'] ? '<i class="material-icons green-text">check</i>' : '<i class="material-icons red-text">clear</i>'; ?></td>
                                            <td><?php echo $info['has_soft_deletes'] ? '<i class="material-icons green-text">check</i>' : '<i class="material-icons red-text">clear</i>'; ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Erros -->
                        <div id="errors" class="col s12" style="margin-top: 20px;">
                            <h5>Erros Recentes</h5>
                            <?php if(isset($results['logs']['details']) && count($results['logs']['details']) > 0): ?>
                                <ul class="collapsible">
                                    <?php $__currentLoopData = $results['logs']['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <div class="collapsible-header">
                                                <i class="material-icons red-text">error</i>
                                                <span class="grey-text"><?php echo e($error['timestamp']); ?></span>
                                                <span class="truncate" style="max-width: 500px;"><?php echo e(\Illuminate\Support\Str::limit(strip_tags($error['message']), 100)); ?></span>
                                            </div>
                                            <div class="collapsible-body">
                                                <pre style="white-space: pre-wrap;"><?php echo e($error['message']); ?></pre>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <p>Nenhum erro recente encontrado.</p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Sistema -->
                        <div id="system" class="col s12" style="margin-top: 20px;">
                            <table class="striped">
                                <tbody>
                                    <tr>
                                        <th width="200">Versão do PHP</th>
                                        <td><?php echo e($results['info']['php_version']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Versão do Laravel</th>
                                        <td><?php echo e($results['info']['laravel_version']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Servidor</th>
                                        <td><?php echo e($results['info']['server']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Sistema</th>
                                        <td><?php echo e($results['info']['system']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Limite de Memória</th>
                                        <td><?php echo e($results['info']['memory_limit']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tempo Máximo de Execução</th>
                                        <td><?php echo e($results['info']['max_execution_time']); ?> segundos</td>
                                    </tr>
                                    <tr>
                                        <th>Tamanho Máximo de POST</th>
                                        <td><?php echo e($results['info']['post_max_size']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tamanho Máximo de Upload</th>
                                        <td><?php echo e($results['info']['upload_max_filesize']); ?></td>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
        
        fetch('<?php echo e(route("diagnostic.fix")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/diagnostic/index.blade.php ENDPATH**/ ?>
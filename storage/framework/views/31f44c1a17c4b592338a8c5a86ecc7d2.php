

<?php $__env->startSection('title', 'Metas Financeiras'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Metas Financeiras
                    <a href="#modal-novo" class="btn-floating btn waves-effect waves-light right modal-trigger">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <!-- Lista de Metas -->
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Meta</th>
                            <th>Atual</th>
                            <th>Progresso</th>
                            <th>Data Final</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($goal->descricao); ?></td>
                                <td>R$ <?php echo e(number_format($goal->valor_meta, 2, ',', '.')); ?></td>
                                <td>R$ <?php echo e(number_format($goal->valor_atual, 2, ',', '.')); ?></td>
                                <td>
                                    <div class="progress">
                                        <div class="determinate" style="width: <?php echo e($goal->percentual); ?>%"></div>
                                    </div>
                                    <?php echo e($goal->percentual); ?>%
                                </td>
                                <td><?php echo e($goal->data_final->format('d/m/Y')); ?></td>
                                <td>
                                    <?php if($goal->status == 'em_andamento'): ?>
                                        <span class="new badge orange" data-badge-caption="">Em Andamento</span>
                                    <?php elseif($goal->status == 'concluida'): ?>
                                        <span class="new badge green" data-badge-caption="">Concluída</span>
                                    <?php else: ?>
                                        <span class="new badge grey" data-badge-caption="">Cancelada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="#modal-editar-<?php echo e($goal->id); ?>" class="btn-small waves-effect waves-light modal-trigger">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <a href="#modal-deletar-<?php echo e($goal->id); ?>" class="btn-small red waves-effect waves-light modal-trigger">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="center">Nenhuma meta encontrada</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo -->
<div id="modal-novo" class="modal">
    <form action="<?php echo e(route('financial.goals.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-content">
            <h4>Nova Meta</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="descricao" name="descricao" required>
                    <label for="descricao">Descrição</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="number" step="0.01" id="valor_meta" name="valor_meta" required>
                    <label for="valor_meta">Valor da Meta</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="number" step="0.01" id="valor_atual" name="valor_atual" value="0">
                    <label for="valor_atual">Valor Atual</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="date" id="data_inicial" name="data_inicial" value="<?php echo e(date('Y-m-d')); ?>" required>
                    <label for="data_inicial">Data Inicial</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="date" id="data_final" name="data_final" required>
                    <label for="data_final">Data Final</label>
                </div>

                <div class="input-field col s12">
                    <textarea id="observacoes" name="observacoes" class="materialize-textarea"></textarea>
                    <label for="observacoes">Observações</label>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
        </div>
    </form>
</div>

<!-- Modais de Edição -->
<?php $__currentLoopData = $goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div id="modal-editar-<?php echo e($goal->id); ?>" class="modal">
        <form action="<?php echo e(route('financial.goals.update', $goal)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="modal-content">
                <h4>Editar Meta</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="descricao-<?php echo e($goal->id); ?>" name="descricao" value="<?php echo e($goal->descricao); ?>" required>
                        <label for="descricao-<?php echo e($goal->id); ?>">Descrição</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="number" step="0.01" id="valor_meta-<?php echo e($goal->id); ?>" name="valor_meta" value="<?php echo e($goal->valor_meta); ?>" required>
                        <label for="valor_meta-<?php echo e($goal->id); ?>">Valor da Meta</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="number" step="0.01" id="valor_atual-<?php echo e($goal->id); ?>" name="valor_atual" value="<?php echo e($goal->valor_atual); ?>">
                        <label for="valor_atual-<?php echo e($goal->id); ?>">Valor Atual</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="date" id="data_inicial-<?php echo e($goal->id); ?>" name="data_inicial" value="<?php echo e($goal->data_inicial->format('Y-m-d')); ?>" required>
                        <label for="data_inicial-<?php echo e($goal->id); ?>">Data Inicial</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="date" id="data_final-<?php echo e($goal->id); ?>" name="data_final" value="<?php echo e($goal->data_final->format('Y-m-d')); ?>" required>
                        <label for="data_final-<?php echo e($goal->id); ?>">Data Final</label>
                    </div>

                    <div class="input-field col s12">
                        <select name="status" required>
                            <option value="em_andamento" <?php echo e($goal->status == 'em_andamento' ? 'selected' : ''); ?>>Em Andamento</option>
                            <option value="concluida" <?php echo e($goal->status == 'concluida' ? 'selected' : ''); ?>>Concluída</option>
                            <option value="cancelada" <?php echo e($goal->status == 'cancelada' ? 'selected' : ''); ?>>Cancelada</option>
                        </select>
                        <label>Status</label>
                    </div>

                    <div class="input-field col s12">
                        <textarea id="observacoes-<?php echo e($goal->id); ?>" name="observacoes" class="materialize-textarea"><?php echo e($goal->observacoes); ?></textarea>
                        <label for="observacoes-<?php echo e($goal->id); ?>">Observações</label>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
            </div>
        </form>
    </div>

    <!-- Modal de Exclusão -->
    <div id="modal-deletar-<?php echo e($goal->id); ?>" class="modal">
        <form action="<?php echo e(route('financial.goals.destroy', $goal)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja excluir esta meta?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Confirmar</button>
            </div>
        </form>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);
        
        var selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);
        
        var textareas = document.querySelectorAll('.materialize-textarea');
        M.textareaAutoResize(textareas);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/goals/index.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Orçamentos'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Orçamentos</span>
                
                <div class="row">
                    <form class="col s12" method="GET">
                        <div class="row">
                            <div class="input-field col s6 m3">
                                <select name="mes" id="mes">
                                    <?php
                                        $meses = [
                                            1 => 'Janeiro',
                                            2 => 'Fevereiro',
                                            3 => 'Março',
                                            4 => 'Abril',
                                            5 => 'Maio',
                                            6 => 'Junho',
                                            7 => 'Julho',
                                            8 => 'Agosto',
                                            9 => 'Setembro',
                                            10 => 'Outubro',
                                            11 => 'Novembro',
                                            12 => 'Dezembro'
                                        ];
                                    ?>
                                    <?php $__currentLoopData = $meses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $numero => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($numero); ?>" <?php echo e($mes == $numero ? 'selected' : ''); ?>>
                                            <?php echo e($nome); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <label for="mes">Mês</label>
                            </div>
                            
                            <div class="input-field col s6 m3">
                                <select name="ano" id="ano">
                                    <?php for($i = date('Y') - 2; $i <= date('Y') + 2; $i++): ?>
                                        <option value="<?php echo e($i); ?>" <?php echo e($ano == $i ? 'selected' : ''); ?>>
                                            <?php echo e($i); ?>

                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <label for="ano">Ano</label>
                            </div>
                            
                            <div class="input-field col s12 m6">
                                <button class="btn waves-effect waves-light" type="submit">
                                    Filtrar
                                    <i class="material-icons right">search</i>
                                </button>
                                
                                <a href="#modal-novo" class="btn waves-effect waves-light modal-trigger">
                                    Novo Orçamento
                                    <i class="material-icons right">add</i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Valor</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($budget->categoria->nome); ?></td>
                                <td>R$ <?php echo e(number_format($budget->valor, 2, ',', '.')); ?></td>
                                <td>
                                    <a href="#modal-editar-<?php echo e($budget->id); ?>" class="btn-small waves-effect waves-light modal-trigger">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <a href="#modal-deletar-<?php echo e($budget->id); ?>" class="btn-small red waves-effect waves-light modal-trigger">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="center">Nenhum orçamento encontrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Orçamento -->
<div id="modal-novo" class="modal">
    <form action="<?php echo e(route('financial.budgets.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="mes" value="<?php echo e($mes); ?>">
        <input type="hidden" name="ano" value="<?php echo e($ano); ?>">
        <div class="modal-content">
            <h4>Novo Orçamento</h4>
            <div class="row">
                <div class="input-field col s12">
                    <select name="categoria_id" required>
                        <option value="" disabled selected>Selecione uma categoria</option>
                        <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($categoria->id); ?>"><?php echo e($categoria->nome); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <label>Categoria</label>
                </div>
                <div class="input-field col s12">
                    <input type="number" step="0.01" name="valor" id="valor" required>
                    <label for="valor">Valor</label>
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
<?php $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div id="modal-editar-<?php echo e($budget->id); ?>" class="modal">
        <form action="<?php echo e(route('financial.budgets.update', $budget)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="modal-content">
                <h4>Editar Orçamento</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <select name="categoria_id" required>
                            <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($categoria->id); ?>" <?php echo e($budget->categoria_id == $categoria->id ? 'selected' : ''); ?>>
                                    <?php echo e($categoria->nome); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <label>Categoria</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="number" step="0.01" name="valor" value="<?php echo e($budget->valor); ?>" required>
                        <label>Valor</label>
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
    <div id="modal-deletar-<?php echo e($budget->id); ?>" class="modal">
        <form action="<?php echo e(route('financial.budgets.destroy', $budget)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja excluir este orçamento?</p>
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
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/budgets/index.blade.php ENDPATH**/ ?>
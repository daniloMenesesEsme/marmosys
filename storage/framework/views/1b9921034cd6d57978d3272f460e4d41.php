

<?php $__env->startSection('title', 'Contas'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Contas <?php echo e(request('tipo', 'receita') == 'receita' ? 'a Receber' : 'a Pagar'); ?>

                    <a href="#modal-novo" class="btn-floating btn waves-effect waves-light right modal-trigger">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <!-- Filtros -->
                <div class="row">
                    <form class="col s12" method="GET">
                        <div class="row">
                            <div class="input-field col s12 m3">
                                <select name="tipo" id="tipo" onchange="this.form.submit()">
                                    <option value="receita" <?php echo e(request('tipo', 'receita') == 'receita' ? 'selected' : ''); ?>>Contas a Receber</option>
                                    <option value="despesa" <?php echo e(request('tipo') == 'despesa' ? 'selected' : ''); ?>>Contas a Pagar</option>
                                </select>
                                <label for="tipo">Tipo</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <select name="status" id="status" onchange="this.form.submit()">
                                    <option value="">Todos</option>
                                    <option value="pendente" <?php echo e(request('status') == 'pendente' ? 'selected' : ''); ?>>Pendentes</option>
                                    <option value="pago" <?php echo e(request('status') == 'pago' ? 'selected' : ''); ?>>Pagas</option>
                                    <option value="cancelado" <?php echo e(request('status') == 'cancelado' ? 'selected' : ''); ?>>Canceladas</option>
                                </select>
                                <label for="status">Status</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="date" id="data_inicio" name="data_inicio" value="<?php echo e(request('data_inicio')); ?>">
                                <label for="data_inicio">Data Inicial</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="date" id="data_fim" name="data_fim" value="<?php echo e(request('data_fim')); ?>">
                                <label for="data_fim">Data Final</label>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Totais -->
                <div class="row">
                    <div class="col s12 m3">
                        <div class="card-panel orange lighten-4">
                            <span class="card-title">A Receber</span>
                            <h5>R$ <?php echo e(number_format($totais['a_receber'], 2, ',', '.')); ?></h5>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="card-panel red lighten-4">
                            <span class="card-title">A Pagar</span>
                            <h5>R$ <?php echo e(number_format($totais['a_pagar'], 2, ',', '.')); ?></h5>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="card-panel green lighten-4">
                            <span class="card-title">Recebido</span>
                            <h5>R$ <?php echo e(number_format($totais['recebido'], 2, ',', '.')); ?></h5>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="card-panel blue lighten-4">
                            <span class="card-title">Pago</span>
                            <h5>R$ <?php echo e(number_format($totais['pago'], 2, ',', '.')); ?></h5>
                        </div>
                    </div>
                </div>

                <!-- Lista de Contas -->
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($account->descricao); ?></td>
                                <td><?php echo e($account->category->nome); ?></td>
                                <td><?php echo e($account->data_vencimento->format('d/m/Y')); ?></td>
                                <td>R$ <?php echo e(number_format($account->valor, 2, ',', '.')); ?></td>
                                <td>
                                    <?php if($account->status == 'pendente'): ?>
                                        <span class="new badge orange" data-badge-caption="">Pendente</span>
                                    <?php elseif($account->status == 'pago'): ?>
                                        <span class="new badge green" data-badge-caption="">Pago</span>
                                    <?php else: ?>
                                        <span class="new badge grey" data-badge-caption="">Cancelado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="#modal-editar-<?php echo e($account->id); ?>" class="btn-small waves-effect waves-light modal-trigger">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <?php if($account->status == 'pendente'): ?>
                                        <a href="#modal-pagar-<?php echo e($account->id); ?>" class="btn-small green waves-effect waves-light modal-trigger">
                                            <i class="material-icons">payment</i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="#modal-deletar-<?php echo e($account->id); ?>" class="btn-small red waves-effect waves-light modal-trigger">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="center">Nenhuma conta encontrada</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php echo e($accounts->links()); ?>

            </div>
        </div>
    </div>
</div>

<!-- Modal Novo -->
<div id="modal-novo" class="modal">
    <form action="<?php echo e(route('financial.accounts.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="tipo" value="<?php echo e(request('tipo', 'receita')); ?>">
        
        <div class="modal-content">
            <h4>Nova Conta</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="descricao" name="descricao" required>
                    <label for="descricao">Descrição</label>
                </div>
                
                <div class="input-field col s12">
                    <select name="category_id" required>
                        <option value="" disabled selected>Selecione uma categoria</option>
                        <?php $__currentLoopData = $categories['receita'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->nome); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <label>Categoria</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="number" step="0.01" id="valor" name="valor" required>
                    <label for="valor">Valor</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="date" id="data_vencimento" name="data_vencimento" required>
                    <label for="data_vencimento">Data de Vencimento</label>
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
<?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div id="modal-editar-<?php echo e($account->id); ?>" class="modal">
        <form action="<?php echo e(route('financial.accounts.update', $account)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="modal-content">
                <h4>Editar Conta</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="descricao-<?php echo e($account->id); ?>" name="descricao" value="<?php echo e($account->descricao); ?>" required>
                        <label for="descricao-<?php echo e($account->id); ?>">Descrição</label>
                    </div>
                    
                    <div class="input-field col s12">
                        <select name="category_id" required>
                            <?php $__currentLoopData = $categories['receita'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e($account->category_id == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->nome); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <label>Categoria</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="number" step="0.01" id="valor-<?php echo e($account->id); ?>" name="valor" value="<?php echo e($account->valor); ?>" required>
                        <label for="valor-<?php echo e($account->id); ?>">Valor</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="date" id="data_vencimento-<?php echo e($account->id); ?>" name="data_vencimento" value="<?php echo e($account->data_vencimento->format('Y-m-d')); ?>" required>
                        <label for="data_vencimento-<?php echo e($account->id); ?>">Data de Vencimento</label>
                    </div>

                    <div class="input-field col s12">
                        <select name="status" required>
                            <option value="pendente" <?php echo e($account->status == 'pendente' ? 'selected' : ''); ?>>Pendente</option>
                            <option value="pago" <?php echo e($account->status == 'pago' ? 'selected' : ''); ?>>Pago</option>
                            <option value="cancelado" <?php echo e($account->status == 'cancelado' ? 'selected' : ''); ?>>Cancelado</option>
                        </select>
                        <label>Status</label>
                    </div>

                    <div class="input-field col s12">
                        <textarea id="observacoes-<?php echo e($account->id); ?>" name="observacoes" class="materialize-textarea"><?php echo e($account->observacoes); ?></textarea>
                        <label for="observacoes-<?php echo e($account->id); ?>">Observações</label>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
            </div>
        </form>
    </div>

    <!-- Modal de Pagamento -->
    <?php if($account->status == 'pendente'): ?>
        <div id="modal-pagar-<?php echo e($account->id); ?>" class="modal">
            <form action="<?php echo e(route('financial.accounts.pay', $account)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="modal-content">
                    <h4>Registrar Pagamento</h4>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="date" id="data_pagamento-<?php echo e($account->id); ?>" name="data_pagamento" value="<?php echo e(date('Y-m-d')); ?>" required>
                            <label for="data_pagamento-<?php echo e($account->id); ?>">Data do Pagamento</label>
                        </div>

                        <div class="input-field col s12">
                            <select name="forma_pagamento" required>
                                <option value="" disabled selected>Selecione a forma de pagamento</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="pix">PIX</option>
                                <option value="cartao">Cartão</option>
                                <option value="boleto">Boleto</option>
                                <option value="transferencia">Transferência</option>
                            </select>
                            <label>Forma de Pagamento</label>
                        </div>

                        <div class="input-field col s12">
                            <textarea id="observacoes-pag-<?php echo e($account->id); ?>" name="observacoes" class="materialize-textarea"></textarea>
                            <label for="observacoes-pag-<?php echo e($account->id); ?>">Observações</label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                    <button type="submit" class="waves-effect waves-green btn-flat">Confirmar</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Modal de Exclusão -->
    <div id="modal-deletar-<?php echo e($account->id); ?>" class="modal">
        <form action="<?php echo e(route('financial.accounts.destroy', $account)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja excluir esta conta?</p>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/accounts/index.blade.php ENDPATH**/ ?>
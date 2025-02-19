

<?php $__env->startSection('title', 'Clientes'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Clientes
                    <a href="#modal-novo" class="btn-floating btn waves-effect waves-light right modal-trigger">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>CPF/CNPJ</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($client->nome); ?></td>
                                <td><?php echo e($client->email); ?></td>
                                <td><?php echo e($client->telefone); ?></td>
                                <td><?php echo e($client->cpf_cnpj); ?></td>
                                <td>
                                    <?php if($client->ativo): ?>
                                        <span class="new badge green" data-badge-caption="">Ativo</span>
                                    <?php else: ?>
                                        <span class="new badge grey" data-badge-caption="">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="#modal-editar-<?php echo e($client->id); ?>" class="btn-small waves-effect waves-light modal-trigger">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <?php if($client->ativo): ?>
                                        <a href="#modal-deletar-<?php echo e($client->id); ?>" class="btn-small red waves-effect waves-light modal-trigger">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="center">Nenhum cliente cadastrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php echo e($clients->links()); ?>

            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Cliente -->
<div id="modal-novo" class="modal">
    <form action="<?php echo e(route('clients.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-content">
            <h4>Novo Cliente</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="nome" name="nome" required>
                    <label for="nome">Nome</label>
                </div>
                <div class="input-field col s12">
                    <input type="email" id="email" name="email">
                    <label for="email">Email</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" id="telefone" name="telefone">
                    <label for="telefone">Telefone</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" id="cpf_cnpj" name="cpf_cnpj">
                    <label for="cpf_cnpj">CPF/CNPJ</label>
                </div>
                <div class="input-field col s12">
                    <textarea id="endereco" name="endereco" class="materialize-textarea"></textarea>
                    <label for="endereco">Endereço</label>
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
<?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div id="modal-editar-<?php echo e($client->id); ?>" class="modal">
        <form action="<?php echo e(route('clients.update', $client)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="modal-content">
                <h4>Editar Cliente</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="nome-<?php echo e($client->id); ?>" name="nome" value="<?php echo e($client->nome); ?>" required>
                        <label for="nome-<?php echo e($client->id); ?>">Nome</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="email" id="email-<?php echo e($client->id); ?>" name="email" value="<?php echo e($client->email); ?>">
                        <label for="email-<?php echo e($client->id); ?>">Email</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" id="telefone-<?php echo e($client->id); ?>" name="telefone" value="<?php echo e($client->telefone); ?>">
                        <label for="telefone-<?php echo e($client->id); ?>">Telefone</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" id="cpf_cnpj-<?php echo e($client->id); ?>" name="cpf_cnpj" value="<?php echo e($client->cpf_cnpj); ?>">
                        <label for="cpf_cnpj-<?php echo e($client->id); ?>">CPF/CNPJ</label>
                    </div>
                    <div class="input-field col s12">
                        <textarea id="endereco-<?php echo e($client->id); ?>" name="endereco" class="materialize-textarea"><?php echo e($client->endereco); ?></textarea>
                        <label for="endereco-<?php echo e($client->id); ?>">Endereço</label>
                    </div>
                    <div class="input-field col s12">
                        <label>
                            <input type="checkbox" name="ativo" value="1" <?php echo e($client->ativo ? 'checked' : ''); ?>>
                            <span>Ativo</span>
                        </label>
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
    <div id="modal-deletar-<?php echo e($client->id); ?>" class="modal">
        <form action="<?php echo e(route('clients.destroy', $client)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja desativar o cliente "<?php echo e($client->nome); ?>"?</p>
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
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/clients/index.blade.php ENDPATH**/ ?>
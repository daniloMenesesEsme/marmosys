<?php $__env->startSection('title', 'Detalhes do Orçamento'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Orçamento <?php echo e($budget->numero); ?></span>

                <div class="row">
                    <div class="col s12 m6">
                        <h6>Dados do Cliente</h6>
                        <p><strong>Nome:</strong> <?php echo e($budget->client->nome); ?></p>
                        <p><strong>CPF/CNPJ:</strong> <?php echo e($budget->client->cpf_cnpj ?? 'Não informado'); ?></p>
                        <p><strong>Endereço:</strong> <?php echo e($budget->client->endereco ?? 'Não informado'); ?></p>
                        <p><strong>Telefone:</strong> <?php echo e($budget->client->telefone ?? 'Não informado'); ?></p>
                        <p><strong>Email:</strong> <?php echo e($budget->client->email ?? 'Não informado'); ?></p>
                    </div>
                    <div class="col s12 m6">
                        <h6>Dados do Orçamento</h6>
                        <p><strong>Data:</strong> <?php echo e($budget->data->format('d/m/Y')); ?></p>
                        <p><strong>Validade:</strong> <?php echo e($budget->data_validade->format('d/m/Y')); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="chip <?php echo e($budget->status_class); ?>">
                                <?php echo e($budget->status_text); ?>

                            </span>
                        </p>
                        <p><strong>Valor Total:</strong> R$ <?php echo e(number_format($budget->valor_total, 2, ',', '.')); ?></p>
                        <p><strong>Desconto:</strong> R$ <?php echo e(number_format($budget->desconto, 2, ',', '.')); ?></p>
                        <p><strong>Valor Final:</strong> R$ <?php echo e(number_format($budget->valor_final, 2, ',', '.')); ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <h5>Itens do Orçamento</h5>
                        <?php $__currentLoopData = $budget->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card">
                                <div class="card-content">
                                    <span class="card-title"><?php echo e($room->nome); ?></span>
                                    <table class="striped responsive-table">
                                        <thead>
                                            <tr>
                                                <th>Material</th>
                                                <th>Quantidade</th>
                                                <th>Unid.</th>
                                                <th>Dimensões</th>
                                                <th>Valor Unit.</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $room->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($item->material->nome); ?></td>
                                                    <td><?php echo e(number_format($item->quantidade, 3, ',', '.')); ?></td>
                                                    <td><?php echo e($item->unidade); ?></td>
                                                    <td><?php echo e(number_format($item->largura, 2, ',', '.')); ?>m x <?php echo e(number_format($item->altura, 2, ',', '.')); ?>m</td>
                                                    <td>R$ <?php echo e(number_format($item->valor_unitario, 2, ',', '.')); ?></td>
                                                    <td>R$ <?php echo e(number_format($item->valor_total, 2, ',', '.')); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td colspan="5" class="right-align"><strong>Subtotal do Ambiente:</strong></td>
                                                <td><strong>R$ <?php echo e(number_format($room->valor_total, 2, ',', '.')); ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="card-action" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="<?php echo e(route('financial.budgets.edit', $budget)); ?>" class="btn waves-effect waves-light teal">
                        <i class="material-icons left">edit</i>
                        EDITAR
                    </a>
                    
                    <a href="<?php echo e(route('financial.budgets.pdf', $budget)); ?>" class="btn purple waves-effect waves-light">
                        <i class="material-icons left">picture_as_pdf</i>
                        GERAR PDF
                    </a>
                    
                    <a href="#" class="btn blue waves-effect waves-light">
                        <i class="material-icons left">print</i>
                        IMPRIMIR
                    </a>

                    <a href="#" class="btn red waves-effect waves-light" onclick="event.preventDefault(); if(confirm('Tem certeza?')) document.getElementById('form-delete').submit();">
                        <i class="material-icons left">delete</i>
                        EXCLUIR
                    </a>

                    <?php if($budget->status === 'aguardando_aprovacao'): ?>
                        <button type="button" onclick="aprovarOrcamento()" class="btn green waves-effect waves-light">
                            <i class="material-icons left">check</i>
                            APROVAR
                        </button>

                        <button type="button" class="btn red waves-effect waves-light modal-trigger" data-target="modal-rejeitar">
                            <i class="material-icons left">close</i>
                            REJEITAR
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-aprovar" action="<?php echo e(route('financial.budgets.approve', $budget)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="approve">
</form>

<form id="form-delete" action="<?php echo e(route('financial.budgets.destroy', $budget)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<script>
function aprovarOrcamento() {
    if(confirm('Tem certeza que deseja aprovar este orçamento?')) {
        document.getElementById('form-aprovar').submit();
    }
}
</script>

<!-- Modal de Rejeição -->
<div id="modal-rejeitar" class="modal">
    <form method="POST" action="<?php echo e(route('financial.budgets.approve', $budget)); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="reject">
        <div class="modal-content">
            <h4>Rejeitar Orçamento</h4>
            <div class="input-field">
                <textarea name="motivo_reprovacao" class="materialize-textarea" required></textarea>
                <label>Motivo da Rejeição</label>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            <button type="submit" class="waves-effect waves-light btn red">Confirmar</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/budgets/show.blade.php ENDPATH**/ ?>
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
                            <div class="ambiente">
                                <h5><?php echo e($room->nome); ?></h5>
                                <table class="striped">
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
                                                <td><?php echo e(number_format($item->largura, 3, ',', '.')); ?>m x <?php echo e(number_format($item->altura, 3, ',', '.')); ?>m</td>
                                                <td>R$ <?php echo e(number_format($item->valor_unitario, 2, ',', '.')); ?></td>
                                                <td>R$ <?php echo e(number_format($item->valor_total, 2, ',', '.')); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="right-align"><strong>Subtotal do Ambiente:</strong></td>
                                            <td><strong>R$ <?php echo e(number_format($room->items->sum('valor_total'), 2, ',', '.')); ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Observações -->
                <?php if($budget->observacoes): ?>
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel grey lighten-4">
                            <h5><i class="material-icons left">notes</i> Observações</h5>
                            <p style="white-space: pre-line;"><?php echo e($budget->observacoes); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Informações de Pagamento -->
                <div class="row">
                    <div class="col s12">
                        <div class="card-panel">
                            <h5>
                                <i class="material-icons left">payment</i> 
                                Informações de Pagamento
                            </h5>
                            
                            <div class="row">
                                <div class="col s12 m6">
                                    <p>
                                        <strong>Forma de Pagamento:</strong> 
                                        <?php echo e($budget->paymentMethod->nome ?? 'Não definido'); ?>

                                    </p>
                                    <p>
                                        <strong>Condição de Pagamento:</strong> 
                                        <?php echo e($budget->payment_condition ?? 'Não definido'); ?>

                                    </p>
                                </div>
                                <div class="col s12 m6">
                                    <p>
                                        <strong>Número de Parcelas:</strong> 
                                        <?php echo e($budget->payment_installments ?? 1); ?>

                                    </p>
                                    <p>
                                        <strong>Taxa:</strong> 
                                        <?php echo e($budget->payment_fee ? number_format($budget->payment_fee, 2, ',', '.') . '%' : '0%'); ?>

                                    </p>
                                </div>
                            </div>
                            
                            <?php if($budget->installments->count() > 0): ?>
                                <h6>Parcelas</h6>
                                <table class="striped">
                                    <thead>
                                        <tr>
                                            <th>Parcela</th>
                                            <th>Valor</th>
                                            <th>Vencimento</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $budget->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($installment->numero_parcela); ?>/<?php echo e($budget->payment_installments); ?></td>
                                                <td>R$ <?php echo e(number_format($installment->valor, 2, ',', '.')); ?></td>
                                                <td><?php echo e($installment->data_vencimento->format('d/m/Y')); ?></td>
                                                <td>
                                                    <?php if($installment->financialAccount): ?>
                                                        <span class="chip <?php echo e($installment->financialAccount->status == 'pendente' ? 'orange white-text' : ($installment->financialAccount->status == 'pago' ? 'green white-text' : 'grey white-text')); ?>">
                                                            <?php echo e($installment->financialAccount->getStatusTextAttribute()); ?>

                                                        </span>
                                                    <?php else: ?>
                                                        <span class="chip grey">Não processado</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            <?php elseif($budget->payment_method_id && $budget->status == 'aprovado'): ?>
                                <div class="card-panel amber lighten-4">
                                    <i class="material-icons left">info</i>
                                    As parcelas serão geradas após a próxima atualização do orçamento
                                </div>
                            <?php endif; ?>
                            
                            <?php if($budget->status == 'aprovado' && $budget->payment_method_id && !$budget->converted_to_receivable): ?>
                                <div class="card-action">
                                    <a href="<?php echo e(route('financial.budgets.convert-receivable', $budget)); ?>" 
                                       class="btn waves-effect waves-light indigo"
                                       onclick="return confirm('Deseja realmente converter este orçamento em contas a receber?')">
                                       <i class="material-icons left">transform</i>
                                       Converter em Contas a Receber
                                    </a>
                                </div>
                            <?php elseif($budget->status == 'aprovado' && $budget->payment_method_id && $budget->converted_to_receivable): ?>
                                <div class="card-action">
                                    <button type="button" class="btn waves-effect waves-light amber darken-3 modal-trigger" data-target="modal-desfazer-conversao">
                                        <i class="material-icons left">undo</i>
                                        Desfazer Conversão para Contas a Receber
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-action" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="<?php echo e(route('financial.budgets.edit', $budget)); ?>" class="btn waves-effect waves-light teal">
                        <i class="material-icons left">edit</i>
                        EDITAR
                    </a>
                    
                    <button type="button" class="btn blue-grey darken-2 waves-effect waves-light"
                        onclick="window.open('<?php echo e(route('financial.budgets.pdf', $budget->id)); ?>', '_blank')">
                        <i class="material-icons left">picture_as_pdf</i>
                        IMPRIMIR PDF
                    </button>
                    
                    <button onclick="printBudget()" class="btn blue waves-effect waves-light">
                        <i class="material-icons left">print</i>
                        IMPRIMIR
                    </button>

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

<!-- Modal de desfazer conversão para contas a receber -->
<div id="modal-desfazer-conversao" class="modal">
    <div class="modal-content">
        <h4>Desfazer Conversão para Contas a Receber</h4>
        <p>Tem certeza que deseja desfazer a conversão deste orçamento para contas a receber? Todas as contas a receber vinculadas a este orçamento serão excluídas.</p>
        <p class="red-text">Atenção: Esta ação não poderá ser desfeita caso as contas já tenham sido processadas ou pagas.</p>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('form-desfazer-conversao').submit();" class="waves-effect waves-light btn red">Desfazer Conversão</a>
    </div>
</div>

<form id="form-desfazer-conversao" action="<?php echo e(route('financial.budgets.undo-convert-receivable', $budget)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>

<form id="form-aprovar" action="<?php echo e(route('financial.budgets.approve', $budget)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="approve">
</form>

<form id="form-delete" action="<?php echo e(route('financial.budgets.destroy', $budget)); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa os modais
        var modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);

        // Inicializa tooltips
        var tooltips = document.querySelectorAll('.tooltipped');
        M.Tooltip.init(tooltips);
    });

    function aprovarOrcamento() {
        if (confirm('Deseja realmente aprovar este orçamento?')) {
            document.getElementById('form-aprovar').submit();
        }
    }
    
    function printBudget() {
        // Abre uma nova janela com a view de impressão otimizada
        var printWindow = window.open("<?php echo e(route('financial.budgets.print', $budget)); ?>", "_blank");
        
        // Aguarda o carregamento da página e então imprime
        printWindow.onload = function() {
            printWindow.print();
        };
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

<!-- Adicione este estilo para controlar o que será impresso -->
<style type="text/css" media="print">
    /* Oculta elementos desnecessários */
    .card-action, .sidenav, .navbar-fixed, nav, footer {
        display: none !important;
    }

    /* Configura página para retrato */
    @page {
        size: portrait;
        margin: 20mm 15mm;
    }

    /* Ajusta o conteúdo do orçamento */
    .container {
        width: 100% !important;
        max-width: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .card {
        box-shadow: none !important;
        border: none !important;
    }

    /* Garante que todo conteúdo seja impresso */
    .row {
        page-break-inside: avoid;
    }

    /* Melhora legibilidade do texto */
    body {
        font-size: 12pt;
        line-height: 1.3;
    }

    /* Ajusta tamanhos de títulos */
    .card-title {
        font-size: 16pt !important;
        margin-bottom: 15px !important;
    }
</style>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa os modais
        var modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);

        // Inicializa tooltips
        var tooltips = document.querySelectorAll('.tooltipped');
        M.Tooltip.init(tooltips);
    });

    function aprovarOrcamento() {
        if (confirm('Deseja realmente aprovar este orçamento?')) {
            document.getElementById('form-aprovar').submit();
        }
    }
    
    function printBudget() {
        // Abre uma nova janela com a view de impressão otimizada
        var printWindow = window.open("<?php echo e(route('financial.budgets.print', $budget)); ?>", "_blank");
        
        // Aguarda o carregamento da página e então imprime
        printWindow.onload = function() {
            printWindow.print();
        };
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/budgets/show.blade.php ENDPATH**/ ?>
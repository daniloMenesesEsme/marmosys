

<?php $__env->startSection('title', 'Planos de Pagamento'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="padding: 0 30px;">
    <div class="row">
        <div class="col s12">
            <div class="card" style="margin: 15px 0;">
                <div class="card-content">
                    <div class="card-title d-flex">
                        <div class="flex-grow-1">
                            <h4>Planos de Pagamento</h4>
                        </div>
                        <div>
                            <a href="<?php echo e(route('financial.registration.payment-plans.create')); ?>" 
                               class="btn-floating btn-large waves-effect waves-light green tooltipped"
                               data-position="left" 
                               data-tooltip="Novo Plano de Pagamento">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-0">
                        <form id="filter-form" class="col s12">
                            <div class="row mb-0">
                                <div class="input-field col s12 m3">
                                    <select name="tipo" id="tipo" onchange="this.form.submit()">
                                        <option value="">Todos os Tipos</option>
                                        <option value="venda" <?php echo e(request('tipo') == 'venda' ? 'selected' : ''); ?>>Venda</option>
                                        <option value="compra" <?php echo e(request('tipo') == 'compra' ? 'selected' : ''); ?>>Compra</option>
                                    </select>
                                    <label for="tipo">Tipo</label>
                                </div>
                                <div class="input-field col s12 m3">
                                    <select name="status" id="status" onchange="this.form.submit()">
                                        <option value="">Todos os Status</option>
                                        <option value="ativo" <?php echo e(request('status') == 'ativo' ? 'selected' : ''); ?>>Ativo</option>
                                        <option value="inativo" <?php echo e(request('status') == 'inativo' ? 'selected' : ''); ?>>Inativo</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                                <div class="input-field col s12 m4">
                                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>">
                                    <label for="search">Buscar</label>
                                </div>
                                <div class="input-field col s12 m2">
                                    <button type="submit" class="btn waves-effect waves-light">
                                        <i class="material-icons">search</i>
                                    </button>
                                    <a href="<?php echo e(route('financial.registration.payment-plans.index')); ?>" class="btn waves-effect waves-light red">
                                        <i class="material-icons">clear</i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabela -->
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Tipo</th>
                                <th>Forma de Pagamento</th>
                                <th>Parcelas</th>
                                <th>Taxa</th>
                                <th>Status</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $paymentPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($plan->nome); ?></td>
                                    <td>
                                        <span class="chip <?php echo e($plan->tipo == 'venda' ? 'blue' : 'orange'); ?> white-text">
                                            <?php echo e($plan->tipo == 'venda' ? 'Venda' : 'Compra'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($plan->paymentMethod->nome); ?></td>
                                    <td><?php echo e($plan->parcelas); ?>x</td>
                                    <td><?php echo e(number_format($plan->taxa, 2)); ?>%</td>
                                    <td>
                                        <span class="chip <?php echo e($plan->ativo ? 'green' : 'red'); ?> white-text">
                                            <?php echo e($plan->ativo ? 'Ativo' : 'Inativo'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="buttons-actions">
                                            <a href="#modal-simulate" 
                                               class="btn-floating waves-effect waves-light green modal-trigger"
                                               onclick="setPlanForSimulation(<?php echo e($plan->id); ?>)"
                                               title="Simular">
                                                <i class="material-icons">calculate</i>
                                            </a>
                                            <a href="<?php echo e(route('financial.registration.payment-plans.edit', $plan)); ?>" 
                                               class="btn-floating waves-effect waves-light amber"
                                               title="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <button type="button"
                                                    onclick="deleteItem('<?php echo e(route('financial.registration.payment-plans.destroy', $plan)); ?>')"
                                                    class="btn-floating waves-effect waves-light red"
                                                    title="Excluir">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="center-align">Nenhum plano de pagamento encontrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div class="row">
                        <div class="col s12">
                            <?php echo e($paymentPlans->links('vendor.pagination.materialize')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Simulação -->
<?php echo $__env->make('financial.registration.payment-plans._simulate_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Modal Confirmação Exclusão -->
<?php echo $__env->make('shared._confirm_delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);

        var modals = document.querySelectorAll('.modal');
        M.Modal.init(modals);
    });

    let currentPlanId = null;

    function setPlanForSimulation(planId) {
        currentPlanId = planId;
    }

    function simulate() {
        const valor = document.getElementById('valor_simulacao').value;
        
        fetch(`/financial/registration/payment-plans/${currentPlanId}/simulate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ valor })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                M.toast({html: data.error, classes: 'red'});
                return;
            }
            renderSimulationResults(data);
        })
        .catch(error => {
            M.toast({html: 'Erro ao simular parcelas', classes: 'red'});
        });
    }

    function renderSimulationResults(parcelas) {
        const tbody = document.getElementById('simulation-results');
        tbody.innerHTML = '';

        parcelas.forEach(parcela => {
            tbody.innerHTML += `
                <tr>
                    <td>${parcela.numero}x</td>
                    <td>R$ ${parseFloat(parcela.valor).toFixed(2)}</td>
                    <td>${new Date(parcela.vencimento).toLocaleDateString()}</td>
                    <td>${parcela.taxa}%</td>
                </tr>
            `;
        });
    }

    <?php if(session('success')): ?>
        M.toast({html: '<?php echo e(session("success")); ?>', classes: 'green'});
    <?php endif; ?>

    <?php if(session('error')): ?>
        M.toast({html: '<?php echo e(session("error")); ?>', classes: 'red'});
    <?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .buttons-actions {
        display: flex;
        gap: 8px;
    }
    .chip {
        height: 24px;
        padding: 0 12px;
        border-radius: 12px;
        line-height: 24px;
    }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/registration/payment-plans/index.blade.php ENDPATH**/ ?>
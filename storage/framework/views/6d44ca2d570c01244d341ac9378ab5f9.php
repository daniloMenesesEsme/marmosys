

<?php $__env->startSection('title', 'Relatório de Agentes Financeiros'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">
                        <div class="row mb-0">
                            <div class="col s12 m6">
                                <i class="material-icons left">assessment</i>
                                Relatório de Agentes Financeiros
                            </div>
                            <div class="col s12 m6 right-align">
                                <a href="<?php echo e(route('financial.reports.agents.import.form')); ?>" class="btn waves-effect waves-light blue">
                                    <i class="material-icons left">cloud_upload</i>
                                    Importar
                                </a>
                                <a href="<?php echo e(route('financial.reports.agents.template')); ?>" class="btn waves-effect waves-light teal">
                                    <i class="material-icons left">file_download</i>
                                    Modelo Excel
                                </a>
                            </div>
                        </div>
                    </span>
                    
                    <!-- Cards de Totais -->
                    <div class="row">
                        <div class="col s12 m3">
                            <div class="card blue darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Total Agentes</span>
                                    <h4><?php echo e($totals['total_agents']); ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m3">
                            <div class="card green darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Agentes Ativos</span>
                                    <h4><?php echo e($totals['total_active']); ?></h4>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($hasTransactionsTable) && $hasTransactionsTable): ?>
                        <div class="col s12 m3">
                            <div class="card orange darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Total Transações</span>
                                    <h4><?php echo e($totals['total_transactions']); ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m3">
                            <div class="card red darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Valor Total</span>
                                    <h4>R$ <?php echo e(number_format($totals['total_value'], 2, ',', '.')); ?></h4>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Filtros -->
                    <div class="row">
                        <form action="<?php echo e(route('financial.reports.agents')); ?>" method="GET" id="filter-form">
                            <div class="col s12 m3">
                                <div class="input-field select-field">
                                    <select name="tipo" id="tipo">
                                        <option value="">Todos os Tipos</option>
                                        <option value="banco" <?php echo e(request('tipo') == 'banco' ? 'selected' : ''); ?>>Banco</option>
                                        <option value="financeira" <?php echo e(request('tipo') == 'financeira' ? 'selected' : ''); ?>>Financeira</option>
                                        <option value="outros" <?php echo e(request('tipo') == 'outros' ? 'selected' : ''); ?>>Outros</option>
                                    </select>
                                    <label for="tipo">Tipo de Agente</label>
                                </div>
                            </div>
                            <div class="col s12 m3">
                                <div class="input-field select-field">
                                    <select name="status" id="status">
                                        <option value="">Todos os Status</option>
                                        <option value="ativo" <?php echo e(request('status') == 'ativo' ? 'selected' : ''); ?>>Ativo</option>
                                        <option value="inativo" <?php echo e(request('status') == 'inativo' ? 'selected' : ''); ?>>Inativo</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                            </div>
                            <div class="col s12 m3">
                                <div class="input-field select-field">
                                    <select name="financial_category_id" id="financial_category_id">
                                        <option value="">Todas as Categorias</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('financial_category_id') == $category->id ? 'selected' : ''); ?>>
                                                <?php echo e($category->nome); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <label for="financial_category_id">Categoria Financeira</label>
                                </div>
                            </div>
                            <div class="col s12 m3">
                                <div class="input-field select-field">
                                    <select name="cost_center_id" id="cost_center_id">
                                        <option value="">Todos os Centros de Custo</option>
                                        <?php $__currentLoopData = $costCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costCenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($costCenter->id); ?>" <?php echo e(request('cost_center_id') == $costCenter->id ? 'selected' : ''); ?>>
                                                <?php echo e($costCenter->nome); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <label for="cost_center_id">Centro de Custo</label>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <input type="text" id="search" name="search" value="<?php echo e(request('search')); ?>">
                                    <label for="search">Pesquisar por Nome ou Código</label>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <button type="submit" class="btn waves-effect waves-light blue">
                                        <i class="material-icons left">search</i>
                                        Filtrar
                                    </button>
                                    <a href="<?php echo e(route('financial.reports.agents')); ?>" class="btn waves-effect waves-light grey">
                                        <i class="material-icons left">clear</i>
                                        Limpar
                                    </a>
                                    
                                    <button type="submit" name="export" value="pdf" class="btn waves-effect waves-light red right ml-10">
                                        <i class="material-icons left">picture_as_pdf</i>
                                        PDF
                                    </button>
                                    <button type="submit" name="export" value="excel" class="btn waves-effect waves-light green right">
                                        <i class="material-icons left">grid_on</i>
                                        Excel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Tabela de Resultados -->
                    <div class="row">
                        <div class="col s12">
                            <?php if(isset($hasTransactionsTable) && !$hasTransactionsTable): ?>
                                <div class="card-panel yellow lighten-4">
                                    <p><i class="material-icons left">warning</i> A tabela de transações financeiras ainda não foi criada. Execute a migração <code>php artisan migrate</code> para ativar as funcionalidades completas do relatório.</p>
                                </div>
                            <?php endif; ?>
                            <table class="striped responsive-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <a href="<?php echo e(route('financial.reports.agents', array_merge(request()->all(), ['order_by' => 'codigo', 'direction' => request('order_by') == 'codigo' && request('direction') == 'asc' ? 'desc' : 'asc']))); ?>">
                                                Código
                                                <?php if(request('order_by') == 'codigo'): ?>
                                                    <i class="material-icons tiny"><?php echo e(request('direction') == 'asc' ? 'arrow_upward' : 'arrow_downward'); ?></i>
                                                <?php endif; ?>
                                            </a>
                                        </th>
                                        <th>
                                            <a href="<?php echo e(route('financial.reports.agents', array_merge(request()->all(), ['order_by' => 'nome', 'direction' => request('order_by') == 'nome' && request('direction') == 'asc' ? 'desc' : 'asc']))); ?>">
                                                Nome
                                                <?php if(request('order_by') == 'nome' || !request('order_by')): ?>
                                                    <i class="material-icons tiny"><?php echo e(request('direction', 'asc') == 'asc' ? 'arrow_upward' : 'arrow_downward'); ?></i>
                                                <?php endif; ?>
                                            </a>
                                        </th>
                                        <th>Tipo</th>
                                        <th>Categoria</th>
                                        <th>Centro de Custo</th>
                                        <th>Status</th>
                                        <?php if(isset($hasTransactionsTable) && $hasTransactionsTable): ?>
                                        <th class="center-align">Qtd. Transações</th>
                                        <th class="right-align">Valor Total</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($agent->codigo); ?></td>
                                            <td><?php echo e($agent->nome); ?></td>
                                            <td>
                                                <?php if($agent->tipo == 'banco'): ?>
                                                    <span class="chip blue white-text">Banco</span>
                                                <?php elseif($agent->tipo == 'financeira'): ?>
                                                    <span class="chip orange white-text">Financeira</span>
                                                <?php else: ?>
                                                    <span class="chip grey white-text">Outros</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($agent->category->nome ?? '-'); ?></td>
                                            <td><?php echo e($agent->costCenter->nome ?? '-'); ?></td>
                                            <td>
                                                <span class="chip <?php echo e($agent->status ? 'green' : 'red'); ?> white-text">
                                                    <?php echo e($agent->status ? 'Ativo' : 'Inativo'); ?>

                                                </span>
                                            </td>
                                            <?php if(isset($hasTransactionsTable) && $hasTransactionsTable): ?>
                                            <td class="center-align"><?php echo e($agent->transactions_count ?? 0); ?></td>
                                            <td class="right-align">R$ <?php echo e(number_format($agent->transactions_sum_valor ?? 0, 2, ',', '.')); ?></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="<?php echo e(isset($hasTransactionsTable) && $hasTransactionsTable ? 8 : 6); ?>" class="center-align">Nenhum registro encontrado</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if(isset($hasTransactionsTable) && $hasTransactionsTable): ?>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="right-align">Totais:</th>
                                        <th class="center-align"><?php echo e($totals['total_transactions']); ?></th>
                                        <th class="right-align">R$ <?php echo e(number_format($totals['total_value'], 2, ',', '.')); ?></th>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects, {
        dropdownOptions: {
            container: document.body,
            constrainWidth: false
        }
    });

    // Atualização forçada após a seleção
    selects.forEach(function(select) {
        select.addEventListener('change', function() {
            var selectedValue = this.value;
            var selectedText = this.options[this.selectedIndex].text;
            
            setTimeout(function() {
                var instance = M.FormSelect.init(select);
                select.value = selectedValue;
                instance.input.value = selectedText;
            }, 100);
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.card h4 {
    margin: 5px 0 0;
    font-size: 2rem;
}
.chip {
    height: 24px;
    line-height: 24px;
    padding: 0 10px;
}
.ml-10 {
    margin-left: 10px;
}
table th a {
    color: inherit;
    display: inline-flex;
    align-items: center;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\marmosys\resources\views/financial/reports/agents/index.blade.php ENDPATH**/ ?>
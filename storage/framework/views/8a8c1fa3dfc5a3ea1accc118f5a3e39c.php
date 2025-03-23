<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório de Agentes Financeiros</title>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header clearfix">
        <div class="header-content">
            <div class="header-left">
                <?php if(isset($company) && $company['logo_base64']): ?>
                    <img src="<?php echo e($company['logo_base64']); ?>" alt="Logo" class="logo" style="max-height: 60px;">
                <?php else: ?>
                    Logo
                <?php endif; ?>
            </div>
            <div class="header-right">
                <h1>Relatório de Agentes Financeiros</h1>
                <div class="header-info">
                    <p>Gerado por: <?php echo e($user_name); ?></p>
                    <p>Data/Hora: <?php echo e($generated_at->format('d/m/Y H:i:s')); ?></p>
                    <p>Período: Todos</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros Aplicados -->
    <div class="filters">
        <strong>Filtros Aplicados:</strong>
        Tipo: <?php echo e($filters['tipo']); ?> | Status: <?php echo e($filters['status']); ?> | 
        Categoria: <?php echo e($filters['categoria']); ?> | Centro de Custo: <?php echo e($filters['centro_custo']); ?>

    </div>
    
    <!-- Tabela de Dados -->
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Categoria</th>
                <th>Centro de Custo</th>
                <th>Status</th>
                <?php if($hasTransactionsTable): ?>
                <th class="text-center">Qtd. Transações</th>
                <th class="text-right">Valor Total</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($agent->codigo); ?></td>
                    <td><?php echo e($agent->nome); ?></td>
                    <td>
                        <?php if($agent->tipo == 'banco'): ?>
                            Banco
                        <?php elseif($agent->tipo == 'financeira'): ?>
                            Financeira
                        <?php else: ?>
                            Outros
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($agent->category->nome ?? '-'); ?></td>
                    <td><?php echo e($agent->costCenter->nome ?? '-'); ?></td>
                    <td><?php echo e($agent->status ? 'Ativo' : 'Inativo'); ?></td>
                    <?php if($hasTransactionsTable): ?>
                    <td class="text-center"><?php echo e($agent->transactions_count ?? 0); ?></td>
                    <td class="text-right">R$ <?php echo e(number_format($agent->transactions_sum_valor ?? 0, 2, ',', '.')); ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <?php if($hasTransactionsTable && $agents->count() > 0): ?>
        <tfoot>
            <tr>
                <th colspan="<?php echo e($hasTransactionsTable ? 6 : 4); ?>" class="text-right">Totais:</th>
                <?php if($hasTransactionsTable): ?>
                <th class="text-center"><?php echo e($totals['total_transactions']); ?></th>
                <th class="text-right">R$ <?php echo e(number_format($totals['total_value'], 2, ',', '.')); ?></th>
                <?php endif; ?>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
    
    <!-- Resumo -->
    <div class="summary">
        <h3>Resumo Financeiro</h3>
        <div class="summary-item">
            <strong>Total de Agentes:</strong> <?php echo e($totals['total_agents']); ?>

        </div>
        <div class="summary-item">
            <strong>Agentes Ativos:</strong> <?php echo e($totals['total_active']); ?>

        </div>
        <?php if($hasTransactionsTable): ?>
        <div class="summary-item receita">
            <strong>Total de Transações:</strong> <?php echo e($totals['total_transactions']); ?>

        </div>
        <div class="summary-item <?php echo e($totals['total_value'] >= 0 ? 'receita' : 'despesa'); ?>">
            <strong>Valor Total:</strong> R$ <?php echo e(number_format($totals['total_value'], 2, ',', '.')); ?>

        </div>
        <?php endif; ?>
    </div>
    
    <!-- Rodapé -->
    <div class="footer">
        <p>Relatório gerado automaticamente pelo sistema MarmosysERP</p>
    </div>
</body>
</html> <?php /**PATH C:\laragon\www\marmosys\resources\views/financial/reports/agents/pdf.blade.php ENDPATH**/ ?>
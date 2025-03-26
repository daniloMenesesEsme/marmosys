<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório Financeiro - MarmosyS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #1976D2;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo-container {
            float: left;
            width: 30%;
        }
        .report-info {
            float: right;
            width: 70%;
            text-align: right;
        }
        .clear {
            clear: both;
        }
        .report-title {
            color: #1976D2;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .report-metadata {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #1976D2;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .receita { color: #4CAF50; }
        .despesa { color: #F44336; }
        .totais {
            margin-top: 30px;
            padding: 15px;
            background-color: #E3F2FD;
            border-radius: 4px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        .page-number {
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="<?php echo e(public_path('images/logo.png')); ?>" height="60">
        </div>
        <div class="report-info">
            <div class="report-title">Relatório Financeiro</div>
            <div class="report-metadata">
                <strong>Gerado por:</strong> <?php echo e(auth()->user()->name); ?><br>
                <strong>Data/Hora:</strong> <?php echo e(now()->format('d/m/Y H:i:s')); ?><br>
                <strong>Período:</strong> <?php echo e($filters->dataInicio?->format('d/m/Y') ?? 'Todos'); ?> até <?php echo e($filters->dataFim?->format('d/m/Y') ?? 'Todos'); ?>

            </div>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Filtros Aplicados -->
    <div style="margin-bottom: 20px;">
        <strong>Filtros Aplicados:</strong><br>
        Tipo: <?php echo e($filters->tipo ? ucfirst($filters->tipo) : 'Todos'); ?> |
        Status: <?php echo e($filters->status ? ucfirst($filters->status) : 'Todos'); ?>

    </div>

    <table>
        <thead>
            <tr>
                <th>Data Vencimento</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Data Pagamento</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($transaction->data_vencimento->format('d/m/Y')); ?></td>
                    <td><?php echo e($transaction->descricao); ?></td>
                    <td class="<?php echo e($transaction->tipo); ?>">
                        <?php echo e(ucfirst($transaction->tipo)); ?>

                    </td>
                    <td style="text-align: right">
                        R$ <?php echo e(number_format($transaction->valor, 2, ',', '.')); ?>

                    </td>
                    <td><?php echo e(ucfirst($transaction->status)); ?></td>
                    <td><?php echo e($transaction->data_pagamento ? $transaction->data_pagamento->format('d/m/Y') : '-'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center">Nenhum registro encontrado</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="totais">
        <h3>Resumo Financeiro</h3>
        <p class="receita">
            <strong>Total Receitas:</strong> R$ <?php echo e(number_format($transactions->where('tipo', 'receita')->sum('valor'), 2, ',', '.')); ?>

        </p>
        <p class="despesa">
            <strong>Total Despesas:</strong> R$ <?php echo e(number_format($transactions->where('tipo', 'despesa')->sum('valor'), 2, ',', '.')); ?>

        </p>
        <p class="<?php echo e($transactions->where('tipo', 'receita')->sum('valor') - $transactions->where('tipo', 'despesa')->sum('valor') >= 0 ? 'receita' : 'despesa'); ?>">
            <strong>Saldo:</strong> R$ <?php echo e(number_format($transactions->where('tipo', 'receita')->sum('valor') - $transactions->where('tipo', 'despesa')->sum('valor'), 2, ',', '.')); ?>

        </p>
    </div>

    <div class="footer">
        Relatório gerado automaticamente pelo sistema MarmosyS<br>
        <?php echo e(config('app.name')); ?> - <?php echo e(config('app.url')); ?>

    </div>

    <div class="page-number">
        Página 1
    </div>
</body>
</html> <?php /**PATH C:\laragon\www\marmosys\resources\views/financial/reports/pdf.blade.php ENDPATH**/ ?>
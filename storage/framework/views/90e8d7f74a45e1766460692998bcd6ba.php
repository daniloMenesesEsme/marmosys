<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório de Vendedores - MarmosyS</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 15px;
            color: #333;
        }
        .header {
            border-bottom: 1px solid #1976D2;
            padding-bottom: 10px;
            margin-bottom: 20px;
            width: 100%;
        }
        .logo-container {
            float: left;
            width: 30%;
            height: 50px;
            display: flex;
            align-items: center;
        }
        .logo-container img {
            max-height: 50px;
            max-width: 100%;
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
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .report-metadata {
            font-size: 9px;
            color: #666;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        .filters {
            margin-bottom: 15px;
            font-size: 9px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            overflow: hidden;
            font-size: 10px;
        }
        th {
            background-color: #1976D2;
            color: white;
            font-weight: normal;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .col-nome { width: 15%; }
        .col-cpf { width: 10%; }
        .col-telefone { width: 10%; }
        .col-email { width: 15%; }
        .col-comissao { width: 8%; }
        .col-meta { width: 10%; }
        .col-admissao { width: 10%; }
        .col-status { width: 8%; }
        .col-orcamentos { width: 10%; }
        
        .status-ativo { color: #4CAF50; }
        .status-inativo { color: #F44336; }
        
        .resumo {
            margin-top: 15px;
            padding: 10px;
            background-color: #E3F2FD;
            border-radius: 3px;
        }
        .resumo h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
        }
        .resumo-item {
            margin-bottom: 3px;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #666;
            text-align: center;
        }
        
        .page-number {
            text-align: right;
            font-size: 8px;
            color: #666;
            margin-top: 5px;
        }
        
        /* Corrigir exibição de imagens codificadas em base64 */
        img[src^="data:"] {
            max-width: 100%;
            max-height: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <?php
                $logoPath = public_path('images/logo.png');
                $logoData = '';
                if (file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                }
            ?>
            <?php if($logoData): ?>
                <img src="data:image/png;base64,<?php echo e($logoData); ?>" alt="Logo">
            <?php else: ?>
                <div style="width: 100%; height: 50px; line-height: 50px; text-align: center; border: 1px dashed #ccc;">Logo</div>
            <?php endif; ?>
        </div>
        <div class="report-info">
            <div class="report-title">Relatório de Vendedores</div>
            <div class="report-metadata">
                <strong>Gerado por:</strong> <?php echo e(auth()->user()->name); ?><br>
                <strong>Data/Hora:</strong> <?php echo e(now()->format('d/m/Y H:i:s')); ?><br>
                <strong>Período:</strong> <?php echo e($filters->data_inicio?->format('d/m/Y') ?? 'Todos'); ?> até <?php echo e($filters->data_fim?->format('d/m/Y') ?? 'Todos'); ?>

            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="filters">
        <strong>Filtros Aplicados:</strong><br>
        Nome: <?php echo e($filters->nome ?: 'Todos'); ?> |
        Status: <?php echo e($filters->status ? ucfirst($filters->status) : 'Todos'); ?>

    </div>

    <table>
        <thead>
            <tr>
                <th class="col-nome">Nome</th>
                <th class="col-cpf">CPF</th>
                <th class="col-telefone">Telefone</th>
                <th class="col-email">Email</th>
                <th class="col-comissao">Comissão (%)</th>
                <th class="col-meta">Meta Mensal</th>
                <th class="col-admissao">Admissão</th>
                <th class="col-status">Status</th>
                <th class="col-orcamentos">Orçamentos</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($seller->nome); ?></td>
                    <td><?php echo e($seller->cpf); ?></td>
                    <td><?php echo e($seller->telefone ?: $seller->celular); ?></td>
                    <td><?php echo e($seller->email); ?></td>
                    <td><?php echo e(number_format($seller->percentual_comissao, 2)); ?>%</td>
                    <td>R$ <?php echo e(number_format($seller->meta_mensal, 2, ',', '.')); ?></td>
                    <td><?php echo e($seller->data_admissao ? date('d/m/Y', strtotime($seller->data_admissao)) : '-'); ?></td>
                    <td class="<?php echo e($seller->ativo ? 'status-ativo' : 'status-inativo'); ?>">
                        <?php echo e($seller->ativo ? 'Ativo' : 'Inativo'); ?>

                    </td>
                    <td><?php echo e($seller->budgets_count); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" style="text-align: center">Nenhum vendedor encontrado</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="resumo">
        <h3>Resumo</h3>
        <div class="resumo-item">
            <strong>Vendedores Ativos:</strong> <?php echo e($totals['vendedores_ativos']); ?>

        </div>
        <div class="resumo-item">
            <strong>Vendedores Inativos:</strong> <?php echo e($totals['vendedores_inativos']); ?>

        </div>
        <div class="resumo-item">
            <strong>Total de Vendedores:</strong> <?php echo e($totals['total_vendedores']); ?>

        </div>
    </div>

    <div class="footer">
        Relatório gerado automaticamente pelo sistema MarmosyS<br>
        <?php echo e(config('app.name')); ?> - <?php echo e(config('app.url')); ?>

    </div>

    <div class="page-number">
        Página 1
    </div>
</body>
</html> <?php /**PATH C:\laragon\www\marmosys\resources\views/sellers/pdf.blade.php ENDPATH**/ ?>
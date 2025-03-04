<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Orçamento #<?php echo e($budget->id); ?></title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .client-info {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f5f5f5;
        }
        .room-title {
            background-color: #eee;
            padding: 5px;
            margin: 10px 0;
        }
        .subtotal {
            text-align: right;
            font-weight: bold;
        }
        .total {
            text-align: right;
            margin-top: 20px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?php echo e(public_path('images/logo.png')); ?>" alt="Logo" class="logo">
        <h1>Orçamento #<?php echo e($budget->id); ?></h1>
    </div>

    <div class="company-info">
        <h3><?php echo e($company->nome_empresa); ?></h3>
        <p>CNPJ: <?php echo e($company->cnpj); ?></p>
        <p><?php echo e($company->endereco); ?></p>
        <p>Tel: <?php echo e($company->telefone); ?></p>
        <p>Email: <?php echo e($company->email); ?></p>
    </div>

    <div class="client-info">
        <h4>Dados do Cliente</h4>
        <table>
            <tr>
                <td><strong>Nome:</strong> <?php echo e($budget->client->nome); ?></td>
                <td><strong>CPF/CNPJ:</strong> <?php echo e($budget->client->cpf_cnpj ?? 'Não informado'); ?></td>
            </tr>
            <tr>
                <td><strong>Endereço:</strong> <?php echo e($budget->client->endereco ?? 'Não informado'); ?></td>
                <td><strong>Telefone:</strong> <?php echo e($budget->client->telefone ?? 'Não informado'); ?></td>
            </tr>
        </table>
    </div>

    <div class="budget-info">
        <h3>Informações do Cliente</h3>
        <p>Data: <?php echo e($budget->created_at->format('d/m/Y')); ?></p>
    </div>

    <?php $__currentLoopData = $budget->rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="room-title">
            <h4><?php echo e($room->nome); ?></h4>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Qtde</th>
                    <th>Unid</th>
                    <th>Descrição do Produto / Serviço</th>
                    <th>Dimens (m)</th>
                    <th>Produto</th>
                    <th>Serviço</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $room->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(number_format($item->quantidade, 3, ',', '.')); ?></td>
                        <td><?php echo e($item->unidade); ?></td>
                        <td><?php echo e($item->material->nome); ?></td>
                        <td><?php echo e(number_format($item->largura, 2, ',', '.')); ?> x <?php echo e(number_format($item->altura, 2, ',', '.')); ?></td>
                        <td><?php echo e(number_format($item->valor_unitario, 2, ',', '.')); ?></td>
                        <td></td>
                        <td><?php echo e(number_format($item->valor_total, 2, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td colspan="6" class="subtotal">Subtotal:</td>
                    <td><?php echo e(number_format($room->valor_total, 2, ',', '.')); ?></td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="total">
        <p><strong>Valor Total:</strong> R$ <?php echo e(number_format($budget->valor_total, 2, ',', '.')); ?></p>
        <p><strong>Desconto:</strong> R$ <?php echo e(number_format($budget->desconto, 2, ',', '.')); ?></p>
        <p><strong>Valor Final:</strong> R$ <?php echo e(number_format($budget->valor_final, 2, ',', '.')); ?></p>
    </div>

    <?php if($company->observacoes_orcamento): ?>
    <div class="observations">
        <h3>Observações</h3>
        <p><?php echo e($company->observacoes_orcamento); ?></p>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p>Validade do Orçamento: <?php echo e($budget->data_validade->format('d/m/Y')); ?></p>
        <p>Este orçamento foi gerado em <?php echo e(now()->format('d/m/Y H:i:s')); ?></p>
    </div>
</body>
</html> <?php /**PATH C:\laragon\www\marmosys\resources\views/financial/budgets/pdf.blade.php ENDPATH**/ ?>
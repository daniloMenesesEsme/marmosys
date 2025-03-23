<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório Financeiro</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        .table th { background-color: #f5f5f5; }
        .totalizadores { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Relatório Financeiro</h1>
    
    <table class="table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th>Valor</th>
                <th>Forma Pagamento</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $transaction)
                <tr>
                    <td>{{ $transaction->data->format('d/m/Y') }}</td>
                    <td>{{ $transaction->cliente->nome }}</td>
                    <td>R$ {{ number_format($transaction->valor, 2, ',', '.') }}</td>
                    <td>{{ $transaction->forma_pagamento->label() }}</td>
                    <td>{{ $transaction->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totalizadores">
        <h3>Totalizadores</h3>
        <p><strong>Total Geral:</strong> R$ {{ number_format($totalizadores['total'], 2, ',', '.') }}</p>
        
        <h4>Por Forma de Pagamento</h4>
        @foreach($totalizadores['por_forma_pagamento'] as $forma => $total)
            <p>{{ $forma }}: R$ {{ number_format($total, 2, ',', '.') }}</p>
        @endforeach
    </div>
</body>
</html> 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório Financeiro - {{ $meses[$mes] }}/{{ $ano }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .card {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Financeiro</h1>
        <h2>{{ $meses[$mes] }} de {{ $ano }}</h2>
    </div>

    <div class="card">
        <h3>Resumo do Período</h3>
        <table>
            <tr>
                <th>Total de Receitas</th>
                <td class="text-right">R$ {{ number_format($totais['receitas'], 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total de Despesas</th>
                <td class="text-right">R$ {{ number_format($totais['despesas'], 2, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <th>Saldo</th>
                <td class="text-right">R$ {{ number_format($totais['saldo'], 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h3>Receitas por Categoria</h3>
        <table>
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th class="text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorias->where('tipo', 'receita') as $categoria)
                <tr>
                    <td>{{ $categoria->nome }}</td>
                    <td class="text-right">R$ {{ number_format($categoria->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <th>Total</th>
                    <th class="text-right">R$ {{ number_format($totais['receitas'], 2, ',', '.') }}</th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>Despesas por Categoria</h3>
        <table>
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th class="text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorias->where('tipo', 'despesa') as $categoria)
                <tr>
                    <td>{{ $categoria->nome }}</td>
                    <td class="text-right">R$ {{ number_format($categoria->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <th>Total</th>
                    <th class="text-right">R$ {{ number_format($totais['despesas'], 2, ',', '.') }}</th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="card">
        <h3>Fluxo de Caixa Diário</h3>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th class="text-right">Receitas</th>
                    <th class="text-right">Despesas</th>
                    <th class="text-right">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fluxoDiario as $dia)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($dia->data_pagamento)->format('d/m/Y') }}</td>
                    <td class="text-right">R$ {{ number_format($dia->receitas, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($dia->despesas, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($dia->receitas - $dia->despesas, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 
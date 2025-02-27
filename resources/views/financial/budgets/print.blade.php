<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Orçamento {{ $budget->numero }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .client-info {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .subtotal {
            text-align: right;
            font-weight: bold;
        }
        .total {
            text-align: right;
            font-size: 14px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        <h2>Angular Granitos - Fábrica</h2>
        <p>CNPJ: 29.123.952/0001-84</p>
        <p>RUA QUINTINO CUNHA, 2950 - CAUCAIA - CE</p>
        <p>Fone: 85 9 9915-2076</p>
        <p>angulargranitos@outlook.com</p>
    </div>

    <div class="company-info">
        <h3>Orçamento Nº {{ $budget->numero }} - {{ $budget->data->format('d/m/Y') }}</h3>
    </div>

    <div class="client-info">
        <h4>Dados do Cliente</h4>
        <p><strong>Nome:</strong> {{ $budget->client->nome }}</p>
        <p><strong>CPF/CNPJ:</strong> {{ $budget->client->cpf_cnpj ?? 'Não informado' }}</p>
        <p><strong>Endereço:</strong> {{ $budget->client->endereco ?? 'Não informado' }}</p>
        <p><strong>Telefone:</strong> {{ $budget->client->telefone ?? 'Não informado' }}</p>
    </div>

    @foreach($budget->rooms as $room)
        <h4>{{ $room->nome }}</h4>
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
                @foreach($room->items as $item)
                    <tr>
                        <td>{{ number_format($item->quantidade, 3, ',', '.') }}</td>
                        <td>{{ $item->unidade }}</td>
                        <td>{{ $item->material->nome }}</td>
                        <td>{{ number_format($item->largura, 2, ',', '.') }} x {{ number_format($item->altura, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                        <td></td>
                        <td>{{ number_format($item->valor_total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6" class="subtotal">Subtotal:</td>
                    <td>{{ number_format($room->valor_total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <div class="total">
        <p><strong>Valor Total:</strong> R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</p>
        <p><strong>Desconto:</strong> R$ {{ number_format($budget->desconto, 2, ',', '.') }}</p>
        <p><strong>Valor Final:</strong> R$ {{ number_format($budget->valor_final, 2, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>Validade do Orçamento: {{ $budget->data_validade->format('d/m/Y') }}</p>
        <p>Impresso em: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Imprimir</button>
        <button onclick="window.close()">Fechar</button>
    </div>
</body>
</html> 
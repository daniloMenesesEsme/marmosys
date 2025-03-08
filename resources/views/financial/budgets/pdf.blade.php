<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Orçamento #{{ $budget->id }}</title>
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
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo">
        <h1>Orçamento #{{ $budget->id }}</h1>
    </div>

    <div class="company-info">
        <h3>{{ $company->nome_empresa }}</h3>
        <p>CNPJ: {{ $company->cnpj }}</p>
        <p>{{ $company->endereco }}</p>
        <p>Tel: {{ $company->telefone }}</p>
        <p>Email: {{ $company->email }}</p>
    </div>

    <div class="client-info">
        <h4>Dados do Cliente</h4>
        <table>
            <tr>
                <td><strong>Nome:</strong> {{ $budget->client->nome }}</td>
                <td><strong>CPF/CNPJ:</strong> {{ $budget->client->cpf_cnpj ?? 'Não informado' }}</td>
            </tr>
            <tr>
                <td><strong>Endereço:</strong> {{ $budget->client->endereco ?? 'Não informado' }}</td>
                <td><strong>Telefone:</strong> {{ $budget->client->telefone ?? 'Não informado' }}</td>
            </tr>
        </table>
    </div>

    <div class="budget-info">
        <h3>Informações do Cliente</h3>
        <p>Data: {{ $budget->created_at->format('d/m/Y') }}</p>
    </div>

    @foreach($budget->rooms as $room)
        <div class="room-title">
            <h4>{{ $room->nome }}</h4>
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

    @if($company->observacoes_orcamento)
    <div class="observations">
        <h3>Observações</h3>
        <p>{{ $company->observacoes_orcamento }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Validade do Orçamento: {{ $budget->data_validade->format('d/m/Y') }}</p>
        <p>Este orçamento foi gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 
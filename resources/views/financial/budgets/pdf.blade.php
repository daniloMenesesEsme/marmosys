<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orçamento #{{ $budget->numero }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .budget-info {
            margin-bottom: 30px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .client-info {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2196f3;
            color: white;
        }
        .room-title {
            background-color: #e3f2fd;
            padding: 10px;
            margin: 20px 0 10px;
            font-weight: bold;
        }
        .total {
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
        .conditions {
            margin: 30px 0;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="MarmoSys">
        <h1>Orçamento #{{ $budget->numero }}</h1>
    </div>

    <div class="budget-info">
        <p><strong>Data:</strong> {{ $budget->created_at->format('d/m/Y') }}</p>
        <p><strong>Previsão de Entrega:</strong> {{ Carbon\Carbon::parse($budget->previsao_entrega)->format('d/m/Y') }}</p>
        <p><strong>Status:</strong> {{ $budget->status_text }}</p>
    </div>

    <div class="client-info">
        <h3>Dados do Cliente</h3>
        <p><strong>Nome:</strong> {{ $budget->client->nome }}</p>
        <p><strong>Telefone:</strong> {{ $budget->client->telefone }}</p>
        <p><strong>Email:</strong> {{ $budget->client->email }}</p>
        <p><strong>Endereço:</strong> {{ $budget->client->endereco }}</p>
    </div>

    @foreach($budget->rooms as $room)
    <div class="room">
        <div class="room-title">{{ $room->nome }}</div>
        <table>
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Dimensões</th>
                    <th>Quantidade</th>
                    <th>Valor Unit.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($room->items as $item)
                <tr>
                    <td>{{ $item->material->nome }}</td>
                    <td>{{ number_format($item->largura, 2, ',', '.') }}m x {{ number_format($item->altura, 2, ',', '.') }}m</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right"><strong>Subtotal do Ambiente:</strong></td>
                    <td><strong>R$ {{ number_format($room->valor_total, 2, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endforeach

    <div class="total">
        <p>Valor Total: R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</p>
    </div>

    <div class="conditions">
        <h3>Condições Gerais</h3>
        <ul>
            <li>Validade do orçamento: 15 dias</li>
            <li>Forma de pagamento: 50% na aprovação e 50% na entrega</li>
            <li>Prazo de entrega: conforme acordado após aprovação</li>
        </ul>
    </div>

    <div class="footer">
        <p>MarmoSys - Excelência em Mármores e Granitos</p>
        <p>Telefone: (XX) XXXX-XXXX | Email: contato@marmosys.com.br</p>
        <p>{{ config('app.address', 'Endereço da Empresa') }}</p>
    </div>
</body>
</html> 
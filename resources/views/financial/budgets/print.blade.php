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
        .logo-placeholder {
            float: right;
            width: 100px;
            height: 100px;
            border: 1px dashed #ccc;
            text-align: center;
            line-height: 100px;
            margin-left: 20px;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }
        .subtotal {
            text-align: right;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="logo-placeholder">Logo</div>
    
    <h3>Orçamento ORC-{{ $budget->numero }}</h3>

    <div class="section">
        <div class="section-title">Dados do Cliente</div>
        <p>Nome: {{ $budget->client->nome }}</p>
        <p>CPF/CNPJ: {{ $budget->client->cpf_cnpj }}</p>
        <p>Endereço: {{ $budget->client->endereco }}</p>
        <p>Telefone: {{ $budget->client->telefone }}</p>
        <p>Email: {{ $budget->client->email }}</p>
    </div>

    <div class="section">
        <div class="section-title">Dados do Orçamento</div>
        <p>Data: {{ $budget->data->format('d/m/Y') }}</p>
        <p>Validade: {{ $budget->validade->format('d/m/Y') }}</p>
        <p>Status: {{ $budget->status }}</p>
        <p>Valor Total: R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Itens do Orçamento</div>
        @foreach($budget->rooms as $room)
            <h4>{{ $room->nome }}</h4>
            <table>
                <tr>
                    <th>Material</th>
                    <th>Quantidade</th>
                    <th>Unid.</th>
                    <th>Dimensões</th>
                    <th>Valor Unit.</th>
                    <th>Total</th>
                </tr>
                @foreach($room->items as $item)
                    <tr>
                        <td>{{ $item->material->nome }}</td>
                        <td>{{ $item->quantidade }}</td>
                        <td>m²</td>
                        <td>{{ $item->dimensoes }}</td>
                        <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="subtotal">Subtotal do Ambiente:</td>
                    <td>R$ {{ number_format($room->items->sum('valor_total'), 2, ',', '.') }}</td>
                </tr>
            </table>
        @endforeach
    </div>
</body>
</html> 
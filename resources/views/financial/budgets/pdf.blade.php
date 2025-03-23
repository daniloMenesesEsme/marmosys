<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orçamento #{{ $budget->numero }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            width: 100%;
            margin-bottom: 30px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
            width: 33.33%;
        }
        .logo {
            width: 150px;
        }
        .company-info {
            text-align: center;
            line-height: 1.2;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-details {
            margin: 0;
            line-height: 1.3;
        }
        .budget-info {
            text-align: right;
        }
        .budget-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .client-info {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 20px;
        }
        .client-info h2 {
            font-size: 14px;
            margin: 0 0 15px 0;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        .client-table {
            width: 100%;
            border-collapse: collapse;
        }
        .client-table td {
            padding: 3px;
        }
        .client-table .label {
            font-weight: bold;
            width: 100px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #f5f5f5;
            padding: 8px;
            text-align: left;
            border-bottom: 2px solid #333;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .items-table .subtotal {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #0066cc;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 20%;">
                    <img src="{{ public_path('storage/' . $company->logo) }}" alt="Logo" class="logo">
                </td>
                <td style="width: 60%; text-align: center;">
                    <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">
                        ANGULAR GRANITOS FÁBRICA
                    </div>
                    <div style="line-height: 1.5;">
                        CNPJ: {{ $company->cnpj }}<br>
                        RUA QUINTINO CUNHA, 2950 - CAUCAIA - CE<br>
                        Fone: {{ $company->telefone }}<br>
                        {{ $company->email }}
                    </div>
                </td>
                <td style="width: 20%; text-align: right; vertical-align: top;">
                    <div>Orçamento</div>
                    <div>Nº {{ $budget->numero }}</div>
                    <div>{{ $budget->data->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="client-info">
        <h2>Dados do Cliente</h2>
        <table class="client-table">
            <tr>
                <td class="label">Nome:</td>
                <td>{{ $budget->client->nome }}</td>
                <td class="label">CPF/CNPJ:</td>
                <td>{{ $budget->client->cpf_cnpj }}</td>
            </tr>
            <tr>
                <td class="label">Endereço:</td>
                <td>{{ $budget->client->endereco }}</td>
                <td class="label">Bairro:</td>
                <td>{{ $budget->client->bairro }}</td>
            </tr>
            <tr>
                <td class="label">Cidade:</td>
                <td>{{ $budget->client->cidade }}</td>
                <td class="label">UF:</td>
                <td>{{ $budget->client->uf }}</td>
            </tr>
            <tr>
                <td class="label">Telefone:</td>
                <td>{{ $budget->client->telefone }}</td>
                <td class="label">Email:</td>
                <td>{{ $budget->client->email }}</td>
            </tr>
        </table>
    </div>

    @foreach($budget->rooms as $room)
    <div class="items-section" style="margin-bottom: 20px;">
        <div style="color: #0e21ee; text-align: left; margin-bottom: 5px; border-bottom: 1px solid #000;">
            {{ strtoupper($room->nome) }}
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th style="border: 1px solid #000; padding: 5px;">Qtde</th>
                <th style="border: 1px solid #000; padding: 5px;">Unid</th>
                <th style="border: 1px solid #000; padding: 5px;">Descrição do Produto/Serviço</th>
                <th style="border: 1px solid #000; padding: 5px;">Dimens (m)</th>
                <th style="border: 1px solid #000; padding: 5px;">Produto</th>
                <th style="border: 1px solid #000; padding: 5px;">Valor Total</th>
            </tr>
            @php
                $subtotal = 0;
            @endphp
            @foreach($room->items as $item)
            @php
                $subtotal += $item->valor_total;
            @endphp
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">{{ number_format($item->quantidade, 2) }}</td>
                <td style="border: 1px solid #000; padding: 5px;">{{ $item->unidade }}</td>
                <td style="border: 1px solid #000; padding: 5px;">{{ $item->descricao }}</td>
                <td style="border: 1px solid #000; padding: 5px;">{{ $item->dimensoes }}</td>
                <td style="border: 1px solid #000; padding: 5px;">R$ {{ number_format($item->valor_produto, 2, ',', '.') }}</td>
                <td style="border: 1px solid #000; padding: 5px;">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
        <div style="text-align: right; margin-bottom: 15px;">
            <span style="color: #0066cc;">Subtotal:</span> R$ {{ number_format($subtotal, 2, ',', '.') }}
        </div>
    </div>
    @endforeach

    <div style="text-align: right; margin-top: 20px;">
        <div>Valor Total: R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</div>
        <div>Valor Final: R$ {{ number_format($budget->valor_final, 2, ',', '.') }}</div>
    </div>

    @if(!empty($budget->observacoes))
        <div style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px;">
            <h4 style="margin-bottom: 10px;">Observações</h4>
            <p style="margin: 0; padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd;">
                {{ $budget->observacoes }}
            </p>
        </div>
    @endif

    <div class="validity" style="margin-top: 20px;">
        <p>Validade do Orçamento: {{ $budget->data_validade->format('d/m/Y') }}</p>
    </div>

    <div class="footer">
        <p>Este orçamento foi gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Marmosys - Sistema de gestão para marmorarias</p>
    </div>
</body>
</html> 
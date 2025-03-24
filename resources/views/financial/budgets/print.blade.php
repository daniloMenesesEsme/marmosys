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
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
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
        th {
            background-color: #f5f5f5;
        }
        .subtotal {
            text-align: right;
            padding: 5px;
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .total {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 20%;">
                    @if(isset($company->logo) && !empty($company->logo))
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="logo">
                    @else
                        <div class="logo-placeholder">Logo</div>
                    @endif
                </td>
                <td style="width: 60%; text-align: center;">
                    <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">
                        {{ $company->nome ?? 'ANGULAR GRANITOS FÁBRICA' }}
                    </div>
                    <div style="line-height: 1.5;">
                        CNPJ: {{ $company->cnpj ?? '00.000.000/0000-00' }}<br>
                        {{ $company->endereco ?? 'RUA QUINTINO CUNHA, 2950' }} - {{ $company->cidade ?? 'CAUCAIA' }} - {{ $company->estado ?? 'CE' }}<br>
                        Fone: {{ $company->telefone ?? '(00) 00000-0000' }}<br>
                        {{ $company->email ?? 'contato@angular.com' }}
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
        <table class="client-table" style="border: none;">
            <tr>
                <td style="border: none; width: 50%;">
                    <strong>Nome:</strong> {{ $budget->client->nome }}
                </td>
                <td style="border: none; width: 50%;">
                    <strong>CPF/CNPJ:</strong> {{ $budget->client->cpf_cnpj ?? 'Não informado' }}
                </td>
            </tr>
            <tr>
                <td style="border: none;">
                    <strong>Endereço:</strong> {{ $budget->client->endereco ?? 'Não informado' }}
                </td>
                <td style="border: none;">
                    <strong>Telefone:</strong> {{ $budget->client->telefone ?? 'Não informado' }}
                </td>
            </tr>
            <tr>
                <td style="border: none;" colspan="2">
                    <strong>Email:</strong> {{ $budget->client->email ?? 'Não informado' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Dados do Orçamento</div>
        <p><strong>Data:</strong> {{ $budget->data->format('d/m/Y') }}</p>
        <p><strong>Validade:</strong> {{ $budget->data_validade->format('d/m/Y') }}</p>
        <p><strong>Vendedor:</strong> {{ $budget->seller->nome ?? 'Não atribuído' }}</p>
        <p><strong>Status:</strong> {{ $budget->status_text }}</p>
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
                        <td>{{ number_format($item->quantidade, 3, ',', '.') }}</td>
                        <td>{{ $item->unidade }}</td>
                        <td>{{ number_format($item->largura, 3, ',', '.') }}m x {{ number_format($item->altura, 3, ',', '.') }}m</td>
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

    <div class="total">
        <div>Valor Total: R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</div>
        <div>Desconto: R$ {{ number_format($budget->desconto, 2, ',', '.') }}</div>
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

    <div style="margin-top: 20px;">
        <p>Validade do Orçamento: {{ $budget->data_validade->format('d/m/Y') }}</p>
    </div>

    <div class="footer">
        <p>Este orçamento foi gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Marmosys - Sistema de gestão para marmorarias</p>
    </div>
</body>
</html> 
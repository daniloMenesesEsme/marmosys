<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Relatório de Agentes Financeiros</title>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header clearfix">
        <div class="header-content">
            <div class="header-left">
                @if(isset($company) && $company['logo_base64'])
                    <img src="{{ $company['logo_base64'] }}" alt="Logo" class="logo" style="max-height: 60px;">
                @else
                    Logo
                @endif
            </div>
            <div class="header-right">
                <h1>Relatório de Agentes Financeiros</h1>
                <div class="header-info">
                    <p>Gerado por: {{ $user_name }}</p>
                    <p>Data/Hora: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
                    <p>Período: Todos</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros Aplicados -->
    <div class="filters">
        <strong>Filtros Aplicados:</strong>
        Tipo: {{ $filters['tipo'] }} | Status: {{ $filters['status'] }} | 
        Categoria: {{ $filters['categoria'] }} | Centro de Custo: {{ $filters['centro_custo'] }}
    </div>
    
    <!-- Tabela de Dados -->
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Categoria</th>
                <th>Centro de Custo</th>
                <th>Status</th>
                @if($hasTransactionsTable)
                <th class="text-center">Qtd. Transações</th>
                <th class="text-right">Valor Total</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($agents as $agent)
                <tr>
                    <td>{{ $agent->codigo }}</td>
                    <td>{{ $agent->nome }}</td>
                    <td>
                        @if($agent->tipo == 'banco')
                            Banco
                        @elseif($agent->tipo == 'financeira')
                            Financeira
                        @else
                            Outros
                        @endif
                    </td>
                    <td>{{ $agent->category->nome ?? '-' }}</td>
                    <td>{{ $agent->costCenter->nome ?? '-' }}</td>
                    <td>{{ $agent->status ? 'Ativo' : 'Inativo' }}</td>
                    @if($hasTransactionsTable)
                    <td class="text-center">{{ $agent->transactions_count ?? 0 }}</td>
                    <td class="text-right">R$ {{ number_format($agent->transactions_sum_valor ?? 0, 2, ',', '.') }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        @if($hasTransactionsTable && $agents->count() > 0)
        <tfoot>
            <tr>
                <th colspan="{{ $hasTransactionsTable ? 6 : 4 }}" class="text-right">Totais:</th>
                @if($hasTransactionsTable)
                <th class="text-center">{{ $totals['total_transactions'] }}</th>
                <th class="text-right">R$ {{ number_format($totals['total_value'], 2, ',', '.') }}</th>
                @endif
            </tr>
        </tfoot>
        @endif
    </table>
    
    <!-- Resumo -->
    <div class="summary">
        <h3>Resumo Financeiro</h3>
        <div class="summary-item">
            <strong>Total de Agentes:</strong> {{ $totals['total_agents'] }}
        </div>
        <div class="summary-item">
            <strong>Agentes Ativos:</strong> {{ $totals['total_active'] }}
        </div>
        @if($hasTransactionsTable)
        <div class="summary-item receita">
            <strong>Total de Transações:</strong> {{ $totals['total_transactions'] }}
        </div>
        <div class="summary-item {{ $totals['total_value'] >= 0 ? 'receita' : 'despesa' }}">
            <strong>Valor Total:</strong> R$ {{ number_format($totals['total_value'], 2, ',', '.') }}
        </div>
        @endif
    </div>
    
    <!-- Rodapé -->
    <div class="footer">
        <p>Relatório gerado automaticamente pelo sistema MarmosysERP</p>
    </div>
</body>
</html> 
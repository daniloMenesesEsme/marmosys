@extends('layouts.app')

@section('title', 'Relatório de Vendedores')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Relatório de Vendedores</span>
                    
                    <div class="row">
                        <div class="col s12">
                            <form action="{{ route('sellers.report') }}" method="GET" class="row">
                                <div class="input-field col s12 m3">
                                    <input type="text" name="nome" id="nome" value="{{ request('nome') }}">
                                    <label for="nome">Nome</label>
                                </div>
                                
                                <div class="input-field col s12 m3">
                                    <select name="status" id="status">
                                        <option value="" {{ !request('status') ? 'selected' : '' }}>Todos</option>
                                        <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativos</option>
                                        <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativos</option>
                                    </select>
                                    <label for="status">Status</label>
                                </div>
                                
                                <div class="input-field col s12 m3">
                                    <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}">
                                    <label for="data_inicio">Data Admissão (Início)</label>
                                </div>
                                
                                <div class="input-field col s12 m3">
                                    <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}">
                                    <label for="data_fim">Data Admissão (Fim)</label>
                                </div>
                                
                                <div class="input-field col s12 m3">
                                    <input type="number" step="0.01" name="comissao_min" id="comissao_min" value="{{ request('comissao_min') }}">
                                    <label for="comissao_min">Comissão Mínima (%)</label>
                                </div>
                                
                                <div class="input-field col s12 m3">
                                    <input type="number" step="0.01" name="comissao_max" id="comissao_max" value="{{ request('comissao_max') }}">
                                    <label for="comissao_max">Comissão Máxima (%)</label>
                                </div>
                                
                                <div class="input-field col s12 m3">
                                    <input type="date" name="periodo_inicio" id="periodo_inicio" value="{{ request('periodo_inicio') }}">
                                    <label for="periodo_inicio">Período Orçamentos (Início)</label>
                                </div>
                                
                                <div class="input-field col s12 m3">
                                    <input type="date" name="periodo_fim" id="periodo_fim" value="{{ request('periodo_fim') }}">
                                    <label for="periodo_fim">Período Orçamentos (Fim)</label>
                                </div>
                                
                                <div class="col s12 m12 center-align">
                                    <button type="submit" class="btn waves-effect waves-light">
                                        <i class="material-icons left">search</i> Filtrar
                                    </button>
                                    
                                    <a href="{{ route('sellers.report') }}" class="btn waves-effect waves-light red">
                                        <i class="material-icons left">clear</i> Limpar Filtros
                                    </a>
                                    
                                    <a href="{{ route('sellers.report', array_merge(request()->all(), ['pdf' => true])) }}" class="btn waves-effect waves-light blue">
                                        <i class="material-icons left">picture_as_pdf</i> Gerar PDF
                                    </a>
                                    
                                    <button type="button" onclick="printReport()" class="btn waves-effect waves-light green">
                                        <i class="material-icons left">print</i> Imprimir
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col s12 m4">
                            <div class="card blue-grey darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Vendedores Ativos</span>
                                    <h4>{{ $totals['vendedores_ativos'] }}</h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col s12 m4">
                            <div class="card red darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Vendedores Inativos</span>
                                    <h4>{{ $totals['vendedores_inativos'] }}</h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col s12 m4">
                            <div class="card teal darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Total de Vendedores</span>
                                    <h4>{{ $totals['total_vendedores'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col s12">
                            <table class="striped responsive-table">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>CPF</th>
                                        <th>Telefone</th>
                                        <th>Email</th>
                                        <th>Comissão (%)</th>
                                        <th>Meta Mensal</th>
                                        <th>Admissão</th>
                                        <th>Status</th>
                                        <th>Orçamentos</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sellers as $seller)
                                        <tr>
                                            <td>{{ $seller->nome }}</td>
                                            <td>{{ $seller->cpf }}</td>
                                            <td>{{ $seller->telefone ?: $seller->celular }}</td>
                                            <td>{{ $seller->email }}</td>
                                            <td>{{ number_format($seller->percentual_comissao, 2) }}%</td>
                                            <td>R$ {{ number_format($seller->meta_mensal, 2, ',', '.') }}</td>
                                            <td>{{ $seller->data_admissao ? date('d/m/Y', strtotime($seller->data_admissao)) : '-' }}</td>
                                            <td>
                                                <span class="badge {{ $seller->ativo ? 'green' : 'red' }} white-text">
                                                    {{ $seller->ativo ? 'Ativo' : 'Inativo' }}
                                                </span>
                                            </td>
                                            <td>{{ $seller->budgets_count }}</td>
                                            <td>
                                                <a href="{{ route('sellers.show', $seller) }}" class="btn-floating btn-small waves-effect waves-light blue">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="center-align">Nenhum vendedor encontrado</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            <div class="row">
                                <div class="col s12 center-align">
                                    {{ $sellers->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template de impressão oculto -->
<div id="printTemplate" style="display: none;">
    <div class="header clearfix">
        <div class="header-left">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        </div>
        <div class="header-right">
            <h1>Relatório de Vendedores</h1>
            <div class="header-info">
                <strong>Gerado por:</strong> {{ auth()->user()->name }}<br>
                <strong>Data/Hora:</strong> {{ now()->format('d/m/Y H:i:s') }}<br>
                <strong>Período:</strong> {{ request('data_inicio') ? date('d/m/Y', strtotime(request('data_inicio'))) : 'Todos' }} até 
                                          {{ request('data_fim') ? date('d/m/Y', strtotime(request('data_fim'))) : 'Todos' }}
            </div>
        </div>
    </div>

    <div class="filters">
        <strong>Filtros Aplicados:</strong><br>
        Nome: {{ request('nome') ?: 'Todos' }} |
        Status: {{ request('status') ? ucfirst(request('status')) : 'Todos' }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-nome">Nome</th>
                <th class="col-cpf">CPF</th>
                <th class="col-telefone">Telefone</th>
                <th class="col-email">Email</th>
                <th class="col-comissao">Comissão (%)</th>
                <th class="col-meta">Meta Mensal</th>
                <th class="col-admissao">Admissão</th>
                <th class="col-status">Status</th>
                <th class="col-orcamentos">Orçamentos</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sellers as $seller)
                <tr>
                    <td>{{ $seller->nome }}</td>
                    <td>{{ $seller->cpf }}</td>
                    <td>{{ $seller->telefone ?: $seller->celular }}</td>
                    <td>{{ $seller->email }}</td>
                    <td>{{ number_format($seller->percentual_comissao, 2) }}%</td>
                    <td>R$ {{ number_format($seller->meta_mensal, 2, ',', '.') }}</td>
                    <td>{{ $seller->data_admissao ? date('d/m/Y', strtotime($seller->data_admissao)) : '-' }}</td>
                    <td class="{{ $seller->ativo ? 'status-ativo' : 'status-inativo' }}">
                        {{ $seller->ativo ? 'Ativo' : 'Inativo' }}
                    </td>
                    <td>{{ $seller->budgets_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Nenhum vendedor encontrado</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3>Resumo</h3>
        <div class="summary-item">
            <strong>Vendedores Ativos:</strong> {{ $totals['vendedores_ativos'] }}
        </div>
        <div class="summary-item">
            <strong>Vendedores Inativos:</strong> {{ $totals['vendedores_inativos'] }}
        </div>
        <div class="summary-item">
            <strong>Total de Vendedores:</strong> {{ $totals['total_vendedores'] }}
        </div>
    </div>

    <div class="footer">
        Relatório gerado automaticamente pelo sistema MarmosyS<br>
        {{ config('app.name') }} - {{ config('app.url') }}
    </div>

    <div class="text-right">
        Página 1
    </div>
</div>

<style>
    /* Estilos comuns */
    @media screen {
        #printTemplate {
            display: none;
        }
    }
    
    /* Estilos específicos para impressão */
    @media print {
        /* Esconde tudo exceto o template de impressão */
        body * {
            visibility: hidden;
        }
        
        #printTemplate, #printTemplate * {
            visibility: visible;
        }
        
        #printTemplate {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            display: block !important;
        }
        
        .btn, .sidenav, .pagination, form, .card-action, nav, footer, 
        .card-title, .card, table.responsive-table, .hide-on-print {
            display: none !important;
        }
        
        /* Estilos para o relatório impresso */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        
        .header {
            width: 100%;
            padding-bottom: 10px;
            border-bottom: 1px solid #1976D2;
            margin-bottom: 20px;
        }
        
        .header-left {
            width: 30%;
            float: left;
        }
        
        .header-right {
            width: 70%;
            float: right;
            text-align: right;
            color: #1976D2;
        }
        
        .header-right h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
            color: #1976D2;
            font-weight: bold;
        }
        
        .logo {
            max-height: 50px;
        }
        
        .header-info {
            font-size: 9px;
            margin-top: 5px;
            color: #666;
            line-height: 1.3;
        }
        
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        
        .filters {
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 9px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        
        table, th, td {
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #1976D2;
            color: white;
            font-weight: normal;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        
        td {
            padding: 5px;
            font-size: 10px;
            overflow: hidden;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .col-nome { width: 15%; }
        .col-cpf { width: 10%; }
        .col-telefone { width: 10%; }
        .col-email { width: 15%; }
        .col-comissao { width: 8%; }
        .col-meta { width: 10%; }
        .col-admissao { width: 10%; }
        .col-status { width: 8%; }
        .col-orcamentos { width: 10%; }
        
        .status-ativo { color: #4CAF50; }
        .status-inativo { color: #F44336; }
        
        .summary {
            background-color: #E3F2FD;
            padding: 10px;
            margin: 20px 0;
            border-radius: 3px;
        }
        
        .summary h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
        }
        
        .summary-item {
            margin-bottom: 3px;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
            font-size: 8px;
            color: #666;
            margin-top: 5px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);
    });
    
    function printReport() {
        // Adiciona uma classe temporária ao body para controlar melhor a impressão
        document.body.classList.add('printing');
        
        // Força a exibição do template de impressão
        document.getElementById('printTemplate').style.display = 'block';
        
        // Inicia a impressão
        window.print();
        
        // Após a impressão, restaura o estado original
        setTimeout(function() {
            document.getElementById('printTemplate').style.display = 'none';
            document.body.classList.remove('printing');
        }, 1000);
    }
</script>
@endsection 
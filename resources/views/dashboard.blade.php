@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Card Clientes -->
    <div class="col s12 m6 l3">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Clientes Ativos</span>
                <h4>{{ $totalClientes }}</h4>
            </div>
            <div class="card-action">
                <a href="{{ route('clients.index') }}">Ver Clientes</a>
            </div>
        </div>
    </div>

    <!-- Card Produtos -->
    <div class="col s12 m6 l3">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Produtos Ativos</span>
                <h4>{{ $totalProdutos }}</h4>
            </div>
            <div class="card-action">
                <a href="{{ route('products.index') }}">Ver Produtos</a>
            </div>
        </div>
    </div>

    <!-- Card Contas a Receber -->
    <div class="col s12 m6 l3">
        <div class="card green lighten-4">
            <div class="card-content">
                <span class="card-title">Contas a Receber</span>
                <h4>R$ {{ number_format($contasReceber, 2, ',', '.') }}</h4>
            </div>
            <div class="card-action">
                <a href="{{ route('financial.accounts.index', ['tipo' => 'receita']) }}">Ver Detalhes</a>
            </div>
        </div>
    </div>

    <!-- Card Contas a Pagar -->
    <div class="col s12 m6 l3">
        <div class="card red lighten-4">
            <div class="card-content">
                <span class="card-title">Contas a Pagar</span>
                <h4>R$ {{ number_format($contasPagar, 2, ',', '.') }}</h4>
            </div>
            <div class="card-action">
                <a href="{{ route('financial.accounts.index', ['tipo' => 'despesa']) }}">Ver Detalhes</a>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico dos últimos 6 meses -->
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Receitas x Despesas - Últimos 6 meses</span>
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Mês</th>
                            <th>Receitas</th>
                            <th>Despesas</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimosMeses as $mes)
                            <tr>
                                <td>{{ $mes['mes'] }}</td>
                                <td>R$ {{ number_format($mes['receitas'], 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($mes['despesas'], 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($mes['receitas'] - $mes['despesas'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 
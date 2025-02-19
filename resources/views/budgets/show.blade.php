@extends('layouts.app')

@section('title', 'Detalhes do Orçamento')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Orçamento #{{ $budget->numero }}</span>
                    </div>
                    <div class="col s6 right-align">
                        @if($budget->status === 'pendente')
                        <a href="{{ route('budgets.edit', $budget) }}" class="btn waves-effect waves-light orange">
                            <i class="material-icons left">edit</i>Editar
                        </a>
                        @endif
                        <a href="#" onclick="window.print()" class="btn waves-effect waves-light green">
                            <i class="material-icons left">print</i>Imprimir
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6">
                        <h6>Informações do Cliente</h6>
                        <table class="striped">
                            <tbody>
                                <tr>
                                    <th>Nome:</th>
                                    <td>{{ $budget->client->nome }}</td>
                                </tr>
                                <tr>
                                    <th>CPF/CNPJ:</th>
                                    <td>{{ $budget->client->cpf_cnpj }}</td>
                                </tr>
                                <tr>
                                    <th>Endereço:</th>
                                    <td>
                                        {{ $budget->client->endereco }}, {{ $budget->client->numero }}<br>
                                        {{ $budget->client->bairro }} - {{ $budget->client->cidade }}/{{ $budget->client->estado }}<br>
                                        CEP: {{ $budget->client->cep }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Contato:</th>
                                    <td>
                                        Tel: {{ $budget->client->celular }}<br>
                                        Email: {{ $budget->client->email }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col s12 m6">
                        <h6>Informações do Orçamento</h6>
                        <table class="striped">
                            <tbody>
                                <tr>
                                    <th>Número:</th>
                                    <td>#{{ $budget->numero }}</td>
                                </tr>
                                <tr>
                                    <th>Data:</th>
                                    <td>{{ $budget->created_at->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Validade:</th>
                                    <td>{{ $budget->data_validade->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="chip {{ $budget->status }}">
                                            {{ ucfirst($budget->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <h6>Itens do Orçamento</h6>
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>Ambiente</th>
                                    <th>Descrição</th>
                                    <th>Largura (m)</th>
                                    <th>Altura (m)</th>
                                    <th>Área (m²)</th>
                                    <th>Quantidade</th>
                                    <th>Valor Unit. (R$)</th>
                                    <th>Total (R$)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($budget->items as $item)
                                <tr>
                                    <td>{{ $item->ambiente }}</td>
                                    <td>{{ $item->descricao }}</td>
                                    <td>{{ number_format($item->largura, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->altura, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->largura * $item->altura, 2, ',', '.') }}</td>
                                    <td>{{ $item->quantidade }}</td>
                                    <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="right-align"><strong>Total Geral:</strong></td>
                                    <td><strong>R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if($budget->observacoes)
                <div class="row">
                    <div class="col s12">
                        <h6>Observações</h6>
                        <p class="grey-text">{{ $budget->observacoes }}</p>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col s12">
                        <a href="{{ route('budgets.index') }}" class="btn waves-effect waves-light grey">
                            <i class="material-icons left">arrow_back</i>
                            Voltar
                        </a>
                        @if($budget->status === 'pendente')
                        <form action="{{ route('budgets.approve', $budget) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn waves-effect waves-light green" onclick="return confirm('Tem certeza que deseja aprovar este orçamento?')">
                                <i class="material-icons left">check</i>
                                Aprovar
                            </button>
                        </form>
                        <form action="{{ route('budgets.reject', $budget) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn waves-effect waves-light red" onclick="return confirm('Tem certeza que deseja rejeitar este orçamento?')">
                                <i class="material-icons left">close</i>
                                Rejeitar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <div id="financeiro" class="col s12">
                    <div class="card">
                        <div class="card-content">
                            @if($budget->financial_account)
                                <table class="striped">
                                    <thead>
                                        <tr>
                                            <th>Categoria</th>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                            <th>Vencimento</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $budget->financial_account->category->nome }}</td>
                                            <td>{{ $budget->financial_account->descricao }}</td>
                                            <td>R$ {{ number_format($budget->financial_account->valor, 2, ',', '.') }}</td>
                                            <td>{{ $budget->financial_account->data_vencimento->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="chip {{ $budget->financial_account->status_class }}">
                                                    {{ $budget->financial_account->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('financial.accounts.edit', $budget->financial_account) }}" 
                                                   class="btn-small waves-effect waves-light orange">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <p class="center-align">Nenhuma conta financeira vinculada a este orçamento.</p>
                                <div class="center-align">
                                    <a href="{{ route('financial.accounts.create', ['budget_id' => $budget->id]) }}" 
                                       class="btn waves-effect waves-light blue">
                                        <i class="material-icons left">add</i>
                                        Criar Conta
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style media="print">
    .btn, .no-print { display: none !important; }
    .card { box-shadow: none !important; }
    .card-content { padding: 0 !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tabs = document.querySelectorAll('.tabs');
    M.Tabs.init(tabs);
    // ... resto do código existente ...
});
</script>
@endpush
@endsection 
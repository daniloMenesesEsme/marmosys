@extends('layouts.app')

@section('title', 'Detalhes do Orçamento')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Orçamento {{ $budget->numero }}</span>

                <div class="row">
                    <div class="col s12 m6">
                        <h6>Dados do Cliente</h6>
                        <p><strong>Nome:</strong> {{ $budget->client->nome }}</p>
                        <p><strong>CPF/CNPJ:</strong> {{ $budget->client->cpf_cnpj ?? 'Não informado' }}</p>
                        <p><strong>Endereço:</strong> {{ $budget->client->endereco ?? 'Não informado' }}</p>
                        <p><strong>Telefone:</strong> {{ $budget->client->telefone ?? 'Não informado' }}</p>
                        <p><strong>Email:</strong> {{ $budget->client->email ?? 'Não informado' }}</p>
                    </div>
                    <div class="col s12 m6">
                        <h6>Dados do Orçamento</h6>
                        <p><strong>Data:</strong> {{ $budget->data->format('d/m/Y') }}</p>
                        <p><strong>Validade:</strong> {{ $budget->data_validade->format('d/m/Y') }}</p>
                        <p><strong>Status:</strong> 
                            <span class="chip {{ $budget->status_class }}">
                                {{ $budget->status_text }}
                            </span>
                        </p>
                        <p><strong>Valor Total:</strong> R$ {{ number_format($budget->valor_total, 2, ',', '.') }}</p>
                        <p><strong>Desconto:</strong> R$ {{ number_format($budget->desconto, 2, ',', '.') }}</p>
                        <p><strong>Valor Final:</strong> R$ {{ number_format($budget->valor_final, 2, ',', '.') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <h5>Itens do Orçamento</h5>
                        @foreach($budget->rooms as $room)
                            <div class="ambiente">
                                <h5>{{ $room->nome }}</h5>
                                <table class="striped">
                                    <thead>
                                        <tr>
                                            <th>Material</th>
                                            <th>Quantidade</th>
                                            <th>Unid.</th>
                                            <th>Dimensões</th>
                                            <th>Valor Unit.</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="right-align"><strong>Subtotal do Ambiente:</strong></td>
                                            <td><strong>R$ {{ number_format($room->items->sum('valor_total'), 2, ',', '.') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        @if($budget->observacoes)
                            <div class="card">
                                <div class="card-content">
                                    <span class="card-title">Observações</span>
                                    <p>{{ $budget->observacoes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-action" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('financial.budgets.edit', $budget) }}" class="btn waves-effect waves-light teal">
                        <i class="material-icons left">edit</i>
                        EDITAR
                    </a>
                    
                    <button type="button" 
                            class="btn btn-secondary" 
                            onclick="window.open('{{ route('financial.budgets.pdf', $budget->id) }}', '_blank')">
                        <i class="fas fa-file-pdf"></i> Visualizar PDF
                    </button>
                    
                    <button onclick="window.print()" class="btn blue waves-effect waves-light">
                        <i class="material-icons left">print</i>
                        IMPRIMIR
                    </button>

                    <a href="#" class="btn red waves-effect waves-light" onclick="event.preventDefault(); if(confirm('Tem certeza?')) document.getElementById('form-delete').submit();">
                        <i class="material-icons left">delete</i>
                        EXCLUIR
                    </a>

                    @if($budget->status === 'aguardando_aprovacao')
                        <button type="button" onclick="aprovarOrcamento()" class="btn green waves-effect waves-light">
                            <i class="material-icons left">check</i>
                            APROVAR
                        </button>

                        <button type="button" class="btn red waves-effect waves-light modal-trigger" data-target="modal-rejeitar">
                            <i class="material-icons left">close</i>
                            REJEITAR
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-aprovar" action="{{ route('financial.budgets.approve', $budget) }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="action" value="approve">
</form>

<form id="form-delete" action="{{ route('financial.budgets.destroy', $budget) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function aprovarOrcamento() {
    if(confirm('Tem certeza que deseja aprovar este orçamento?')) {
        document.getElementById('form-aprovar').submit();
    }
}
</script>

<!-- Modal de Rejeição -->
<div id="modal-rejeitar" class="modal">
    <form method="POST" action="{{ route('financial.budgets.approve', $budget) }}">
        @csrf
        <input type="hidden" name="action" value="reject">
        <div class="modal-content">
            <h4>Rejeitar Orçamento</h4>
            <div class="input-field">
                <textarea name="motivo_reprovacao" class="materialize-textarea" required></textarea>
                <label>Motivo da Rejeição</label>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            <button type="submit" class="waves-effect waves-light btn red">Confirmar</button>
        </div>
    </form>
</div>

<!-- Adicione este estilo para controlar o que será impresso -->
<style type="text/css" media="print">
    /* Oculta elementos desnecessários */
    .card-action, .sidenav, .navbar-fixed, nav, footer {
        display: none !important;
    }

    /* Configura página para retrato */
    @page {
        size: portrait;
        margin: 20mm 15mm;
    }

    /* Ajusta o conteúdo do orçamento */
    .container {
        width: 100% !important;
        max-width: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .card {
        box-shadow: none !important;
        border: none !important;
    }

    /* Garante que todo conteúdo seja impresso */
    .row {
        page-break-inside: avoid;
    }

    /* Melhora legibilidade do texto */
    body {
        font-size: 12pt;
        line-height: 1.3;
    }

    /* Ajusta tamanhos de títulos */
    .card-title {
        font-size: 16pt !important;
        margin-bottom: 15px !important;
    }
</style>
@endsection 

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
                            <div class="card">
                                <div class="card-content">
                                    <span class="card-title">{{ $room->nome }}</span>
                                    <table class="striped responsive-table">
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
                                                    <td>{{ number_format($item->largura, 2, ',', '.') }}m x {{ number_format($item->altura, 2, ',', '.') }}m</td>
                                                    <td>R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                                    <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5" class="right-align"><strong>Subtotal do Ambiente:</strong></td>
                                                <td><strong>R$ {{ number_format($room->valor_total, 2, ',', '.') }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-action">
                    <a href="{{ route('financial.budgets.edit', $budget) }}" 
                       class="btn waves-effect waves-light">
                        <i class="material-icons left">edit</i>
                        Editar
                    </a>
                    
                    <a href="{{ route('financial.budgets.pdf', $budget) }}" 
                       class="btn waves-effect waves-light purple">
                        <i class="material-icons left">picture_as_pdf</i>
                        Gerar PDF
                    </a>
                    
                    <a href="{{ route('financial.budgets.print', $budget) }}" 
                       class="btn waves-effect waves-light blue-grey">
                        <i class="material-icons left">print</i>
                        Imprimir
                    </a>
                    
                    <form action="{{ route('financial.budgets.destroy', $budget) }}" 
                          method="POST" 
                          style="display: inline;"
                          onsubmit="return confirm('Tem certeza que deseja excluir este orçamento?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn waves-effect waves-light red">
                            <i class="material-icons left">delete</i>
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Produtos</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="{{ route('stock.products.create') }}" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>Novo Produto
                        </a>
                    </div>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Unidade</th>
                            <th>Estoque Atual</th>
                            <th>Estoque Mínimo</th>
                            <th>Valor Unitário</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->codigo }}</td>
                            <td>{{ $product->nome }}</td>
                            <td>{{ $product->category->nome }}</td>
                            <td>{{ $product->unidade }}</td>
                            <td class="{{ $product->estoque_atual < $product->estoque_minimo ? 'red-text' : '' }}">
                                {{ number_format($product->estoque_atual, 2, ',', '.') }}
                            </td>
                            <td>{{ number_format($product->estoque_minimo, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($product->valor_unitario, 2, ',', '.') }}</td>
                            <td>
                                <span class="chip {{ $product->ativo ? 'green white-text' : 'red white-text' }}">
                                    {{ $product->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('stock.products.edit', $product) }}" class="btn-small waves-effect waves-light orange">
                                    <i class="material-icons">edit</i>
                                </a>
                                <form action="{{ route('stock.products.destroy', $product) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small waves-effect waves-light red" onclick="return confirm('Tem certeza que deseja {{ $product->ativo ? 'inativar' : 'ativar' }} este produto?')">
                                        <i class="material-icons">{{ $product->ativo ? 'delete' : 'restore' }}</i>
                                    </button>
                                </form>
                                <a href="{{ route('stock.movements.create', ['product_id' => $product->id]) }}" class="btn-small waves-effect waves-light green">
                                    <i class="material-icons">swap_horiz</i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
@extends('layouts.app')

@section('title', 'Movimentações de Estoque')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Movimentações de Estoque</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="{{ route('stock.movements.create') }}" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>Nova Movimentação
                        </a>
                    </div>
                </div>

                <div class="row">
                    <form action="{{ route('stock.movements.index') }}" method="GET">
                        <div class="input-field col s12 m3">
                            <select name="tipo" id="tipo">
                                <option value="">Todos os Tipos</option>
                                <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                                <option value="saida" {{ request('tipo') == 'saida' ? 'selected' : '' }}>Saída</option>
                            </select>
                            <label for="tipo">Tipo</label>
                        </div>

                        <div class="input-field col s12 m3">
                            <select name="product_id" id="product_id">
                                <option value="">Todos os Produtos</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="product_id">Produto</label>
                        </div>

                        <div class="input-field col s12 m2">
                            <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}">
                            <label for="data_inicio">Data Inicial</label>
                        </div>

                        <div class="input-field col s12 m2">
                            <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}">
                            <label for="data_fim">Data Final</label>
                        </div>

                        <div class="input-field col s12 m2">
                            <button type="submit" class="btn waves-effect waves-light">
                                <i class="material-icons">search</i>
                            </button>
                            <a href="{{ route('stock.movements.index') }}" class="btn waves-effect waves-light red">
                                <i class="material-icons">clear</i>
                            </a>
                        </div>
                    </form>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Motivo</th>
                            <th>Documento</th>
                            <th>Observações</th>
                            <th>Usuário</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="chip {{ $movement->tipo === 'entrada' ? 'green white-text' : 'red white-text' }}">
                                    {{ ucfirst($movement->tipo) }}
                                </span>
                            </td>
                            <td>{{ $movement->product->nome }}</td>
                            <td>{{ number_format($movement->quantidade, 2, ',', '.') }}</td>
                            <td>{{ $movement->motivo }}</td>
                            <td>{{ $movement->documento ?? '-' }}</td>
                            <td>{{ $movement->observacoes ?? '-' }}</td>
                            <td>{{ $movement->user->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});
</script>
@endpush
@endsection 
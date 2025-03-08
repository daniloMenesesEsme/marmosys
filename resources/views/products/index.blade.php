@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Produtos e Serviços
                    <a href="{{ route('products.create') }}" class="btn-floating btn waves-effect waves-light blue right">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <!-- Filtros -->
                <div class="row">
                    <form action="{{ route('products.index') }}" method="GET" class="col s12">
                        <div class="row">
                            <div class="input-field col s12 m3">
                                <select name="tipo" id="filtro-tipo">
                                    <option value="">Todos os Tipos</option>
                                    @foreach(\App\Enums\ProductType::cases() as $type)
                                        <option value="{{ $type->value }}" {{ request('tipo') == $type->value ? 'selected' : '' }}>
                                            {{ $type->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="filtro-tipo">Tipo</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="busca" name="busca" value="{{ request('busca') }}">
                                <label for="busca">Buscar</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <select name="status" id="filtro-status">
                                    <option value="">Todos os Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativos</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativos</option>
                                </select>
                                <label for="filtro-status">Status</label>
                            </div>
                            <div class="col s12 m3">
                                <button type="submit" class="btn waves-effect waves-light blue">
                                    <i class="material-icons left">search</i>
                                    Filtrar
                                </button>
                                <a href="{{ route('products.index') }}" class="btn waves-effect waves-light grey">
                                    <i class="material-icons left">clear</i>
                                    Limpar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Preço</th>
                            <th class="center-align">Estoque</th>
                            <th>Status</th>
                            <th class="right-align">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->codigo }}</td>
                                <td>{{ $product->nome }}</td>
                                <td>
                                    <span class="chip">
                                        {{ $product->tipo?->label() ?? 'Produto' }}
                                    </span>
                                </td>
                                <td>R$ {{ number_format($product->preco_venda, 2, ',', '.') }}</td>
                                <td class="center-align">
                                    @if($product->tipo !== \App\Enums\ProductType::SERVICE)
                                        <span class="chip {{ $product->status_estoque['class'] }}">
                                            {{ $product->estoque_atual }} {{ $product->unidade_medida }}
                                        </span>
                                    @else
                                        <span class="chip blue white-text">Serviço</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="chip {{ $product->ativo ? 'green' : 'grey' }} white-text">
                                        {{ $product->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="right-align">
                                    <a href="{{ route('products.show', $product) }}" class="btn-small waves-effect waves-light blue">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn-small waves-effect waves-light orange">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    @if($product->ativo)
                                        <a href="#modal-deletar-{{ $product->id }}" class="btn-small waves-effect waves-light red modal-trigger">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="center-align">Nenhum item encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($products as $product)
    <!-- Modal de Exclusão -->
    <div id="modal-deletar-{{ $product->id }}" class="modal">
        <form action="{{ route('products.destroy', $product) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja desativar "{{ $product->nome }}"?</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Confirmar</button>
            </div>
        </form>
    </div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);

    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});
</script>
@endpush
@endsection 
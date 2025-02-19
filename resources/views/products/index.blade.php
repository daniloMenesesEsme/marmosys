@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Produtos
                    <a href="#modal-novo" class="btn-floating btn waves-effect waves-light right modal-trigger">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Preço Venda</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->codigo }}</td>
                                <td>{{ $product->nome }}</td>
                                <td>R$ {{ number_format($product->preco_venda, 2, ',', '.') }}</td>
                                <td>{{ $product->estoque }}</td>
                                <td>
                                    @if($product->ativo)
                                        <span class="new badge green" data-badge-caption="">Ativo</span>
                                    @else
                                        <span class="new badge grey" data-badge-caption="">Inativo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#modal-editar-{{ $product->id }}" class="btn-small waves-effect waves-light modal-trigger">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    @if($product->ativo)
                                        <a href="#modal-deletar-{{ $product->id }}" class="btn-small red waves-effect waves-light modal-trigger">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="center">Nenhum produto cadastrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Produto -->
<div id="modal-novo" class="modal">
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <h4>Novo Produto</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="codigo" name="codigo" required>
                    <label for="codigo">Código</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" id="nome" name="nome" required>
                    <label for="nome">Nome</label>
                </div>
                <div class="input-field col s12">
                    <textarea id="descricao" name="descricao" class="materialize-textarea"></textarea>
                    <label for="descricao">Descrição</label>
                </div>
                <div class="input-field col s6">
                    <input type="number" step="0.01" id="preco_venda" name="preco_venda" required>
                    <label for="preco_venda">Preço de Venda</label>
                </div>
                <div class="input-field col s6">
                    <input type="number" id="estoque" name="estoque" required>
                    <label for="estoque">Estoque</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
        </div>
    </form>
</div>

<!-- Modais de Edição -->
@foreach($products as $product)
    <div id="modal-editar-{{ $product->id }}" class="modal">
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <h4>Editar Produto</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="codigo-{{ $product->id }}" name="codigo" value="{{ $product->codigo }}" required>
                        <label for="codigo-{{ $product->id }}">Código</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" id="nome-{{ $product->id }}" name="nome" value="{{ $product->nome }}" required>
                        <label for="nome-{{ $product->id }}">Nome</label>
                    </div>
                    <div class="input-field col s12">
                        <textarea id="descricao-{{ $product->id }}" name="descricao" class="materialize-textarea">{{ $product->descricao }}</textarea>
                        <label for="descricao-{{ $product->id }}">Descrição</label>
                    </div>
                    <div class="input-field col s6">
                        <input type="number" step="0.01" id="preco_venda-{{ $product->id }}" name="preco_venda" value="{{ $product->preco_venda }}" required>
                        <label for="preco_venda-{{ $product->id }}">Preço de Venda</label>
                    </div>
                    <div class="input-field col s6">
                        <input type="number" id="estoque-{{ $product->id }}" name="estoque" value="{{ $product->estoque }}" required>
                        <label for="estoque-{{ $product->id }}">Estoque</label>
                    </div>
                    <div class="input-field col s12">
                        <label>
                            <input type="checkbox" name="ativo" value="1" {{ $product->ativo ? 'checked' : '' }}>
                            <span>Ativo</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
            </div>
        </form>
    </div>

    <!-- Modal de Exclusão -->
    <div id="modal-deletar-{{ $product->id }}" class="modal">
        <form action="{{ route('products.destroy', $product) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja desativar o produto "{{ $product->nome }}"?</p>
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
        
        var textareas = document.querySelectorAll('.materialize-textarea');
        M.textareaAutoResize(textareas);
    });
</script>
@endpush
@endsection 
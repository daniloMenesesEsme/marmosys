@extends('layouts.app')

@section('title', 'Detalhes do Produto')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">{{ $product->nome }}</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="#modal-ajuste-estoque" class="btn waves-effect waves-light blue modal-trigger">
                            <i class="material-icons left">add_shopping_cart</i>Ajustar Estoque
                        </a>
                        <a href="{{ route('products.edit', $product) }}" class="btn waves-effect waves-light orange">
                            <i class="material-icons left">edit</i>Editar
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12 m6">
                        <h6>Informações Básicas</h6>
                        <table class="striped">
                            <tbody>
                                <tr>
                                    <th>Código:</th>
                                    <td>{{ $product->codigo ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Categoria:</th>
                                    <td>{{ $product->categoria ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Fornecedor:</th>
                                    <td>{{ $product->fornecedor ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Unidade de Medida:</th>
                                    <td>{{ $product->unidade_medida }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="chip {{ $product->ativo ? 'green white-text' : 'red white-text' }}">
                                            {{ $product->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col s12 m6">
                        <h6>Informações de Estoque e Preços</h6>
                        <table class="striped">
                            <tbody>
                                <tr>
                                    <th>Estoque Atual:</th>
                                    <td>
                                        <span class="chip {{ $product->status_estoque['class'] }}">
                                            {{ $product->estoque_atual }} {{ $product->unidade_medida }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Estoque Mínimo:</th>
                                    <td>{{ $product->estoque_minimo }} {{ $product->unidade_medida }}</td>
                                </tr>
                                <tr>
                                    <th>Preço de Custo:</th>
                                    <td>R$ {{ number_format($product->preco_custo, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Preço de Venda:</th>
                                    <td>R$ {{ number_format($product->preco_venda, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Margem de Lucro:</th>
                                    <td>{{ number_format($product->margem_lucro, 2, ',', '.') }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($product->descricao)
                <div class="row">
                    <div class="col s12">
                        <h6>Descrição</h6>
                        <p class="grey-text">{{ $product->descricao }}</p>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col s12">
                        <h6>Últimos Movimentos</h6>
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>Quantidade</th>
                                    <th>Observação</th>
                                    <th>Usuário</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->movimentos()->latest()->take(5)->get() as $movimento)
                                <tr>
                                    <td>{{ $movimento->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="chip {{ $movimento->tipo == 'entrada' ? 'green white-text' : 'red white-text' }}">
                                            {{ ucfirst($movimento->tipo) }}
                                        </span>
                                    </td>
                                    <td>{{ $movimento->quantidade }} {{ $product->unidade_medida }}</td>
                                    <td>{{ $movimento->observacao }}</td>
                                    <td>{{ $movimento->user->name }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="center-align">Nenhum movimento registrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <a href="{{ route('products.index') }}" class="btn waves-effect waves-light grey">
                            <i class="material-icons left">arrow_back</i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Ajuste de Estoque -->
<div id="modal-ajuste-estoque" class="modal">
    <form action="{{ route('products.ajustar-estoque', $product) }}" method="POST">
        @csrf
        <div class="modal-content">
            <h4>Ajustar Estoque</h4>
            
            <div class="row">
                <div class="input-field col s12 m6">
                    <select name="tipo_movimento" id="tipo_movimento" required>
                        <option value="" disabled selected>Selecione</option>
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                    <label for="tipo_movimento">Tipo de Movimento*</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="number" id="quantidade" name="quantidade" step="0.01" min="0.01" required>
                    <label for="quantidade">Quantidade*</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <textarea id="observacao" name="observacao" class="materialize-textarea" required></textarea>
                    <label for="observacao">Observação*</label>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <a href="#!" class="modal-close btn waves-effect waves-light grey">Cancelar</a>
            <button type="submit" class="btn waves-effect waves-light blue">
                <i class="material-icons left">save</i>
                Salvar
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modals = document.querySelectorAll('.modal');
    M.Modal.init(modals);

    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    var textareas = document.querySelectorAll('textarea');
    M.textareaAutoResize(textareas);
});
</script>
@endpush
@endsection 
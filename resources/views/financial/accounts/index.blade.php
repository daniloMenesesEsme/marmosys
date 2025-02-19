@extends('layouts.app')

@section('title', 'Contas')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Contas {{ request('tipo', 'receita') == 'receita' ? 'a Receber' : 'a Pagar' }}
                    <a href="#modal-novo" class="btn-floating btn waves-effect waves-light right modal-trigger">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <!-- Filtros -->
                <div class="row">
                    <form class="col s12" method="GET">
                        <div class="row">
                            <div class="input-field col s12 m3">
                                <select name="tipo" id="tipo" onchange="this.form.submit()">
                                    <option value="receita" {{ request('tipo', 'receita') == 'receita' ? 'selected' : '' }}>Contas a Receber</option>
                                    <option value="despesa" {{ request('tipo') == 'despesa' ? 'selected' : '' }}>Contas a Pagar</option>
                                </select>
                                <label for="tipo">Tipo</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <select name="status" id="status" onchange="this.form.submit()">
                                    <option value="">Todos</option>
                                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendentes</option>
                                    <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pagas</option>
                                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Canceladas</option>
                                </select>
                                <label for="status">Status</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}">
                                <label for="data_inicio">Data Inicial</label>
                            </div>

                            <div class="input-field col s12 m3">
                                <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}">
                                <label for="data_fim">Data Final</label>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Totais -->
                <div class="row">
                    <div class="col s12 m3">
                        <div class="card-panel orange lighten-4">
                            <span class="card-title">A Receber</span>
                            <h5>R$ {{ number_format($totais['a_receber'], 2, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="card-panel red lighten-4">
                            <span class="card-title">A Pagar</span>
                            <h5>R$ {{ number_format($totais['a_pagar'], 2, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="card-panel green lighten-4">
                            <span class="card-title">Recebido</span>
                            <h5>R$ {{ number_format($totais['recebido'], 2, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <div class="card-panel blue lighten-4">
                            <span class="card-title">Pago</span>
                            <h5>R$ {{ number_format($totais['pago'], 2, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Lista de Contas -->
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td>{{ $account->descricao }}</td>
                                <td>{{ $account->category->nome }}</td>
                                <td>{{ $account->data_vencimento->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($account->valor, 2, ',', '.') }}</td>
                                <td>
                                    @if($account->status == 'pendente')
                                        <span class="new badge orange" data-badge-caption="">Pendente</span>
                                    @elseif($account->status == 'pago')
                                        <span class="new badge green" data-badge-caption="">Pago</span>
                                    @else
                                        <span class="new badge grey" data-badge-caption="">Cancelado</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#modal-editar-{{ $account->id }}" class="btn-small waves-effect waves-light modal-trigger">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    @if($account->status == 'pendente')
                                        <a href="#modal-pagar-{{ $account->id }}" class="btn-small green waves-effect waves-light modal-trigger">
                                            <i class="material-icons">payment</i>
                                        </a>
                                    @endif
                                    <a href="#modal-deletar-{{ $account->id }}" class="btn-small red waves-effect waves-light modal-trigger">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="center">Nenhuma conta encontrada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $accounts->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo -->
<div id="modal-novo" class="modal">
    <form action="{{ route('financial.accounts.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tipo" value="{{ request('tipo', 'receita') }}">
        
        <div class="modal-content">
            <h4>Nova Conta</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="descricao" name="descricao" required>
                    <label for="descricao">Descrição</label>
                </div>
                
                <div class="input-field col s12">
                    <select name="category_id" required>
                        <option value="" disabled selected>Selecione uma categoria</option>
                        @foreach($categories['receita'] ?? [] as $category)
                            <option value="{{ $category->id }}">{{ $category->nome }}</option>
                        @endforeach
                    </select>
                    <label>Categoria</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="number" step="0.01" id="valor" name="valor" required>
                    <label for="valor">Valor</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="date" id="data_vencimento" name="data_vencimento" required>
                    <label for="data_vencimento">Data de Vencimento</label>
                </div>

                <div class="input-field col s12">
                    <textarea id="observacoes" name="observacoes" class="materialize-textarea"></textarea>
                    <label for="observacoes">Observações</label>
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
@foreach($accounts as $account)
    <div id="modal-editar-{{ $account->id }}" class="modal">
        <form action="{{ route('financial.accounts.update', $account) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="modal-content">
                <h4>Editar Conta</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="descricao-{{ $account->id }}" name="descricao" value="{{ $account->descricao }}" required>
                        <label for="descricao-{{ $account->id }}">Descrição</label>
                    </div>
                    
                    <div class="input-field col s12">
                        <select name="category_id" required>
                            @foreach($categories['receita'] ?? [] as $category)
                                <option value="{{ $category->id }}" {{ $account->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->nome }}
                                </option>
                            @endforeach
                        </select>
                        <label>Categoria</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="number" step="0.01" id="valor-{{ $account->id }}" name="valor" value="{{ $account->valor }}" required>
                        <label for="valor-{{ $account->id }}">Valor</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="date" id="data_vencimento-{{ $account->id }}" name="data_vencimento" value="{{ $account->data_vencimento->format('Y-m-d') }}" required>
                        <label for="data_vencimento-{{ $account->id }}">Data de Vencimento</label>
                    </div>

                    <div class="input-field col s12">
                        <select name="status" required>
                            <option value="pendente" {{ $account->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="pago" {{ $account->status == 'pago' ? 'selected' : '' }}>Pago</option>
                            <option value="cancelado" {{ $account->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        <label>Status</label>
                    </div>

                    <div class="input-field col s12">
                        <textarea id="observacoes-{{ $account->id }}" name="observacoes" class="materialize-textarea">{{ $account->observacoes }}</textarea>
                        <label for="observacoes-{{ $account->id }}">Observações</label>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
            </div>
        </form>
    </div>

    <!-- Modal de Pagamento -->
    @if($account->status == 'pendente')
        <div id="modal-pagar-{{ $account->id }}" class="modal">
            <form action="{{ route('financial.accounts.pay', $account) }}" method="POST">
                @csrf
                
                <div class="modal-content">
                    <h4>Registrar Pagamento</h4>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="date" id="data_pagamento-{{ $account->id }}" name="data_pagamento" value="{{ date('Y-m-d') }}" required>
                            <label for="data_pagamento-{{ $account->id }}">Data do Pagamento</label>
                        </div>

                        <div class="input-field col s12">
                            <select name="forma_pagamento" required>
                                <option value="" disabled selected>Selecione a forma de pagamento</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="pix">PIX</option>
                                <option value="cartao">Cartão</option>
                                <option value="boleto">Boleto</option>
                                <option value="transferencia">Transferência</option>
                            </select>
                            <label>Forma de Pagamento</label>
                        </div>

                        <div class="input-field col s12">
                            <textarea id="observacoes-pag-{{ $account->id }}" name="observacoes" class="materialize-textarea"></textarea>
                            <label for="observacoes-pag-{{ $account->id }}">Observações</label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
                    <button type="submit" class="waves-effect waves-green btn-flat">Confirmar</button>
                </div>
            </form>
        </div>
    @endif

    <!-- Modal de Exclusão -->
    <div id="modal-deletar-{{ $account->id }}" class="modal">
        <form action="{{ route('financial.accounts.destroy', $account) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja excluir esta conta?</p>
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
        
        var textareas = document.querySelectorAll('.materialize-textarea');
        M.textareaAutoResize(textareas);
    });
</script>
@endpush
@endsection 
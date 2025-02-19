@extends('layouts.app')

@section('title', 'Metas Financeiras')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Metas Financeiras
                    <a href="#modal-novo" class="btn-floating btn waves-effect waves-light right modal-trigger">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <!-- Lista de Metas -->
                <table class="striped">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Meta</th>
                            <th>Atual</th>
                            <th>Progresso</th>
                            <th>Data Final</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($goals as $goal)
                            <tr>
                                <td>{{ $goal->descricao }}</td>
                                <td>R$ {{ number_format($goal->valor_meta, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($goal->valor_atual, 2, ',', '.') }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="determinate" style="width: {{ $goal->percentual }}%"></div>
                                    </div>
                                    {{ $goal->percentual }}%
                                </td>
                                <td>{{ $goal->data_final->format('d/m/Y') }}</td>
                                <td>
                                    @if($goal->status == 'em_andamento')
                                        <span class="new badge orange" data-badge-caption="">Em Andamento</span>
                                    @elseif($goal->status == 'concluida')
                                        <span class="new badge green" data-badge-caption="">Concluída</span>
                                    @else
                                        <span class="new badge grey" data-badge-caption="">Cancelada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#modal-editar-{{ $goal->id }}" class="btn-small waves-effect waves-light modal-trigger">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <a href="#modal-deletar-{{ $goal->id }}" class="btn-small red waves-effect waves-light modal-trigger">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="center">Nenhuma meta encontrada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo -->
<div id="modal-novo" class="modal">
    <form action="{{ route('financial.goals.store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <h4>Nova Meta</h4>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="descricao" name="descricao" required>
                    <label for="descricao">Descrição</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="number" step="0.01" id="valor_meta" name="valor_meta" required>
                    <label for="valor_meta">Valor da Meta</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="number" step="0.01" id="valor_atual" name="valor_atual" value="0">
                    <label for="valor_atual">Valor Atual</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="date" id="data_inicial" name="data_inicial" value="{{ date('Y-m-d') }}" required>
                    <label for="data_inicial">Data Inicial</label>
                </div>

                <div class="input-field col s12 m6">
                    <input type="date" id="data_final" name="data_final" required>
                    <label for="data_final">Data Final</label>
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
@foreach($goals as $goal)
    <div id="modal-editar-{{ $goal->id }}" class="modal">
        <form action="{{ route('financial.goals.update', $goal) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="modal-content">
                <h4>Editar Meta</h4>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="descricao-{{ $goal->id }}" name="descricao" value="{{ $goal->descricao }}" required>
                        <label for="descricao-{{ $goal->id }}">Descrição</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="number" step="0.01" id="valor_meta-{{ $goal->id }}" name="valor_meta" value="{{ $goal->valor_meta }}" required>
                        <label for="valor_meta-{{ $goal->id }}">Valor da Meta</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="number" step="0.01" id="valor_atual-{{ $goal->id }}" name="valor_atual" value="{{ $goal->valor_atual }}">
                        <label for="valor_atual-{{ $goal->id }}">Valor Atual</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="date" id="data_inicial-{{ $goal->id }}" name="data_inicial" value="{{ $goal->data_inicial->format('Y-m-d') }}" required>
                        <label for="data_inicial-{{ $goal->id }}">Data Inicial</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <input type="date" id="data_final-{{ $goal->id }}" name="data_final" value="{{ $goal->data_final->format('Y-m-d') }}" required>
                        <label for="data_final-{{ $goal->id }}">Data Final</label>
                    </div>

                    <div class="input-field col s12">
                        <select name="status" required>
                            <option value="em_andamento" {{ $goal->status == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ $goal->status == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="cancelada" {{ $goal->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        <label>Status</label>
                    </div>

                    <div class="input-field col s12">
                        <textarea id="observacoes-{{ $goal->id }}" name="observacoes" class="materialize-textarea">{{ $goal->observacoes }}</textarea>
                        <label for="observacoes-{{ $goal->id }}">Observações</label>
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
    <div id="modal-deletar-{{ $goal->id }}" class="modal">
        <form action="{{ route('financial.goals.destroy', $goal) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <h4>Confirmar Exclusão</h4>
                <p>Tem certeza que deseja excluir esta meta?</p>
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
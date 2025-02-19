@extends('layouts.app')

@section('title', 'Orçamentos')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Orçamentos</span>
                
                <div class="row">
                    <form class="col s12" method="GET">
                        <div class="row">
                            <div class="input-field col s6 m3">
                                <select name="mes" id="mes">
                                    @php
                                        $meses = [
                                            1 => 'Janeiro',
                                            2 => 'Fevereiro',
                                            3 => 'Março',
                                            4 => 'Abril',
                                            5 => 'Maio',
                                            6 => 'Junho',
                                            7 => 'Julho',
                                            8 => 'Agosto',
                                            9 => 'Setembro',
                                            10 => 'Outubro',
                                            11 => 'Novembro',
                                            12 => 'Dezembro'
                                        ];
                                    @endphp
                                    @foreach($meses as $numero => $nome)
                                        <option value="{{ $numero }}" {{ $mes == $numero ? 'selected' : '' }}>
                                            {{ $nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="mes">Mês</label>
                            </div>
                            
                            <div class="input-field col s6 m3">
                                <select name="ano" id="ano">
                                    @for ($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                        <option value="{{ $i }}" {{ $ano == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <label for="ano">Ano</label>
                            </div>
                            
                            <div class="input-field col s12 m6">
                                <button class="btn waves-effect waves-light" type="submit">
                                    Filtrar
                                    <i class="material-icons right">search</i>
                                </button>
                                
                                <a href="#modal-novo" class="btn waves-effect waves-light modal-trigger">
                                    Novo Orçamento
                                    <i class="material-icons right">add</i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Valor</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgets as $budget)
                            <tr>
                                <td>{{ $budget->categoria->nome }}</td>
                                <td>R$ {{ number_format($budget->valor, 2, ',', '.') }}</td>
                                <td>
                                    <a href="#" class="btn-small waves-effect waves-light">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <a href="#" class="btn-small red waves-effect waves-light">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="center">Nenhum orçamento encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Orçamento -->
<div id="modal-novo" class="modal">
    <form action="{{ route('financial.budgets.store') }}" method="POST">
        @csrf
        <div class="modal-content">
            <h4>Novo Orçamento</h4>
            
            <div class="row">
                <div class="input-field col s12">
                    <select name="categoria_id" required>
                        <option value="" disabled selected>Selecione uma categoria</option>
                        @foreach($categorias ?? [] as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                    <label>Categoria</label>
                </div>
                
                <div class="input-field col s12">
                    <input type="number" step="0.01" name="valor" id="valor" required>
                    <label for="valor">Valor</label>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancelar</a>
            <button type="submit" class="waves-effect waves-green btn-flat">Salvar</button>
        </div>
    </form>
</div>

<style>
    .select-dropdown {
        text-align: center !important;
    }
    .dropdown-content li > span {
        text-align: center !important;
    }
    .select-wrapper input.select-dropdown {
        text-align: center !important;
    }
</style>
@endsection

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
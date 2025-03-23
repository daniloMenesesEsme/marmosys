@extends('layouts.app')

@section('title', 'Conciliação Bancária')

@section('content')
<div class="row">
    <!-- Botão de Importar OFX no topo -->
    <div class="col s12 right-align" style="margin-bottom: 20px;">
        <a href="{{ route('financial.reconciliation.import') }}" 
           class="btn waves-effect waves-light green">
            <i class="material-icons left">cloud_upload</i>
            Importar OFX
        </a>
    </div>

    <form action="{{ route('financial.reconciliation.update') }}" method="POST">
        @csrf
        
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Dados do Pagamento</span>
                    
                    <div class="row">
                        <div class="input-field col s12 m6">
                            <input type="date" id="data_pagamento" name="data_pagamento" 
                                   value="{{ date('Y-m-d') }}" required>
                            <label for="data_pagamento">Data do Pagamento</label>
                        </div>

                        <div class="input-field col s12 m6">
                            <select name="forma_pagamento" required>
                                <option value="" disabled selected>Selecione</option>
                                @foreach($formasPagamento as $forma)
                                    <option value="{{ $forma }}">{{ $forma }}</option>
                                @endforeach
                            </select>
                            <label>Forma de Pagamento</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receitas Pendentes -->
        <div class="col s12">
            <div class="card green lighten-5">
                <div class="card-content">
                    <div class="card-title">
                        Receitas Pendentes
                        <span class="right">
                            Total: R$ {{ number_format($totais['receitas'], 2, ',', '.') }}
                        </span>
                    </div>

                    <table class="striped">
                        <thead>
                            <tr>
                                <th width="50">
                                    <label>
                                        <input type="checkbox" class="filled-in select-all-receitas" />
                                        <span></span>
                                    </label>
                                </th>
                                <th>Vencimento</th>
                                <th>Categoria</th>
                                <th>Descrição</th>
                                <th class="right-align">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contas->get('receita', collect()) as $conta)
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" class="filled-in conta-receita" 
                                               name="contas[]" value="{{ $conta->id }}" />
                                        <span></span>
                                    </label>
                                </td>
                                <td>{{ $conta->data_vencimento->format('d/m/Y') }}</td>
                                <td>{{ $conta->category->nome }}</td>
                                <td>{{ $conta->descricao }}</td>
                                <td class="right-align">R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="center-align">Nenhuma receita pendente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Despesas Pendentes -->
        <div class="col s12">
            <div class="card red lighten-5">
                <div class="card-content">
                    <div class="card-title">
                        Despesas Pendentes
                        <span class="right">
                            Total: R$ {{ number_format($totais['despesas'], 2, ',', '.') }}
                        </span>
                    </div>

                    <table class="striped">
                        <thead>
                            <tr>
                                <th width="50">
                                    <label>
                                        <input type="checkbox" class="filled-in select-all-despesas" />
                                        <span></span>
                                    </label>
                                </th>
                                <th>Vencimento</th>
                                <th>Categoria</th>
                                <th>Descrição</th>
                                <th class="right-align">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contas->get('despesa', collect()) as $conta)
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" class="filled-in conta-despesa" 
                                               name="contas[]" value="{{ $conta->id }}" />
                                        <span></span>
                                    </label>
                                </td>
                                <td>{{ $conta->data_vencimento->format('d/m/Y') }}</td>
                                <td>{{ $conta->category->nome }}</td>
                                <td>{{ $conta->descricao }}</td>
                                <td class="right-align">R$ {{ number_format($conta->valor, 2, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="center-align">Nenhuma despesa pendente.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Botão de Conciliar -->
        <div class="col s12 center-align">
            <button type="submit" class="btn-large waves-effect waves-light blue">
                <i class="material-icons left">check</i>
                Conciliar Contas Selecionadas
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);

    // Selecionar todas as receitas
    document.querySelector('.select-all-receitas').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.conta-receita');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Selecionar todas as despesas
    document.querySelector('.select-all-despesas').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.conta-despesa');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
});
</script>
@endpush
@endsection 
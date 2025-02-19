@extends('layouts.app')

@section('title', 'Centros de Custo')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Centros de Custo
                    <a href="{{ route('financial.cost-centers.create') }}" 
                       class="btn-floating waves-effect waves-light blue right">
                        <i class="material-icons">add</i>
                    </a>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th width="150">Total Despesas</th>
                            <th width="100">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($costCenters as $center)
                        <tr>
                            <td>{{ $center->nome }}</td>
                            <td>{{ $center->descricao }}</td>
                            <td>
                                <span class="chip {{ $center->ativo ? 'green' : 'red' }} white-text">
                                    {{ $center->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="right-align">
                                R$ {{ number_format($center->financial_accounts()
                                    ->whereHas('category', function($q) {
                                        $q->where('tipo', 'despesa');
                                    })
                                    ->sum('valor'), 2, ',', '.') }}
                            </td>
                            <td class="center-align">
                                <a href="{{ route('financial.cost-centers.edit', $center) }}" 
                                   class="btn-small waves-effect waves-light orange">
                                    <i class="material-icons">edit</i>
                                </a>
                                
                                @if(!$center->financial_accounts()->exists())
                                <form action="{{ route('financial.cost-centers.destroy', $center) }}" 
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small waves-effect waves-light red" 
                                            onclick="return confirm('Tem certeza que deseja excluir?')">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="center-align">Nenhum centro de custo cadastrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 
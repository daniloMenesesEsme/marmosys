@extends('layouts.app')

@section('title', 'Histórico de Aprovações')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Histórico de Aprovações</span>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Orçamento</th>
                            <th>Usuário</th>
                            <th>Ação</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('financial.budgets.show', $log->budget) }}">
                                        #{{ $log->budget->numero }}
                                    </a>
                                </td>
                                <td>{{ $log->user->name }}</td>
                                <td>
                                    <span class="chip {{ $log->action === 'approve' ? 'green' : 'red' }} white-text">
                                        {{ $log->action === 'approve' ? 'Aprovado' : 'Reprovado' }}
                                    </span>
                                </td>
                                <td>{{ $log->motivo }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="center">Nenhum registro encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
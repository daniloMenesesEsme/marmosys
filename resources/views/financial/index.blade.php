@extends('layouts.app')

@section('title', 'Financeiro')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Módulo Financeiro</span>
                
                <div class="row">
                    <div class="col s12 m6 l4">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Orçamentos</span>
                                <p>Gerenciar orçamentos</p>
                            </div>
                            <div class="card-action">
                                <a href="{{ route('financial.budgets.index') }}">Acessar</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col s12 m6 l4">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">Contas</span>
                                <p>Gerenciar contas a pagar e receber</p>
                            </div>
                            <div class="card-action">
                                <a href="{{ route('financial.accounts.index') }}">Acessar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
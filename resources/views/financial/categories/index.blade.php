@extends('layouts.app')

@section('title', 'Categorias Financeiras')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Categorias Financeiras</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="{{ route('financial.categories.create') }}" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>Nova Categoria
                        </a>
                    </div>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->nome }}</td>
                            <td>
                                <span class="chip {{ $category->tipo_class }}">
                                    {{ $category->tipo_text }}
                                </span>
                            </td>
                            <td>{{ Str::limit($category->descricao, 50) ?: '-' }}</td>
                            <td>
                                <span class="chip {{ $category->ativo ? 'green white-text' : 'red white-text' }}">
                                    {{ $category->ativo ? 'Ativa' : 'Inativa' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('financial.categories.edit', $category) }}" class="btn-small waves-effect waves-light orange">
                                    <i class="material-icons">edit</i>
                                </a>
                                <form action="{{ route('financial.categories.destroy', $category) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small waves-effect waves-light red" onclick="return confirm('Tem certeza que deseja {{ $category->ativo ? 'inativar' : 'ativar' }} esta categoria?')">
                                        <i class="material-icons">{{ $category->ativo ? 'delete' : 'restore' }}</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="center-align">Nenhuma categoria cadastrada</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="center-align">
                    {{ $categories->links('vendor.pagination.materialize') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@extends('layouts.app')

@section('title', 'Categorias de Materiais')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Categorias de Materiais</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="{{ route('stock.categories.create') }}" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>Nova Categoria
                        </a>
                    </div>
                </div>

                <table class="striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Materiais</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->nome }}</td>
                            <td>{{ ucfirst($category->tipo) }}</td>
                            <td>{{ $category->materials_count }}</td>
                            <td>
                                <a href="{{ route('stock.categories.edit', $category) }}" class="btn-small waves-effect waves-light orange">
                                    <i class="material-icons">edit</i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 
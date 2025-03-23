@extends('layouts.app')

@section('title', 'Materiais')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s6">
                        <span class="card-title">Materiais</span>
                    </div>
                    <div class="col s6 right-align">
                        <a href="{{ route('stock.materials.create') }}" class="btn waves-effect waves-light blue">
                            <i class="material-icons left">add</i>Novo Material
                        </a>
                    </div>
                </div>

                <table class="striped responsive-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Estoque Atual</th>
                            <th>Estoque Mínimo</th>
                            <th>Preço Venda</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $material)
                        <tr class="{{ $material->estoque_atual <= $material->estoque_minimo ? 'red-text' : '' }}">
                            <td>{{ $material->codigo }}</td>
                            <td>{{ $material->nome }}</td>
                            <td>{{ $material->category->nome }}</td>
                            <td>{{ number_format($material->estoque_atual, 2, ',', '.') }} {{ $material->unidade_medida }}</td>
                            <td>{{ number_format($material->estoque_minimo, 2, ',', '.') }} {{ $material->unidade_medida }}</td>
                            <td>R$ {{ number_format($material->preco_venda, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('stock.materials.edit', $material) }}" class="btn-small waves-effect waves-light orange">
                                    <i class="material-icons">edit</i>
                                </a>
                                <form action="{{ route('stock.materials.destroy', $material) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small waves-effect waves-light red" onclick="return confirm('Tem certeza que deseja desativar este material?')">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $materials->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
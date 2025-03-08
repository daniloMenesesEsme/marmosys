@extends('layouts.app')

@section('title', 'Empresas')

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Empresas</span>
                    
                    @if(session('success'))
                        <div class="chip green white-text">
                            {{ session('success') }}
                            <i class="close material-icons">close</i>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col s12">
                            <a href="{{ route('companies.create') }}" class="btn-floating btn-large waves-effect waves-light blue right">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                    </div>

                    <table class="striped responsive-table">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Nome Fantasia</th>
                                <th>CNPJ</th>
                                <th>Telefone</th>
                                <th>Cidade/UF</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                                <tr>
                                    <td>
                                        @if($company->logo)
                                            <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="circle" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <i class="material-icons grey-text">business</i>
                                        @endif
                                    </td>
                                    <td>{{ $company->nome_fantasia }}</td>
                                    <td>{{ $company->formatted_cnpj }}</td>
                                    <td>{{ $company->formatted_telefone }}</td>
                                    <td>{{ $company->cidade }}/{{ $company->estado }}</td>
                                    <td>
                                        <div class="row" style="margin-bottom: 0;">
                                            <a href="{{ route('companies.show', $company) }}" class="btn-floating waves-effect waves-light blue">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="{{ route('companies.edit', $company) }}" class="btn-floating waves-effect waves-light orange">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <form action="{{ route('companies.destroy', $company) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta empresa?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-floating waves-effect waves-light red">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="center-align">Nenhuma empresa cadastrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col s12">
                            {{ $companies->links('vendor.pagination.materialize') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var chips = document.querySelectorAll('.chip');
        chips.forEach(function(chip) {
            chip.querySelector('.close').addEventListener('click', function() {
                chip.remove();
            });
        });
    });
</script>
@endpush 
@extends('layouts.app')

@section('title', 'Relatório de Fornecedores')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">
                        <div class="row mb-0">
                            <div class="col s12 m6">
                                <i class="material-icons left">assessment</i>
                                Relatório de Fornecedores
                            </div>
                            <div class="col s12 m6 right-align">
                                <button type="button" class="btn waves-effect waves-light red" onclick="window.print()">
                                    <i class="material-icons left">picture_as_pdf</i>
                                    PDF
                                </button>
                                <a href="{{ route('suppliers.report', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn waves-effect waves-light green">
                                    <i class="material-icons left">grid_on</i>
                                    Excel
                                </a>
                            </div>
                        </div>
                    </span>
                    
                    <!-- Cards de Totais -->
                    <div class="row">
                        <div class="col s12 m4">
                            <div class="card blue darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Total Fornecedores</span>
                                    <h4>{{ $suppliers->count() }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m4">
                            <div class="card green darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Fornecedores Ativos</span>
                                    <h4>{{ $suppliers->where('ativo', true)->count() }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m4">
                            <div class="card red darken-1">
                                <div class="card-content white-text">
                                    <span class="card-title">Fornecedores Inativos</span>
                                    <h4>{{ $suppliers->where('ativo', false)->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row">
                        <form method="GET" action="{{ route('suppliers.report') }}" class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <span class="card-title">Filtros</span>
                                    <div class="row mb-0">
                                        <div class="input-field col s12 m6 l3">
                                            <input type="text" class="datepicker" name="date_start" id="date_start" value="{{ request('date_start') }}">
                                            <label for="date_start">Data Inicial</label>
                                        </div>

                                        <div class="input-field col s12 m6 l3">
                                            <input type="text" class="datepicker" name="date_end" id="date_end" value="{{ request('date_end') }}">
                                            <label for="date_end">Data Final</label>
                                        </div>

                                        <div class="input-field col s12 m6 l3">
                                            <select name="status" id="status">
                                                <option value="">Todos</option>
                                                <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>Ativos</option>
                                                <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>Inativos</option>
                                            </select>
                                            <label>Status</label>
                                        </div>

                                        <div class="input-field col s12 m6 l3">
                                            <select name="estado" id="estado">
                                                <option value="">Todos os Estados</option>
                                                <option value="AC" {{ request('estado') === 'AC' ? 'selected' : '' }}>Acre</option>
                                                <option value="AL" {{ request('estado') === 'AL' ? 'selected' : '' }}>Alagoas</option>
                                                <option value="AP" {{ request('estado') === 'AP' ? 'selected' : '' }}>Amapá</option>
                                                <option value="AM" {{ request('estado') === 'AM' ? 'selected' : '' }}>Amazonas</option>
                                                <option value="BA" {{ request('estado') === 'BA' ? 'selected' : '' }}>Bahia</option>
                                                <option value="CE" {{ request('estado') === 'CE' ? 'selected' : '' }}>Ceará</option>
                                                <option value="DF" {{ request('estado') === 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                                <option value="ES" {{ request('estado') === 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                                <option value="GO" {{ request('estado') === 'GO' ? 'selected' : '' }}>Goiás</option>
                                                <option value="MA" {{ request('estado') === 'MA' ? 'selected' : '' }}>Maranhão</option>
                                                <option value="MT" {{ request('estado') === 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                                <option value="MS" {{ request('estado') === 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                                <option value="MG" {{ request('estado') === 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                                <option value="PA" {{ request('estado') === 'PA' ? 'selected' : '' }}>Pará</option>
                                                <option value="PB" {{ request('estado') === 'PB' ? 'selected' : '' }}>Paraíba</option>
                                                <option value="PR" {{ request('estado') === 'PR' ? 'selected' : '' }}>Paraná</option>
                                                <option value="PE" {{ request('estado') === 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                                <option value="PI" {{ request('estado') === 'PI' ? 'selected' : '' }}>Piauí</option>
                                                <option value="RJ" {{ request('estado') === 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                                <option value="RN" {{ request('estado') === 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                                <option value="RS" {{ request('estado') === 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                                <option value="RO" {{ request('estado') === 'RO' ? 'selected' : '' }}>Rondônia</option>
                                                <option value="RR" {{ request('estado') === 'RR' ? 'selected' : '' }}>Roraima</option>
                                                <option value="SC" {{ request('estado') === 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                                <option value="SP" {{ request('estado') === 'SP' ? 'selected' : '' }}>São Paulo</option>
                                                <option value="SE" {{ request('estado') === 'SE' ? 'selected' : '' }}>Sergipe</option>
                                                <option value="TO" {{ request('estado') === 'TO' ? 'selected' : '' }}>Tocantins</option>
                                            </select>
                                            <label>Estado</label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col s12 center-align">
                                            <button type="submit" class="btn waves-effect waves-light">
                                                <i class="material-icons left">search</i>
                                                Filtrar
                                            </button>
                                            <a href="{{ route('suppliers.report') }}" class="btn waves-effect waves-light red">
                                                <i class="material-icons left">clear</i>
                                                Limpar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Resultados -->
                    <div class="row">
                        <div class="col s12">
                            @if($suppliers->isEmpty())
                                <div class="card-panel blue-grey lighten-4">
                                    <span class="blue-text text-darken-2">
                                        <i class="material-icons left">info</i>
                                        Nenhum fornecedor encontrado com os filtros selecionados.
                                    </span>
                                </div>
                            @else
                                <table class="striped responsive-table">
                                    <thead>
                                        <tr>
                                            <th>Razão Social</th>
                                            <th>Nome Fantasia</th>
                                            <th>CNPJ</th>
                                            <th>Telefone</th>
                                            <th>Cidade/UF</th>
                                            <th>Status</th>
                                            <th>Data Cadastro</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($suppliers as $supplier)
                                            <tr>
                                                <td>{{ $supplier->razao_social }}</td>
                                                <td>{{ $supplier->nome_fantasia }}</td>
                                                <td>{{ $supplier->formatted_cnpj }}</td>
                                                <td>{{ $supplier->formatted_telefone }}</td>
                                                <td>{{ $supplier->cidade }}/{{ $supplier->estado }}</td>
                                                <td>
                                                    <span class="chip {{ $supplier->ativo ? 'green white-text' : 'red white-text' }}">
                                                        {{ $supplier->ativo ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                </td>
                                                <td>{{ $supplier->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Resumo -->
                                <div class="card-panel grey lighten-4" style="margin-top: 20px;">
                                    <h5 class="m-0"><i class="material-icons left">calculate</i> Resumo</h5>
                                    <div class="row mb-0">
                                        <div class="col s12 m4">
                                            <p>
                                                <strong>Total de Fornecedores:</strong> {{ $suppliers->count() }}
                                            </p>
                                        </div>
                                        <div class="col s12 m4">
                                            <p class="green-text">
                                                <strong>Ativos:</strong> {{ $suppliers->where('ativo', true)->count() }}
                                            </p>
                                        </div>
                                        <div class="col s12 m4">
                                            <p class="red-text">
                                                <strong>Inativos:</strong> {{ $suppliers->where('ativo', false)->count() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
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
        // Inicializa os selects do Materialize
        var elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);

        // Inicializa os datepickers
        var datepickers = document.querySelectorAll('.datepicker');
        M.Datepicker.init(datepickers, {
            format: 'yyyy-mm-dd',
            i18n: {
                months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                today: 'Hoje',
                clear: 'Limpar',
                cancel: 'Cancelar',
                done: 'OK'
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    @media print {
        .sidenav-main,
        .navbar,
        form,
        .btn,
        .card-title {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
        }
        
        .container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }
    
    .btn {
        margin-right: 5px;
    }
    
    .card-title {
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .chip {
        font-weight: normal;
    }
</style>
@endpush 
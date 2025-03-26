@extends('layouts.app')

@section('title', isset($supplier) ? 'Editar Fornecedor' : 'Novo Fornecedor')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <h4 class="header">
                <i class="material-icons left">business</i>
                {{ isset($supplier) ? 'Editar Fornecedor' : 'Novo Fornecedor' }}
            </h4>
        </div>
    </div>

<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                    <form method="POST" action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}">
                    @csrf
                    @if(isset($supplier))
                        @method('PUT')
                    @endif

                    <div class="row">
                            <!-- CNPJ -->
                        <div class="input-field col s12 m6">
                                <i class="material-icons prefix">business</i>
                                <input type="text" name="cnpj" id="cnpj" value="{{ old('cnpj', isset($supplier) ? $supplier->cnpj : '') }}" class="cnpj" required>
                                <label for="cnpj">CNPJ</label>
                                @error('cnpj')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- Inscrição Estadual -->
                        <div class="input-field col s12 m6">
                                <i class="material-icons prefix">description</i>
                                <input type="text" name="inscricao_estadual" id="inscricao_estadual" value="{{ old('inscricao_estadual', isset($supplier) ? $supplier->inscricao_estadual : '') }}">
                                <label for="inscricao_estadual">Inscrição Estadual</label>
                                @error('inscricao_estadual')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- Inscrição Municipal -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">description</i>
                                <input type="text" name="inscricao_municipal" id="inscricao_municipal" value="{{ old('inscricao_municipal', isset($supplier) ? $supplier->inscricao_municipal : '') }}">
                                <label for="inscricao_municipal">Inscrição Municipal</label>
                                @error('inscricao_municipal')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                    </div>

                            <!-- Razão Social -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">business</i>
                                <input type="text" name="razao_social" id="razao_social" value="{{ old('razao_social', isset($supplier) ? $supplier->razao_social : '') }}" required>
                                <label for="razao_social">Razão Social</label>
                                @error('razao_social')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- Nome Fantasia -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">store</i>
                                <input type="text" name="nome_fantasia" id="nome_fantasia" value="{{ old('nome_fantasia', isset($supplier) ? $supplier->nome_fantasia : '') }}" required>
                                <label for="nome_fantasia">Nome Fantasia</label>
                                @error('nome_fantasia')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- Telefone -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">phone</i>
                                <input type="text" name="telefone" id="telefone" value="{{ old('telefone', isset($supplier) ? $supplier->telefone : '') }}" class="phone" required>
                                <label for="telefone">Telefone</label>
                                @error('telefone')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                    </div>

                            <!-- Email -->
                        <div class="input-field col s12 m6">
                                <i class="material-icons prefix">email</i>
                                <input type="email" name="email" id="email" value="{{ old('email', isset($supplier) ? $supplier->email : '') }}" required>
                                <label for="email">Email</label>
                                @error('email')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- CEP -->
                        <div class="input-field col s12 m6">
                                <i class="material-icons prefix">location_on</i>
                                <input type="text" name="cep" id="cep" value="{{ old('cep', isset($supplier) ? $supplier->cep : '') }}" class="cep" required>
                                <label for="cep">CEP</label>
                                @error('cep')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                    </div>

                            <!-- Endereço -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">home</i>
                                <input type="text" name="endereco" id="endereco" value="{{ old('endereco', isset($supplier) ? $supplier->endereco : '') }}" required>
                                <label for="endereco">Endereço</label>
                                @error('endereco')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- Número -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">looks_one</i>
                                <input type="text" name="numero" id="numero" value="{{ old('numero', isset($supplier) ? $supplier->numero : '') }}" required>
                                <label for="numero">Número</label>
                                @error('numero')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- Complemento -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">add_location</i>
                                <input type="text" name="complemento" id="complemento" value="{{ old('complemento', isset($supplier) ? $supplier->complemento : '') }}">
                                <label for="complemento">Complemento</label>
                                @error('complemento')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                    </div>

                            <!-- Bairro -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">location_city</i>
                                <input type="text" name="bairro" id="bairro" value="{{ old('bairro', isset($supplier) ? $supplier->bairro : '') }}" required>
                                <label for="bairro">Bairro</label>
                                @error('bairro')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- Cidade -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">location_city</i>
                                <input type="text" name="cidade" id="cidade" value="{{ old('cidade', isset($supplier) ? $supplier->cidade : '') }}" required>
                                <label for="cidade">Cidade</label>
                                @error('cidade')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- Estado -->
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">location_city</i>
                            <select name="estado" id="estado" required>
                                    <option value="">Selecione um estado</option>
                                    <option value="AC" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'AC' ? 'selected' : '' }}>Acre</option>
                                    <option value="AL" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'AL' ? 'selected' : '' }}>Alagoas</option>
                                    <option value="AP" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'AP' ? 'selected' : '' }}>Amapá</option>
                                    <option value="AM" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'AM' ? 'selected' : '' }}>Amazonas</option>
                                    <option value="BA" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'BA' ? 'selected' : '' }}>Bahia</option>
                                    <option value="CE" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'CE' ? 'selected' : '' }}>Ceará</option>
                                    <option value="DF" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                    <option value="ES" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                    <option value="GO" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'GO' ? 'selected' : '' }}>Goiás</option>
                                    <option value="MA" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'MA' ? 'selected' : '' }}>Maranhão</option>
                                    <option value="MT" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                    <option value="MS" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                    <option value="MG" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                    <option value="PA" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'PA' ? 'selected' : '' }}>Pará</option>
                                    <option value="PB" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'PB' ? 'selected' : '' }}>Paraíba</option>
                                    <option value="PR" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'PR' ? 'selected' : '' }}>Paraná</option>
                                    <option value="PE" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                    <option value="PI" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'PI' ? 'selected' : '' }}>Piauí</option>
                                    <option value="RJ" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                    <option value="RN" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                    <option value="RS" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                    <option value="RO" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'RO' ? 'selected' : '' }}>Rondônia</option>
                                    <option value="RR" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'RR' ? 'selected' : '' }}>Roraima</option>
                                    <option value="SC" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                    <option value="SP" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'SP' ? 'selected' : '' }}>São Paulo</option>
                                    <option value="SE" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'SE' ? 'selected' : '' }}>Sergipe</option>
                                    <option value="TO" {{ old('estado', isset($supplier) ? $supplier->estado : '') === 'TO' ? 'selected' : '' }}>Tocantins</option>
                            </select>
                                <label>Estado</label>
                                @error('estado')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                        </div>

                            <!-- Observações -->
                            <div class="input-field col s12">
                                <i class="material-icons prefix">note</i>
                                <textarea name="observacoes" id="observacoes" class="materialize-textarea">{{ old('observacoes', isset($supplier) ? $supplier->observacoes : '') }}</textarea>
                                <label for="observacoes">Observações</label>
                                @error('observacoes')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                    </div>

                            <!-- Status -->
                        <div class="input-field col s12">
                                <label>
                                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', isset($supplier) ? $supplier->ativo : true) ? 'checked' : '' }}>
                                    <span>Ativo</span>
                                </label>
                                @error('ativo')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                    </div>

                    <div class="row">
                            <div class="col s12 center-align">
                                <button type="submit" class="btn waves-effect waves-light green">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                                <a href="{{ route('suppliers.index') }}" class="btn waves-effect waves-light red">
                                    <i class="material-icons left">cancel</i>
                                    Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
        // Inicializa os selects do Materialize
        var elems = document.querySelectorAll('select');
        var instances = M.FormSelect.init(elems);

    // Máscaras
        $('.cnpj').mask('00.000.000/0000-00');
        $('.phone').mask('(00) 00000-0000');
        $('.cep').mask('00000-000');

        // Busca de CEP
        $('#cep').on('blur', function() {
            var cep = $(this).val().replace(/\D/g, '');
        if (cep.length === 8) {
                $.get(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                    if (!data.erro) {
                        $('#endereco').val(data.logradouro);
                        $('#bairro').val(data.bairro);
                        $('#cidade').val(data.localidade);
                        $('#estado').val(data.uf);
                        M.updateTextFields();
                        $('select').formSelect();
                    }
                });
            }
        });

        // Busca de CNPJ
        $('#cnpj').on('blur', function() {
            var cnpj = $(this).val().replace(/\D/g, '');
            if (cnpj.length === 14) {
                $.get(`{{ route('suppliers.find', '') }}/${cnpj}`, function(data) {
                    if (data) {
                        M.toast({html: 'CNPJ já cadastrado!', classes: 'red'});
                    }
                });
        }
    });
});
</script>
@endsection 
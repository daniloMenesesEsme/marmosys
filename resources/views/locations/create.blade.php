@extends('layouts.app')

@section('title', 'Nova Localidade')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Nova Localidade</span>
                    
                    <form action="{{ route('locations.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="input-field col s12 m4">
                                <input type="text" id="cep" name="cep" class="validate" required>
                                <label for="cep">CEP *</label>
                                @error('cep')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <select name="state" id="state" required>
                                    <option value="" disabled selected>Selecione um estado</option>
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                </select>
                                <label for="state">Estado *</label>
                                @error('state')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="text" id="city" name="city" class="validate" required>
                                <label for="city">Cidade *</label>
                                @error('city')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m4">
                                <input type="text" id="name" name="name" class="validate" required>
                                <label for="name">Bairro *</label>
                                @error('name')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="text" id="latitude" name="latitude">
                                <label for="latitude">Latitude</label>
                                @error('latitude')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m4">
                                <input type="text" id="longitude" name="longitude">
                                <label for="longitude">Longitude</label>
                                @error('longitude')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select name="status" id="status" required>
                                    <option value="active" selected>Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                                <label for="status">Status *</label>
                                @error('status')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="input-field col s12 m6">
                                <textarea id="description" name="description" class="materialize-textarea"></textarea>
                                <label for="description">Descrição</label>
                                @error('description')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="material-icons left">save</i>
                                    Salvar
                                </button>
                                <a href="{{ route('locations.index') }}" class="btn-flat waves-effect">
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
    // Inicializa os componentes do Materialize
    M.FormSelect.init(document.querySelectorAll('select'));
    M.textareaAutoResize(document.querySelectorAll('.materialize-textarea'));

    // Elementos do formulário
    const cepInput = document.querySelector('#cep');
    const stateSelect = document.querySelector('#state');
    const cityInput = document.querySelector('#city');
    const neighborhoodInput = document.querySelector('#name');
    const latitudeInput = document.querySelector('#latitude');
    const longitudeInput = document.querySelector('#longitude');

    // Máscara para o CEP
    cepInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 8) {
            value = value.replace(/(\d{5})(\d{1,3})/, '$1-$2');
            e.target.value = value;
        }
    });

    // Busca endereço quando o CEP estiver completo
    cepInput.addEventListener('blur', async function() {
        const cep = this.value.replace(/\D/g, '');
        
        if (cep.length === 8) {
            try {
                // Mostra mensagem de carregamento
                M.toast({html: 'Buscando endereço...', classes: 'blue'});
                
                // Faz a requisição para a API do ViaCEP
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                
                if (data.erro) {
                    throw new Error('CEP não encontrado');
                }
                
                // Preenche os campos com os dados retornados
                stateSelect.value = data.uf;
                M.FormSelect.init(stateSelect);
                
                cityInput.value = data.localidade;
                neighborhoodInput.value = data.bairro;
                
                // Atualiza os labels
                M.updateTextFields();
                
                // Busca as coordenadas
                const address = `${data.bairro}, ${data.localidade}, ${data.uf}, Brasil`;
                const geocodeResponse = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
                const locations = await geocodeResponse.json();
                
                if (locations && locations.length > 0) {
                    latitudeInput.value = locations[0].lat;
                    longitudeInput.value = locations[0].lon;
                    M.updateTextFields();
                }
                
                M.toast({html: 'Endereço encontrado!', classes: 'green'});
            } catch (error) {
                console.error('Erro:', error);
                M.toast({html: error.message || 'Erro ao buscar endereço', classes: 'red'});
                
                // Limpa os campos em caso de erro
                stateSelect.value = '';
                M.FormSelect.init(stateSelect);
                cityInput.value = '';
                neighborhoodInput.value = '';
                latitudeInput.value = '';
                longitudeInput.value = '';
                M.updateTextFields();
            }
        }
    });

    // Previne envio do form ao pressionar Enter no CEP
    cepInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.blur(); // Dispara o evento blur para buscar o CEP
            return false;
        }
    });
});
</script>
@endsection 
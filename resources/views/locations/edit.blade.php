@extends('layouts.app')

@section('title', 'Editar Localidade')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Editar Localidade: {{ $location->name }}</h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <form action="{{ route('locations.update', $location) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select name="state" id="state" required>
                                    <option value="" disabled>Selecione um estado</option>
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
                            
                            <div class="input-field col s12 m6">
                                <input type="text" id="city" name="city" class="autocomplete" required>
                                <label for="city">Cidade *</label>
                                @error('city')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="name" type="text" name="name" class="autocomplete" value="{{ old('name', $location->name) }}" required>
                                <label for="name">Bairro *</label>
                                @error('name')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="input-field col s12 m6">
                                <select name="status" id="status">
                                    <option value="active" {{ old('status', $location->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                                    <option value="inactive" {{ old('status', $location->status) == 'inactive' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                <label for="status">Status *</label>
                                @error('status')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="latitude" type="text" name="latitude" value="{{ old('latitude', $location->latitude) }}">
                                <label for="latitude">Latitude</label>
                                @error('latitude')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="input-field col s12 m6">
                                <input id="longitude" type="text" name="longitude" value="{{ old('longitude', $location->longitude) }}">
                                <label for="longitude">Longitude</label>
                                @error('longitude')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="description" name="description" class="materialize-textarea">{{ old('description', $location->description) }}</textarea>
                                <label for="description">Descrição</label>
                                @error('description')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="material-icons left">save</i>Atualizar
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
    // Inicializa os selects
    let selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
    
    // Inicializa os textareas
    let textareas = document.querySelectorAll('.materialize-textarea');
    M.textareaAutoResize(textareas);

    // Seleciona o estado atual
    let stateSelect = document.querySelector('#state');
    stateSelect.value = '{{ $location->state }}';
    M.FormSelect.init(stateSelect);

    // Inicializa o autocomplete da cidade
    let cityInput = document.querySelector('#city');
    cityInput.value = '{{ $location->city }}';
    M.Autocomplete.init(cityInput, {
        data: {},
        minLength: 2,
        onAutocomplete: function(val) {
            loadNeighborhoods(stateSelect.value, val);
        }
    });

    // Inicializa o autocomplete do bairro
    let neighborhoodInput = document.querySelector('#name');
    M.Autocomplete.init(neighborhoodInput, {
        data: {},
        minLength: 2,
        onAutocomplete: function(val) {
            loadGeocode(stateSelect.value, cityInput.value, val);
        }
    });

    // Carrega as cidades quando o estado é alterado
    stateSelect.addEventListener('change', function() {
        cityInput.value = '';
        neighborhoodInput.value = '';
        document.querySelector('#latitude').value = '';
        document.querySelector('#longitude').value = '';
        
        loadCities(this.value);
    });

    // Carrega os bairros quando a cidade é alterada
    cityInput.addEventListener('input', debounce(function() {
        if (this.value.length >= 2) {
            loadCities(stateSelect.value, this.value);
        }
    }, 300));

    // Carrega as coordenadas quando o bairro é alterado
    neighborhoodInput.addEventListener('input', debounce(function() {
        if (this.value.length >= 2) {
            loadNeighborhoods(stateSelect.value, cityInput.value, this.value);
        }
    }, 300));

    // Função para carregar cidades
    function loadCities(state, search = '') {
        fetch(`/locations/cities/${state}?search=${search}`)
            .then(response => response.json())
            .then(cities => {
                let cityData = {};
                cities.forEach(city => {
                    cityData[city.name] = null;
                });
                let cityInstance = M.Autocomplete.getInstance(cityInput);
                cityInstance.updateData(cityData);
                cityInstance.open();
            })
            .catch(error => console.error('Erro ao carregar cidades:', error));
    }

    // Função para carregar bairros
    function loadNeighborhoods(state, city, search = '') {
        if (!city) return;
        
        fetch(`/locations/neighborhoods/${state}/${encodeURIComponent(city)}?search=${search}`)
            .then(response => response.json())
            .then(neighborhoods => {
                let neighborhoodData = {};
                neighborhoods.forEach(neighborhood => {
                    neighborhoodData[neighborhood.name] = null;
                });
                let neighborhoodInstance = M.Autocomplete.getInstance(neighborhoodInput);
                neighborhoodInstance.updateData(neighborhoodData);
                neighborhoodInstance.open();
            })
            .catch(error => console.error('Erro ao carregar bairros:', error));
    }

    // Função para carregar coordenadas
    function loadGeocode(state, city, neighborhood) {
        if (!city || !neighborhood) return;
        
        fetch(`/locations/geocode/${state}/${encodeURIComponent(city)}/${encodeURIComponent(neighborhood)}`)
            .then(response => response.json())
            .then(data => {
                if (data.latitude && data.longitude) {
                    document.querySelector('#latitude').value = data.latitude;
                    document.querySelector('#longitude').value = data.longitude;
                    M.updateTextFields();
                }
            })
            .catch(error => console.error('Erro ao carregar coordenadas:', error));
    }

    // Função de debounce para evitar muitas requisições
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Carrega os dados iniciais
    if (stateSelect.value) {
        loadCities(stateSelect.value);
        if (cityInput.value) {
            loadNeighborhoods(stateSelect.value, cityInput.value);
        }
    }
});
</script>
@endsection 
@extends('layouts.app')

@section('title', 'Editar Área de Atendimento')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Editar Área de Atendimento</h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <form action="{{ route('service-areas.update', $serviceArea) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select name="location_id" id="location_id" required>
                                    <option value="" disabled>Selecione uma localidade</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id', $serviceArea->location_id) == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                            @if($location->state)
                                                ({{ $location->state }}{{ $location->city ? ' - ' . $location->city : '' }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <label for="location_id">Localidade *</label>
                                @error('location_id')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="input-field col s12 m6">
                                <select name="establishment_type_id" id="establishment_type_id">
                                    <option value="">Todos os tipos de estabelecimento</option>
                                    @foreach($establishmentTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('establishment_type_id', $serviceArea->establishment_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="establishment_type_id">Tipo de Estabelecimento</label>
                                <span class="helper-text">Deixe em branco para incluir todos os tipos nesta localidade</span>
                                @error('establishment_type_id')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select name="seller_id" id="seller_id">
                                    <option value="">Nenhum vendedor atribuído</option>
                                    @foreach($sellers as $seller)
                                        <option value="{{ $seller->id }}" {{ old('seller_id', $serviceArea->seller_id) == $seller->id ? 'selected' : '' }}>
                                            {{ $seller->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="seller_id">Vendedor</label>
                                @error('seller_id')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="input-field col s12 m3">
                                <input id="goal_amount" type="number" step="0.01" min="0" name="goal_amount" value="{{ old('goal_amount', $serviceArea->goal_amount) }}">
                                <label for="goal_amount">Meta de Vendas (R$)</label>
                                <span class="helper-text">Meta de vendas anual para esta área</span>
                                @error('goal_amount')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="input-field col s12 m3">
                                <select name="status" id="status">
                                    <option value="active" {{ old('status', $serviceArea->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                                    <option value="inactive" {{ old('status', $serviceArea->status) == 'inactive' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                <label for="status">Status *</label>
                                @error('status')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="notes" name="notes" class="materialize-textarea">{{ old('notes', $serviceArea->notes) }}</textarea>
                                <label for="notes">Observações</label>
                                @error('notes')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="material-icons left">save</i>Atualizar
                                </button>
                                <a href="{{ route('service-areas.index') }}" class="btn-flat waves-effect">
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
        let selects = document.querySelectorAll('select');
        M.FormSelect.init(selects);
        
        // Inicializa os textareas
        let textareas = document.querySelectorAll('.materialize-textarea');
        M.textareaAutoResize(textareas);
        
        // Verifica se já existe uma área com a mesma combinação
        let locationId = {{ $serviceArea->location_id }};
        let typeId = {{ $serviceArea->establishment_type_id ?? 'null' }};
        let areaId = {{ $serviceArea->id }};
        
        let locationSelect = document.getElementById('location_id');
        let typeSelect = document.getElementById('establishment_type_id');
        
        function checkExistingArea() {
            let newLocationId = locationSelect.value;
            let newTypeId = typeSelect.value || '';
            
            // Só verificar se mudou a localidade ou o tipo
            if (newLocationId && (newLocationId != locationId || newTypeId != typeId)) {
                fetch(`/api/check-service-area?location_id=${newLocationId}&establishment_type_id=${newTypeId}&exclude_id=${areaId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            M.toast({html: '<i class="material-icons left">warning</i> Já existe uma área de atendimento com esta combinação!', classes: 'red', displayLength: 6000});
                        }
                    });
            }
        }
        
        locationSelect.addEventListener('change', checkExistingArea);
        typeSelect.addEventListener('change', checkExistingArea);
    });
</script>
@endsection 
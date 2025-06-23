<div class="row">
    <div class="col s12 m6">
        <div class="input-field">
            <select name="location_id" id="location_id">
                <option value="">Selecione uma localidade (opcional)</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ (isset($cliente) && $cliente->location_id == $location->id) ? 'selected' : '' }}>
                        {{ $location->name }}
                        @if($location->state)
                            ({{ $location->state }}{{ $location->city ? ' - ' . $location->city : '' }})
                        @endif
                    </option>
                @endforeach
            </select>
            <label for="location_id">Localidade</label>
        </div>
    </div>
    
    <div class="col s12 m6">
        <div class="input-field">
            <select name="establishment_type_id" id="establishment_type_id">
                <option value="">Selecione um tipo de estabelecimento (opcional)</option>
                @foreach($establishmentTypes as $type)
                    <option value="{{ $type->id }}" {{ (isset($cliente) && $cliente->establishment_type_id == $type->id) ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            <label for="establishment_type_id">Tipo de Estabelecimento</label>
        </div>
    </div>
</div> 
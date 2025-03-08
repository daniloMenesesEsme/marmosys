<div class="room-template" style="display: none;">
    <div class="card room-card" data-room-index="{ROOM_INDEX}">
        <div class="card-content">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" name="rooms[{ROOM_INDEX}][nome]" class="room-name" required>
                    <label>Nome do Ambiente*</label>
                </div>
            </div>

            <div class="items-container">
                <!-- Template para novo item -->
                <div class="item-template" style="display: none;">
                    <div class="row item-row">
                        <div class="input-field col s12 m3">
                            <select name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][material_id]" class="material-select" required>
                                <option value="" disabled selected>Selecione o material</option>
                                @foreach($materiais as $material)
                                    <option value="{{ $material->id }}" 
                                            data-preco="{{ $material->preco_padrao }}"
                                            data-unidade="{{ $material->unidade_medida }}">
                                        {{ $material->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <label>Material*</label>
                        </div>

                        <div class="input-field col s6 m2">
                            <input type="number" step="0.001" min="0.001" 
                                   name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][quantidade]" 
                                   class="quantidade" required>
                            <label>Quantidade*</label>
                        </div>

                        <div class="input-field col s6 m1">
                            <input type="text" name="rooms[{ROOM_INDEX}][items][{ITEM_INDEX}][unidade]" 
                                   class="unidade" readonly>
                            <label>Unid.</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
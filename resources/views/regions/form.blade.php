<div class="row">
    <div class="input-field col s12 m6">
        <input id="name" name="name" type="text" class="validate" value="{{ $region->name ?? old('name') }}" required>
        <label for="name">Nome da Região</label>
    </div>
    
    <div class="input-field col s12 m6">
        <select id="state" name="state" class="validate" required>
            <option value="" disabled {{ !isset($region->state) ? 'selected' : '' }}>Selecione um estado</option>
            <option value="AC" {{ (isset($region->state) && $region->state == 'AC') ? 'selected' : '' }}>Acre</option>
            <option value="AL" {{ (isset($region->state) && $region->state == 'AL') ? 'selected' : '' }}>Alagoas</option>
            <option value="AP" {{ (isset($region->state) && $region->state == 'AP') ? 'selected' : '' }}>Amapá</option>
            <option value="AM" {{ (isset($region->state) && $region->state == 'AM') ? 'selected' : '' }}>Amazonas</option>
            <option value="BA" {{ (isset($region->state) && $region->state == 'BA') ? 'selected' : '' }}>Bahia</option>
            <option value="CE" {{ (isset($region->state) && $region->state == 'CE') ? 'selected' : '' }}>Ceará</option>
            <option value="DF" {{ (isset($region->state) && $region->state == 'DF') ? 'selected' : '' }}>Distrito Federal</option>
            <option value="ES" {{ (isset($region->state) && $region->state == 'ES') ? 'selected' : '' }}>Espírito Santo</option>
            <option value="GO" {{ (isset($region->state) && $region->state == 'GO') ? 'selected' : '' }}>Goiás</option>
            <option value="MA" {{ (isset($region->state) && $region->state == 'MA') ? 'selected' : '' }}>Maranhão</option>
            <option value="MT" {{ (isset($region->state) && $region->state == 'MT') ? 'selected' : '' }}>Mato Grosso</option>
            <option value="MS" {{ (isset($region->state) && $region->state == 'MS') ? 'selected' : '' }}>Mato Grosso do Sul</option>
            <option value="MG" {{ (isset($region->state) && $region->state == 'MG') ? 'selected' : '' }}>Minas Gerais</option>
            <option value="PA" {{ (isset($region->state) && $region->state == 'PA') ? 'selected' : '' }}>Pará</option>
            <option value="PB" {{ (isset($region->state) && $region->state == 'PB') ? 'selected' : '' }}>Paraíba</option>
            <option value="PR" {{ (isset($region->state) && $region->state == 'PR') ? 'selected' : '' }}>Paraná</option>
            <option value="PE" {{ (isset($region->state) && $region->state == 'PE') ? 'selected' : '' }}>Pernambuco</option>
            <option value="PI" {{ (isset($region->state) && $region->state == 'PI') ? 'selected' : '' }}>Piauí</option>
            <option value="RJ" {{ (isset($region->state) && $region->state == 'RJ') ? 'selected' : '' }}>Rio de Janeiro</option>
            <option value="RN" {{ (isset($region->state) && $region->state == 'RN') ? 'selected' : '' }}>Rio Grande do Norte</option>
            <option value="RS" {{ (isset($region->state) && $region->state == 'RS') ? 'selected' : '' }}>Rio Grande do Sul</option>
            <option value="RO" {{ (isset($region->state) && $region->state == 'RO') ? 'selected' : '' }}>Rondônia</option>
            <option value="RR" {{ (isset($region->state) && $region->state == 'RR') ? 'selected' : '' }}>Roraima</option>
            <option value="SC" {{ (isset($region->state) && $region->state == 'SC') ? 'selected' : '' }}>Santa Catarina</option>
            <option value="SP" {{ (isset($region->state) && $region->state == 'SP') ? 'selected' : '' }}>São Paulo</option>
            <option value="SE" {{ (isset($region->state) && $region->state == 'SE') ? 'selected' : '' }}>Sergipe</option>
            <option value="TO" {{ (isset($region->state) && $region->state == 'TO') ? 'selected' : '' }}>Tocantins</option>
        </select>
        <label for="state">Estado</label>
    </div>
</div>

<div class="row">
    <div class="input-field col s12">
        <textarea id="description" name="description" class="materialize-textarea">{{ $region->description ?? old('description') }}</textarea>
        <label for="description">Descrição</label>
    </div>
</div>

<div class="row">
    <div class="input-field col s12">
        <select id="status" name="status" class="validate" required>
            <option value="active" {{ (isset($region->status) && $region->status == 'active') ? 'selected' : '' }}>Ativo</option>
            <option value="inactive" {{ (isset($region->status) && $region->status == 'inactive') ? 'selected' : '' }}>Inativo</option>
        </select>
        <label for="status">Status</label>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <button type="submit" class="btn waves-effect waves-light blue">
            <i class="material-icons left">save</i>Salvar
        </button>
        <a href="{{ route('regions.index') }}" class="btn waves-effect waves-light grey">
            <i class="material-icons left">arrow_back</i>Voltar
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems);
});
</script> 
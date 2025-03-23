@extends('layouts.app')

@section('title', isset($costCenter) ? 'Editar Centro de Custo' : 'Novo Centro de Custo')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    {{ isset($costCenter) ? 'Editar Centro de Custo' : 'Novo Centro de Custo' }}
                </span>

                <form action="{{ isset($costCenter) 
                    ? route('financial.cost-centers.update', $costCenter) 
                    : route('financial.cost-centers.store') }}" 
                      method="POST">
                    @csrf
                    @if(isset($costCenter))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" id="nome" name="nome" 
                                   value="{{ old('nome', $costCenter->nome ?? '') }}" required>
                            <label for="nome">Nome</label>
                            @error('nome')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="input-field col s12">
                            <textarea id="descricao" name="descricao" 
                                      class="materialize-textarea">{{ old('descricao', $costCenter->descricao ?? '') }}</textarea>
                            <label for="descricao">Descrição</label>
                            @error('descricao')
                                <span class="red-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col s12">
                            <label>
                                <input type="checkbox" class="filled-in" name="ativo" value="1"
                                       {{ old('ativo', $costCenter->ativo ?? true) ? 'checked' : '' }}>
                                <span>Ativo</span>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <button type="submit" class="btn waves-effect waves-light blue">
                                <i class="material-icons left">save</i>
                                Salvar
                            </button>
                            
                            <a href="{{ route('financial.cost-centers.index') }}" 
                               class="btn waves-effect waves-light red">
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
@endsection 

<style>
.select-field {
    margin-bottom: 0 !important;
}
.select-field .select-wrapper input.select-dropdown {
    height: 2rem;
    line-height: 2rem;
    font-size: 14px;
}
.select-field .helper-text {
    margin-top: 0;
    font-size: 12px;
}
</style> 

<script>
document.addEventListener('DOMContentLoaded', function() {
    var selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});
</script> 
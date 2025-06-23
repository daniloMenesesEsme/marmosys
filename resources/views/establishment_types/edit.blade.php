@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Editar Tipo de Estabelecimento')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col s12">
            <h4>Editar Tipo de Estabelecimento: {{ $establishmentType->name }}</h4>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <form action="{{ route('establishment-types.update', $establishmentType) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="input-field col s12 m8">
                                <input id="name" type="text" name="name" value="{{ old('name', $establishmentType->name) }}" required autofocus>
                                <label for="name">Nome do Tipo de Estabelecimento *</label>
                                @error('name')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="input-field col s12 m4">
                                <select name="status" id="status">
                                    <option value="active" {{ old('status', $establishmentType->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                                    <option value="inactive" {{ old('status', $establishmentType->status) == 'inactive' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                <label for="status">Status *</label>
                                @error('status')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="description" name="description" class="materialize-textarea">{{ old('description', $establishmentType->description) }}</textarea>
                                <label for="description">Descrição</label>
                                @error('description')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="avg_ticket" type="number" step="0.01" min="0" name="avg_ticket" value="{{ old('avg_ticket', $establishmentType->avg_ticket) }}">
                                <label for="avg_ticket">Ticket Médio (R$)</label>
                                <span class="helper-text">Valor médio de compra esperado para este tipo de estabelecimento</span>
                                @error('avg_ticket')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="input-field col s12 m6">
                                <input id="potential_level" type="number" step="1" min="1" max="10" name="potential_level" value="{{ old('potential_level', $establishmentType->potential_level) }}">
                                <label for="potential_level">Nível de Potencial (1-10)</label>
                                <span class="helper-text">Classificação do potencial de vendas (1 = baixo, 10 = alto)</span>
                                @error('potential_level')
                                    <span class="red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn waves-effect waves-light">
                                    <i class="material-icons left">save</i>Atualizar
                                </button>
                                <a href="{{ route('establishment-types.index') }}" class="btn-flat waves-effect">
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
    });
</script>
@endsection 
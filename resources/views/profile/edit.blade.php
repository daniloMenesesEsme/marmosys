@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="row">
    <div class="col s12 m8 offset-m2">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Editar Perfil</span>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="input-field">
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        <label for="name">Nome</label>
                        @error('name')
                            <span class="red-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-field">
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        <label for="email">E-mail</label>
                        @error('email')
                            <span class="red-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-field">
                        <input id="current_password" type="password" name="current_password">
                        <label for="current_password">Senha Atual</label>
                        @error('current_password')
                            <span class="red-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-field">
                        <input id="password" type="password" name="password">
                        <label for="password">Nova Senha</label>
                        @error('password')
                            <span class="red-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-field">
                        <input id="password_confirmation" type="password" name="password_confirmation">
                        <label for="password_confirmation">Confirmar Nova Senha</label>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn waves-effect waves-light">
                            Atualizar
                            <i class="material-icons right">save</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
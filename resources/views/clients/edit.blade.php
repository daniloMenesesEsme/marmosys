@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Editar Cliente</span>

                <form action="{{ route('clients.update', $client) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- Os mesmos campos do create.blade.php, mas com os valores preenchidos --}}
                    {{-- Utilize $client->campo para preencher os valores --}}
                    {{-- O restante do código é idêntico ao create.blade.php --}}
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Os mesmos scripts do create.blade.php --}}
@endpush 
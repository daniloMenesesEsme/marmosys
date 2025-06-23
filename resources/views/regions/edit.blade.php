@extends('layouts.app')

@section('title', 'Editar Região')

@section('content')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Editar Região</span>
                        
                        <form action="{{ route('regions.update', $region) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            @include('regions.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
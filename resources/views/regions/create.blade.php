@extends('layouts.app')

@section('title', 'Nova Região')

@section('content')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Nova Região</span>
                        
                        <form action="{{ route('regions.store') }}" method="POST">
                            @csrf
                            
                            @include('regions.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
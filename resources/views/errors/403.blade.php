@extends('layouts.app')

@section('title', 'Acceso restringido · Grupo Scout San Miguel')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="scout-card p-5 text-center">
                <div class="display-5 text-warning mb-3">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1 class="h3 fw-bold mb-2">Acceso restringido</h1>
                <p class="text-secondary mb-4">
                    {{ $exception->getMessage() ?: 'No tienes permisos para acceder a esta sección.' }}
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-scout">Ir al inicio</a>
                    @auth
                        <a href="{{ route('calendar') }}" class="btn btn-scout">Ir al calendario</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


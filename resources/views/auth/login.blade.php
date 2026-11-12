@extends('layouts.app')

@section('title', 'Iniciar sesión · Grupo Scout San Miguel')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="scout-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/scout-emblem.svg') }}" alt="Emblema Scout" width="54" height="54" class="mb-2">
                    <h1 class="h3 fw-bold mb-1">Iniciar sesión</h1>
                    <p class="text-muted small mb-0">Accede con tu nombre de usuario y contraseña.</p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Nombre de usuario</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" autocomplete="username" autofocus required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="current-password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>
                        <label class="form-check-label small text-secondary" for="remember">
                            Mantener sesión iniciada
                        </label>
                    </div>

                    <button type="submit" class="btn btn-scout w-100 mb-3">
                        Entrar
                    </button>

                    <div class="text-center small text-muted">
                        ¿Aún no tienes usuario?
                        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none" style="color: var(--scout-primary);">
                            Regístrate aquí
                        </a>
                    </div>
                </form>

                @if (app()->isLocal())
                    <hr class="my-4">
                    <div class="bg-light border rounded-3 p-3 small text-secondary">
                        <div class="fw-semibold text-dark mb-1">Cuentas de prueba (entorno local)</div>
                        <div>Mayor de edad (calendario + pagos): <code>adulto_demo</code> / <code>Scout1234!</code></div>
                        <div>Menor de edad (solo calendario): <code>menor_demo</code> / <code>Scout1234!</code></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Crear usuario · Grupo Scout San Miguel')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="scout-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold mb-1">Crear cuenta del grupo</h1>
                    <p class="text-muted small mb-0">
                        Para consultar el calendario y gestionar cuotas necesitas registrarte. Según tu edad, tu cuenta tendrá permisos de menor o de mayor de edad.
                    </p>
                </div>

                <form method="POST" action="{{ route('register.store') }}" novalidate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold">Nombre y apellidos</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="username" class="form-label fw-semibold">Nombre de usuario</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" autocomplete="username" required>
                            <div class="form-text">Lo usarás para iniciar sesión.</div>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="birth_date" class="form-label fw-semibold">Fecha de nacimiento</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                            <div class="form-text">Los mayores de 18 años tienen acceso a pagos.</div>
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="scout_section" class="form-label fw-semibold">Rama scout</label>
                            <select id="scout_section" name="scout_section" class="form-select @error('scout_section') is-invalid @enderror" required>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->value }}" @selected(old('scout_section', 'grupo') === $section->value)>
                                        {{ $section->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('scout_section')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="phone" class="form-label fw-semibold">Teléfono (opcional)</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="password_confirmation" class="form-label fw-semibold">Repetir contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-scout w-100 mt-4 mb-3">
                        Crear mi usuario
                    </button>

                    <div class="text-center small text-muted">
                        ¿Ya tienes usuario?
                        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color: var(--scout-primary);">
                            Inicia sesión
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


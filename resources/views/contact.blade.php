@extends('layouts.app')

@section('title', 'Contacto · Grupo Scout San Miguel')

@section('content')
<div class="container">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="scout-card p-4">
                <h1 class="h3 fw-bold mb-1">Contacta con el Grupo</h1>
                <p class="text-muted small mb-4">¿Quieres apuntar a tu hijo/a, resolver dudas sobre inscripciones o hablar con el Kraal? Escríbenos aquí.</p>

                <form method="POST" action="{{ route('contact.store') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nombre</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label fw-semibold">Asunto</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="message" class="form-label fw-semibold">Mensaje</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-scout px-4">
                        <i class="bi bi-send me-2"></i>Enviar mensaje
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="scout-card p-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Dónde estamos</h2>
                <p class="small text-secondary mb-2">
                    <i class="bi bi-geo-alt-fill text-success me-2"></i>{{ $scout['address'] }}
                </p>
                <p class="small text-secondary mb-2">
                    <i class="bi bi-clock-fill text-success me-2"></i>{{ $scout['schedule'] }}
                </p>
                <p class="small text-secondary mb-3">
                    <i class="bi bi-envelope-fill text-success me-2"></i>{{ $scout['email'] }}
                </p>
                <a href="{{ $scout['maps_directions_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-scout btn-sm w-100">
                    <i class="bi bi-map me-2"></i>Abrir ruta en Google Maps
                </a>
            </div>

            <div class="scout-card overflow-hidden">
                <div class="ratio ratio-4x3">
                    <iframe
                        title="Mapa de contacto Grupo Scout San Miguel"
                        src="{{ $scout['maps_embed_url'] }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


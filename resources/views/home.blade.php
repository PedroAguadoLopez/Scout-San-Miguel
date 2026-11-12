@extends('layouts.app')

@section('title', 'Inicio · Grupo Scout San Miguel (Algeciras)')

@section('content')
<div class="container">
    {{-- Hero ligero y acogedor --}}
    <section class="hero-scout p-4 p-lg-5 mb-5 shadow-sm">
        <div class="row align-items-center gy-4">
            <div class="col-lg-8">
                <span class="badge rounded-pill text-bg-warning text-dark mb-3 px-3 py-2">
                    <i class="bi bi-compass me-1"></i>Algeciras · Campo de Gibraltar
                </span>
                <h1 class="display-5 fw-bold mb-3">Grupo Scout San Miguel</h1>
                <p class="lead opacity-90 mb-4" style="max-width: 620px;">
                    Educación en valores, naturaleza y amistad cada sábado en Algeciras. Un espacio para niños, jóvenes y familias donde crecer dejando el mundo un poco mejor.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#quienes-somos" class="btn btn-light fw-semibold px-4">
                        ¿Quiénes somos?
                    </a>
                    <a href="{{ route('camps.index') }}" class="btn btn-outline-light px-4">
                        <i class="bi bi-images me-1"></i>Ver campamentos
                    </a>
                    @auth
                        <a href="{{ route('calendar') }}" class="btn btn-warning text-dark fw-semibold px-4">
                            <i class="bi bi-calendar3 me-1"></i>Ir al calendario
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-warning text-dark fw-semibold px-4">
                            Crear usuario
                        </a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <img src="{{ asset('images/scout-emblem.svg') }}" alt="Escudo Grupo Scout San Miguel" class="img-fluid" style="max-height: 190px;">
            </div>
        </div>
    </section>

    {{-- ¿Quiénes somos? --}}
    <section id="quienes-somos" class="mb-5">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <h2 class="h3 fw-bold mb-2">¿Quiénes somos?</h2>
                <div class="section-heading-line mb-3"></div>
                <p class="text-secondary mb-3">
                    El <strong>Grupo Scout San Miguel</strong> forma parte de la familia scout de Algeciras. Acompañamos a chicos y chicas desde los 6 hasta los 21 años a través del juego, el aire libre, el trabajo en equipo y el servicio a nuestra ciudad.
                </p>
                <p class="text-secondary mb-0">
                    Nuestras reuniones se celebran habitualmente los <strong>{{ strtolower($scout['schedule']) }}</strong>, combinando actividades en el local con acampadas en Los Alcornocales y campamentos de verano.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="scout-card p-3 h-100">
                            <div class="fw-semibold mb-1" style="color: var(--scout-primary);">
                                <i class="bi bi-tree me-2"></i>Vida en la naturaleza
                            </div>
                            <p class="small text-muted mb-0">Rutas, acampadas trimestrales y nuestro gran campamento de verano.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="scout-card p-3 h-100">
                            <div class="fw-semibold mb-1" style="color: var(--scout-primary);">
                                <i class="bi bi-people me-2"></i>Cinco ramas por edades
                            </div>
                            <p class="small text-muted mb-0">Castores, Lobatos, Scouts, Escultas y Rovers guiados por el Kraal.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="scout-card p-3 h-100">
                            <div class="fw-semibold mb-1" style="color: var(--scout-primary);">
                                <i class="bi bi-calendar-event me-2"></i>Calendario de ronda
                            </div>
                            <p class="small text-muted mb-0">
                                {{ $upcomingEventsCount }} actividades próximas visibles al iniciar sesión.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="scout-card p-3 h-100">
                            <div class="fw-semibold mb-1" style="color: var(--scout-primary);">
                                <i class="bi bi-shield-check me-2"></i>Gestión para familias
                            </div>
                            <p class="small text-muted mb-0">Las cuentas de mayores de edad permiten consultar y abonar cuotas con Stripe.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Fotos destacadas de antiguos campamentos --}}
    <section class="mb-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
            <div>
                <h2 class="h3 fw-bold mb-2">Recuerdos de campamentos</h2>
                <div class="section-heading-line"></div>
            </div>
            <a href="{{ route('camps.index') }}" class="btn btn-outline-scout btn-sm">
                Ver todos los campamentos <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse ($featuredCamps as $camp)
                <div class="col-md-4">
                    <div class="scout-card h-100 d-flex flex-column">
                        <img src="{{ asset($camp->cover_image) }}" alt="{{ $camp->title }}" class="camp-card-img w-100">
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge text-bg-light border text-dark">{{ $camp->year }}</span>
                                <small class="text-muted"><i class="bi bi-camera me-1"></i>{{ $camp->photos_count }} fotos</small>
                            </div>
                            <h3 class="h6 fw-bold mb-1">{{ $camp->title }}</h3>
                            <p class="small text-muted mb-3 flex-grow-1">{{ $camp->summary }}</p>
                            <a href="{{ route('camps.show', $camp) }}" class="btn btn-sm btn-outline-scout align-self-start">
                                Ver galería
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="scout-card p-4 text-center text-muted">
                        Próximamente añadiremos las fotografías de nuestros últimos campamentos.
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Cómo llegar --}}
    <section id="como-llegar" class="mb-4">
        <div class="scout-card p-4">
            <div class="row align-items-center gy-4">
                <div class="col-lg-5">
                    <h2 class="h3 fw-bold mb-2">Cómo llegar</h2>
                    <div class="section-heading-line mb-3"></div>
                    <p class="text-secondary mb-3">
                        Nuestro local de reuniones se encuentra en Algeciras. Si vienes por primera vez o quieres acercarte un sábado, puedes abrir la ruta directa en Google Maps.
                    </p>
                    <ul class="list-unstyled small text-secondary mb-4">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt-fill text-success me-2"></i>
                            <strong>Dirección:</strong> {{ $scout['address'] }}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-clock-fill text-success me-2"></i>
                            <strong>Horario habitual:</strong> {{ $scout['schedule'] }}
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill text-success me-2"></i>
                            <strong>Correo:</strong> {{ $scout['email'] }}
                        </li>
                    </ul>
                    <a href="{{ $scout['maps_directions_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-scout">
                        <i class="bi bi-map me-2"></i>Cómo llegar con Google Maps
                    </a>
                </div>
                <div class="col-lg-7">
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden border">
                        <iframe
                            title="Mapa de ubicación del Grupo Scout San Miguel en Algeciras"
                            src="{{ $scout['maps_embed_url'] }}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection


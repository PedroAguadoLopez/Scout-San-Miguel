<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Grupo Scout San Miguel · Algeciras')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/scout.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-scout sticky-top py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="{{ route('home') }}">
                <img src="{{ asset('images/scout-emblem.svg') }}" alt="Emblema Grupo Scout San Miguel" width="38" height="38">
                <span>
                    <span class="d-block lh-1" style="color: var(--scout-primary);">Scout San Miguel</span>
                    <small class="text-muted fw-normal" style="font-size: 0.72rem;">Algeciras</small>
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Abrir navegación">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('camps.*') ? 'active' : '' }}" href="{{ route('camps.index') }}">Campamentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}" href="{{ route('calendar') }}">
                            Calendario
                            @guest
                                <i class="bi bi-lock-fill small ms-1 text-muted" title="Requiere iniciar sesión"></i>
                            @endguest
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.create') }}">Contacto</a>
                    </li>
                    @auth
                        @if (auth()->user()->isAdult())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                                    <i class="bi bi-credit-card me-1"></i>Mis Pagos
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-scout btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                                <span>{{ auth()->user()->username }}</span>
                                <span class="badge {{ auth()->user()->isAdult() ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ auth()->user()->role->label() }}
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person-vcard me-2"></i>Mi perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('calendar') }}">
                                        <i class="bi bi-calendar3 me-2"></i>Calendario de ronda
                                    </a>
                                </li>
                                @if (auth()->user()->isAdult())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('payments.index') }}">
                                            <i class="bi bi-credit-card me-2"></i>Cuotas y pagos (Stripe)
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-scout btn-sm">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-scout btn-sm">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="footer-scout py-4 mt-5">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-md-6">
                    <div class="fw-semibold text-white">Grupo Scout San Miguel · Algeciras</div>
                    <div class="small opacity-75">{{ config('scout.schedule') }} · {{ config('scout.city') }}</div>
                </div>
                <div class="col-md-6 text-md-end small">
                    <a href="{{ route('home') }}" class="me-3">Inicio</a>
                    <a href="{{ route('camps.index') }}" class="me-3">Campamentos</a>
                    <a href="{{ route('calendar') }}" class="me-3">Calendario</a>
                    <a href="{{ route('contact.create') }}">Contacto</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>


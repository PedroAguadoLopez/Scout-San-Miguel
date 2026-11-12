@extends('layouts.app')

@section('title', 'Mi Perfil · Grupo Scout San Miguel')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="scout-card p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 fw-bold mb-1">{{ $user->name }}</h1>
                        <div class="text-muted small">&#64;{{ $user->username }} · {{ $user->email }}</div>
                    </div>
                    <span class="badge {{ $user->isAdult() ? 'text-bg-success' : 'text-bg-secondary' }} fs-6 px-3 py-2">
                        {{ $user->role->label() }}
                    </span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <div class="small text-muted">Rama scout</div>
                            <div class="fw-semibold">{{ $user->scout_section?->label() ?? 'Todo el Grupo' }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <div class="small text-muted">Fecha de nacimiento</div>
                            <div class="fw-semibold">{{ $user->birth_date?->format('d/m/Y') ?? 'No indicada' }}</div>
                        </div>
                    </div>
                </div>

                <h2 class="h6 fw-bold mb-3">Permisos de tu cuenta</h2>
                <ul class="list-group mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-calendar3 text-success me-2"></i>Acceso al calendario mensual y anual</span>
                        <span class="badge text-bg-success">Permitido</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-credit-card me-2 {{ $user->isAdult() ? 'text-success' : 'text-muted' }}"></i>Consulta y pago de cuotas con Stripe</span>
                        @if ($user->isAdult())
                            <span class="badge text-bg-success">Permitido</span>
                        @else
                            <span class="badge text-bg-secondary">Solo mayores de edad</span>
                        @endif
                    </li>
                </ul>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('calendar') }}" class="btn btn-scout">
                        <i class="bi bi-calendar3 me-1"></i>Ver calendario
                    </a>
                    @if ($user->isAdult())
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-scout">
                            <i class="bi bi-credit-card me-1"></i>Mis pagos
                            @if ($pendingPaymentsCount > 0)
                                <span class="badge text-bg-warning text-dark ms-1">{{ $pendingPaymentsCount }}</span>
                            @endif
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

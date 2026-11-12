@extends('layouts.app')

@section('title', 'Calendario · Grupo Scout San Miguel')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Calendario de Actividades</h1>
            <p class="text-muted mb-0">
                Consulta las reuniones, acampadas y campamentos del {{ $year }} en el Grupo Scout San Miguel.
            </p>
        </div>

        <form method="GET" action="{{ route('calendar') }}" class="d-flex flex-wrap align-items-center gap-2">
            <input type="hidden" name="month" value="{{ $currentMonth->month }}">
            <input type="hidden" name="year" value="{{ $currentMonth->year }}">
            <label for="section" class="form-label mb-0 small fw-semibold">Rama:</label>
            <select name="section" id="section" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Todas las ramas</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->value }}" @selected($selectedSection === $section)>
                        {{ $section->label() }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Navegación del mes --}}
    <div class="scout-card p-3 p-md-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <a href="{{ route('calendar', ['month' => $previousMonth->month, 'year' => $previousMonth->year, 'section' => $selectedSection?->value]) }}" class="btn btn-outline-scout btn-sm">
                <i class="bi bi-chevron-left me-1"></i>{{ ucfirst($previousMonth->translatedFormat('F Y')) }}
            </a>

            <div class="text-center">
                <h2 class="h4 fw-bold text-capitalize mb-0">
                    {{ $currentMonth->translatedFormat('F Y') }}
                </h2>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('calendar', ['section' => $selectedSection?->value]) }}" class="btn btn-light border btn-sm">
                    Hoy
                </a>
                <a href="{{ route('calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year, 'section' => $selectedSection?->value]) }}" class="btn btn-outline-scout btn-sm">
                    {{ ucfirst($nextMonth->translatedFormat('F Y')) }}<i class="bi bi-chevron-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="calendar-grid">
            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $weekday)
                <div class="calendar-weekday">{{ $weekday }}</div>
            @endforeach

            @foreach ($weeks as $week)
                @foreach ($week as $cell)
                    <div class="calendar-cell {{ $cell['inMonth'] ? '' : 'is-outside' }} {{ $cell['isToday'] ? 'is-today' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="calendar-day-num">{{ $cell['date']->day }}</span>
                            <span class="d-md-none small text-muted text-capitalize">
                                {{ $cell['date']->translatedFormat('l d M') }}
                            </span>
                        </div>

                        @foreach ($cell['events'] as $event)
                            <button
                                type="button"
                                class="calendar-event-pill {{ $event->scout_section->badgeClass() }}"
                                data-bs-toggle="modal"
                                data-bs-target="#eventModal{{ $event->id }}"
                                title="{{ $event->title }}">
                                @if (! $event->all_day)
                                    <strong>{{ $event->starts_at->format('H:i') }}</strong>
                                @endif
                                {{ $event->title }}
                            </button>
                        @endforeach
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- Resumen anual del calendario --}}
    <div class="scout-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 fw-bold mb-0">Todas las actividades del año {{ $year }}</h2>
            <span class="badge text-bg-light border text-dark">{{ $yearEvents->count() }} actividades</span>
        </div>

        @if ($yearEvents->isEmpty())
            <p class="text-muted mb-0">No hay actividades programadas para este filtro en {{ $year }}.</p>
        @else
            <div class="list-group list-group-flush">
                @foreach ($yearEvents as $event)
                    <div class="list-group-item px-0 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <span class="badge {{ $event->scout_section->badgeClass() }}">{{ $event->scout_section->label() }}</span>
                                <span class="fw-semibold">{{ $event->title }}</span>
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $event->starts_at->translatedFormat('l j \d\e F Y') }}
                                @if (! $event->all_day)
                                    · {{ $event->starts_at->format('H:i') }}
                                    @if ($event->ends_at)
                                        - {{ $event->ends_at->format('H:i') }}
                                    @endif
                                @else
                                    · Todo el día
                                @endif
                                @if ($event->location)
                                    · <i class="bi bi-geo-alt me-1"></i>{{ $event->location }}
                                @endif
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-scout" data-bs-toggle="modal" data-bs-target="#eventModal{{ $event->id }}">
                            Ver detalle
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Modales de detalle de eventos --}}
@php
    $uniqueEvents = collect($weeks)->flatten(1)->pluck('events')->flatten(1)->merge($yearEvents)->unique('id');
@endphp

@foreach ($uniqueEvents as $event)
    <div class="modal fade" id="eventModal{{ $event->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <span class="badge {{ $event->scout_section->badgeClass() }} mb-1">{{ $event->scout_section->label() }}</span>
                        <h5 class="modal-title fw-bold">{{ $event->title }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2">
                        <i class="bi bi-clock me-1"></i>
                        <strong>Inicio:</strong> {{ $event->starts_at->translatedFormat('l j \d\e F Y, H:i') }}
                        @if ($event->ends_at)
                            <br><i class="bi bi-clock-history me-1"></i>
                            <strong>Fin:</strong> {{ $event->ends_at->translatedFormat('l j \d\e F Y, H:i') }}
                        @endif
                    </p>
                    @if ($event->location)
                        <p class="small text-muted mb-3">
                            <i class="bi bi-geo-alt me-1"></i>
                            <strong>Lugar:</strong> {{ $event->location }}
                        </p>
                    @endif
                    <p class="mb-0 text-secondary">
                        {{ $event->description ?: 'Actividad programada dentro de la ronda solar del Grupo Scout San Miguel.' }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection


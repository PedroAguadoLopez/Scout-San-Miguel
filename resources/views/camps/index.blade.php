@extends('layouts.app')

@section('title', 'Campamentos · Grupo Scout San Miguel')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Antiguos Campamentos</h1>
            <p class="text-muted mb-0">Recuerdos, lugares y fotografías de nuestras salidas y campamentos de verano.</p>
        </div>

        <form method="GET" action="{{ route('camps.index') }}" class="d-flex align-items-center gap-2">
            <label for="year" class="form-label mb-0 small fw-semibold">Año:</label>
            <select name="year" id="year" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Todos los años</option>
                @foreach ($years as $year)
                    <option value="{{ $year }}" @selected($selectedYear === (int) $year)>{{ $year }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="row g-4">
        @forelse ($camps as $camp)
            <div class="col-md-6 col-lg-4">
                <div class="scout-card h-100 d-flex flex-column">
                    <img src="{{ asset($camp->cover_image) }}" alt="{{ $camp->title }}" class="camp-card-img w-100">
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge text-bg-success">{{ $camp->year }}</span>
                            <small class="text-muted">
                                <i class="bi bi-images me-1"></i>{{ $camp->photos->count() }} fotos
                            </small>
                        </div>
                        <h2 class="h5 fw-bold mb-1">{{ $camp->title }}</h2>
                        <div class="small text-muted mb-2">
                            <i class="bi bi-geo-alt me-1"></i>{{ $camp->location }} ·
                            {{ $camp->start_date->format('d/m/Y') }} - {{ $camp->end_date->format('d/m/Y') }}
                        </div>
                        <p class="small text-secondary mb-3 flex-grow-1">{{ $camp->summary }}</p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('camps.show', $camp) }}" class="btn btn-scout btn-sm">
                                Abrir álbum
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="scout-card p-5 text-center text-muted">
                    No hay campamentos registrados para el filtro seleccionado.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection


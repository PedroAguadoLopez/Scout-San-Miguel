@extends('layouts.app')

@section('title', $camp->title . ' · Campamentos')

@section('content')
<div class="container">
    <div class="mb-3">
        <a href="{{ route('camps.index') }}" class="text-decoration-none small fw-semibold" style="color: var(--scout-primary);">
            <i class="bi bi-arrow-left me-1"></i>Volver a todos los campamentos
        </a>
    </div>

    <div class="scout-card p-4 mb-4">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-7">
                <span class="badge text-bg-success mb-2">{{ $camp->year }}</span>
                <h1 class="h2 fw-bold mb-2">{{ $camp->title }}</h1>
                <div class="text-muted small mb-3">
                    <span class="me-3"><i class="bi bi-geo-alt me-1"></i>{{ $camp->location }}</span>
                    <span><i class="bi bi-calendar-range me-1"></i>Del {{ $camp->start_date->format('d/m/Y') }} al {{ $camp->end_date->format('d/m/Y') }}</span>
                </div>
                <p class="text-secondary mb-0" style="white-space: pre-line;">{{ $camp->description }}</p>
            </div>
            <div class="col-lg-5">
                <img src="{{ asset($camp->cover_image) }}" alt="{{ $camp->title }}" class="img-fluid rounded-3 border">
            </div>
        </div>
    </div>

    <h2 class="h4 fw-bold mb-3">Galería de fotografías</h2>
    @if ($camp->photos->isEmpty())
        <div class="scout-card p-4 text-muted text-center">
            Este campamento aún no tiene fotografías adicionales subidas.
        </div>
    @else
        <div class="row g-4">
            @foreach ($camp->photos as $index => $photo)
                <div class="col-md-6 col-lg-4">
                    <div class="scout-card h-100 overflow-hidden">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#photoModal{{ $photo->id }}" class="d-block">
                            <img src="{{ asset($photo->image_path) }}" alt="{{ $photo->caption ?? $camp->title }}" class="camp-card-img w-100">
                        </a>
                        @if ($photo->caption)
                            <div class="p-3 small text-secondary">{{ $photo->caption }}</div>
                        @endif
                    </div>
                </div>

                <div class="modal fade" id="photoModal{{ $photo->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $camp->title }} · Foto {{ $index + 1 }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="{{ asset($photo->image_path) }}" alt="{{ $photo->caption ?? $camp->title }}" class="img-fluid rounded">
                                @if ($photo->caption)
                                    <p class="mt-3 mb-0 text-secondary">{{ $photo->caption }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection


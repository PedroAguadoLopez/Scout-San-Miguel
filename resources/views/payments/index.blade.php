@extends('layouts.app')

@section('title', 'Mis Pagos · Grupo Scout San Miguel')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Mis Pagos y Cuotas</h1>
            <p class="text-muted mb-0">
                Consulta únicamente tus cuotas mensuales y anuales pendientes, y realiza el ingreso de forma segura con Stripe.
            </p>
        </div>
        <span class="badge text-bg-success px-3 py-2">
            <i class="bi bi-shield-lock me-1"></i>Pago seguro con Stripe
        </span>
    </div>

    @if (! $stripeConfigured)
        <div class="alert alert-warning shadow-sm mb-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <strong>Modo de configuración:</strong> Para habilitar el cobro real en Stripe Checkout, añade tus claves de prueba <code>STRIPE_KEY</code> y <code>STRIPE_SECRET</code> en el archivo <code>.env</code>.
        </div>
    @endif

    {{-- Resumen de pagos --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="scout-card p-3 h-100">
                <div class="small text-muted">Total pendiente</div>
                <div class="h3 fw-bold mb-0" style="color: var(--scout-primary);">{{ $totalPendingFormatted }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="scout-card p-3 h-100">
                <div class="small text-muted">Cuotas mensuales pendientes</div>
                <div class="h3 fw-bold mb-0">{{ $pendingMonthly->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="scout-card p-3 h-100">
                <div class="small text-muted">Cuotas anuales pendientes</div>
                <div class="h3 fw-bold mb-0">{{ $pendingAnnual->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Pagos anuales pendientes --}}
    <div class="scout-card p-4 mb-4">
        <h2 class="h5 fw-bold mb-3">
            <i class="bi bi-calendar-check me-2 text-success"></i>Pagos anuales pendientes
        </h2>

        @if ($pendingAnnual->isEmpty())
            <p class="text-muted mb-0">No tienes pagos anuales pendientes.</p>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Concepto</th>
                            <th>Periodo</th>
                            <th>Vencimiento</th>
                            <th>Importe</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingAnnual as $payment)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $payment->concept }}</div>
                                    @if ($payment->description)
                                        <div class="small text-muted">{{ $payment->description }}</div>
                                    @endif
                                </td>
                                <td><span class="badge text-bg-light border text-dark">{{ $payment->period_label }}</span></td>
                                <td class="small">{{ $payment->due_date->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $payment->formattedAmount() }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('payments.checkout', $payment) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-scout btn-sm">
                                            <i class="bi bi-credit-card me-1"></i>Pagar con Stripe
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Pagos mensuales pendientes --}}
    <div class="scout-card p-4 mb-4">
        <h2 class="h5 fw-bold mb-3">
            <i class="bi bi-calendar-month me-2 text-success"></i>Pagos mensuales pendientes
        </h2>

        @if ($pendingMonthly->isEmpty())
            <p class="text-muted mb-0">No tienes cuotas mensuales pendientes.</p>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Concepto</th>
                            <th>Mes</th>
                            <th>Vencimiento</th>
                            <th>Importe</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingMonthly as $payment)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $payment->concept }}</div>
                                    @if ($payment->description)
                                        <div class="small text-muted">{{ $payment->description }}</div>
                                    @endif
                                </td>
                                <td><span class="badge text-bg-light border text-dark">{{ $payment->period_label }}</span></td>
                                <td class="small">{{ $payment->due_date->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $payment->formattedAmount() }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('payments.checkout', $payment) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-scout btn-sm">
                                            <i class="bi bi-credit-card me-1"></i>Pagar con Stripe
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Historial de pagos realizados --}}
    <div class="scout-card p-4">
        <h2 class="h5 fw-bold mb-3">
            <i class="bi bi-receipt me-2 text-secondary"></i>Historial de pagos completados
        </h2>

        @if ($paidPayments->isEmpty())
            <p class="text-muted mb-0">Aún no has realizado ningún pago desde la web.</p>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Concepto</th>
                            <th>Tipo</th>
                            <th>Periodo</th>
                            <th>Fecha de pago</th>
                            <th>Importe</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paidPayments as $payment)
                            <tr>
                                <td class="fw-semibold">{{ $payment->concept }}</td>
                                <td class="small">{{ $payment->fee_type->label() }}</td>
                                <td class="small">{{ $payment->period_label }}</td>
                                <td class="small">{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                                <td class="fw-semibold">{{ $payment->formattedAmount() }}</td>
                                <td><span class="badge text-bg-success">Pagado</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection


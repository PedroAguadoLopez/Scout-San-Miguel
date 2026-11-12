<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CampController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/campamentos', [CampController::class, 'index'])->name('camps.index');
Route::get('/campamentos/{camp:slug}', [CampController::class, 'show'])->name('camps.show');

Route::get('/contacto', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/iniciar-sesion', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/iniciar-sesion', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');

    Route::get('/registro', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/registro', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/cerrar-sesion', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/calendario', CalendarController::class)->name('calendar');
    Route::get('/perfil', ProfileController::class)->name('profile');

    Route::middleware('adult')->prefix('pagos')->name('payments.')->group(function (): void {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::post('/{payment}/checkout', [PaymentController::class, 'checkout'])->name('checkout');
        Route::get('/{payment}/exito', [PaymentController::class, 'success'])->name('success');
        Route::get('/{payment}/cancelado', [PaymentController::class, 'cancel'])->name('cancel');
    });
});

Route::post('/stripe/webhook', StripeWebhookController::class)->name('stripe.webhook');

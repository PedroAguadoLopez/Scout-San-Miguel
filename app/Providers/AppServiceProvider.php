<?php

namespace App\Providers;

use App\Contracts\PaymentGateway;
use App\Models\Payment;
use App\Policies\PaymentPolicy;
use App\Services\StripeCheckoutService;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGateway::class, StripeCheckoutService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('es');
        Gate::policy(Payment::class, PaymentPolicy::class);

        RateLimiter::for('login', function (Request $request): Limit {
            $username = Str::lower($request->string('username')->toString());

            return Limit::perMinute(5)->by(Str::transliterate($username.'|'.$request->ip()));
        });
    }
}

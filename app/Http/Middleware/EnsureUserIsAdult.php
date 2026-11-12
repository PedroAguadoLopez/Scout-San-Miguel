<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdult
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdult()) {
            abort(403, 'Esta sección está reservada únicamente para personas mayores de edad del Grupo Scout San Miguel.');
        }

        return $next($request);
    }
}

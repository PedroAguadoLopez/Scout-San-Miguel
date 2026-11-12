<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ScoutSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'sections' => ScoutSection::cases(),
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $birthDate = CarbonImmutable::parse($validated['birth_date']);
        $role = User::resolveRoleFromBirthDate($birthDate);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'birth_date' => $birthDate->toDateString(),
            'role' => $role,
            'scout_section' => $validated['scout_section'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $welcomeMessage = $user->isAdult()
            ? '¡Bienvenido al Grupo Scout San Miguel! Tu cuenta de mayor de edad te da acceso al calendario y a tus pagos.'
            : '¡Bienvenido al Grupo Scout San Miguel! Tu cuenta de menor de edad te da acceso al calendario de actividades.';

        return redirect()
            ->route('calendar')
            ->with('status', $welcomeMessage);
    }
}

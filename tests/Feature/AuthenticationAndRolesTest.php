<?php

use App\Enums\ScoutSection;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('el registro crea una cuenta de mayor de edad cuando tiene 18 años o más', function (): void {
    $response = $this->post(route('register.store'), [
        'name' => 'Pedro García',
        'username' => 'pedro_adulto',
        'email' => 'pedro.adulto@example.com',
        'birth_date' => now()->subYears(25)->toDateString(),
        'scout_section' => ScoutSection::Grupo->value,
        'password' => 'ScoutPass123!',
        'password_confirmation' => 'ScoutPass123!',
    ]);

    $response->assertRedirect(route('calendar'));
    $this->assertAuthenticated();

    $user = User::where('username', 'pedro_adulto')->firstOrFail();
    expect($user->role)->toBe(UserRole::Adult);
    expect($user->isAdult())->toBeTrue();
});

test('el registro crea una cuenta de menor de edad cuando tiene menos de 18 años', function (): void {
    $response = $this->post(route('register.store'), [
        'name' => 'Sofía López',
        'username' => 'sofia_menor',
        'email' => 'sofia.menor@example.com',
        'birth_date' => now()->subYears(14)->toDateString(),
        'scout_section' => ScoutSection::Scouts->value,
        'password' => 'ScoutPass123!',
        'password_confirmation' => 'ScoutPass123!',
    ]);

    $response->assertRedirect(route('calendar'));
    $this->assertAuthenticated();

    $user = User::where('username', 'sofia_menor')->firstOrFail();
    expect($user->role)->toBe(UserRole::Minor);
    expect($user->isMinor())->toBeTrue();
});

test('el inicio de sesión funciona solo con nombre de usuario y contraseña', function (): void {
    $user = User::factory()->adult()->create([
        'username' => 'socio_algeciras',
        'password' => 'ClaveSegura123!',
    ]);

    $response = $this->post(route('login.store'), [
        'username' => 'socio_algeciras',
        'password' => 'ClaveSegura123!',
    ]);

    $response->assertRedirect(route('calendar'));
    $this->assertAuthenticatedAs($user);
});

test('el inicio de sesión rechaza credenciales inválidas', function (): void {
    User::factory()->adult()->create([
        'username' => 'socio_algeciras',
        'password' => 'ClaveSegura123!',
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'username' => 'socio_algeciras',
        'password' => 'incorrecta',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

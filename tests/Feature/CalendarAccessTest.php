<?php

use App\Enums\ScoutSection;
use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('los usuarios no autenticados son redirigidos al login al intentar ver el calendario', function (): void {
    $this->get(route('calendar'))
        ->assertRedirect(route('login'));
});

test('un menor de edad y un mayor de edad pueden ver el calendario mensual y anual', function (): void {
    $event = CalendarEvent::factory()->create([
        'title' => 'Acampada Sierra del Pinar',
        'scout_section' => ScoutSection::Scouts,
        'starts_at' => now()->startOfMonth()->addDays(5)->setTime(10, 0),
        'ends_at' => now()->startOfMonth()->addDays(5)->setTime(18, 0),
    ]);

    $minor = User::factory()->minor()->create();
    $adult = User::factory()->adult()->create();

    $this->actingAs($minor)
        ->get(route('calendar'))
        ->assertSuccessful()
        ->assertSee($event->title);

    $this->actingAs($adult)
        ->get(route('calendar'))
        ->assertSuccessful()
        ->assertSee($event->title);
});

test('el filtro por rama del calendario muestra solo eventos de la rama seleccionada o del grupo', function (): void {
    CalendarEvent::factory()->create([
        'title' => 'Actividad de Lobatos',
        'scout_section' => ScoutSection::Lobatos,
        'starts_at' => now()->startOfMonth()->addDays(3)->setTime(17, 0),
    ]);

    CalendarEvent::factory()->create([
        'title' => 'Ruta de Rovers',
        'scout_section' => ScoutSection::Rovers,
        'starts_at' => now()->startOfMonth()->addDays(4)->setTime(17, 0),
    ]);

    $user = User::factory()->minor()->create();

    $this->actingAs($user)
        ->get(route('calendar', ['section' => ScoutSection::Lobatos->value]))
        ->assertSuccessful()
        ->assertSee('Actividad de Lobatos')
        ->assertDontSee('Ruta de Rovers');
});

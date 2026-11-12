<?php

use App\Models\Camp;
use App\Models\CampPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('la página principal muestra información del grupo, campamentos destacados y cómo llegar', function (): void {
    $featuredCamp = Camp::factory()->featured()->create([
        'title' => 'Campamento Grazalema 2025',
        'year' => 2025,
    ]);
    CampPhoto::factory()->for($featuredCamp)->create();

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('Grupo Scout San Miguel');
    $response->assertSee('¿Quiénes somos?');
    $response->assertSee('Cómo llegar');
    $response->assertSee('Campamento Grazalema 2025');
    $response->assertSee('https://www.google.com/maps/dir/', false);
});

test('los visitantes pueden explorar la galería de campamentos y filtrar por año', function (): void {
    $camp2025 = Camp::factory()->create(['title' => 'Campamento Alcornocales 2025', 'year' => 2025]);
    $camp2024 = Camp::factory()->create(['title' => 'Campamento Cazorla 2024', 'year' => 2024]);

    $this->get(route('camps.index'))
        ->assertSuccessful()
        ->assertSee('Campamento Alcornocales 2025')
        ->assertSee('Campamento Cazorla 2024');

    $this->get(route('camps.index', ['year' => 2025]))
        ->assertSuccessful()
        ->assertSee('Campamento Alcornocales 2025')
        ->assertDontSee('Campamento Cazorla 2024');

    $this->get(route('camps.show', $camp2025))
        ->assertSuccessful()
        ->assertSee('Campamento Alcornocales 2025');
});

test('los visitantes pueden enviar un mensaje desde contacto', function (): void {
    $response = $this->post(route('contact.store'), [
        'name' => 'Ana Pérez',
        'email' => 'ana@example.com',
        'subject' => 'Información sobre Castores',
        'message' => 'Hola, nos gustaría saber cuándo empezar las reuniones en Algeciras.',
    ]);

    $response->assertRedirect(route('contact.create'));
    $this->assertDatabaseHas('contact_messages', [
        'email' => 'ana@example.com',
        'subject' => 'Información sobre Castores',
    ]);
});

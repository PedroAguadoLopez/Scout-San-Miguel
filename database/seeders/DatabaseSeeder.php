<?php

namespace Database\Seeders;

use App\Enums\FeeType;
use App\Enums\PaymentStatus;
use App\Enums\ScoutSection;
use App\Enums\UserRole;
use App\Models\CalendarEvent;
use App\Models\Camp;
use App\Models\CampPhoto;
use App\Models\Payment;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adult = User::updateOrCreate(
            ['username' => 'adulto_demo'],
            [
                'name' => 'María Rodríguez Gómez',
                'email' => 'maria.rodriguez@example.com',
                'birth_date' => '1986-04-18',
                'role' => UserRole::Adult,
                'scout_section' => ScoutSection::Lobatos,
                'phone' => '600123456',
                'password' => 'Scout1234!',
            ]
        );

        $otherAdult = User::updateOrCreate(
            ['username' => 'otro_adulto'],
            [
                'name' => 'Carlos Navarro Ruiz',
                'email' => 'carlos.navarro@example.com',
                'birth_date' => '1982-09-12',
                'role' => UserRole::Adult,
                'scout_section' => ScoutSection::Scouts,
                'phone' => '600987654',
                'password' => 'Scout1234!',
            ]
        );

        User::updateOrCreate(
            ['username' => 'menor_demo'],
            [
                'name' => 'Lucía Rodríguez Martín',
                'email' => 'lucia.menor@example.com',
                'birth_date' => '2013-06-22',
                'role' => UserRole::Minor,
                'scout_section' => ScoutSection::Scouts,
                'password' => 'Scout1234!',
            ]
        );

        User::updateOrCreate(
            ['username' => 'kraal_demo'],
            [
                'name' => 'Javier Morales Pérez',
                'email' => 'kraal@scoutsanmiguelalgeciras.org',
                'birth_date' => '1994-02-10',
                'role' => UserRole::Admin,
                'scout_section' => ScoutSection::Kraal,
                'password' => 'Scout1234!',
            ]
        );

        $this->seedCamps();
        $this->seedCalendarEvents();
        $this->seedPayments($adult, $otherAdult);
    }

    private function seedCamps(): void
    {
        if (Camp::query()->exists()) {
            return;
        }

        $camps = [
            [
                'title' => 'Campamento de Verano en Grazalema',
                'slug' => 'campamento-verano-grazalema-2025',
                'location' => 'Sierra de Grazalema, Cádiz',
                'year' => 2025,
                'start_date' => '2025-07-14',
                'end_date' => '2025-07-26',
                'summary' => 'Doce días de convivencia en plena sierra gaditana con rutas de montaña, construcciones y veladas bajo las estrellas.',
                'description' => "Nuestro campamento de verano 2025 reunió a todas las ramas del Grupo Scout San Miguel en el Parque Natural Sierra de Grazalema.\n\nDurante doce días realizamos marchas por etapas, talleres de cabuyería, cocina de campaña y nuestro tradicional fuego de campamento de despedida.",
                'cover_image' => 'images/camps/grazalema-1.svg',
                'is_featured' => true,
                'photos' => [
                    ['image_path' => 'images/camps/grazalema-1.svg', 'caption' => 'Zona de acampada principal al amanecer en la Sierra de Grazalema.'],
                    ['image_path' => 'images/camps/grazalema-2.svg', 'caption' => 'Velada nocturna y fuego de campamento con todo el grupo.'],
                ],
            ],
            [
                'title' => 'Acampada de Primavera en Los Alcornocales',
                'slug' => 'acampada-primavera-alcornocales-2025',
                'location' => 'Los Barrios · Parque de Los Alcornocales',
                'year' => 2025,
                'start_date' => '2025-04-11',
                'end_date' => '2025-04-13',
                'summary' => 'Fin de semana de senderismo y orientación muy cerca de Algeciras, recorriendo el entorno del Río de la Miel.',
                'description' => "Un fin de semana completo en nuestro parque natural vecino.\n\nLas ramas trabajaron orientación con brújula, respeto al bosque mediterráneo y dinámicas de patrulla y seisena.",
                'cover_image' => 'images/camps/alcornocales-1.svg',
                'is_featured' => true,
                'photos' => [
                    ['image_path' => 'images/camps/alcornocales-1.svg', 'caption' => 'Senda entre alcornoques rumbo al área recreativa.'],
                    ['image_path' => 'images/camps/alcornocales-2.svg', 'caption' => 'Parada junto al arroyo durante la ruta de la mañana.'],
                ],
            ],
            [
                'title' => 'Campamento de Verano Sierra de Cazorla',
                'slug' => 'campamento-verano-cazorla-2024',
                'location' => 'Sierra de Cazorla, Jaén',
                'year' => 2024,
                'start_date' => '2024-07-15',
                'end_date' => '2024-07-27',
                'summary' => 'Travesías de altura, juegos de ciudad y promesas scout en uno de los parajes más espectaculares de Andalucía.',
                'description' => "Viajamos hasta Jaén para vivir casi dos semanas inolvidables.\n\nEscultas y Rovers completaron su raid de tres días mientras Manada y Tropa disfrutaban de actividades acuáticas y veladas temáticas.",
                'cover_image' => 'images/camps/cazorla-1.svg',
                'is_featured' => true,
                'photos' => [
                    ['image_path' => 'images/camps/cazorla-1.svg', 'caption' => 'Vista del valle y campamento base en Cazorla.'],
                ],
            ],
            [
                'title' => 'Acampada de San Jorge en Algeciras',
                'slug' => 'acampada-san-jorge-algeciras-2024',
                'location' => 'Algeciras, Cádiz',
                'year' => 2024,
                'start_date' => '2024-04-20',
                'end_date' => '2024-04-21',
                'summary' => 'Encuentro festivo con familias y entrega de pañoletas junto a la Bahía de Algeciras.',
                'description' => 'Celebramos San Jorge con juegos de grupo, comida compartida con las familias y ceremonias de promesa.',
                'cover_image' => 'images/camps/sanjorge-1.svg',
                'is_featured' => false,
                'photos' => [
                    ['image_path' => 'images/camps/sanjorge-1.svg', 'caption' => 'Ceremonia de apertura con vistas a la Bahía de Algeciras.'],
                ],
            ],
        ];

        foreach ($camps as $campData) {
            $photos = $campData['photos'];
            unset($campData['photos']);

            $camp = Camp::create($campData);

            foreach ($photos as $index => $photo) {
                CampPhoto::create([
                    'camp_id' => $camp->id,
                    'image_path' => $photo['image_path'],
                    'caption' => $photo['caption'],
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }

    private function seedCalendarEvents(): void
    {
        if (CalendarEvent::query()->exists()) {
            return;
        }

        $now = CarbonImmutable::now();
        $firstSaturday = $now->startOfMonth()->next(CarbonImmutable::SATURDAY);
        $secondSaturday = $firstSaturday->addWeek();
        $thirdSaturday = $secondSaturday->addWeek();

        $events = [
            [
                'title' => 'Reunión ordinaria de sábado',
                'description' => 'Juegos por ramas, talleres de cabuyería y preparación de la próxima salida.',
                'location' => 'Local Scout San Miguel, Algeciras',
                'scout_section' => ScoutSection::Grupo,
                'event_type' => 'reunion',
                'starts_at' => $firstSaturday->setTime(16, 30),
                'ends_at' => $firstSaturday->setTime(19, 30),
                'all_day' => false,
            ],
            [
                'title' => 'Ruta por el Río de la Miel',
                'description' => 'Excursión de mañana para Lobatos y Scouts. Traer cantimplora, bocadillo y calzado de montaña.',
                'location' => 'Sendero Río de la Miel, Algeciras',
                'scout_section' => ScoutSection::Scouts,
                'event_type' => 'excursion',
                'starts_at' => $secondSaturday->setTime(9, 30),
                'ends_at' => $secondSaturday->setTime(17, 0),
                'all_day' => false,
            ],
            [
                'title' => 'Taller de Colonia y Manada',
                'description' => 'Manualidades y dinámicas de cooperación en el local.',
                'location' => 'Local Scout San Miguel, Algeciras',
                'scout_section' => ScoutSection::Lobatos,
                'event_type' => 'reunion',
                'starts_at' => $secondSaturday->setTime(16, 30),
                'ends_at' => $secondSaturday->setTime(19, 0),
                'all_day' => false,
            ],
            [
                'title' => 'Acampada de Otoño / Ronda',
                'description' => 'Acampada de fin de semana con todo el grupo en Los Alcornocales.',
                'location' => 'Área recreativa Los Alcornocales',
                'scout_section' => ScoutSection::Grupo,
                'event_type' => 'acampada',
                'starts_at' => $thirdSaturday->setTime(10, 0),
                'ends_at' => $thirdSaturday->addDay()->setTime(17, 30),
                'all_day' => false,
            ],
            [
                'title' => 'Reunión de familias e inicio de trimestre',
                'description' => 'Presentación de objetivos del trimestre y revisión de cuotas.',
                'location' => 'Salón Parroquial San Miguel, Algeciras',
                'scout_section' => ScoutSection::Kraal,
                'event_type' => 'reunion',
                'starts_at' => $now->addMonth()->startOfMonth()->next(CarbonImmutable::SATURDAY)->setTime(18, 0),
                'ends_at' => $now->addMonth()->startOfMonth()->next(CarbonImmutable::SATURDAY)->setTime(19, 30),
                'all_day' => false,
            ],
            [
                'title' => 'Campamento de Invierno / Navidad',
                'description' => 'Convivencia de fin de año con actividades de servicio y juegos nocturnos.',
                'location' => 'Albergue Juvenil Sierra de Cádiz',
                'scout_section' => ScoutSection::Grupo,
                'event_type' => 'campamento',
                'starts_at' => CarbonImmutable::create($now->year, 12, 26, 9, 0),
                'ends_at' => CarbonImmutable::create($now->year, 12, 29, 18, 0),
                'all_day' => true,
            ],
        ];

        foreach ($events as $event) {
            CalendarEvent::create($event);
        }
    }

    private function seedPayments(User $adult, User $otherAdult): void
    {
        if (Payment::query()->exists()) {
            return;
        }

        $now = CarbonImmutable::now();
        $monthLabel = ucfirst($now->translatedFormat('F Y'));
        $previousMonthLabel = ucfirst($now->subMonth()->translatedFormat('F Y'));
        $seasonLabel = 'Ronda '.$now->year.'/'.($now->year + 1);

        Payment::create([
            'user_id' => $adult->id,
            'concept' => 'Cuota anual de inscripción y seguro',
            'description' => 'Renovación anual federativa y seguro de accidentes.',
            'fee_type' => FeeType::Annual,
            'period_label' => $seasonLabel,
            'amount_cents' => 6500,
            'currency' => 'eur',
            'due_date' => $now->addDays(20)->toDateString(),
            'status' => PaymentStatus::Pending,
        ]);

        Payment::create([
            'user_id' => $adult->id,
            'concept' => 'Cuota mensual de actividades',
            'description' => 'Aportación mensual para material y mantenimiento del local.',
            'fee_type' => FeeType::Monthly,
            'period_label' => $monthLabel,
            'amount_cents' => 1500,
            'currency' => 'eur',
            'due_date' => $now->endOfMonth()->toDateString(),
            'status' => PaymentStatus::Pending,
        ]);

        Payment::create([
            'user_id' => $adult->id,
            'concept' => 'Cuota mensual de actividades',
            'description' => 'Aportación mensual de ronda.',
            'fee_type' => FeeType::Monthly,
            'period_label' => $previousMonthLabel,
            'amount_cents' => 1500,
            'currency' => 'eur',
            'due_date' => $now->subMonth()->endOfMonth()->toDateString(),
            'status' => PaymentStatus::Paid,
            'stripe_session_id' => 'cs_seed_demo_001',
            'stripe_payment_intent_id' => 'pi_seed_demo_001',
            'paid_at' => $now->subMonth()->addDays(5),
        ]);

        Payment::create([
            'user_id' => $otherAdult->id,
            'concept' => 'Cuota mensual privada de otro socio',
            'description' => 'No visible para otros usuarios.',
            'fee_type' => FeeType::Monthly,
            'period_label' => $monthLabel,
            'amount_cents' => 1500,
            'currency' => 'eur',
            'due_date' => $now->endOfMonth()->toDateString(),
            'status' => PaymentStatus::Pending,
        ]);
    }
}

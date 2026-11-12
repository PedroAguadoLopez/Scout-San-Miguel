<?php

return [
    'group_name' => 'Grupo Scout San Miguel',
    'city' => 'Algeciras, Cádiz',
    'address' => env('SCOUT_ADDRESS', 'Parroquia de San Miguel / Local Scout San Miguel, Barriada de San José Artesano, 11204 Algeciras, Cádiz'),
    'schedule' => 'Sábados de 16:30 a 19:30 h',
    'email' => env('SCOUT_CONTACT_EMAIL', 'contacto@scoutsanmiguelalgeciras.org'),
    'phone' => env('SCOUT_CONTACT_PHONE', '+34 956 000 000'),
    'maps_query' => env('SCOUT_MAPS_QUERY', 'Algeciras, Cádiz, España'),
    'maps_lat' => env('SCOUT_MAPS_LAT', '36.1408'),
    'maps_lng' => env('SCOUT_MAPS_LNG', '-5.4562'),
    'maps_directions_url' => env(
        'SCOUT_MAPS_DIRECTIONS_URL',
        'https://www.google.com/maps/dir/?api=1&destination=36.1408,-5.4562'
    ),
    'maps_embed_url' => env(
        'SCOUT_MAPS_EMBED_URL',
        'https://maps.google.com/maps?q=36.1408,-5.4562&z=15&output=embed'
    ),
];


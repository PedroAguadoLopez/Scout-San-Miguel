<?php

namespace App\Enums;

enum ScoutSection: string
{
    case Grupo = 'grupo';
    case Castores = 'castores';
    case Lobatos = 'lobatos';
    case Scouts = 'scouts';
    case Escultas = 'escultas';
    case Rovers = 'rovers';
    case Kraal = 'kraal';

    public function label(): string
    {
        return match ($this) {
            self::Grupo => 'Todo el Grupo',
            self::Castores => 'Colonia (Castores)',
            self::Lobatos => 'Manada (Lobatos)',
            self::Scouts => 'Tropa (Scouts)',
            self::Escultas => 'Unidad (Escultas)',
            self::Rovers => 'Clan (Rovers)',
            self::Kraal => 'Kraal y Familias',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Grupo => 'text-bg-success',
            self::Castores => 'text-bg-info',
            self::Lobatos => 'text-bg-warning',
            self::Scouts => 'text-bg-primary',
            self::Escultas => 'text-bg-danger',
            self::Rovers => 'text-bg-dark',
            self::Kraal => 'text-bg-secondary',
        };
    }
}

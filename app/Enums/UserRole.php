<?php

namespace App\Enums;

enum UserRole: string
{
    case Minor = 'minor';
    case Adult = 'adult';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Minor => 'Menor de edad',
            self::Adult => 'Mayor de edad',
            self::Admin => 'Kraal / Responsable',
        };
    }

    public function canPay(): bool
    {
        return match ($this) {
            self::Adult, self::Admin => true,
            self::Minor => false,
        };
    }
}

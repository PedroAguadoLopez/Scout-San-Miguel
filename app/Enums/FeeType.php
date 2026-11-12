<?php

namespace App\Enums;

enum FeeType: string
{
    case Monthly = 'monthly';
    case Annual = 'annual';

    public function label(): string
    {
        return match ($this) {
            self::Monthly => 'Cuota mensual',
            self::Annual => 'Cuota anual',
        };
    }
}

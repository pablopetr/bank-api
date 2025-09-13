<?php

namespace App\Enums;

enum AccountStatus: string
{
    case Active = 'Active';
    case Inactive = 'Inactive';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}

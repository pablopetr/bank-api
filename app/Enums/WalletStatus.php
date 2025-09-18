<?php

namespace App\Enums;

enum WalletStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}

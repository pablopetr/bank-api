<?php

namespace App\Enums;

enum WalletType: string
{
    case Default = 'Default';
    case Wallet = 'Wallet';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}

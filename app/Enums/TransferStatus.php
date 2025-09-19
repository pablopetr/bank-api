<?php

namespace App\Enums;

enum TransferStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}

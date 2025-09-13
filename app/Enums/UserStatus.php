<?php

namespace App\Enums;

enum UserStatus: string
{
    case WaitingForApproval = 'WaitingForApproval';
    case Approved = 'Approved';
    case Rejected = 'Rejected';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}

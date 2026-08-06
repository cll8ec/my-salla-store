<?php

declare(strict_types=1);

namespace App\Domains\Identity\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Banned = 'banned';
    case PendingVerification = 'pending_verification';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Banned => 'Banned',
            self::PendingVerification => 'Pending Verification',
        };
    }

    public function canLogin(): bool
    {
        return $this === self::Active;
    }
}

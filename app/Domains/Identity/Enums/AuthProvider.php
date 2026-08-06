<?php

declare(strict_types=1);

namespace App\Domains\Identity\Enums;

enum AuthProvider: string
{
    case Email = 'email';
    case Phone = 'phone';
    case Google = 'google';
    case Apple = 'apple';
    case Salla = 'salla';

    public function label(): string
    {
        return match ($this) {
            self::Email => 'Email & Password',
            self::Phone => 'Phone OTP',
            self::Google => 'Google',
            self::Apple => 'Apple',
            self::Salla => 'Salla OAuth',
        };
    }

    public function requiresPassword(): bool
    {
        return $this === self::Email;
    }
}

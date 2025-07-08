<?php

declare(strict_types=1);

namespace MXRVX\Telegram\Bot\Entities;

enum UserStatus: string
{
    case Creator = 'creator';
    case Administrator = 'administrator';
    case Member = 'member';
    case Restricted = 'restricted';
    case Left = 'left';
    case Kicked = 'kicked';
    case Unknown = 'unknown';

    public static function make(?string $value): self
    {
        if ($value === null) {
            return self::Unknown;
        }

        try {
            return self::from($value);
        } catch (\ValueError $e) {
        }

        return self::Unknown;
    }

    public static function typecast(string $value): self
    {
        return self::make($value);
    }
}

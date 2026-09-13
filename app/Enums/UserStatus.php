<?php

namespace App\Enums;

enum UserStatus: int
{
    case Active = 1;
    case Suspended = 2;

    public function label(): string
    {
        return match ($this) {
            self::Active => '有効',
            self::Suspended => '停止',
        };
    }
}

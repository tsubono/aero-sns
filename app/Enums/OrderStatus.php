<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Completed = 1;

    public function label(): string
    {
        return match ($this) {
            self::Completed => '完了',
        };
    }
}

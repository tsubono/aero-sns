<?php

namespace App\Enums;

enum PointHistoryType: int
{
    case Charge = 1;
    case Use = 2;
    case Refund = 3;

    public function label(): string
    {
        return match ($this) {
            self::Charge => 'チャージ',
            self::Use => '購入',
            self::Refund => '返却',
        };
    }
}

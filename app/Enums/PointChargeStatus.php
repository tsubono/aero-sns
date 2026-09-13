<?php

namespace App\Enums;

enum PointChargeStatus: int
{
    case Processing = 1;
    case Completed = 2;
    case Failed = 3;

    public function label(): string
    {
        return match ($this) {
            self::Processing => '処理中',
            self::Completed => '完了',
            self::Failed => '失敗',
        };
    }
}

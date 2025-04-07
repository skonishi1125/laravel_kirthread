<?php

namespace App\Enums\Rpg;

enum HealType: int
{
    case NoType = 0;
    case Hp = 1;
    case Ap = 2;

    public function label(): string
    {
        return match ($this) {
            self::NoType => '分類なし', // 現状該当するものが無いので不要かも。
            self::Hp => 'HP回復系',
            self::Ap => 'AP回復系',
        };
    }
}

<?php

namespace App\Enums\Rpg;

enum EffectType: int
{
    case Special = 0;
    case Damage = 1;
    case Heal = 2;
    case Buff = 3;

    public function label(): string
    {
        return match ($this) {
            self::Special => '特殊系',
            self::Damage => '攻撃系',
            self::Heal => '回復系',
            self::Buff => 'バフ系',
        };
    }
}

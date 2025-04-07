<?php

namespace App\Enums\Rpg;

enum AttackType: int
{
    case NoType = 0;
    case Physical = 1;
    case Magic = 2;

    public function label(): string
    {
        return match ($this) {
            self::NoType => '分類なし',
            self::Physical => '物理攻撃',
            self::Magic => '魔法攻撃',
        };
    }
}

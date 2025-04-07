<?php

namespace App\Enums\Rpg;

enum AfterCleared
{
    case RecoveryHp;
    case RecoveryAp;
    case ResurrectionHp;
    case ResurrectionAp;

    public function Multiplier(): float
    {
        return match ($this) {
            self::RecoveryHp => 0.20,
            self::RecoveryAp => 0.10,
            self::ResurrectionHp => 0.10,
            self::ResurrectionAp => 0.05,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::RecoveryHp => '戦闘後に回復させるHPの倍率',
            self::RecoveryAp => '戦闘後に回復させるAPの倍率',
            self::ResurrectionHp => '戦闘不能後、回復させるHPの倍率',
            self::ResurrectionAp => '戦闘不能後、回復させるAPの倍率',
        };
    }
}

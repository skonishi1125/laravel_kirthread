<?php

namespace App\Enums\Rpg;

enum TargetRange: int
{
    case Self = 0;
    case Single = 1;
    case All = 2;

    public function label(): string
    {
        return match ($this) {
            self::Self => '自身を対象',
            self::Single => '単体を対象',
            self::All => '全体を対象',
        };
    }
}

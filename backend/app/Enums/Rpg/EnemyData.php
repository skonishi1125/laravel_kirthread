<?php

namespace App\Enums\Rpg;

enum EnemyData: int
{
    case Srara = 10;
    case Gao = 11;
    case Norawani = 12;
    case Ikkaku = 13;
    case Oyadamawani = 14;

    case HighSrara = 20;

    case FlareDrago = 30;

    public function label(): string
    {
        return match ($this) {
            self::Srara => 'スララ',
            self::Gao => 'ガオー',
            self::Norawani => 'ノラワニ',
            self::Ikkaku => 'イッカク',
            self::Oyadamawani => 'オヤダマワニ',

            self::HighSrara => 'ハイスララ',

            self::FlareDrago => 'フレアドラゴ',

        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Srara => '冒険者の前に立ちはだかる最初の敵。油断禁物！',
            self::Gao => '素早い動きが特徴。攻撃力が高めなので早めに倒そう。',
            self::Norawani => '草むらを彷徨いている野良のワニ。バランスの良いステータスが特徴。',
            self::Ikkaku => '頑丈な甲殻を持つ。立派なツノから呼ばれる雷攻撃には要注意。',
            self::Oyadamawani => 'ノラワニ達を仕切る強力なワニ。鋭い歯を持ち、噛まれたらひとたまりもない',

            self::HighSrara => 'スララの変異体。バランスの良いパラメータを持つ。',

            self::FlareDrago => '非常に知能の高い龍で、狡猾な手口を使い冒険者を餌とする。',
        };
    }

    public function image_path(): string
    {
        return match ($this) {
            self::Srara => 'srara.png',
            self::Gao => 'gao.png',
            self::Norawani => 'norawani.png',
            self::Ikkaku => 'ikkaku.png',
            self::Oyadamawani => 'oyadamawani.png',

            self::HighSrara => 'highsrara.png',

            self::FlareDrago => 'flaredrago.png',
        };
    }
}

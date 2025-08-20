<?php

namespace App\Enums\Rpg;

enum EnemyData: int
{
    case Srara = 10;
    case Gao = 11;
    case BigSrara = 12;

    case Rizard = 20;
    case Scorpio = 21;
    case RockRizard = 22;

    case Norawani = 30;
    case Oyadamawani = 31;

    case MageSrara = 40;
    case Clion = 41;
    case Ikkaku = 42;
    case SpikeWhale = 43;

    case HighSrara = 50;

    case FlareDrago = 60;

    public function label(): string
    {
        return match ($this) {
            self::Srara => 'スララ',
            self::Gao => 'ガオー',
            self::BigSrara => 'ビッグスララ',

            self::Rizard => 'リザード',
            self::Scorpio => 'スコーピオ',
            self::RockRizard => 'ロックリザード',

            self::Norawani => 'ノラワニ',
            self::Oyadamawani => 'オヤダマワニ',

            self::MageSrara => 'メイジスララ',
            self::Clion => 'クリオン',
            self::Ikkaku => 'イッカク',
            self::SpikeWhale => 'スパイクホエール',

            self::HighSrara => 'ハイスララ',

            self::FlareDrago => 'フレアドラゴ',

        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Srara => '冒険者の前に立ちはだかる最初の敵。油断禁物！',
            self::Gao => '素早い動きが特徴。攻撃力が高めなので早めに倒そう。',
            self::BigSrara => 'スララの合成体。相変わらず鈍重だが生命力は侮れない。',

            self::Rizard => '',
            self::Scorpio => '',
            self::RockRizard => '',

            self::Norawani => '草むらを彷徨いている野良のワニ。バランスの良いステータスが特徴。',
            self::Oyadamawani => 'ノラワニ達を仕切る強力なワニ。鋭い歯を持ち、噛まれたらひとたまりもない',

            self::MageSrara => 'メイジスララ',
            self::Clion => 'クリオン',
            self::Ikkaku => '頑丈な甲殻を持つ。立派なツノから呼ばれる雷攻撃には要注意。',
            self::SpikeWhale => 'スパイクホエール',

            self::HighSrara => 'スララの変異体。バランスの良いパラメータを持つ。',

            self::FlareDrago => '非常に知能の高い龍で、狡猾な手口を使い冒険者を餌とする。',
        };
    }

    public function image_path(): string
    {
        return match ($this) {
            self::Srara => 'srara.png',
            self::Gao => 'gao.png',
            self::BigSrara => 'bigsrara.png',

            self::Rizard => 'rizard.png',
            self::Scorpio => 'scorpio.png',
            self::RockRizard => 'rockrizard.png',

            self::Norawani => 'norawani.png',
            self::Oyadamawani => 'oyadamawani.png',

            self::MageSrara => 'magesrara.png',
            self::Clion => 'clion.png',
            self::Ikkaku => 'ikkaku.png',
            self::SpikeWhale => 'spikewhale.png',

            self::HighSrara => 'highsrara.png',

            self::FlareDrago => 'flaredrago.png',
        };
    }

    public static function grasslandAppearingEnemies(): array
    {
        return [
            self::Srara->value,
            self::Gao->value,
            self::BigSrara->value,
        ];
    }
}

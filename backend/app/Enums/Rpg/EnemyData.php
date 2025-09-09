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

    case Bou = 30;
    case IwaMet = 31;
    case Norawani = 32;
    case MagmaDile = 33;

    case MageSrara = 40;
    case Clion = 41;
    case Ikkaku = 42;
    case SpikeWhale = 43;

    case Eripen = 50;
    case IceFairy = 51;
    case ScissorFlipper = 52;

    case Nepenthos = 60;
    case Dionaea = 61;
    case HazardBerry = 62;
    case WandEater = 63;

    case HoshiHotaru = 70;
    case Gyao = 71;
    case ShadowWeed = 72;
    case Twilight = 73;

    case HighSrara = 900;

    case FlareDrago = 901;

    public function label(): string
    {
        return match ($this) {
            self::Srara => 'スララ',
            self::Gao => 'ガオー',
            self::BigSrara => 'ビッグスララ',

            self::Rizard => 'リザード',
            self::Scorpio => 'スコーピオ',
            self::RockRizard => 'ロックリザード',

            self::Bou => 'ボウ',
            self::IwaMet => 'イワメット',
            self::Norawani => 'ノラワニ',
            self::MagmaDile => 'マグマダイル',

            self::MageSrara => 'メイジスララ',
            self::Clion => 'クリオン',
            self::Ikkaku => 'イッカク',
            self::SpikeWhale => 'スパイクホエール',

            self::Eripen => 'エリペン',
            self::IceFairy => 'アイスフェアリー',
            self::ScissorFlipper => 'シザーフリッパー',

            self::Nepenthos => 'ネペントス',
            self::Dionaea => 'ディオネア',
            self::HazardBerry => 'ハザードベリー',
            self::WandEater => 'ワンドイーター',

            self::HoshiHotaru => 'ホシホタル',
            self::Gyao => 'ギャオー',
            self::ShadowWeed => 'シャドウウィード',
            self::Twilight => 'トワイライト',

            self::HighSrara => 'ハイスララ',

            self::FlareDrago => 'フレアドラゴ',

        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Srara => '冒険者の前に立ちはだかる最初の敵。油断禁物！',
            self::Gao => '素早い動きが特徴。攻撃力が高いため、優先的に倒そう。',
            self::BigSrara => 'スララが偶然合体した生物。消化液で防御力を落としてくるのがいやらしい。',

            self::Rizard => 'カラッとしたところもジメジメしたところも好きなトカゲ。防御力には自信あり。',
            self::Scorpio => '致命の一撃を得意とするサソリ。魔法に弱いので、早めに倒してしまおう。',
            self::RockRizard => '頑丈な皮膚を持つトカゲの大将。魔法攻撃で攻めよう。',

            self::Bou => '炎のマナが集まってできた生命体。集団で出てくるため、全体攻撃でさっさと倒そう。',
            self::IwaMet => '防御が自慢のカメ。魔法での攻撃が有効。',
            self::Norawani => '火山を彷徨く野良のワニ。生命力が高くバランスの良いステータスが特徴。',
            self::MagmaDile => 'ノラワニ達を仕切るボス。咆哮で攻撃力を上げてから、最後に暴れ回る。',

            self::MageSrara => 'スララの変異体。冒険者の杖を振り回して簡易的な魔法を使う。',
            self::Clion => 'ふよふよと浮遊する生命体。吐き出す泡は強力なので、吐かれる前にさっさと倒してしまおう。',
            self::Ikkaku => '頑丈な甲殻を持つ。立派なツノから呼ばれる雷攻撃には要注意。',
            self::SpikeWhale => '海岸のボス。特定のタイミングで大波を呼んでくるので、タイミングを覚えて身構えよう。',

            self::Eripen => 'エリペン',
            self::IceFairy => 'アイスフェアリー',
            self::ScissorFlipper => 'シザーフリッパー',

            self::Nepenthos => 'ネペントス',
            self::Dionaea => 'ディオネア',
            self::HazardBerry => 'ハザードベリー',
            self::WandEater => 'ワンドイーター',

            self::HoshiHotaru => 'ホシホタル',
            self::Gyao => 'ギャオー',
            self::ShadowWeed => 'シャドウウィード',
            self::Twilight => 'トワイライト',

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

            self::Bou => 'bou.png',
            self::IwaMet => 'iwamet.png',
            self::Norawani => 'norawani.png',
            self::MagmaDile => 'MagmaDile.png',

            self::MageSrara => 'magesrara.png',
            self::Clion => 'clion.png',
            self::Ikkaku => 'ikkaku.png',
            self::SpikeWhale => 'spikewhale.png',

            self::Eripen => 'eripen.png',
            self::IceFairy => 'icefairy.png',
            self::ScissorFlipper => 'scissorflipper.png',

            self::Nepenthos => 'nepenthos.png',
            self::Dionaea => 'dionaea.png',
            self::HazardBerry => 'hazardberry.png',
            self::WandEater => 'wandeater.png',

            self::HoshiHotaru => 'hoshihotaru.png',
            self::Gyao => 'gyao.png',
            self::ShadowWeed => 'shadowweed.png',
            self::Twilight => 'twilight.png',

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

    public static function desertAppearingEnemies(): array
    {
        return [
            self::Gao->value,
            self::Rizard->value,
            self::Scorpio->value,
            self::RockRizard->value,
        ];
    }

    public static function volcanoAppearingEnemies(): array
    {
        return [
            self::Bou->value,
            self::IwaMet->value,
            self::Norawani->value,
            self::MagmaDile->value,
        ];
    }

    public static function coastAppearingEnemies(): array
    {
        return [
            self::MageSrara->value,
            self::Clion->value,
            self::Ikkaku->value,
            self::SpikeWhale->value,
        ];
    }

    public static function iceAndSnowAppearingEnemies(): array
    {
        return [
            self::Ikkaku->value,
            self::Eripen->value,
            self::IceFairy->value,
            self::ScissorFlipper->value,
        ];
    }

    public static function wetFogAppearingEnemies(): array
    {
        return [
            self::Rizard->value,
            self::Nepenthos->value,
            self::Dionaea->value,
            self::HazardBerry->value,
            self::WandEater->value,
        ];
    }

    public static function nightForestAppearingEnemies(): array
    {
        return [
            self::HoshiHotaru->value,
            self::Gyao->value,
            self::ShadowWeed->value,
            self::Twilight->value,
        ];
    }

    public static function decayedFarmlandAppearingEnemies(): array
    {
        return [
            self::HoshiHotaru->value,
        ];
    }

    public static function castleTownAppearingEnemies(): array
    {
        return [
            self::Gyao->value,
        ];
    }
}

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

    case CurseScareCrow = 80;
    case DustBomb = 81;
    case ZombieClion = 82;
    case Narehate = 83;

    case DarkSrara = 90;
    case BazaarRizard = 91;
    case Anima = 92;
    case GolemBall = 94;
    case StoneGolem = 95;

    case GaiaHand = 100;
    case DeathScorpio = 101;
    case MetalGecko = 102;
    case Stinger = 103;
    case PlasmaBook = 104;

    case Celavie = 110;
    case GrandCube = 111;

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

            self::CurseScareCrow => '呪いのカカシ',
            self::DustBomb => 'ダストボム',
            self::ZombieClion => 'ゾンビクリオン',
            self::Narehate => 'ナレハテ',

            self::DarkSrara => 'ダークスララ',
            self::BazaarRizard => 'バザールリザード',
            self::Anima => 'アニマ',
            self::GolemBall => 'ゴーレムボール',
            self::StoneGolem => 'ストーンゴーレム',

            self::GaiaHand => 'ガイアハンド',
            self::DeathScorpio => 'デススコーピオ',
            self::MetalGecko => 'メタルゲッコー',
            self::Stinger => 'スティンガー',
            self::PlasmaBook => 'プラズマブック',

            self::Celavie => 'セラヴィ',
            self::GrandCube => 'グランドキューブ',

            self::HighSrara => 'ハイスララ',

            self::FlareDrago => 'フレアドラゴ',

        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Srara => '冒険者の前に立ちはだかる最初の敵。油断禁物！',
            self::Gao => '素早い動きが特徴。攻撃力が高いため優先的に倒そう。',
            self::BigSrara => 'スララが偶然ぶつかり合体した生命体。消化液でDEFを落としてくるのがいやらしい。',

            self::Rizard => 'カラッとしたところもジメジメしたところも好きなトカゲ。防御力には自信あり。',
            self::Scorpio => '致命の一撃を得意とするサソリ。魔法耐性が非常に低いので魔法で攻めよう。',
            self::RockRizard => '頑丈な皮膚を持つトカゲの大将。魔法攻撃で攻めよう。',

            self::Bou => '炎魔法の残り香が集まってできた生命体。集団で出てきてファイアの魔法で攻め立てられる。物理防御力が低いので全体攻撃で攻めると吉。',
            self::IwaMet => '防御が自慢のカメ（？）。魔法での攻撃が有効。',
            self::Norawani => '火山を彷徨く野良のワニ（おそらく）。バランスの良いステータスが特徴。必ず戦闘前に咆哮を上げてステータスを高める。',
            self::MagmaDile => 'ノラワニ達を仕切るボス。必ず戦闘前に咆哮を上げてステータスを高める。咆哮がおさまったのちしばらくすると噛みつきそして強烈に暴れ回るのでタイミングを見て防御を選択できると戦いやすいだろう。暴れ回ったのちは再び咆哮で士気を高める。',

            self::MageSrara => 'スララの変異体。冒険者の落とした杖を振り回して簡易的な魔法を覚えた。',
            self::Clion => 'ふよふよと浮遊する生命体。鈍足だが吐き出す泡は強力なので、使われる前に各個撃破しよう。',
            self::Ikkaku => '立派なツノから呼ばれる雷魔法は単体攻撃、全体攻撃の2種類があるため注意。ツルツルした見た目だが、ガラスのように脆い。',
            self::SpikeWhale => '海岸のボス。一定のタイミングで大波を呼んでくるので、タイミングを覚えて身構えよう。',

            self::Eripen => '首回りの氷が襟に見えることからこの名前をつけられた。準備をしたのち、滑って突進してくるので身構えること。',
            self::IceFairy => '冷たい大気が魔法の残り香によって生命体となった。見た目に反して打たれ強くすばしっこい。全体攻撃を得意とする。',
            self::ScissorFlipper => 'エリペンの群れを連ねるリーダー。エリペン種の強烈な突進が鋭い刃物のような腕を持ったことでより強烈な一撃となった。同じく、準備をしたのちに突進をしてくる。',

            self::Nepenthos => 'ネペントス',
            self::Dionaea => 'ディオネア',
            self::HazardBerry => 'ハザードベリー',
            self::WandEater => 'ワンドイーター',

            self::HoshiHotaru => 'ホシホタル',
            self::Gyao => 'ギャオー',
            self::ShadowWeed => 'シャドウウィード',
            self::Twilight => 'トワイライト',

            self::CurseScareCrow => '呪いのカカシ',
            self::DustBomb => 'ダストボム',
            self::ZombieClion => 'ゾンビクリオン',
            self::Narehate => 'ナレハテ',

            self::DarkSrara => 'ダークスララ',
            self::BazaarRizard => 'バザールリザード',
            self::Anima => 'アニマ',
            self::GolemBall => 'ゴーレムボール',
            self::StoneGolem => 'ストーンゴーレム',

            self::GaiaHand => 'ガイアハンド',
            self::DeathScorpio => 'デススコーピオ',
            self::MetalGecko => 'メタルゲッコー',
            self::Stinger => 'スティンガー',
            self::PlasmaBook => 'プラズマブック',

            self::Celavie => 'セラヴィ',
            self::GrandCube => 'グランドキューブ',

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

            self::CurseScareCrow => 'cursescarecrow.png',
            self::DustBomb => 'dustbomb.png',
            self::ZombieClion => 'zombieclion.png',
            self::Narehate => 'narehate.png',

            self::DarkSrara => 'darksrara.png',
            self::BazaarRizard => 'bazaarrizard.png',
            self::Anima => 'anima.png',
            self::GolemBall => 'golemball.png',
            self::StoneGolem => 'stonegolem.png',

            self::GaiaHand => 'gaiahand.png',
            self::DeathScorpio => 'deathscorpio.png',
            self::MetalGecko => 'metalgecko.png',
            self::Stinger => 'stinger.png',
            self::PlasmaBook => 'plasmabook.png',

            self::Celavie => 'celavie.png',
            self::GrandCube => 'grandcube.png',

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
            self::Srara->value,
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
            self::IwaMet->value,
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
            self::CurseScareCrow->value,
            self::DustBomb->value,
            self::ZombieClion->value,
            self::Narehate->value,
        ];
    }

    public static function castleTownAppearingEnemies(): array
    {
        return [
            self::DarkSrara->value,
            self::BazaarRizard->value,
            self::Anima->value,
            self::GolemBall->value,
            self::StoneGolem->value,
        ];
    }

    public static function ancientCastleAppearingEnemies(): array
    {
        return [
            self::GaiaHand->value,
            self::DeathScorpio->value,
            self::MetalGecko->value,
            self::Stinger->value,
            self::PlasmaBook->value,
        ];
    }

    public static function vastExpanseAppearingEnemies(): array
    {
        return [
            self::Celavie->value,
            self::GrandCube->value,
        ];
    }
}

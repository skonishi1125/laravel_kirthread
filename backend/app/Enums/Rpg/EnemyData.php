<?php

namespace App\Enums\Rpg;

enum EnemyData: int
{
    case Srara = 10;
    case Gao = 11;
    case BigSrara = 12;

    case Lizard = 20;
    case Scorpio = 21;
    case RockLizard = 22;

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
    case BazaarLizard = 91;
    case Anima = 92;
    case WitherNepenthos = 93;
    case PotDio = 94;
    case GolemBall = 95;
    case Eliminator = 96;
    case StoneGolem = 97;

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

            self::Lizard => 'リザード',
            self::Scorpio => 'スコーピオ',
            self::RockLizard => 'ロックリザード',

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
            self::ZombieClion => 'ゾンビクリオン',
            self::Narehate => 'ナレハテ',

            self::BazaarLizard => 'バザールリザード',
            self::DustBomb => 'ダストボム',
            self::WitherNepenthos => '枯れたネペントス',
            self::PotDio => 'ポットディオ',
            self::GolemBall => 'ゴーレムボール',
            self::Eliminator => 'エリミネーター',
            self::StoneGolem => 'ストーンゴーレム',

            self::DarkSrara => 'ダークスララ',
            self::Anima => 'アニマ',
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

            self::Lizard => 'カラッとしたところもジメジメしたところも好きなトカゲ。防御力には自信あり。',
            self::Scorpio => '致命の一撃を得意とするサソリ。魔法耐性が非常に低いので魔法で攻めよう。',
            self::RockLizard => '頑丈な皮膚を持つトカゲの大将。魔法攻撃で攻めよう。',

            self::Bou => '炎魔法の残り香が集まってできた生命体。集団で出てきてファイアの魔法で攻め立てられる。物理防御力が低いので全体攻撃で攻めると吉。',
            self::IwaMet => '防御が自慢のカメ（？）。魔法での攻撃が有効。',
            self::Norawani => 'そこらを彷徨く野良のワニ（おそらく）。バランスの良いステータスが特徴。必ず戦闘前に咆哮を上げてステータスを高める。',
            self::MagmaDile => 'ノラワニ達を仕切るボス。必ず戦闘前に咆哮を上げてステータスを高める。咆哮がおさまったのちしばらくすると噛みつきそして強烈に暴れ回る。噛みつきのタイミングで防御を選択できると戦いやすいだろう。',

            self::MageSrara => 'スララの変異体。冒険者の落とした杖を振り回して簡易的な魔法を覚えた。',
            self::Clion => 'ふよふよと浮遊する生命体。鈍足だが吐き出す泡は強力なので、使われる前に各個撃破しよう。',
            self::Ikkaku => '立派なツノから呼ばれる雷魔法は単体攻撃、全体攻撃の2種類があるため注意。ツルツルした見た目だが、ガラスのように脆い。',
            self::SpikeWhale => '海岸のボス。一定のタイミングで大波を呼んでくるので、タイミングを覚えて身構えよう。',

            self::Eripen => '首回りの氷が襟に見えることからこの名前をつけられた。準備をしたのち、滑って突進してくるので身構えること。',
            self::IceFairy => '冷たい大気と魔法の残り香が混ざり合ってできた物体。見た目に反して打たれ強くすばしっこい。あられを飛ばす全体攻撃を得意とする。APが少なく2回しか撃つことができないため、防御や弱体スキルでやり過ごしてから迎撃しよう。',
            self::ScissorFlipper => 'エリペンの群れを連ねるリーダー。エリペン種の習性である突進攻撃が鋭い刃物のような腕を持ったことでより殺傷性を増した一撃となった。同じく準備をしたのちに突進してくるため身構えること。',

            self::Nepenthos => '沼地に住み着いているウツボカズラ。獲物に消化液を吐きかけ、捕食を試みる。',
            self::Dionaea => '沼地に住む植物。人間・魔物問わず獲物を挟んで消化する。獲物の攻撃を弱める手段を得意とし、抵抗できなくなったところを捕食する。',
            self::HazardBerry => '自身を魔物にかじらせて回復させる果実の魔物。かじってもらう理由は種の分布を広げるため。人間が食べてもおいしくないので、優先的に倒してしまおう。',
            self::WandEater => '古くから沼地に住み着く大型の魔物。魔物の中でも顕著に人間に襲いかかる。様々な弱体効果を持つ技を操り獲物の自由を奪ったのち、強靭なツルのムチで痛い打撃を叩き込む。',

            self::HoshiHotaru => '夜の森に生息するホタルで、幻想的な光は時に冒険者を惑わせる。SPDを下げる攻撃魔法を使う。',
            self::Gyao => '常夜の森に棲み付くガオーが変化した姿。ガオーと違って魔法攻撃が得意。変わらず素早く火力も高いので優先的に倒そう。',
            self::ShadowWeed => '草木に擬態した影が本体の魔物。光が基本的に存在しない常夜の森ではその姿を捉えることは難しいが、ホシホタルだけは例外のため天敵。物理的な攻撃を得意としており、魔法への耐性も極めて高い。強力な全体攻撃は一度しか使えないので、やり過ごせば戦いやすいだろう。',
            self::Twilight => '常夜の森のヌシ。豊富な魔法攻撃を使用して冒険者を苦しめる。性格自体はおっとりとしているようだ。巨大だがその姿を表すことは滅多になく、かつては幸運の象徴だったとも言われている。',

            self::CurseScareCrow => '呪いのカカシ',
            self::ZombieClion => 'ゾンビクリオン',
            self::Narehate => 'ナレハテ',

            self::BazaarLizard => '商魂逞しいトカゲの魔物。物理攻撃に耐性があり、アイテムを取り出して使ってくる。アイテムの手持ちはそれぞれ1つだけのため使ったらそれっきり。',
            self::DustBomb => 'ごみ袋のような魔物。ターン数を重ねるごとにぶくぶくと膨れ上がっていき、大爆発を起こす。早めに倒してしまうか、もしくは爆発のタイミングで防御しよう。尖ったもので袋に穴が空いているように見えるが、身体の一部らしく問題なく膨らむことができるようだ。',
            self::WitherNepenthos => '枯れたネペントス',
            self::PotDio => 'ポットディオ',
            self::GolemBall => 'ゴーレムボール',
            self::Eliminator => 'エリミネーター',
            self::StoneGolem => 'ストーンゴーレム',

            self::DarkSrara => 'ダークスララ',
            self::Anima => 'アニマ',
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

            self::Lizard => 'lizard.png',
            self::Scorpio => 'scorpio.png',
            self::RockLizard => 'rocklizard.png',

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
            self::BazaarLizard => 'bazaarlizard.png',
            self::Anima => 'anima.png',
            self::WitherNepenthos => 'withernepenthos.png',
            self::PotDio => 'potdio.png',
            self::GolemBall => 'golemball.png',
            self::Eliminator => 'eliminator.png',
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
            self::Lizard->value,
            self::Scorpio->value,
            self::RockLizard->value,
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
            self::Lizard->value,
            self::Norawani->value,
            self::Nepenthos->value,
            self::Dionaea->value,
            self::HazardBerry->value,
            self::WandEater->value,
        ];
    }

    public static function nightForestAppearingEnemies(): array
    {
        return [
            self::Srara->value,
            self::Gao->value,
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
            self::ZombieClion->value,
            self::Narehate->value,
        ];
    }

    public static function castleTownAppearingEnemies(): array
    {
        return [
            self::BazaarLizard->value,
            self::DustBomb->value,
            self::HazardBerry->value,
            self::WitherNepenthos->value,
            self::PotDio->value,
            self::GolemBall->value,
            self::Eliminator->value,
            self::StoneGolem->value,
        ];
    }

    public static function ancientCastleAppearingEnemies(): array
    {
        return [
            self::DarkSrara->value,
            self::Anima->value,
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

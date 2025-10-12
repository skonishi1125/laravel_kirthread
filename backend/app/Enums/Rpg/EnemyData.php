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
    case ZombieClion = 81;
    case Narehate = 82;

    case DustBomb = 90;
    case BazaarLizard = 91;
    case WitherNepenthos = 92;
    case PotDio = 93;
    case GolemBall = 94;
    case Eliminator = 95;
    case StoneGolem = 96;

    case DarkSrara = 100;
    case Anima = 101;
    case GaiaHand = 102;
    case DeathScorpio = 103;
    case MetalGecko = 104;
    case Stinger = 105;
    case PlasmaBook = 106;
    case FlareDrago = 107;

    case TraitorLordOfDragon = 110;

    case Celavie = 120;
    case GrandCube = 121;
    case OriginSlum = 122;
    case OriginGwappa = 123;
    case HollowHero = 124;

    case HighSrara = 900;

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
            self::FlareDrago => 'フレアドラゴ',

            self::TraitorLordOfDragon => '龍祀の叛国卿',

            self::Celavie => 'セラヴィ',
            self::GrandCube => 'グランドキューブ',
            self::OriginSlum => 'オリジンスルム',
            self::OriginGwappa => 'オリジングワッパ',
            self::HollowHero => '黙滅ノ幽者',

            self::HighSrara => 'ハイスララ',

        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Srara => '冒険者の前に立ちはだかる最初の敵。油断禁物！',
            self::Gao => '素早い動きが特徴。攻撃力が高いため優先的に倒そう。',
            self::BigSrara => 'スララが偶然ぶつかり合体した生命体。消化液でDEFを落としてくるのがいやらしい。',

            self::Lizard => 'カラッとしたところもジメジメしたところも好きなトカゲ。物理耐性に自信あり。',
            self::Scorpio => '致命の一撃を得意とするサソリ。魔法耐性が非常に低いので魔法で攻めよう。',
            self::RockLizard => '頑丈な皮膚を持つトカゲの大将。魔法攻撃で攻めよう。',

            self::Bou => '炎魔法の残り香が集まってできた生命体。集団で出てきてファイアの魔法で攻め立てられる。物理攻撃に弱く、全体攻撃で攻めることができると楽。',
            self::IwaMet => '防御が自慢のカメ（？）。魔法での攻撃が有効。',
            self::Norawani => 'そこらを彷徨く野良のワニ（おそらく）。バランスの良いステータスが特徴。必ず戦闘前に咆哮を上げてステータスを高める。',
            self::MagmaDile => 'ノラワニ達を仕切るボス。必ず戦闘前に咆哮を上げてステータスを高める。咆哮の効果が治まると噛みつき、そして強烈に暴れ回る。暴れるタイミングを掴んで防御を選択し、痛い一撃をやり過ごすことができると戦いやすいだろう。',

            self::MageSrara => 'スララの変異体。冒険者の落とした杖を振り回して簡易的な魔法を覚えた。',
            self::Clion => 'ふよふよと浮遊する生命体。鈍足だが吐き出す泡は強力なので、使われる前に各個撃破しよう。',
            self::Ikkaku => '立派なツノから呼ばれる雷魔法は単体攻撃、全体攻撃の2種類があるため注意。ツルツルした見た目だが、ガラスのように脆い。',
            self::SpikeWhale => '海岸のボス。一定のタイミングで大波を呼んでくるので、タイミングを覚えて身構えよう。',

            self::Eripen => '首回りの氷が襟に見えることからこの名前をつけられた。準備をしたのち、滑って突進してくるので身構えること。',
            self::IceFairy => '冷たい大気と魔法の残り香が混ざり合って生まれた魔物。見た目に反して打たれ強くすばしっこい。あられを飛ばす全体攻撃を得意とする。APが少なく2回しか撃つことができないため、防御や弱体スキルでやり過ごしてから迎撃しよう。',
            self::ScissorFlipper => 'エリペンの群れを連ねるリーダー。エリペン種の習性である突進攻撃が鋭い刃物のような腕を持ったことでより殺傷性を増した一撃となった。同じく準備をしたのちに突進してくるため身構えること。',

            self::Nepenthos => '沼地に住み着いているウツボカズラ。獲物に消化液を吐きかけ、捕食を試みる。',
            self::Dionaea => '沼地に住むハエトリグサ。人間・魔物問わず獲物を挟んで消化する。獲物の攻撃を弱める手段を得意とし、抵抗できなくなったところを捕食する。',
            self::HazardBerry => '自身を魔物にかじらせて回復させる果実の魔物。かじってもらう理由は種の分布を広げるため。人間が食べてもおいしくないので、優先的に倒してしまおう。',
            self::WandEater => '古くから沼地に住み着く大型の魔物。魔物の中でも顕著に人間に襲いかかる。様々な弱体効果を持つ技を操り獲物の自由を奪ったのち、強靭なツルのムチで痛い打撃を叩き込む。',

            self::HoshiHotaru => '夜の森に生息するホタルで、幻想的な光は時に冒険者を惑わせる。SPDを下げる攻撃魔法を使う。',
            self::Gyao => '常夜の森に棲み付くガオーが変化した姿。ガオーと違って魔法攻撃が得意。変わらず素早く火力も高いので優先的に倒そう。',
            self::ShadowWeed => '草木に擬態した影が本体の魔物。光が基本的に存在しない常夜の森ではその姿を捉えることは難しいが、ホシホタルだけは例外のため天敵。物理的な攻撃を得意としており、魔法への耐性も極めて高い。強力な全体攻撃は一度しか使えないので、やり過ごせば戦いやすいだろう。',
            self::Twilight => '常夜の森のヌシ。豊富な魔法攻撃を使用して冒険者を苦しめる。性格自体はおっとりとしているようだ。巨大だがその姿を表すことは滅多になく、かつては幸運の象徴だったとも言われている。',

            self::CurseScareCrow => '耕作地に佇むカカシ。周囲に漂う魔力が物体に意識を与えた。1度でも攻撃を与えると数ターン後に仕掛けてくる全体攻撃は喰らってしまえばひとたまりもないだろう。防御や何もしないことでやり過ごそう。本当は大人しい性格で争いたくないらしいが、バケツについた目つきの悪さでよく喧嘩をふっかけられる。',
            self::ZombieClion => '耕作地を漂うクリオン種の魔物。相手を問答無用で戦闘不能にする呪文は対策必須。回避の方法はないので即座に倒してしまうか、リザレクトポットを買い込んでおこう。',
            self::Narehate => '耕作地で目撃される、人とも魔物とも言い難い存在。基本的に鈍重な動きだが守りは硬く、一撃の火力も大きい。行動速度を一時的に高める技を使用するため、発動された場合は先制されるケースを考慮して立ち回ろう。',

            self::BazaarLizard => '商魂逞しいトカゲの魔物。物理攻撃に耐性があり、アイテムを取り出して使ってくる。アイテムの手持ちはそれぞれ1つだけのため使ったらそれっきり。',
            self::DustBomb => 'ごみ袋のような魔物。ターン数を重ねるごとにぶくぶくと膨れ上がっていき、大爆発を起こす。早めに倒してしまうか、もしくは爆発のタイミングで防御しよう。尖ったもので袋に穴が空いているように見えるが、身体の一部らしく問題なく膨らむことができるようだ。',
            self::WitherNepenthos => '廃墟に棲み付いたウツボカズラの魔物。肥沃な土地ではないため枯れかけている。従来のネペントスと同じく消化液を吐いてくるが、飢え故に消化液は濃く強力。',
            self::PotDio => 'プランターに入ったハエトリグサの魔物。世話されていないため枯れかけている。人の手で植えられたものになると推測でき、当時の人間たちは魔物すら懐柔する力を持っていたことが分かる。',
            self::GolemBall => 'ゴーレムを補佐する球状の物質。単体、全体射出のレーザービームを操る。',
            self::Eliminator => 'ゴーレムを補佐する球状の物質。サポートを専門としておりこちらの能力を鈍らせ戦力を削いでくる。内部エネルギーには上限があり尽きれば機能停止して崩れ落ちる。攻撃して停止させることも可能だが、強固なため大人しく上限が尽きるのを待つのも良いだろう。',
            self::StoneGolem => '城下町で見られる旧式の石造ゴーレムで、形態変換機構を備えており物理攻撃と魔法攻撃を使い分ける。非常にしぶといため持久戦は必須となる。かつては城の門番だったようだが役目を失った今は命令の残滓に従い彷徨っているようだ。',

            self::DarkSrara => 'スララの上位種で鋭い物理攻撃と魔法攻撃を操り、弱点らしい部分もない。ただしこのモンスターと遭遇するほどの実力を持つ冒険者なら、いなすことが出来るはずだろう。',
            self::Anima => '古城に漂う歪んだ力の残り香が集まってできたボウ種の魔物。同じく物理攻撃に弱い。',
            self::GaiaHand => '古城の魔物。攻防共に物理的な能力に優れており、握り潰されればひとたまりもない。滅多に見られることはないが、手のひらに口があるらしい。',
            self::DeathScorpio => 'スコーピオ種の上位種。鋭い針を用いた致命の一撃を得意とする。防御が通用しないため、さっさと倒すのが一番良いだろう。魔法攻撃が有効だ。',
            self::MetalGecko => 'リザードの上位種。鋭い爪は鎧すら切り裂く。自身は甲殻で覆われており打たれ強い。魔法で攻めつつ、持久戦に持ち込むと良いだろう。DEFを下げる行動をしたのち、鋭い爪で全体攻撃をしてくるので備えよう。',
            self::Stinger => 'イッカクの上位種。鉄のような見た目をしているが非常に物理攻撃が有効。単体・全体の魔法を使用する。',
            self::PlasmaBook => '古城の魔力が書籍にまとわりつき魔物となった。SPDを下げる全体魔法攻撃が非常に厄介だが、本体も打たれ強い。この魔物を先に討伐するというよりかは、SPDが下がった状態でどう戦況を組み立てるかを意識するのが良いだろう。',
            self::FlareDrago => '古城の主。鋭い爪と堅牢な甲殻を持ち、吐く息はあらゆるものを焼き尽くす。龍の咆哮は対峙者の抗戦意欲を削ぎ落とし恐怖させる、非常に危険度の高い魔物。古城から縄張りを広げようとする行動は現在のところ見られないが、何か事情があるのかもしれない。',

            self::TraitorLordOfDragon => '古城の祭壇で見かけた、人とも魔物とも言い難い存在。異界の門を召喚して魔法を呼び出す。威力は驚異的であるため、どう立ち回るか考えておかなければならないだろう。自身の血を啜りHPを回復することがあるが、代償として抵抗力が下がるため反撃のチャンスでもある。宙に浮かぶ異界の門からは、時折この世のものとは思えない生命体が覗いているかのような視線が感じられる。',

            self::Celavie => '茫洋の地に存在する異質な大気と魔法の残り香が混ざり合って生まれた魔物。物理攻撃力と防御力に優れている。強力な全体攻撃は2回しか撃つことができないため、防御や弱体スキルでやり過ごしてから迎撃しよう。',
            self::GrandCube => '茫洋の地に現れる物質のような魔物で、冒険者のステータスを滅茶苦茶にする。またSTR依存の魔法攻撃、INT依存の物理攻撃を使ってきたりと、ますます混乱させられそうな技も得意。',
            self::OriginSlum => '茫洋の地に存在する魔物。観察した限りではスララ種の魔物であり、高い戦闘力を持つ魔物だと思われる。その立ち振る舞いからは、悠久の時を過ごしてきたかのような雰囲気を感じ取ることができる。稀にこちら側へHPの回復呪文を唱えて傷を癒してくれるが、その特異な行動に悪意は感じられない。',
            self::OriginGwappa => '茫洋の地に存在する魔物。けたたましく鳴きながら周囲を走り回り、その脚力は凄まじい。立ち振る舞いからは想像がつかないが、それでも悠久の時を過ごしてきたかのような雰囲気を感じ取ることができる。稀にこちら側へAPの回復呪文を唱えてくれるが、その特異な行動に悪意は感じられない。',
            self::HollowHero => '茫洋の地で見かけた存在。人とも魔物とも形容し難い異形の姿を持ち、圧倒的な戦闘能力を誇る。狂乱したかの如く暴れ回ることもあれば、静寂の中に佇み微動だにしないこともある。その静けさこそが態勢を整えるタイミングとなるだろう。放たれる技の数々は高い威力と効果を備えるだけでなく、奇しくも所作そのものから崇高さを感じ取ることができる。',

            self::HighSrara => 'スララの変異体。バランスの良いパラメータを持つ。',

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
            self::FlareDrago => 'flaredrago.png',

            self::TraitorLordOfDragon => 'traitorlordofdragon.png',

            self::Celavie => 'celavie.png',
            self::GrandCube => 'grandcube.png',
            self::OriginSlum => 'originslum.png',
            self::OriginGwappa => 'origingwappa.png',
            self::HollowHero => 'hollowhero.png',

            self::HighSrara => 'highsrara.png',

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
            self::FlareDrago->value,
        ];
    }

    public static function ancientCastleAltarAppearingEnemies(): array
    {
        return [
            self::TraitorLordOfDragon->value,
        ];
    }

    public static function vastExpanseAppearingEnemies(): array
    {
        return [
            self::Celavie->value,
            self::GrandCube->value,
            self::OriginSlum->value,
            self::OriginGwappa->value,
            self::HollowHero->value,
        ];
    }
}

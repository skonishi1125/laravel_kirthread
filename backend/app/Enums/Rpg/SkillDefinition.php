<?php

namespace App\Enums\Rpg;

enum SkillDefinition: int
{
    // -------------------- 格闘家 --------------------
    case MiddleBlow = 100;
    case RapidFist = 101;
    case SpinKick = 102;
    case AxeShoot = 103;
    case HeavyKnuckle = 104;
    case TitanBreak = 105;
    case FightSoul = 106;
    case Transform = 107;

    // -------------------- 治療師 --------------------
    case Healing = 200;
    case QuickHeal = 201;
    case SlowHeal = 202;
    case AllHealing = 203;
    case QuickAllHealing = 204;
    case SlowAllHealing = 205;
    case LuminousRelieve = 206; // いらんか？
    case AllRelieve = 207; // いらんか？
    case Resurrection = 208;
    case MiniVolt = 209;
    case HolyArrow = 210;
    case HeavenRay = 211;

    // -------------------- 重騎士 --------------------
    case WideGuard = 300;
    case AdvancedGuard = 301;
    case CurseEdge = 302;
    case WideThrust = 303;
    case BraveSlash = 304;
    case Protection = 305;
    case OverProtect = 306;
    case BloodMoon = 307;

    // -------------------- 魔導師 --------------------
    case PetitBolt = 400;
    case CrashBlast = 401;
    case BoltStorm = 402;
    case ManaExplosion = 403;
    case AileCaliber = 404;
    case MiniHeal = 405;
    case PopHeal = 406;
    case Meditation = 407;
    case BattleMage = 408;
    case MagicalSmash = 409;

    // -------------------- 弓馭者 --------------------
    case FirstAid = 500;
    case FairyFog = 501;
    case BallistaShot = 502;
    case BreakBowGun = 503;
    case ArmorBreaker = 504;
    case EdgeFold = 505;
    case WeaponDemolish = 506;
    case WindAccel = 507;
    case GaleStrike = 508;
    case SirenAura = 509;
    case CerberusForce = 510;

    // -------------------- 理術師 --------------------
    case BookSmash = 600;
    case AxiomStrike = 601;
    case MagicMissile = 602;
    case LogosRay = 603;
    case PowerEnt = 604;
    case BladeForce = 605;
    case ShieldEnt = 606;
    case DefendThreat = 607;
    case MagicEnt = 608;
    case ArcWisdom = 609;
    case SpeedEnt = 610;
    case SonicTrimming = 611;
    case LuckEnt = 612;
    case FortuneStar = 613;

    // -------------------- 敵 --------------------
    case Bite = 1000;
    case Rampage = 1001;
    case Biribiri = 1002;
    case Discharge = 1003;
    case EnemyHealing = 1004;
    case EnemyAllHealing = 1005;
    case Regeneration = 1006;
    case EnemyGuardSpell = 1007;
    case EnemyAllGuardSpell = 1008;
    case Roar = 1009;
    case DigestiveFluid = 1010;
    case Freeze = 1011;
    case Bubble = 1012;
    case Wave = 1013;
    case Fire = 1014;
    case HailShot = 1015;
    case Prepare = 1016; // 大技に噛ませるとよい
    case Rush = 1017;
    case FreeToEat = 1018;
    case WeakPollen = 1019;
    case GrassWhip = 1020;
    case StellarBlink = 1021;
    case Blink = 1022;
    case MagicTackle = 1023;
    case UseAllPotion = 1024;
    case UseMiniBomb = 1025;
    case SwellUp = 1026;
    case Explosion = 1027;
    case RazerBeam = 1028;
    case RazerSweep = 1029;
    case PowerBreak = 1030;

    public function label(): string
    {
        return match ($this) {
            self::MiddleBlow => 'ミドルブロウ',
            self::RapidFist => 'ラピッドフィスト',
            self::SpinKick => 'スピンキック',
            self::AxeShoot => 'アックスシュート',
            self::HeavyKnuckle => 'ヘビーナックル',
            self::TitanBreak => 'タイタンブレイク',
            self::FightSoul => 'ファイトソウル',
            self::Transform => 'トランスフォーム',

            self::Healing => 'ヒーリング',
            self::QuickHeal => 'クイックヒール',
            self::SlowHeal => 'スロウヒール',
            self::AllHealing => 'オールヒーリング',
            self::QuickAllHealing => 'クイックオールヒーリング',
            self::SlowAllHealing => 'スロウオールヒーリング',
            self::LuminousRelieve => 'ルミナスリリーヴ',
            self::AllRelieve => 'オールリリーヴ',
            self::Resurrection => 'リザレクション',
            self::MiniVolt => 'ミニボルト',
            self::HolyArrow => 'ホーリーアロー',
            self::HeavenRay => 'ヘヴンレイ',

            self::WideGuard => 'ワイドガード',
            self::AdvancedGuard => 'アドバンスドガード',
            self::CurseEdge => 'カースエッジ',
            self::WideThrust => 'ワイドスラスト',
            self::BraveSlash => 'ブレイヴスラッシュ',
            self::Protection => 'プロテクション',
            self::OverProtect => 'オーバープロテクト',
            self::BloodMoon => 'ブラッドムーン',

            self::PetitBolt => 'プチボルト',
            self::CrashBlast => 'クラッシュブラスト',
            self::BoltStorm => 'ボルトストーム',
            self::ManaExplosion => 'マナエクスプロージョン',
            self::AileCaliber => 'エイルカリバー',
            self::MiniHeal => 'ミニヒール',
            self::PopHeal => 'ポップヒール',
            self::Meditation => 'メディテーション',
            self::BattleMage => 'バトルメイジ',
            self::MagicalSmash => 'マジカルスマッシュ',

            self::FirstAid => 'ファーストエイド',
            self::FairyFog => 'フェアリーフォグ',
            self::BallistaShot => 'バリスタショット',
            self::BreakBowGun => 'ブレイクボウガン',
            self::ArmorBreaker => 'アーマーブレイカー',
            self::EdgeFold => 'エッジフォールド',
            self::WeaponDemolish => 'ウェポンデモリッシュ',
            self::WindAccel => 'ウインドアクセル',
            self::GaleStrike => 'ゲイルストライク',
            self::SirenAura => 'セイレーンオーラ',
            self::CerberusForce => 'ケルベロスフォース',

            self::BookSmash => 'ブックスマッシュ',
            self::AxiomStrike => 'アクシオムストライク',
            self::MagicMissile => 'マジックミサイル',
            self::LogosRay => 'ロゴスレイ',
            self::PowerEnt => 'パワーエント',
            self::BladeForce => 'ブレードフォース',
            self::ShieldEnt => 'シールドエント',
            self::DefendThreat => 'ディフェンドスリート',
            self::MagicEnt => 'マジックエント',
            self::ArcWisdom => 'アークウィズダム',
            self::SpeedEnt => 'スピードエント',
            self::SonicTrimming => 'ソニックトリミング',
            self::LuckEnt => 'ラックエント',
            self::FortuneStar => 'フォーチュンスター',

            self::Bite => 'かみつく', // 攻撃 物理 単体
            self::Rampage => 'あばれる', // 攻撃 物理 全体
            self::Biribiri => 'ビリビリ', // 攻撃 魔法 単体
            self::Discharge => '放電', // 攻撃 魔法 全体
            self::EnemyHealing => 'エネミーヒーリング',// 回復 単体
            self::EnemyAllHealing => 'エネミーオールヒーリング', // 回復 全体
            self::Regeneration => '再生', // 回復 自身
            self::EnemyGuardSpell => 'エネミーガードスペル', // バフ 単体
            self::EnemyAllGuardSpell => 'エネミーオールガードスペル', // バフ 全体
            self::Roar => '咆哮', // バフ 自身
            self::DigestiveFluid => '消化液', // 特殊 攻撃 + 防御デバフ
            self::Freeze => 'フリーズ', // 攻撃魔法単体
            self::Bubble => 'バブル', // 攻撃魔法全体
            self::Wave => '大波', // 攻撃魔法全体
            self::Fire => 'ファイアー', // 攻撃魔法単体
            self::HailShot => 'ヘイルショット', // 物理 全体
            self::Prepare => '準備',
            self::Rush => '突進', // 物理 全体
            self::FreeToEat => 'かじられる', // 回復 単体
            self::WeakPollen => '弱体の花粉', // 特殊 魔法 全体 STR・INTデバフ
            self::GrassWhip => 'グラスウィップ', // 物理 全体
            self::StellarBlink => 'ステラブリンク', // 魔法 単体 SPDデバフ
            self::Blink => 'きらめく', // 何もしない行動
            self::MagicTackle => 'マジックタックル', // 魔法 単体
            self::UseAllPotion => 'オールポーション使用', // 回復 全体 BazaarLizard
            self::UseMiniBomb => 'ミニボム使用', // 攻撃 単体 BazaarLizard
            self::SwellUp => '膨れ上がる', // 何もしない
            self::Explosion => '爆発', // 物理 全体
            self::RazerBeam => 'レーザービーム', // 魔法 単体
            self::RazerSweep => 'レーザースイープ', // 魔法 全体
            self::PowerBreak => 'パワーブレイク', // 特殊 魔法 全体 デバフ
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MiddleBlow => '素早いフットワークと共に、敵単体に拳を叩き込む。',
            self::RapidFist => '目にも止まらぬ速さで、敵単体に高速の六連攻撃！',
            self::SpinKick => '大きく身体を捻り、勢いをつけたまま敵全体に回転蹴りを放つ。',
            self::AxeShoot => '横凪ぎの踵落とし。敵全体に高いダメージを与える。',
            self::HeavyKnuckle => '敵単体に重い拳を撃ち込む。敵単体手に固定のダメージを与える。',
            self::TitanBreak => '最も最後に行動するが、その分溜め込んだ膂力で敵単体に大ダメージ。',
            self::FightSoul => '気合を高め、自身のSTRを暫くの間上昇させる。',
            self::Transform => '自分のDEFとINTを犠牲に、STRとSPDを飛躍的に上昇させる。上昇値はSLvに依存。',

            self::Healing => '治療師の基礎回復魔法。味方単体のHPを回復する呪文を唱える。',
            self::QuickHeal => '先制発動する。迅速な詠唱で味方単体のHPを回復する。',
            self::SlowHeal => 'スロウヒール',
            self::AllHealing => '癒しの魔力を広範囲に拡散し、味方全体のHPを回復する。',
            self::QuickAllHealing => 'クイックオールヒーリング',
            self::SlowAllHealing => 'スロウオールヒーリング',
            self::LuminousRelieve => '聖なる力を込めた魔力を呪文に込める。味方1人のHPを大きく回復させる。',
            self::AllRelieve => '魔力を大きく消費し、味方全体のHPを大きく回復させる。',
            self::Resurrection => '聖なる力を戦闘不能の味方に分け与え、戦闘不能状態から復活させる。',
            self::MiniVolt => '魔力を敵単体に放ち攻撃する、治療師の扱う護身用魔法。',
            self::HolyArrow => '光の弓矢を聖なる魔力で具現化し、敵単体に射出する。',
            self::HeavenRay => '光の柱が広範囲に降り注ぎ、敵全体に大ダメージを与える。',

            self::WideGuard => '先制発動する。使用ターン中の味方全員のダメージを軽減する。',
            self::AdvancedGuard => '先制発動する。魔力を纏い、使用ターン中の味方全員のダメージを大きく軽減。',
            self::CurseEdge => '自身のHPを一定量消費し、敵単体に攻撃する。HPの消費量はSLvに依存する。',
            self::WideThrust => '手持ちの斧で力強く薙ぎ払い、敵全体にダメージを与える。',
            self::BraveSlash => '正義心を膂力とし、敵単体に攻撃。自分の防御力に依存して威力が上昇する。',
            self::Protection => '守護魔法を味方単体に付与し、DEFを暫くの間アップさせる。',
            self::OverProtect => '広範囲に拡大した守護魔法を唱え、味方全員のDEFを暫くの間アップさせる。',
            self::BloodMoon => '一定時間自身の防御力を0にし、その値をSTRに還元する。還元される値はSLvに依存。',

            self::PetitBolt => '小さな魔力弾を放ち敵単体に攻撃。低コストな攻撃手段。',
            self::CrashBlast => '魔力の塊を生成し、敵単体にぶつけて爆発させる。大きなダメージを与える。',
            self::BoltStorm => '魔力弾を周囲に生成し敵全体に打ち込み、ダメージを与える。',
            self::ManaExplosion => '巨大な魔力を糧としたマナの塊を大爆発させることで、敵全体に大ダメージ。',
            self::AileCaliber => '魔道の刃で敵単体を切り裂く。自分のINTに依存した物理ダメージを与える。',
            self::MiniHeal => '初歩的な回復魔法のひとつ。味方1人のHPを回復する呪文を唱える。',
            self::PopHeal => '回復魔力を周囲に浮かべ、味方全体のHPを回復する。',
            self::Meditation => '深く瞑想することで心を落ち着かせ、暫くの間自身のINTを高める。',
            self::BattleMage => '自身の智力全てを膂力に変換。暫くの間INTが0になり、その分だけSTRが上昇する。',
            self::MagicalSmash => '魔法少女必携？敵単体を手持ちの杖で思いっきりぶん殴る！',

            self::FirstAid => '味方1人の救護を行い、HPを固定量回復する。回復量はSLvに依存する。',
            self::FairyFog => '妖精の力で味方全体を癒しの霧で包み込み、HPを回復する。',
            self::BallistaShot => '最も最後に行動する。引き絞った大弩で敵単体に大ダメージ。',
            self::BreakBowGun => '敵単体に重量のある弩を打ち込む。ダメージを与え、短時間DEFを下げる。',
            self::ArmorBreaker => '相手の防御陣形の綻びを射抜く弩撃。敵全体にダメージを与え、短時間DEFを下げる。',
            self::EdgeFold => '敵単体に弱体の力を込めた弓矢を放つ。ダメージを与え、そのターン中のSTRとINTを下げる。',
            self::WeaponDemolish => '攻撃器官の基点を撃ち威力を削ぐ一撃。敵全体にダメージを与え、そのターン中のSTRとINTを下げる。',
            self::WindAccel => '風の魔力を込めた鋭い弓矢を敵単体に放つ。ダメージを与え、次のターンのSPDを上げる。',
            self::GaleStrike => '暴風を身に纏い、敵単体に攻撃を叩き込む。ウインドアクセルが付与されていた場合は威力が上昇。',
            self::SirenAura => '潮の歌をまとい魔力を澄ませる。暫くの間自身のINTを高め、また回復スキルの効果を強化する。',
            self::CerberusForce => '冥犬の護勢を宿し、その身を強化する。暫くの間自身のSTRとDEFを向上させる。',

            self::BookSmash => '手持ちの魔導書を用いて敵単体を全力でぶん殴る。',
            self::AxiomStrike => '魔力の理を解析し物理的な衝撃波に変換。敵単体に高威力の物理ダメージ。',
            self::MagicMissile => '魔力で創り出したミサイルの弾丸を敵単体に飛ばして攻撃。',
            self::LogosRay => '魔力の理を解析し光線を生成。敵単体に高威力の魔法ダメージ。',
            self::PowerEnt => '味方1人のSTRを暫くの間向上させる。',
            self::BladeForce => '味方の武器に魔力を使役し、全員のSTRを暫くの間向上させる。',
            self::ShieldEnt => '味方1人のDEFを暫くの間向上させる。',
            self::DefendThreat => '脅威を感知してオートで保護する呪文を付与する。味方全員のDEFを暫くの間向上させる。',
            self::MagicEnt => '味方1人のINTを暫くの間向上させる。',
            self::ArcWisdom => '自身の知見を魔法を通じて仲間に分け与える。味方全員のINTを暫くの間向上させる。',
            self::SpeedEnt => '味方1人のSPDを暫くの間向上させる。',
            self::SonicTrimming => '行動をアルゴリズム化して動きを最適化する呪文。味方全員のSPDを暫くの間向上させる。',
            self::LuckEnt => '味方1人のLUCを暫くの間大幅に向上させる。',
            self::FortuneStar => 'なんだかいい日になるような気がする。味方全員のLUCが大幅に向上する。',

            self::Bite => '相手単体に噛みつき物理攻撃する。',
            self::Rampage => '大暴れして、相手全体に物理攻撃。',
            self::Biribiri => '相手単体に雷の力で魔法攻撃。',
            self::Discharge => '電気の力を解き放ち、相手全体に魔法攻撃に雷の力で魔法攻撃。',
            self::EnemyHealing => '敵専用の回復魔法。単体のHPを回復。',
            self::EnemyAllHealing => '敵専用の回復魔法。全体のHPを回復。',
            self::Regeneration => '細胞分裂することで自信のHPを回復する。',
            self::EnemyGuardSpell => '敵専用のバフ魔法。単体の防御力を上げる。',
            self::EnemyAllGuardSpell => '敵専用のバフ魔法。全体の防御力を上げる。',
            self::Roar => '雄叫びを上げ、自身の攻撃力を上げる。',
            self::DigestiveFluid => '消化液を吐き出す。相手単体にダメージを与え、DEFを低下させる。',
            self::Freeze => '氷の呪文で相手単体に魔法攻撃。',
            self::Bubble => '相手全体に泡を撒き散らし、魔法攻撃。',
            self::Wave => '大波を呼び寄せ、相手全体に魔法攻撃。',
            self::Fire => '炎の呪文で相手単体に魔法攻撃。',
            self::HailShot => '霰の粒を撒き散らし、相手全体に物理攻撃。',
            self::Prepare => '次の攻撃の準備をする。',
            self::Rush => '思いっきり突撃して相手全体に物理攻撃。',
            self::FreeToEat => '自身をかじってもらい、味方単体の体力を回復。',
            self::WeakPollen => '弱体の花粉を振り撒き、相手全体に魔法ダメージ + STR, INTを下げる。',
            self::GrassWhip => 'しなる鞭を相手全体に思いっきり叩きつける。',
            self::StellarBlink => '星の光を瞬かせ、それを魔力として相手単体にぶつける。SPDを下げる。',
            self::Blink => 'キラキラかがやき、何もしない。',
            self::MagicTackle => '魔法のタックル。理屈じゃない！相手に魔法単体攻撃。',
            self::UseAllPotion => 'オールポーションを使って、全員の体力回復。',
            self::UseMiniBomb => 'ミニボムを使って、単体に攻撃。',
            self::SwellUp => 'ぷくぷくと膨れ上がる。',
            self::Explosion => '自身を大爆発させ、相手全体に物理ダメージ。',
            self::RazerBeam => 'レーザーを射出し、単体に魔法ダメージ。',
            self::RazerSweep => 'レーザーを射出し薙ぎ払う。相手全体に魔法ダメージ。倍率が高い。',
            self::PowerBreak => '相手全体にダメージを与え、STRを下げる。',
        };
    }
}

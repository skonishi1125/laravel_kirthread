<?php

namespace App\Enums\Rpg;

enum SkillDefinition: int
{
    // -------------------- 格闘家 --------------------
    case MiddleBlow = 10;
    case SpinKick = 11;
    case FightSoul = 12;
    case HeavyKnuckle = 13;
    case RapidFist = 14;
    case AxeShoot = 15;
    case TitanBreak = 16;
    case Transform = 17;

    // -------------------- 治療師 --------------------
    case Healing = 20;
    case AllHealing = 21;
    case QuickHeal = 22;
    case MiniVolt = 23;
    case HolyArrow = 24;
    case HeavenRay = 25;
    case LuminousRelieve = 26;
    case AllRelieve = 27;
    case Resurrection = 28;

    // -------------------- 重騎士 --------------------
    case WideGuard = 30;
    case AdvancedGuard = 31;
    case CurseEdge = 32;
    case WideThrust = 33;
    case BraveSlash = 34;
    case Protection = 35;
    case OverProtect = 36;
    case BloodMoon = 37;

    // -------------------- 魔導師 --------------------
    case PetitBolt = 40;
    case BoltStorm = 41;
    case MagicalSmash = 42;
    case AileCaliber = 43;
    case MiniHeal = 44;
    case PopHeal = 45;
    case CrashBlast = 46;
    case ManaExplosion = 47;
    case Meditation = 48;
    case BattleMage = 49;

    // -------------------- 弓馭者 --------------------
    case FirstAid = 50;
    case FairyFog = 51;
    case BreakBowGun = 52;
    case EdgeFold = 53;
    case WindAccel = 54;
    case GaleStrike = 55;
    case BallistaShot = 56;
    case SirenAura = 57;
    case CerberusForce = 58;

    // -------------------- 理術師 --------------------
    case BookSmash = 60;
    case AxiomStrike = 61;
    case MagicMissile = 62;
    case LogosRay = 63;
    case PowerEnt = 64;
    case BladeForce = 65;
    case ShieldEnt = 66;
    case DefendThreat = 67;
    case MagicEnt = 68;
    case ArcWisdom = 69;
    case SpeedEnt = 70;
    case SonicTrimming = 71;
    case LuckEnt = 72;
    case FortuneStar = 73;

    // -------------------- 敵 --------------------
    case Bite = 100;
    case Rampage = 101;
    case Biribiri = 102;
    case Discharge = 103;
    case EnemyHealing = 104;
    case EnemyAllHealing = 105;
    case Regeneration = 106;
    case EnemyGuardSpell = 107;
    case EnemyAllGuardSpell = 108;
    case Roar = 109;
    case DigestiveFluid = 110;
    case Freeze = 111;
    case Bubble = 112;
    case Wave = 113;
    case Fire = 114;
    case HailShot = 115;
    case Prepare = 116; // 大技に噛ませるとよい
    case Rush = 117;

    public function label(): string
    {
        return match ($this) {
            self::MiddleBlow => 'ミドルブロウ',
            self::SpinKick => 'スピンキック',
            self::FightSoul => 'ファイトソウル',
            self::HeavyKnuckle => 'ヘビーナックル',
            self::RapidFist => 'ラピッドフィスト',
            self::AxeShoot => 'アックスシュート',
            self::TitanBreak => 'タイタンブレイク',
            self::Transform => 'トランスフォーム',

            self::Healing => 'ヒーリング',
            self::AllHealing => 'オールヒーリング',
            self::QuickHeal => 'クイックヒール',
            self::MiniVolt => 'ミニボルト',
            self::HolyArrow => 'ホーリーアロー',
            self::HeavenRay => 'ヘヴンレイ',
            self::LuminousRelieve => 'ルミナスリリーヴ',
            self::AllRelieve => 'オールリリーヴ',
            self::Resurrection => 'リザレクション',

            self::WideGuard => 'ワイドガード',
            self::AdvancedGuard => 'アドバンスドガード',
            self::CurseEdge => 'カースエッジ',
            self::WideThrust => 'ワイドスラスト',
            self::BraveSlash => 'ブレイヴスラッシュ',
            self::Protection => 'プロテクション',
            self::OverProtect => 'オーバープロテクト',
            self::BloodMoon => 'ブラッドムーン',

            self::PetitBolt => 'プチボルト',
            self::BoltStorm => 'ボルトストーム',
            self::MagicalSmash => 'マジカルスマッシュ',
            self::AileCaliber => 'エイルカリバー',
            self::MiniHeal => 'ミニヒール',
            self::PopHeal => 'ポップヒール',
            self::CrashBlast => 'クラッシュブラスト',
            self::ManaExplosion => 'マナエクスプロージョン',
            self::Meditation => 'メディテーション',
            self::BattleMage => 'バトルメイジ',

            self::FirstAid => 'ファーストエイド',
            self::FairyFog => 'フェアリーフォグ',
            self::BreakBowGun => 'ブレイクボウガン',
            self::EdgeFold => 'エッジフォールド',
            self::WindAccel => 'ウインドアクセル',
            self::GaleStrike => 'ゲイルストライク',
            self::BallistaShot => 'バリスタショット',
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

        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MiddleBlow => '素早いフットワークと共に、敵単体に拳を叩き込む。',
            self::SpinKick => '大きく身体を捻り、勢いをつけたまま敵全体に回転蹴りを放つ。',
            self::FightSoul => '気合を高め、自身のSTRを暫くの間上昇させる。',
            self::HeavyKnuckle => '敵単体に重い拳を撃ち込む。敵単体手に固定のダメージを与える。',
            self::RapidFist => '目にも止まらぬ速さで、敵単体に高速の六連攻撃！',
            self::AxeShoot => '横凪ぎの踵落とし。敵全体に高いダメージを与える。',
            self::TitanBreak => '最も最後に行動するが、その分溜め込んだ膂力で敵単体に大ダメージ。',
            self::Transform => '自分のDEFとINTを犠牲に、STRとSPDを飛躍的に上昇させる。上昇値はSLvに依存。',

            self::Healing => '治療師の基礎回復魔法。味方単体のHPを回復する呪文を唱える。',
            self::AllHealing => '癒しの魔力を広範囲に拡散し、味方全体のHPを回復する。',
            self::QuickHeal => '先制発動する。迅速な詠唱で味方単体のHPを回復する。',
            self::MiniVolt => '魔力を敵単体に放ち攻撃する、治療師の扱う護身用魔法。',
            self::HolyArrow => '光の弓矢を聖なる魔力で具現化し、敵単体に射出する。',
            self::HeavenRay => '光の柱が広範囲に降り注ぎ、敵全体に大ダメージを与える。',
            self::LuminousRelieve => '聖なる力を込めた魔力を呪文に込める。味方1人のHPを大きく回復させる。',
            self::AllRelieve => '魔力を大きく消費し、味方全体のHPを大きく回復させる。',
            self::Resurrection => '聖なる力を戦闘不能の味方に分け与え、戦闘不能状態から復活させる。',

            self::WideGuard => '先制発動する。使用ターン中の味方全員のダメージを軽減する。',
            self::AdvancedGuard => '先制発動する。魔力を纏い、使用ターン中の味方全員のダメージを大きく軽減。',
            self::CurseEdge => '自身のHPを一定量消費し、敵単体に攻撃する。HPの消費量はSLvに依存する。',
            self::WideThrust => '手持ちの斧で力強く薙ぎ払い、敵全体にダメージを与える。',
            self::BraveSlash => '正義心を膂力とし、敵単体に攻撃。自分の防御力に依存して威力が上昇する。',
            self::Protection => '守護魔法を味方単体に付与し、DEFを暫くの間アップさせる。',
            self::OverProtect => '広範囲に拡大した守護魔法を唱え、味方全員のDEFを暫くの間アップさせる。',
            self::BloodMoon => '一定時間自身の防御力を0にし、その値をSTRに還元する。還元される値はSLvに依存。',

            self::PetitBolt => '小さな魔力弾を放ち敵単体に攻撃。低コストな攻撃手段。',
            self::BoltStorm => '魔力弾を周囲に生成し敵全体に打ち込み、ダメージを与える。',
            self::MagicalSmash => '魔法少女必携？敵単体を手持ちの杖で思いっきりぶん殴る！',
            self::AileCaliber => '魔道の刃で敵単体を切り裂く。自分のINTに依存した物理ダメージを与える。',
            self::MiniHeal => '初歩的な回復魔法のひとつ。味方1人のHPを回復する呪文を唱える。',
            self::PopHeal => '回復魔力を周囲に浮かべ、味方全体のHPを回復する。',
            self::CrashBlast => '魔力で生成したエネルギー弾を敵単体に撃ち、ダメージを与える。',
            self::ManaExplosion => '巨大な魔力の塊を大爆発させ、敵全体に大ダメージを与える。',
            self::Meditation => '深く瞑想することで心を落ち着かせ、暫くの間自身のINTを高める。',
            self::BattleMage => '自身の智力全てを膂力に変換。暫くの間INTが0になり、STRが飛躍的に上昇。',

            self::FirstAid => '味方1人の救護を行い、HPを固定量回復する。回復量はSLvに依存する。',
            self::FairyFog => '妖精の力で味方全体を癒しの霧で包み込み、HPを回復する。',
            self::BreakBowGun => '敵単体に重量のある弩を打ち込む。ダメージを与え、短時間DEFを下げる。',
            self::EdgeFold => '敵単体に弱体の魔力を込めた弓矢を放つ。ダメージを与え、そのターン中のSTRとINTを下げる。',
            self::WindAccel => '風の魔力を込めた鋭い弓矢を敵単体に放つ。ダメージを与え、次のターンのSPDを上げる。',
            self::GaleStrike => '暴風を身に纏い、敵単体に攻撃を叩き込む。ウインドアクセルが付与されていた場合は威力が上昇。',
            self::BallistaShot => '最も最後に行動する。引き絞った大弩で敵単体に大ダメージ。',
            self::SirenAura => '幻獣の力を身に宿す。暫くの間自身のINTを高め、また回復スキルの効果を上げる。',
            self::CerberusForce => '幻獣の力を身に宿す。暫くの間自身のSTRとDEFを向上させる。',

            self::BookSmash => '手持ちの魔導書を用いて敵単体を全力でぶん殴る。',
            self::AxiomStrike => '魔力の理を解析し物理的な衝撃波に変換。敵単体に高威力の物理ダメージ。',
            self::MagicMissile => '魔力で創り出したミサイルの弾丸を敵単体に飛ばして攻撃。',
            self::LogosRay => '魔力の理を解析し光線を生成。敵単体に高威力の魔法ダメージ。',
            self::PowerEnt => '味方1人のSTRを暫くの間向上させる。',
            self::BladeForce => '味方の武器に魔力を使役し、全員のSTRを暫くの間向上させる。',
            self::ShieldEnt => '味方1人のDEFを暫くの間向上させる。',
            self::DefendThreat => '脅威を感知して自動で保護する呪文を付与する。味方全員のDEFを暫くの間向上させる。',
            self::MagicEnt => '味方1人のINTを暫くの間向上させる。',
            self::ArcWisdom => '自身の知見を魔法を通じて仲間に分け与える。味方全員のINTを暫くの間向上させる。',
            self::SpeedEnt => '味方1人のSPDを暫くの間向上させる。',
            self::SonicTrimming => '行動をアルゴリズム化して無駄を省き最適化する呪文。味方全員のSPDを暫くの間向上させる。',
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
        };
    }
}

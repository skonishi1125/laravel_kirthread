<?php

namespace App\Enums\Rpg;

enum SkillDefinition: int
{
    // -------------------- 格闘家 --------------------
    case MiddleBlow = 10;
    case HeavyKnuckle = 11;
    case SpinKick = 12;
    case Transform = 14;

    // -------------------- 治療師 --------------------
    case Healing = 20;
    case AllHealing = 21;
    case MiniVolt = 22;
    case HolyAllow = 23;
    case HeavenRay = 24;

    // -------------------- 重騎士 --------------------
    case WideGuard = 30;
    case WideThrust = 31;
    case BraveSlash = 32;
    case GuardUp = 33;

    // -------------------- 魔導師 --------------------
    case PetitBlast = 40;
    case CrashBolt = 41;
    case ManaExplosion = 42;
    case MiniHeal = 43;
    case PopHeal = 44;
    case BattleMage = 45;

    // -------------------- 弓馭者 --------------------
    case FirstAid = 50;
    case FairyFog = 51;
    case BreakBowGun = 52;
    case WindAccel = 53;

    // -------------------- 理術師 --------------------
    case BookSmash = 60;
    case MagicMissile = 61;
    case GuardSpell = 62;
    case AttackSpell = 63;
    case MagicSpell = 64;

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

    public function label(): string
    {
        return match ($this) {
            self::MiddleBlow => 'ミドルブロウ',
            self::SpinKick => 'スピンキック',
            self::HeavyKnuckle => 'ヘビーナックル',
            self::Transform => 'トランスフォーム',

            self::Healing => 'ヒーリング',
            self::AllHealing => 'オールヒーリング',
            self::MiniVolt => 'ミニボルト',
            self::HolyAllow => 'ホーリーアロー',
            self::HeavenRay => 'ヘヴンレイ',

            self::WideThrust => 'ワイドスラスト',
            self::WideGuard => 'ワイドガード',
            self::BraveSlash => 'ブレイヴスラッシュ',
            self::GuardUp => 'ガードアップ',

            self::MiniHeal => 'ミニヒール',
            self::PopHeal => 'ポップヒール',
            self::PetitBlast => 'プチブラスト',
            self::CrashBolt => 'クラッシュボルト',
            self::ManaExplosion => 'マナエクスプロージョン',
            self::BattleMage => 'バトルメイジ',

            self::FirstAid => 'ファーストエイド',
            self::FairyFog => 'フェアリーフォグ',
            self::BreakBowGun => 'ブレイクボウガン',
            self::WindAccel => 'ウインドアクセル',

            self::BookSmash => 'ブックスマッシュ',
            self::MagicMissile => 'マジックミサイル',
            self::GuardSpell => 'ガードスペル',
            self::AttackSpell => 'アタックスペル',
            self::MagicSpell => 'マジックスペル',

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
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MiddleBlow => '素早いフットワークと共に、敵単体に拳を叩き込む。',
            self::SpinKick => '大きく身体を捻り、勢いをつけたまま敵全体に回転蹴りを放つ。',
            self::HeavyKnuckle => '敵単体に強烈な一撃を撃ち込み、対象の相手に固定ダメージを与える。',
            self::Transform => '自分のDEFとINTを犠牲に、STRとSPDを飛躍的に上昇させる。',

            self::Healing => '治療師の基礎回復魔法。味方1人のHPを回復する呪文を唱える。',
            self::AllHealing => '癒しの力を広範囲に広げ、味方全体のHPを回復する。',
            self::MiniVolt => '魔力を敵単体に放つ、治療師の扱う護身用攻撃魔法。',
            self::HolyAllow => 'マナに聖なる力を込めて具現化した光の矢を敵単体に撃ち込む。',
            self::HeavenRay => '光の柱が広範囲に降り注ぎ、敵全体にダメージを与える。',

            self::WideThrust => '手持ちの斧で敵全体を力強く薙ぎ払う。',
            self::WideGuard => '先制発動する。使用ターンの味方全員のダメージを軽減する。',
            self::BraveSlash => '敵単体に自分の防御力に依存した物理ダメージを与える。',
            self::GuardUp => '味方1人の守備力をアップさせる。上昇率は自身のステータスに依存する。',

            self::MiniHeal => '初歩的な回復魔法のひとつ。味方1人のHPを回復する呪文を唱える。',
            self::PopHeal => '回復魔力を周囲に浮かべ、味方全体のHPを回復する。',
            self::PetitBlast => '小さな魔力弾を敵単体に放つ、低コストな攻撃手段。',
            self::CrashBolt => 'マナで生成したエネルギー弾を敵単体に撃ち、ダメージを与える。',
            self::ManaExplosion => '巨大なマナの塊を大爆発させ、敵全体に大ダメージを与える。',
            self::BattleMage => '自分の知力を全て力に変換し、STRを飛躍的に上昇させる。',

            self::FirstAid => '味方1人の救護を行い、HPを固定量回復する。回復量はSLvに依存する。',
            self::FairyFog => '妖精の力で味方全体を癒しの霧で包み込み、HPを回復する。',
            self::BreakBowGun => '敵単体に弩を打ち込みダメージを与え、DEFを低下させる。',
            self::WindAccel => '敵単体を攻撃しつつ、風の力を纏うことで次のターンのSPDを上げる。',

            self::GuardSpell => '味方1人の守備力をアップさせる。上昇率は自身のステータスに依存する。',
            self::AttackSpell => '味方1人の攻撃力をアップさせる。上昇率は自身のステータスに依存する。',
            self::MagicSpell => '味方1人の魔力をアップさせる。上昇率は自身のステータスに依存する。',
            self::BookSmash => '手持ちの魔導書で敵単体を全力でぶん殴る。',
            self::MagicMissile => 'マナの弾丸を敵単体に飛ばして攻撃する。',

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
        };
    }
}

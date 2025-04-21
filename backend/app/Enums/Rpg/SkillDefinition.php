<?php

namespace App\Enums\Rpg;

enum SkillDefinition: int
{
    // -------------------- 格闘家 --------------------
    case MiddleBlow = 10;
    case SpinKick = 11;

    // -------------------- 治療師 --------------------
    case Healing = 20;
    case AllHealing = 21;
    case MiniBolt = 22;

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
    case BreakBowGun = 51;
    case WindAccel = 52;

    // -------------------- 理術師 --------------------
    case BookSmash = 60;
    case GuardSpell = 61;
    case AttackSpell = 62;
    case MagicSpell = 63;

    public function label(): string
    {
        return match ($this) {
            self::MiddleBlow => 'ミドルブロウ',
            self::SpinKick => 'スピンキック',

            self::Healing => 'ヒーリング',
            self::AllHealing => 'オールヒーリング',
            self::MiniBolt => 'ミニボルト',

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

            self::BreakBowGun => 'ブレイクボウガン',
            self::FirstAid => 'ファーストエイド',
            self::WindAccel => 'ウインドアクセル',

            self::GuardSpell => 'ガードスペル',
            self::AttackSpell => 'アタックスペル',
            self::MagicSpell => 'マジックスペル',
            self::BookSmash => 'ブックスマッシュ',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MiddleBlow => '素早いフットワークと共に、敵単体に拳を叩き込む。',
            self::SpinKick => '大きく身体を捻り、勢いをつけたまま敵全体に回転蹴りを放つ。',

            self::Healing => '治療師の基本的な回復魔法。味方1人のHPを回復する呪文を唱える。',
            self::AllHealing => '治療師の基本的な回復魔法。味方1人のHPを回復する呪文を唱える。',
            self::MiniBolt => '魔力を敵単体に放つ、治療師の扱う護身用攻撃魔法。',

            self::WideThrust => '手持ちの斧で敵全体を力強く薙ぎ払う。',
            self::WideGuard => '先制発動する。使用ターンの味方全員の物理ダメージを軽減する。',
            self::BraveSlash => '敵単体に自分の防御力に依存した物理ダメージを与える。',
            self::GuardUp => '味方1人の守備力をアップさせる。',

            self::MiniHeal => '初歩的な回復魔法のひとつ。味方1人のHPを回復する呪文を唱える。',
            self::PopHeal => '回復魔力を周囲に浮かべ、味方全体のHPを回復する。',
            self::PetitBlast => '小さな魔力弾を敵単体に放つ、低コストな攻撃手段。',
            self::CrashBolt => 'マナで生成したエネルギー弾を敵単体に撃ち、ダメージを与える。',
            self::ManaExplosion => '巨大なマナの塊を大爆発させ、敵全体に大ダメージを与える。',
            self::BattleMage => '自分の知力を全て力に変換し、STRを飛躍的に上昇させる。',

            self::BreakBowGun => '敵単体に弩を打ち込みダメージを与え、DEFを低下させる。',
            self::FirstAid => '味方1人の救護を行い、対象のHPを回復する。',
            self::WindAccel => '敵単体を攻撃しつつ、風の力を纏うことで次のターンのSPDを上げる。',

            self::GuardSpell => '味方1人の守備力をアップさせる。',
            self::AttackSpell => '味方1人の攻撃力をアップさせる。',
            self::MagicSpell => '味方1人の魔力をアップさせる。',
            self::BookSmash => '手持ちの魔導書を用いて、敵単体を全力でぶん殴る。'
        };
    }
}

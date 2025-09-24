<?php

namespace App\Enums\Rpg;

enum ItemData: int
{
    case MiniPotion = 1;
    case Potion = 2;
    case LifeElixir = 3;
    case AllPotion = 4;
    case AllLifeElixir = 5;
    case ResurrectElement = 6;

    case ManaDrop = 11;
    case ManaElixir = 12;
    case AllManaDrop = 13;
    case AllManaElixir = 14;

    case AttackGummy = 21;
    case AttackMist = 22;

    case MiniBomb = 101;
    case Bomb = 102;
    case TerminateBomb = 103;

    case EtherCapsule = 111;
    case EtherCrystal = 112;

    case GoldBar = 201;

    public function label(): string
    {
        return match ($this) {
            self::MiniPotion => 'ミニポーション',
            self::Potion => 'ポーション',
            self::LifeElixir => 'ライフエリクサ',
            self::AllPotion => 'オールポーション',
            self::AllLifeElixir => 'オールライフエリクサ',
            self::ResurrectElement => 'リザレクトエレメント',

            self::ManaDrop => 'マナドロップ',
            self::ManaElixir => 'マナエリクサ',
            self::AllManaDrop => 'オールマナドロップ',
            self::AllManaElixir => 'オールマナエリクサ',

            self::AttackGummy => 'アタックグミ',
            self::AttackMist => 'アタックミスト',

            self::MiniBomb => 'ミニボム',
            self::Bomb => 'ボム',
            self::TerminateBomb => 'ターミネイトボム',

            self::EtherCapsule => 'エーテルカプセル',
            self::EtherCrystal => 'エーテルクリスタル',

            self::GoldBar => '金塊',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MiniPotion => 'お得な初心者必需品。仲間1人のHPを15回復。',
            self::Potion => '基本の回復アイテム。仲間1人のHPを50回復。',
            self::LifeElixir => '服用者の生命力に効果が依存する薬。仲間1人のHPを50パーセント回復。',
            self::AllPotion => '全体効果のあるポーション。仲間全員のHPを40回復。',
            self::AllLifeElixir => '仲間全員のHPを全回復。',
            self::ResurrectElement => '戦闘不能の味方に使用すると50パーセントの体力で復活させる。',

            self::ManaDrop => '小さなマナの雫。仲間1人のAPを20回復。',
            self::ManaElixir => '高純度のマナの液体。仲間1人のAPを50パーセント回復。',
            self::AllManaDrop => '仲間全員のAPを20回復。',
            self::AllManaElixir => '仲間全員のAPを全回復。',

            self::AttackGummy => '飲むと一定時間攻撃力が上昇するグミ。',
            self::AttackMist => '周囲に振り撒くことで一定期間味方全員の攻撃力が上昇する。',

            self::MiniBomb => '敵単体に物理ダメージ。小さいが火力は充分。',
            self::Bomb => '敵単体に物理の大ダメージ。',
            self::TerminateBomb => '大きな爆風と共に、敵全体に物理の大ダメージ。',

            self::EtherCapsule => 'マナの力が詰まったカプセル。敵単体に魔法の大ダメージ。',
            self::EtherCrystal => 'マナの凝縮されたクリスタル。解き放つことで敵全体に魔法の大ダメージ。',

            self::GoldBar => '売るとお金になる。',
        };
    }
}

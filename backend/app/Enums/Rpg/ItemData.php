<?php

namespace App\Enums\Rpg;

enum ItemData: int
{
    case MiniPotion = 1;
    case Potion = 2;
    case AllPotion = 3;
    case HighPotion = 4;
    case AllHighPotion = 5;
    case LifeElixir = 6;
    case FullLifeElixir = 7;
    case ResurrectElement = 8;

    case ManaDrop = 11;
    case AllManaDrop = 12;
    case ManaWater = 13;
    case ManaElixir = 14;
    case FullManaElixir = 15;

    case AttackGummy = 21;
    case AttackMist = 22;
    case DefenceGummy = 23;
    case DefenceMist = 24;
    case IntGummy = 25;
    case IntMist = 26;

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
            self::AllPotion => 'オールポーション',
            self::HighPotion => 'ハイポーション',
            self::AllHighPotion => 'オールハイポーション',
            self::LifeElixir => 'ライフエリクサ',
            self::FullLifeElixir => 'フルライフエリクサ',
            self::ResurrectElement => 'リザレクトエレメント',

            self::ManaDrop => 'マナドロップ',
            self::AllManaDrop => 'オールマナドロップ',
            self::ManaWater => 'マナウォーター',
            self::ManaElixir => 'マナエリクサ',
            self::FullManaElixir => 'フルマナエリクサ',

            self::AttackGummy => 'アタックグミ',
            self::AttackMist => 'アタックミスト',
            self::DefenceGummy => 'ディフェンスグミ',
            self::DefenceMist => 'ディフェンスミスト',
            self::IntGummy => 'イントグミ',
            self::IntMist => 'イントミスト',

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
            self::AllPotion => '全体効果のあるポーション。仲間全員のHPを50回復。',
            self::HighPotion => '高い効力を持つ回復アイテム。仲間1人のHPを100回復。',
            self::AllHighPotion => '全体効果のあるハイポーション。仲間全員のHPを100回復。',
            self::LifeElixir => '服用者の生命力に効果が依存する薬。仲間1人のHPを50パーセント分回復。',
            self::FullLifeElixir => '服用者の生命力を最大限に活性化させる。仲間1人のHPを全回復。',
            self::ResurrectElement => '生命力そのものが詰められた特殊なビン。戦闘不能の味方を50パーセントのHPで復活。',

            self::ManaDrop => 'マナの詰まった小さな雫。仲間1人のAPを20回復。',
            self::AllManaDrop => 'マナドロップを広く拡散できるようにしたアイテム。仲間全員のAPを20回復。',
            self::ManaWater => '雫を集め多く摂取できるように設計されたアイテム。仲間1人のAPを50回復。',
            self::ManaElixir => 'マナの液体を高純度に抽出したもの。仲間1人のAPを50パーセント回復。',
            self::FullManaElixir => 'マナの液体をさらに高純度に抽出。仲間1人のAPを全回復。',

            // %のものを出すと、最後まで支えて良さそうだ
            self::AttackGummy => '食べると一定時間STRが20ポイント上昇するグミ。',
            self::AttackMist => '周囲に振り撒くことで、しばらくの間味方全員のSTRが20ポイント上昇。',
            self::DefenceGummy => '食べると一定時間DEFが20ポイント上昇するグミ。',
            self::DefenceMist => '周囲に振り撒くことで、しばらくの間味方全員のDEFが20ポイント上昇。',
            self::IntGummy => '食べると一定時間INTが20ポイント上昇するグミ。',
            self::IntMist => '周囲に振り撒くことで、しばらくの間味方全員のINTが20ポイント上昇。',

            self::MiniBomb => '小さいが火力は充分。使用すると敵単体に物理のダメージを与える。',
            self::Bomb => 'まさしく爆弾そのもの。使用すると敵単体に物理の大ダメージ。',
            self::TerminateBomb => '取扱には資格が必要ではないかと協議中。大きな爆風と共に、敵全体に物理の大ダメージ。',

            self::EtherCapsule => '攻撃魔法が詰められたカプセル。使うことで、敵単体に魔法の大ダメージ。',
            self::EtherCrystal => '高純度のクリスタルを使用することで、上位の魔法が格納できるようになった。解き放つことで敵全体に魔法の大ダメージ。',

            self::GoldBar => '売るとお金になる。',
        };
    }
}

<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\Item;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        Item::truncate();

        $items = [
            [
                'id' => 1,
                'name' => 'ミニポーション',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'heal_type' => Item::HEAL_HP_TYPE,
                'effect_type' => Item::EFFECT_HEAL_TYPE,
                'target_range' => Item::TARGET_RANGE_SINGLE,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => 50,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 100,
                'description' => '冒険初心者の必需品。仲間1人のHPを50回復。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'ライフエリクサ',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'heal_type' => Item::HEAL_HP_TYPE,
                'effect_type' => Item::EFFECT_HEAL_TYPE,
                'target_range' => Item::TARGET_RANGE_SINGLE,
                'is_percent_based' => true,
                'percent' => 0.5,
                'fixed_value' => null,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 1000,
                'description' => '服用者の生命力に効果が依存する薬。仲間1人のHPを50%分回復。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 3,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'オールポーション',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'heal_type' => Item::HEAL_HP_TYPE,
                'effect_type' => Item::EFFECT_HEAL_TYPE,
                'target_range' => Item::TARGET_RANGE_ALL,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => 40,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 1000,
                'description' => '冒険中級者の必需品。仲間全員のHPを40回復。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 3,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'オールライフエリクサ',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'heal_type' => Item::HEAL_HP_TYPE,
                'effect_type' => Item::EFFECT_HEAL_TYPE,
                'target_range' => Item::TARGET_RANGE_ALL,
                'is_percent_based' => true,
                'percent' => 1.0,
                'fixed_value' => null,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 30000,
                'description' => '仲間全員のHPを全回復。',
                'is_buyable' => false,
                'is_battle_available' => true,
                'required_clears' => 7,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 11,
                'name' => 'マナドロップ',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'heal_type' => Item::HEAL_AP_TYPE,
                'effect_type' => Item::EFFECT_HEAL_TYPE,
                'target_range' => Item::TARGET_RANGE_SINGLE,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => 20,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 500,
                'description' => '小さなマナの雫。仲間1人のAPを20回復。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 12,
                'name' => 'マナエリクサ',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'heal_type' => Item::HEAL_AP_TYPE,
                'effect_type' => Item::EFFECT_HEAL_TYPE,
                'target_range' => Item::TARGET_RANGE_SINGLE,
                'is_percent_based' => true,
                'percent' => 0.5,
                'fixed_value' => null,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 3000,
                'description' => '純度の高いマナの詰まった水。仲間1人のAPを50%回復。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 3,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 13,
                'name' => 'マナオール', // 名前あとで
                'attack_type' => Item::ATTACK_NO_TYPE,
                'heal_type' => Item::HEAL_AP_TYPE,
                'effect_type' => Item::EFFECT_HEAL_TYPE,
                'target_range' => Item::TARGET_RANGE_ALL,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => 20,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 3000,
                'description' => '仲間全員のAPを20回復。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 3,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 14,
                'name' => 'オールマナエリクサ',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'heal_type' => Item::HEAL_AP_TYPE,
                'effect_type' => Item::EFFECT_HEAL_TYPE,
                'target_range' => Item::TARGET_RANGE_ALL,
                'is_percent_based' => true,
                'percent' => 1.0,
                'fixed_value' => null,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 3000,
                'description' => '仲間全員のAPを全回復。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 7,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 21,
                'name' => 'アタックドロップ',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'effect_type' => Item::EFFECT_BUFF_TYPE,
                'target_range' => Item::TARGET_RANGE_SINGLE,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => 20,
                'buff_turn' => 4,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 300,
                'description' => '飲むと一時的に攻撃力が上昇する不思議な雫。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 3,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 22,
                'name' => 'アタックミスト',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'effect_type' => Item::EFFECT_BUFF_TYPE,
                'target_range' => Item::TARGET_RANGE_ALL,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => 20,
                'buff_turn' => 4,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 1000,
                'description' => '振り撒くことで一時的に味方全員の攻撃力が上昇する。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 5,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 31,
                'name' => 'ミニボム',
                'attack_type' => Item::ATTACK_PHYSICAL_TYPE,
                'effect_type' => Item::EFFECT_DAMAGE_TYPE,
                'target_range' => Item::TARGET_RANGE_SINGLE,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => 100,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 300,
                'description' => '敵単体にダメージを与える。小さいが火力は充分。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 32,
                'name' => 'ターミネイトボム',
                'attack_type' => Item::ATTACK_PHYSICAL_TYPE,
                'effect_type' => Item::EFFECT_DAMAGE_TYPE,
                'target_range' => Item::TARGET_RANGE_ALL,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => 200,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 5000,
                'description' => '広い範囲を吹っ飛ばし敵全体に大ダメージ。取扱注意！',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 5,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 33,
                'name' => 'エーテルカプセル',
                'attack_type' => Item::ATTACK_MAGIC_TYPE,
                'effect_type' => Item::EFFECT_DAMAGE_TYPE,
                'target_range' => Item::TARGET_RANGE_SINGLE,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => '100',
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 1000,
                'description' => '無属性の魔法が込められたカプセル。投げつけた敵単体に魔法攻撃。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 3,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 34,
                'name' => 'エーテルクリスタル',
                'attack_type' => Item::ATTACK_MAGIC_TYPE,
                'effect_type' => Item::EFFECT_DAMAGE_TYPE,
                'target_range' => Item::TARGET_RANGE_ALL,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => '150',
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 8000,
                'description' => 'クリスタルに込めた魔導を解き放ち、敵全体に魔法攻撃。',
                'is_buyable' => true,
                'is_battle_available' => true,
                'required_clears' => 7,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'id' => 90,
                'name' => '金塊',
                'attack_type' => Item::ATTACK_NO_TYPE,
                'effect_type' => Item::EFFECT_SPECIAL_TYPE,
                'target_range' => Item::TARGET_RANGE_SELF,
                'is_percent_based' => false,
                'percent' => null,
                'fixed_value' => null,
                'buff_turn' => null,
                'elemental_id' => 0,
                'max_possession_number' => 3,
                'price' => 60000,
                'description' => '売るとお金になる。',
                'is_buyable' => false,
                'is_battle_available' => false,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

    }
}

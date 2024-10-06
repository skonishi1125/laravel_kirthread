<?php

namespace Database\Seeders\rpg;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Game\Rpg\Item;
use Carbon\Carbon;

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
            'name' => 'ヒールポーション',
            'attack_type' => Item::ATTACK_NO_TYPE,
            'effect_type' => Item::EFFECT_HEAL_TYPE,
            'target_range' => Item::TARGET_RANGE_SINGLE,
            'is_percent_based' => false,
            'percent' => null,
            'fixed_value' => 50,
            'buff_turn' => null,
            'elemental_id' => 0,
            'max_possesion_number' => 3,
            'price' => 100,
            'description' => '仲間1人のHPを50回復。',
            'is_buyable' => true,
            'created_at' => $now, 'updated_at' => $now,
          ],
          [
            'id' => 2,
            'name' => 'マナウォーター',
            'attack_type' => Item::ATTACK_NO_TYPE,
            'effect_type' => Item::EFFECT_HEAL_TYPE,
            'target_range' => Item::TARGET_RANGE_SINGLE,
            'is_percent_based' => false,
            'percent' => null,
            'fixed_value' => 20,
            'buff_turn' => null,
            'elemental_id' => 0,
            'max_possesion_number' => 3,
            'price' => 300,
            'description' => '仲間1人のMPを20回復。',
            'is_buyable' => true,
            'created_at' => $now, 'updated_at' => $now,
          ],
          [
            'id' => 3,
            'name' => 'エリクサー',
            'attack_type' => Item::ATTACK_NO_TYPE,
            'effect_type' => Item::EFFECT_HEAL_TYPE,
            'target_range' => Item::TARGET_RANGE_SINGLE,
            'is_percent_based' => true,
            'percent' => 0.5,
            'fixed_value' => null,
            'buff_turn' => null,
            'elemental_id' => 0,
            'max_possesion_number' => 3,
            'price' => 1000,
            'description' => '仲間1人のHPを50%分回復。',
            'is_buyable' => true,
            'created_at' => $now, 'updated_at' => $now,
          ],
          [
            'id' => 4,
            'name' => 'オールポーション',
            'attack_type' => Item::ATTACK_NO_TYPE,
            'effect_type' => Item::EFFECT_HEAL_TYPE,
            'target_range' => Item::TARGET_RANGE_ALL,
            'is_percent_based' => false,
            'percent' => null,
            'fixed_value' => 40,
            'buff_turn' => null,
            'elemental_id' => 0,
            'max_possesion_number' => 3,
            'price' => 1000,
            'description' => '仲間全員のHPを40回復。',
            'is_buyable' => true,
            'created_at' => $now, 'updated_at' => $now,
          ],
          [
            'id' => 5,
            'name' => 'ラストエリクサー',
            'attack_type' => Item::ATTACK_NO_TYPE,
            'effect_type' => Item::EFFECT_HEAL_TYPE,
            'target_range' => Item::TARGET_RANGE_ALL,
            'is_percent_based' => true,
            'percent' => 1.0,
            'fixed_value' => null,
            'buff_turn' => null,
            'elemental_id' => 0,
            'max_possesion_number' => 3,
            'price' => 30000,
            'description' => '仲間全員の体力を全回復。',
            'is_buyable' => false,
            'created_at' => $now, 'updated_at' => $now,
          ],
          [
            'id' => 6,
            'name' => 'ミニボム',
            'attack_type' => Item::ATTACK_PHYSICAL_TYPE,
            'effect_type' => Item::EFFECT_DAMAGE_TYPE,
            'target_range' => Item::TARGET_RANGE_SINGLE,
            'is_percent_based' => false,
            'percent' => null,
            'fixed_value' => 100,
            'buff_turn' => null,
            'elemental_id' => 0,
            'max_possesion_number' => 3,
            'price' => 300,
            'description' => '敵単体にダメージを与える。小さいが火力は充分。',
            'is_buyable' => true,
            'created_at' => $now, 'updated_at' => $now,
          ],
          [
            'id' => 7,
            'name' => 'ターミネイトボム',
            'attack_type' => Item::ATTACK_PHYSICAL_TYPE,
            'effect_type' => Item::EFFECT_DAMAGE_TYPE,
            'target_range' => Item::TARGET_RANGE_ALL,
            'is_percent_based' => false,
            'percent' => null,
            'fixed_value' => 200,
            'buff_turn' => null,
            'elemental_id' => 0,
            'max_possesion_number' => 3,
            'price' => 5000,
            'description' => '敵全体に大ダメージを与える。取扱注意！',
            'is_buyable' => true,
            'created_at' => $now, 'updated_at' => $now,
          ],
          [
            'id' => 8,
            'name' => 'アタックドロップ',
            'attack_type' => Item::ATTACK_NO_TYPE,
            'effect_type' => Item::EFFECT_BUFF_TYPE,
            'target_range' => Item::TARGET_RANGE_SINGLE,
            'is_percent_based' => true,
            'percent' => 0.2,
            'fixed_value' => null,
            'buff_turn' => 3,
            'elemental_id' => 0,
            'max_possesion_number' => 3,
            'price' => 300,
            'description' => '飲むと攻撃力が上昇する不思議な雫。',
            'is_buyable' => true,
            'created_at' => $now, 'updated_at' => $now,
          ],
          [
            'id' => 9,
            'name' => 'アタックミスト',
            'attack_type' => Item::ATTACK_NO_TYPE,
            'effect_type' => Item::EFFECT_BUFF_TYPE,
            'target_range' => Item::TARGET_RANGE_ALL,
            'is_percent_based' => true,
            'percent' => 0.2,
            'fixed_value' => null,
            'buff_turn' => 3,
            'elemental_id' => 0,
            'max_possesion_number' => 3,
            'price' => 1000,
            'description' => '振り撒くことで味方全員の攻撃力が上昇する。',
            'is_buyable' => true,
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
            'max_possesion_number' => 3,
            'price' => 60000,
            'description' => '売るとお金になる。',
            'is_buyable' => false,
            'created_at' => $now, 'updated_at' => $now,
          ],
        ];

        foreach ($items as $item) {
          Item::create($item);
        }



    }
}

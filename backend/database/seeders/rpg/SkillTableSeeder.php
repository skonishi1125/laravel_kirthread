<?php

namespace Database\Seeders\rpg;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Game\Rpg\Skill;
use App\Models\Game\Rpg\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
      $now = Carbon::now();

      // データベースリセット
      Skill::truncate();

      $skills = [
        // 格闘家スキル
        [
          'id' => '10',
          'name' => 'ミドルブロウ',
          'available_role_id' => Role::ROLE_STRIKER,
          'attack_type' => Skill::ATTACK_PHYSICAL_TYPE,
          'effect_type' => Skill::EFFECT_DAMAGE_TYPE,
          'target_range' => Skill::TARGET_RANGE_SINGLE,
          'lv1_percent' => 1.2,
          'lv1_ap_cost' => 6,
          'lv2_percent' => 1.4,
          'lv2_ap_cost' => 8,
          'lv3_percent' => 2.0,
          'lv3_ap_cost' => 10,
          'elemental_id' => 1,
          'description' => '敵単体に素早く拳を叩き込む。',
          'created_at' => $now,
          'updated_at' => $now
        ],
        [
          'id' => '11',
          'name' => 'スピンキック',
          'available_role_id' => Role::ROLE_STRIKER,
          'attack_type' => Skill::ATTACK_PHYSICAL_TYPE,
          'effect_type' => Skill::EFFECT_DAMAGE_TYPE,
          'target_range' => Skill::TARGET_RANGE_ALL,
          'lv1_percent' => 0.8,
          'lv1_ap_cost' => 10,
          'lv2_percent' => 1.0,
          'lv2_ap_cost' => 12,
          'lv3_percent' => 1.3,
          'lv3_ap_cost' => 16,
          'elemental_id' => 1,
          'description' => '敵全体に鋭い回転蹴り。',
          'created_at' => $now,
          'updated_at' => $now
        ],

        // トランスフォーム、タイタンブレイク

        // 治療師スキル
        // 重騎士スキル
        [
          'id' => '30',
          'name' => 'ワイドスラスト',
          'available_role_id' => Role::ROLE_PARADIN,
          'attack_type' => Skill::ATTACK_PHYSICAL_TYPE,
          'effect_type' => Skill::EFFECT_DAMAGE_TYPE,
          'target_range' => Skill::TARGET_RANGE_ALL,
          'lv1_percent' => 0.5,
          'lv1_ap_cost' => 8,
          'lv2_percent' => 0.7,
          'lv2_ap_cost' => 12,
          'lv3_percent' => 1.25,
          'lv3_ap_cost' => 15,
          'elemental_id' => 1,
          'description' => '敵全体を力強く薙ぎ払う。',
          'created_at' => $now,
          'updated_at' => $now
        ],
        [
          'id' => '31',
          'name' => 'ワイドガード',
          'available_role_id' => Role::ROLE_PARADIN,
          'attack_type' => Skill::ATTACK_NO_TYPE,
          'effect_type' => Skill::EFFECT_BUFF_TYPE,
          'target_range' => Skill::TARGET_RANGE_ALL,
          'lv1_percent' => 0.3,
          'lv1_ap_cost' => 3,
          'lv1_buff_turn' => 1,
          'lv2_percent' => 0.5,
          'lv2_ap_cost' => 5,
          'lv2_buff_turn' => 1,
          'lv3_percent' => 0.8,
          'lv3_ap_cost' => 5,
          'lv3_buff_turn' => 1,
          'elemental_id' => 1,
          'description' => '使用ターンの味方全員の物理ダメージを軽減する。',
          'created_at' => $now,
          'updated_at' => $now
        ],
        [
          'id' => '32',
          'name' => 'ブレイヴスラッシュ',
          'available_role_id' => Role::ROLE_PARADIN,
          'attack_type' => Skill::ATTACK_PHYSICAL_TYPE,
          'effect_type' => Skill::EFFECT_DAMAGE_TYPE,
          'target_range' => Skill::TARGET_RANGE_SINGLE,
          'lv1_percent' => 1.0,
          'lv1_ap_cost' => 7,
          'lv2_percent' => 1.3,
          'lv2_ap_cost' => 10,
          'lv3_percent' => 2.0,
          'lv3_ap_cost' => 12,
          'elemental_id' => 1,
          'description' => '敵単体に壮烈の一撃。自分の防御力に依存したダメージを与える。',
          'created_at' => $now,
          'updated_at' => $now
        ],
        // ブラッディムーン



        // 魔導士スキル
        [
          'id' => '40',
          'name' => 'ミニヒール',
          'available_role_id' => Role::ROLE_MAGE,
          'attack_type' => Skill::ATTACK_MAGIC_TYPE,
          'effect_type' => Skill::EFFECT_HEAL_TYPE,
          'target_range' => Skill::TARGET_RANGE_SINGLE,
          'lv1_percent' => 1.0,
          'lv1_ap_cost' => 5,
          'lv2_percent' => 1.2,
          'lv2_ap_cost' => 6,
          'lv3_percent' => 1.5,
          'lv3_ap_cost' => 7,
          'elemental_id' => 1,
          'description' => '味方1人のHPを回復する呪文を唱える。初歩的な回復魔法のひとつ。',
          'created_at' => $now,
          'updated_at' => $now
        ],
        [
          'id' => '41',
          'name' => 'ポップヒール',
          'available_role_id' => Role::ROLE_MAGE,
          'attack_type' => Skill::ATTACK_MAGIC_TYPE,
          'effect_type' => Skill::EFFECT_HEAL_TYPE,
          'target_range' => Skill::TARGET_RANGE_ALL,
          'lv1_percent' => 0.5,
          'lv1_ap_cost' => 10,
          'lv2_percent' => 0.7,
          'lv2_ap_cost' => 13,
          'lv3_percent' => 1.0,
          'lv3_ap_cost' => 15,
          'elemental_id' => 1,
          'description' => '回復魔力を周囲に浮かべ、全体のHPを回復する。',
          'created_at' => $now,
          'updated_at' => $now
        ],
        [
          'id' => '42',
          'name' => 'プチブラスト',
          'available_role_id' => Role::ROLE_MAGE,
          'attack_type' => Skill::ATTACK_MAGIC_TYPE,
          'effect_type' => Skill::EFFECT_DAMAGE_TYPE,
          'target_range' => Skill::TARGET_RANGE_SINGLE,
          'lv1_percent' => 0.8,
          'lv1_ap_cost' => 3,
          'lv2_percent' => 1.0,
          'lv2_ap_cost' => 3,
          'lv3_percent' => 1.3,
          'lv3_ap_cost' => 4,
          'elemental_id' => 1,
          'description' => '小さな魔力弾を敵単体に放つ、低コストな攻撃手段。',
          'created_at' => $now,
          'updated_at' => $now
        ],
        [
          'id' => '43',
          'name' => 'クラッシュボルト',
          'available_role_id' => Role::ROLE_MAGE,
          'attack_type' => Skill::ATTACK_MAGIC_TYPE,
          'effect_type' => Skill::EFFECT_DAMAGE_TYPE,
          'target_range' => Skill::TARGET_RANGE_SINGLE,
          'lv1_percent' => 1.5,
          'lv1_ap_cost' => 10,
          'lv2_percent' => 2.0,
          'lv2_ap_cost' => 15,
          'lv3_percent' => 3.0,
          'lv3_ap_cost' => 20,
          'elemental_id' => 1,
          'description' => 'マナの塊を撃ち放つ。敵単体に大ダメージ。',
          'created_at' => $now,
          'updated_at' => $now
        ],
        [
          'id' => '44',
          'name' => 'マナエクスプロージョン',
          'available_role_id' => Role::ROLE_MAGE,
          'attack_type' => Skill::ATTACK_MAGIC_TYPE,
          'effect_type' => Skill::EFFECT_DAMAGE_TYPE,
          'target_range' => Skill::TARGET_RANGE_ALL,
          'lv1_percent' => 2.0,
          'lv1_ap_cost' => 40,
          'lv2_percent' => 2.5,
          'lv2_ap_cost' => 50,
          'lv3_percent' => 3.0,
          'lv3_ap_cost' => 60,
          'elemental_id' => 1,
          'description' => '巨大なマナの塊を爆発させ、敵全体に大ダメージを与える。',
          'created_at' => $now,
          'updated_at' => $now
        ],
        // バトルメイジ

        // 敵の使用するスキル 100番以降

      ];

      foreach ($skills as $skill) {
        Skill::create($skill);
      }


    }
}

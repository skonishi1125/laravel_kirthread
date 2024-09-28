<?php

namespace Database\Seeders\rpg;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Game\Rpg\Skill;
use App\Models\Game\Rpg\Role;
use Carbon\Carbon;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $now = Carbon::now();

      // データベースリセット
      Role::truncate();

      $roles = [
        [
          'id' => Role::ROLE_STRIKER,
          'class' => 'striker',
          'class_kana' => '格闘家',
          'growth_hp'  => 4,
          'growth_ap'  => 2,
          'growth_str' => 5,
          'growth_def' => 1,
          'growth_int' => 1,
          'growth_spd' => 5,
          'growth_luc' => 2,
          'portrait_image_path' => 'portrait_striker.png',
          'cutscene_image_path' => 'cutscene.png',
          'description' => '素早い手数と高火力が魅力の職業でHPも高いが、防御が低いため過信は禁物。',
          'created_at' => $now,
          'updated_at' => $now,
        ],
        [
          'id' => Role::ROLE_MEDIC,
          'class' => 'medic',
          'class_kana' => '治療師',
          'growth_hp'  => 3,
          'growth_ap'  => 4,
          'growth_str' => 2,
          'growth_def' => 3,
          'growth_int' => 4,
          'growth_spd' => 2,
          'growth_luc' => 2,
          'portrait_image_path' => 'portrait_medic.png',
          'cutscene_image_path' => 'cutscene.png',
          'description' => '仲間を助ける回復のスペシャリスト。物理・魔法攻撃も多少はこなせる。',
          'created_at' => $now,
          'updated_at' => $now,
        ],
        [
          'id' => Role::ROLE_PARADIN,
          'class' => 'paradin',
          'class_kana' => '重騎士',
          'growth_hp'  => 5,
          'growth_ap'  => 3,
          'growth_str' => 3,
          'growth_def' => 5,
          'growth_int' => 1,
          'growth_spd' => 1,
          'growth_luc' => 2,
          'portrait_image_path' => 'portrait_paradin.png',
          'cutscene_image_path' => 'cutscene.png',
          'description' => '最も打たれ強くパーティの心強い盾となる。ただし行動速度は全職業で一番遅い',
          'created_at' => $now,
          'updated_at' => $now,
        ],
        [
          'id' => Role::ROLE_MAGE,
          'class' => 'mage',
          'class_kana' => '魔導士',
          'growth_hp'  => 2,
          'growth_ap'  => 5,
          'growth_str' => 1,
          'growth_def' => 2,
          'growth_int' => 5,
          'growth_spd' => 3,
          'growth_luc' => 2,
          'portrait_image_path' => 'portrait_mage.png',
          'cutscene_image_path' => 'cutscene.png',
          'description' => '多様な魔法を使いこなして戦う。APを使用した高火力の一撃が魅力。',
          'created_at' => $now,
          'updated_at' => $now,
        ],
        [
          'id' => Role::ROLE_RANGER,
          'class' => 'ranger',
          'class_kana' => '弓馭者', // ゆみぎょしゃ
          'growth_hp'  => 4,
          'growth_ap'  => 3,
          'growth_str' => 3,
          'growth_def' => 2,
          'growth_int' => 2,
          'growth_spd' => 4,
          'growth_luc' => 2,
          'portrait_image_path' => 'portrait_ranger.png',
          'cutscene_image_path' => 'cutscene.png',
          'description' => '持久力のある戦いを得意とし、パーティの補完となるパラメーターバランスの良い職業',
          'created_at' => $now,
          'updated_at' => $now,
        ],
        [
          'id' => Role::ROLE_BUFFER,
          'class' => 'buffer',
          'class_kana' => '理術師',
          'growth_hp'  => 3,
          'growth_ap'  => 4,
          'growth_str' => 1,
          'growth_def' => 3,
          'growth_int' => 3,
          'growth_spd' => 4,
          'growth_luc' => 2,
          'portrait_image_path' => 'portrait_ranger.png',
          'cutscene_image_path' => 'cutscene.png',
          'description' => '味方全員のステータスを高めるスキルを豊富に持つ、優秀なサポーター。',
          'created_at' => $now,
          'updated_at' => $now,
        ],
      ];

      foreach ($roles as $role) {
        Role::create($role);
      }

    }
}

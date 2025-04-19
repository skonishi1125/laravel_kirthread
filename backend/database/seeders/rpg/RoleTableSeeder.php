<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
                'class' => Role::ROLE_STRIKER_CLASS_NAME,
                'class_japanese' => '格闘家',
                'default_name' => 'スト',
                'growth_hp' => 4,
                'growth_ap' => 2,
                'growth_str' => 5,
                'growth_def' => 1,
                'growth_int' => 1,
                'growth_spd' => 5,
                'growth_luc' => 2,
                'portrait_image_path' => 'portrait_striker.png',
                'cutscene_image_path' => 'cutscene.png',
                'description' => '素早さが魅力の高火力物理アタッカー。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Role::ROLE_MEDIC,
                'class' => Role::ROLE_MEDIC_CLASS_NAME,
                'class_japanese' => '治療師',
                'default_name' => 'メディ',
                'growth_hp' => 3,
                'growth_ap' => 4,
                'growth_str' => 2,
                'growth_def' => 3,
                'growth_int' => 4,
                'growth_spd' => 2,
                'growth_luc' => 2,
                'portrait_image_path' => 'portrait_medic.png',
                'cutscene_image_path' => 'cutscene.png',
                'description' => '味方を癒す回復のスペシャリスト。攻撃も多少こなせる。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Role::ROLE_PALADIN,
                'class' => Role::ROLE_PALADIN_CLASS_NAME,
                'class_japanese' => '重騎士',
                'default_name' => 'パラ',
                'growth_hp' => 5,
                'growth_ap' => 3,
                'growth_str' => 3,
                'growth_def' => 5,
                'growth_int' => 1,
                'growth_spd' => 1,
                'growth_luc' => 2,
                'portrait_image_path' => 'portrait_paladin.png',
                'cutscene_image_path' => 'cutscene.png',
                'description' => '最も打たれ強く、仲間たちの心強い盾となる。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Role::ROLE_MAGE,
                'class' => Role::ROLE_MAGE_CLASS_NAME,
                'class_japanese' => '魔導師',
                'default_name' => 'メイ',
                'growth_hp' => 2,
                'growth_ap' => 5,
                'growth_str' => 1,
                'growth_def' => 2,
                'growth_int' => 5,
                'growth_spd' => 3,
                'growth_luc' => 2,
                'portrait_image_path' => 'portrait_mage.png',
                'cutscene_image_path' => 'cutscene.png',
                'description' => 'スキルを使用した高火力の魔法攻撃が魅力。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Role::ROLE_RANGER,
                'class' => Role::ROLE_RANGER_CLASS_NAME,
                'class_japanese' => '弓馭者', // ゆみぎょしゃ
                'default_name' => 'レン',
                'growth_hp' => 4,
                'growth_ap' => 3,
                'growth_str' => 3,
                'growth_def' => 2,
                'growth_int' => 2,
                'growth_spd' => 4,
                'growth_luc' => 2,
                'portrait_image_path' => 'portrait_ranger.png',
                'cutscene_image_path' => 'cutscene.png',
                'description' => 'バランス良くなんでもこなせる、パーティの補完的職業。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => Role::ROLE_BUFFER,
                'class' => Role::ROLE_BUFFER_CLASS_NAME,
                'class_japanese' => '理術師',
                'default_name' => 'バファ',
                'growth_hp' => 3,
                'growth_ap' => 4,
                'growth_str' => 1,
                'growth_def' => 3,
                'growth_int' => 3,
                'growth_spd' => 4,
                'growth_luc' => 2,
                'portrait_image_path' => 'portrait_buffer.png',
                'cutscene_image_path' => 'cutscene.png',
                'description' => '仲間のステータスを高める力を持つサポーター。',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

    }
}

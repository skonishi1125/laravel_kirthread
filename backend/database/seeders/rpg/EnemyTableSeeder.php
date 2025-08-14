<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\EnemyData;
use App\Models\Game\Rpg\Enemy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EnemyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        Enemy::truncate();

        $enemies = [
            [
                'id' => EnemyData::Srara,
                'name' => EnemyData::Srara->label(),
                'appear_field_id' => 1,
                'value_hp' => 80,
                'value_ap' => 0,
                'value_str' => 18,
                'value_def' => 10,
                'value_int' => 0,
                'value_spd' => 10,
                'value_luc' => 0,
                'exp' => 15,
                'drop_money' => 10,
                'portrait_image_path' => EnemyData::Srara->image_path(),
                'description' => EnemyData::Srara->description(),
                'is_boss' => false,
                'has_pattern' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => EnemyData::Gao,
                'name' => EnemyData::Gao->label(),
                'appear_field_id' => 1,
                'value_hp' => 100,
                'value_ap' => 0,
                'value_str' => 25,
                'value_def' => 10,
                'value_int' => 0,
                'value_spd' => 30,
                'value_luc' => 0,
                'exp' => 50,
                'drop_money' => 15,
                'portrait_image_path' => EnemyData::Gao->image_path(),
                'description' => EnemyData::Gao->description(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => EnemyData::Norawani,
                'name' => EnemyData::Norawani->label(),
                'appear_field_id' => 1,
                'value_hp' => 160,
                'value_ap' => 0,
                'value_str' => 25,
                'value_def' => 20,
                'value_int' => 20,
                'value_spd' => 25,
                'value_luc' => 0,
                'exp' => 100,
                'drop_money' => 50,
                'portrait_image_path' => EnemyData::Norawani->image_path(),
                'description' => EnemyData::Norawani->description(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => EnemyData::Ikkaku,
                'name' => EnemyData::Ikkaku->label(),
                'appear_field_id' => 1,
                'value_hp' => 130,
                'value_ap' => 10,
                'value_str' => 25,
                'value_def' => 55,
                'value_int' => 40,
                'value_spd' => 30,
                'value_luc' => 0,
                'exp' => 150,
                'drop_money' => 100,
                'portrait_image_path' => EnemyData::Ikkaku->image_path(),
                'description' => EnemyData::Ikkaku->description(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => EnemyData::Oyadamawani,
                'name' => EnemyData::Oyadamawani->label(),
                'appear_field_id' => 1,
                'value_hp' => 600,
                'value_ap' => 30,
                'value_str' => 50,
                'value_def' => 50,
                'value_int' => 20,
                'value_spd' => 40,
                'value_luc' => 30,
                'exp' => 1500,
                'drop_money' => 500,
                'portrait_image_path' => EnemyData::Oyadamawani->image_path(),
                'description' => EnemyData::Oyadamawani->description(),
                'is_boss' => true,
                'has_pattern' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // field 2
            [
                'id' => EnemyData::HighSrara,
                'name' => EnemyData::HighSrara->label(),
                'appear_field_id' => 2,
                'value_hp' => 120,
                'value_ap' => 20,
                'value_str' => 40,
                'value_def' => 10,
                'value_int' => 30,
                'value_spd' => 60,
                'value_luc' => 0,
                'exp' => 200,
                'drop_money' => 900,
                'portrait_image_path' => EnemyData::HighSrara->image_path(),
                'description' => EnemyData::HighSrara->description(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // field 3
            [
                'id' => EnemyData::FlareDrago,
                'name' => EnemyData::FlareDrago->label(),
                'appear_field_id' => 3,
                'value_hp' => 4000,
                'value_ap' => 100,
                'value_str' => 100,
                'value_def' => 80,
                'value_int' => 400,
                'value_spd' => 250,
                'value_luc' => 100,
                'exp' => 4500,
                'drop_money' => 2500,
                'portrait_image_path' => EnemyData::FlareDrago->image_path(),
                'description' => EnemyData::FlareDrago->description(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($enemies as $enemy) {
            Enemy::create($enemy);
        }

    }
}

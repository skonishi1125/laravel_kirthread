<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\PresetAppearingEnemy;
use Illuminate\Database\Seeder;

class PresetAppearingEnemyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        PresetAppearingEnemy::truncate();

        $seeds = [
            // --------- field 1 ---------
            // 1-1 
            [
                'field_id' => 1,
                'stage_id' => 1,
                'enemy_id' => 1,
                'number' => 2,
            ],
            // 1-2
            [
                'field_id' => 1,
                'stage_id' => 2,
                'enemy_id' => 1,
                'number' => 1,
            ],
            [
                'field_id' => 1,
                'stage_id' => 2,
                'enemy_id' => 2,
                'number' => 1,
            ],
            [
                'field_id' => 1,
                'stage_id' => 2,
                'enemy_id' => 1,
                'number' => 1,
            ],
            // 1-3
            [
                'field_id' => 1,
                'stage_id' => 3,
                'enemy_id' => 3,
                'number' => 2,
            ],
            // 1-4
            [
                'field_id' => 1,
                'stage_id' => 4,
                'enemy_id' => 4,
                'number' => 3,
            ],
            // ノラワニがオヤダマワニを囲む形にする
            [
                'field_id' => 1,
                'stage_id' => 5,
                'enemy_id' => 3,
                'number' => 2,
            ],
            [
                'field_id' => 1,
                'stage_id' => 5,
                'enemy_id' => 5,
                'number' => 1,
            ],
            [
                'field_id' => 1,
                'stage_id' => 5,
                'enemy_id' => 3,
                'number' => 2,
            ],
        ];

        foreach ($seeds as $seed) {
            PresetAppearingEnemy::create($seed);
        }

    }
}

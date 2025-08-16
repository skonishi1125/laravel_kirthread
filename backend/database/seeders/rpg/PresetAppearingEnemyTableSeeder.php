<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\EnemyData;
use App\Enums\Rpg\FieldData;
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

        // $seeds = [
        //     // --------- field 1 ---------
        //     // 1-1と1-2は、最初はみんな倒したらついつい進んじゃうとおもう。
        //     // そのため、緩めにしておく
        //     // 1-1
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 1,
        //         'enemy_id' => EnemyData::Srara,
        //         'number' => 1,
        //     ],
        //     // 1-2
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 2,
        //         'enemy_id' => EnemyData::Srara,
        //         'number' => 2,
        //     ],
        //     // 1-3
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 3,
        //         'enemy_id' => EnemyData::Srara,
        //         'number' => 1,
        //     ],
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 3,
        //         'enemy_id' => EnemyData::Gao,
        //         'number' => 1,
        //     ],
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 3,
        //         'enemy_id' => EnemyData::Srara,
        //         'number' => 1,
        //     ],
        //     // 1-4
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 4,
        //         'enemy_id' => EnemyData::Norawani,
        //         'number' => 2,
        //     ],
        //     // 1-5
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 5,
        //         'enemy_id' => EnemyData::Ikkaku,
        //         'number' => 3,
        //     ],
        //     // 1-6(boss) ノラワニがオヤダマワニを囲む形にする
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 6,
        //         'enemy_id' => EnemyData::Norawani,
        //         'number' => 1,
        //     ],
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 6,
        //         'enemy_id' => EnemyData::Oyadamawani,
        //         'number' => 1,
        //     ],
        //     [
        //         'field_id' => FieldData::Grassland,
        //         'stage_id' => 6,
        //         'enemy_id' => EnemyData::Norawani,
        //         'number' => 1,
        //     ],
        // ];

        $seeds = [
            // 1-1
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Srara,
                'number' => 1
            ],
            // 1-2
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Srara,
                'number' => 2
            ],
            // 1-3 ボス スララで囲む
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Srara,
                'number' => 1
            ],
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 3,
                'enemy_id' => EnemyData::BigSrara,
                'number' => 1
            ],
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Srara,
                'number' => 1
            ],
        ];


        foreach ($seeds as $seed) {
            PresetAppearingEnemy::create($seed);
        }

    }
}

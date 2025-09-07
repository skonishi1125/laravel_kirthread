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
        $seeds = [
            // --------- 草原 ---------
            // 1-1と1-2は、最初はみんな適当に進んじゃうと思うので軽めにしておく
            // 1-1
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Srara,
                'number' => 1,
            ],
            // [
            //     'field_id' => FieldData::Grassland,
            //     'stage_id' => 1,
            //     'enemy_id' => EnemyData::FlareDrago,
            //     'number' => 1,
            // ],
            // 1-2
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Srara,
                'number' => 2,
            ],
            // 1-3
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Gao,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Srara,
                'number' => 1,
            ],
            // 1-4 ボス スララで囲む
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Srara,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 4,
                'enemy_id' => EnemyData::BigSrara,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Grassland,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Srara,
                'number' => 1,
            ],
            // --------- 砂漠 ---------
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Gao,
                'number' => 2,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Rizard,
                'number' => 2,
            ],
            //  スコーピオ, ガオー, スコーピオ
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Scorpio,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Gao,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Scorpio,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Rizard,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 4,
                'enemy_id' => EnemyData::RockRizard,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Scorpio,
                'number' => 1,
            ],
            // --------- 火山 ---------
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Bou,
                'number' => 4,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 2,
                'enemy_id' => EnemyData::IwaMet,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Norawani,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Bou,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Norawani,
                'number' => 2,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Bou,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Norawani,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 4,
                'enemy_id' => EnemyData::MagmaDile,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Norawani,
                'number' => 1,
            ],
            // --------- 海岸 ---------
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 1,
                'enemy_id' => EnemyData::MageSrara,
                'number' => 2,
            ],
            // クリオン、メイジスララ、クリオン
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Clion,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 2,
                'enemy_id' => EnemyData::MageSrara,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Clion,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Ikkaku,
                'number' => 2,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Clion,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 4,
                'enemy_id' => EnemyData::SpikeWhale,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Ikkaku,
                'number' => 1,
            ],

            // 氷雪地帯
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Eripen,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 1,
                'enemy_id' => EnemyData::IceFairy,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 1,
                'enemy_id' => EnemyData::ScissorFlipper,
                'number' => 1,
            ],

            // 湿霧の地
            [
                'field_id' => FieldData::WetFog,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Srara,
                'number' => 1,
            ],

            // 常夜の樹海
            [
                'field_id' => FieldData::NightForest,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Srara,
                'number' => 1,
            ],

        ];

        foreach ($seeds as $seed) {
            PresetAppearingEnemy::create($seed);
        }

    }
}

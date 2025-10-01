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
            // stage 1
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Srara,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Gao,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Srara,
                'number' => 1,
            ],
            // stage 2
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Gao,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Rizard,
                'number' => 1,
            ],
            //  stage 3
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Scorpio,
                'number' => 2,
            ],
            // stage 4
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Rizard,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Scorpio,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Rizard,
                'number' => 1,
            ],
            // stage 5
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 5,
                'enemy_id' => EnemyData::Rizard,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 5,
                'enemy_id' => EnemyData::RockRizard,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Desert,
                'stage_id' => 5,
                'enemy_id' => EnemyData::Scorpio,
                'number' => 1,
            ],
            // --------- 火山 ---------
            // stage 1
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Norawani,
                'number' => 1,
            ],
            // stage 2
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Bou,
                'number' => 5,
            ],
            // stage 3
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Bou,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 3,
                'enemy_id' => EnemyData::IwaMet,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Bou,
                'number' => 1,
            ],
            // stage 4
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 4,
                'enemy_id' => EnemyData::IwaMet,
                'number' => 2,
            ],
            // stage 5
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 5,
                'enemy_id' => EnemyData::MagmaDile,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Volcano,
                'stage_id' => 5,
                'enemy_id' => EnemyData::Norawani,
                'number' => 1,
            ],
            // --------- 海岸 ---------
            // stage1
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 1,
                'enemy_id' => EnemyData::MageSrara,
                'number' => 2,
            ],
            // stage2
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
            // stage3
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 3,
                'enemy_id' => EnemyData::MageSrara,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Ikkaku,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 3,
                'enemy_id' => EnemyData::MageSrara,
                'number' => 1,
            ],
            // stage4
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Clion,
                'number' => 2,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Ikkaku,
                'number' => 2,
            ],
            // stage5
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 5,
                'enemy_id' => EnemyData::Ikkaku,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 5,
                'enemy_id' => EnemyData::SpikeWhale,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::Coast,
                'stage_id' => 5,
                'enemy_id' => EnemyData::Ikkaku,
                'number' => 1,
            ],

            // 氷雪地帯
            // stage1 最初きつめ
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 1,
                'enemy_id' => EnemyData::IwaMet,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Ikkaku,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 1,
                'enemy_id' => EnemyData::IwaMet,
                'number' => 1,
            ],
            // stage2 同じくきつめ
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 2,
                'enemy_id' => EnemyData::IceFairy,
                'number' => 1,
            ],
            // stage3 ちょっと優しい設計(ペンギンのレクチャー)
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 3,
                'enemy_id' => EnemyData::Eripen,
                'number' => 1,
            ],
            // stage4
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 4,
                'enemy_id' => EnemyData::Eripen,
                'number' => 3,
            ],
            // stage5
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 5,
                'enemy_id' => EnemyData::Eripen,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 5,
                'enemy_id' => EnemyData::ScissorFlipper,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::IceAndSnow,
                'stage_id' => 5,
                'enemy_id' => EnemyData::Eripen,
                'number' => 1,
            ],

            // WetFog
            // stage1
            // [
            //     'field_id' => FieldData::WetFog,
            //     'stage_id' => 1,
            //     'enemy_id' => EnemyData::Norawani,
            //     'number' => 1,
            // ],
            // [
            //     'field_id' => FieldData::WetFog,
            //     'stage_id' => 1,
            //     'enemy_id' => EnemyData::Rizard,
            //     'number' => 1,
            // ],
            // [
            //     'field_id' => FieldData::WetFog,
            //     'stage_id' => 1,
            //     'enemy_id' => EnemyData::Norawani,
            //     'number' => 1,
            // ],
            // // stage 2
            // [
            //     'field_id' => FieldData::WetFog,
            //     'stage_id' => 2,
            //     'enemy_id' => EnemyData::Nepenthos,
            //     'number' => 1,
            // ],
            // [
            //     'field_id' => FieldData::WetFog,
            //     'stage_id' => 2,
            //     'enemy_id' => EnemyData::HazardBerry,
            //     'number' => 1,
            // ],
            // stage 3
            [
                'field_id' => FieldData::WetFog,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Dionaea,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::WetFog,
                'stage_id' => 3,
                'enemy_id' => EnemyData::HazardBerry,
                'number' => 1,
            ],

            // [
            //     'field_id' => FieldData::WetFog,
            //     'stage_id' => 1,
            //     'enemy_id' => EnemyData::Nepenthos,
            //     'number' => 1,
            // ],
            // [
            //     'field_id' => FieldData::WetFog,
            //     'stage_id' => 1,
            //     'enemy_id' => EnemyData::Dionaea,
            //     'number' => 1,
            // ],
            // [
            //     'field_id' => FieldData::WetFog,
            //     'stage_id' => 1,
            //     'enemy_id' => EnemyData::HazardBerry,
            //     'number' => 1,
            // ],
            // [
            //     'field_id' => FieldData::WetFog,
            //     'stage_id' => 2,
            //     'enemy_id' => EnemyData::WandEater,
            //     'number' => 1,
            // ],

            // 常夜の森
            [
                'field_id' => FieldData::NightForest,
                'stage_id' => 1,
                'enemy_id' => EnemyData::HoshiHotaru,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::NightForest,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Gyao,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::NightForest,
                'stage_id' => 1,
                'enemy_id' => EnemyData::ShadowWeed,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::NightForest,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Twilight,
                'number' => 1,
            ],

            // 退廃
            [
                'field_id' => FieldData::DecayedFarmland,
                'stage_id' => 1,
                'enemy_id' => EnemyData::CurseScareCrow,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::DecayedFarmland,
                'stage_id' => 1,
                'enemy_id' => EnemyData::DustBomb,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::DecayedFarmland,
                'stage_id' => 1,
                'enemy_id' => EnemyData::ZombieClion,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::DecayedFarmland,
                'stage_id' => 2,
                'enemy_id' => EnemyData::Narehate,
                'number' => 1,
            ],

            // 門前雀羅
            [
                'field_id' => FieldData::CastleTown,
                'stage_id' => 1,
                'enemy_id' => EnemyData::DarkSrara,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::CastleTown,
                'stage_id' => 1,
                'enemy_id' => EnemyData::BazaarRizard,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::CastleTown,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Anima,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::CastleTown,
                'stage_id' => 1,
                'enemy_id' => EnemyData::GolemBall,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::CastleTown,
                'stage_id' => 2,
                'enemy_id' => EnemyData::StoneGolem,
                'number' => 1,
            ],

            // 古城
            [
                'field_id' => FieldData::AncientCastle,
                'stage_id' => 1,
                'enemy_id' => EnemyData::GaiaHand,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::AncientCastle,
                'stage_id' => 1,
                'enemy_id' => EnemyData::DeathScorpio,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::AncientCastle,
                'stage_id' => 1,
                'enemy_id' => EnemyData::MetalGecko,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::AncientCastle,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Stinger,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::AncientCastle,
                'stage_id' => 1,
                'enemy_id' => EnemyData::PlasmaBook,
                'number' => 1,
            ],

            // 茫洋の地
            [
                'field_id' => FieldData::VastExpanse,
                'stage_id' => 1,
                'enemy_id' => EnemyData::Celavie,
                'number' => 1,
            ],
            [
                'field_id' => FieldData::VastExpanse,
                'stage_id' => 1,
                'enemy_id' => EnemyData::GrandCube,
                'number' => 1,
            ],

        ];

        foreach ($seeds as $seed) {
            PresetAppearingEnemy::create($seed);
        }

    }
}

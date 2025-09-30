<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\FieldData;
use App\Models\Game\Rpg\Field;
use Illuminate\Database\Seeder;

class FieldTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Field::truncate();

        $seeds = [
            [
                'id' => FieldData::Grassland,
                'name' => FieldData::Grassland->label(),
                'background_image_path' => FieldData::Grassland->image_path(),
                'difficulty' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::Desert,
                'name' => FieldData::Desert->label(),
                'background_image_path' => FieldData::Desert->image_path(),
                'difficulty' => 2,
                'required_clears' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::Volcano,
                'name' => FieldData::Volcano->label(),
                'background_image_path' => FieldData::Volcano->image_path(),
                'difficulty' => 3,
                'required_clears' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::Coast,
                'name' => FieldData::Coast->label(),
                'background_image_path' => FieldData::Coast->image_path(),
                'difficulty' => 3,
                'required_clears' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::IceAndSnow,
                'name' => FieldData::IceAndSnow->label(),
                'background_image_path' => FieldData::IceAndSnow->image_path(),
                'difficulty' => 4,
                'required_clears' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::WetFog,
                'name' => FieldData::WetFog->label(),
                'background_image_path' => FieldData::WetFog->image_path(),
                'difficulty' => 4,
                'required_clears' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::NightForest,
                'name' => FieldData::NightForest->label(),
                'background_image_path' => FieldData::NightForest->image_path(),
                'difficulty' => 4,
                'required_clears' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::DecayedFarmland,
                'name' => FieldData::DecayedFarmland->label(),
                'background_image_path' => FieldData::DecayedFarmland->image_path(),
                'difficulty' => 6,
                'required_clears' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::CastleTown,
                'name' => FieldData::CastleTown->label(),
                'background_image_path' => FieldData::CastleTown->image_path(),
                'difficulty' => 5,
                'required_clears' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::AncientCastle,
                'name' => FieldData::AncientCastle->label(),
                'background_image_path' => FieldData::AncientCastle->image_path(),
                'difficulty' => 6,
                'required_clears' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::VastExpanse,
                'name' => FieldData::VastExpanse->label(),
                'background_image_path' => FieldData::VastExpanse->image_path(),
                'difficulty' => 7,
                /**
                 * ステージ全てをクリアしても開放されないように、11としておく。
                 * 詳細には古城クリア && 耕作地クリア && 書籍を読んだら、という感じ。
                 */
                'required_clears' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($seeds as $seed) {
            Field::create($seed);
        }

    }
}

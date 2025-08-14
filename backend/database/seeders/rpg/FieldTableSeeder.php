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
                'difficulty' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::Desert,
                'name' => FieldData::Desert->label(),
                'difficulty' => 2,
                'required_clears' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::Volcano,
                'name' => FieldData::Volcano->label(),
                'difficulty' => 2,
                'required_clears' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::Coast,
                'name' => FieldData::Coast->label(),
                'difficulty' => 2,
                'required_clears' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::IceAndSnow,
                'name' => FieldData::IceAndSnow->label(),
                'difficulty' => 3,
                'required_clears' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::WetFog,
                'name' => FieldData::WetFog->label(),
                'difficulty' => 3,
                'required_clears' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::NightForest,
                'name' => FieldData::NightForest->label(),
                'difficulty' => 3,
                'required_clears' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::DecayedFarmland,
                'name' => FieldData::DecayedFarmland->label(),
                'difficulty' => 4,
                'required_clears' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::CastleTown,
                'name' => FieldData::CastleTown->label(),
                'difficulty' => 4,
                'required_clears' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::AncientCastle,
                'name' => FieldData::AncientCastle->label(),
                'difficulty' => 5,
                'required_clears' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => FieldData::VastExpanse,
                'name' => FieldData::VastExpanse->label(),
                'difficulty' => 6,
                'required_clears' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($seeds as $seed) {
            Field::create($seed);
        }

    }
}

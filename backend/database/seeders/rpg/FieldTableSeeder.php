<?php

namespace Database\Seeders\rpg;

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

        // 本番環境は一旦草原だけ。
        if (config('app.env') === 'production') {
            $seeds = [
                [
                    'name' => '草原',
                    'difficulty' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
        } else {
            $seeds = [
                [
                    'name' => '草原',
                    'difficulty' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '砂漠',
                    'difficulty' => 2,
                    'required_clears' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '火山',
                    'difficulty' => 2,
                    'required_clears' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '海岸',
                    'difficulty' => 2,
                    'required_clears' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '氷雪地帯',
                    'difficulty' => 3,
                    'required_clears' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '湿霧の地',
                    'difficulty' => 3,
                    'required_clears' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '常夜の樹海',
                    'difficulty' => 3,
                    'required_clears' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '退廃した耕作地',
                    'difficulty' => 4,
                    'required_clears' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '門前雀羅の城下街',
                    'difficulty' => 4,
                    'required_clears' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '古城',
                    'difficulty' => 5,
                    'required_clears' => 6,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => '茫洋の地',
                    'difficulty' => 6,
                    'required_clears' => 10,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
        }

        foreach ($seeds as $seed) {
            Field::create($seed);
        }

    }
}

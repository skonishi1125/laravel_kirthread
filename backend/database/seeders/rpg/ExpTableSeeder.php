<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\Exp;
use Illuminate\Database\Seeder;

class ExpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exp::truncate();

        $seeds = [
            ['level' => 1, 'total_exp' => 0],
            ['level' => 2, 'total_exp' => 15],
            ['level' => 3, 'total_exp' => 80],
            // ここから、前レベル増分×1.25 で等比で増やす
            ['level' => 4, 'total_exp' => 160],
            ['level' => 5, 'total_exp' => 260],
            ['level' => 6, 'total_exp' => 385],
            ['level' => 7, 'total_exp' => 540],
            ['level' => 8, 'total_exp' => 735],
            ['level' => 9, 'total_exp' => 980],
            ['level' => 10, 'total_exp' => 1285],
            ['level' => 11, 'total_exp' => 1665],
            ['level' => 12, 'total_exp' => 2140],
            ['level' => 13, 'total_exp' => 2735],
            ['level' => 14, 'total_exp' => 3480],
            ['level' => 15, 'total_exp' => 4410],
            ['level' => 16, 'total_exp' => 5570],
            ['level' => 17, 'total_exp' => 7020],
            ['level' => 18, 'total_exp' => 8830],
            ['level' => 19, 'total_exp' => 11090],
            ['level' => 20, 'total_exp' => 13915],
            ['level' => 21, 'total_exp' => 17445],
            ['level' => 22, 'total_exp' => 21855],
            ['level' => 23, 'total_exp' => 27365],
            ['level' => 24, 'total_exp' => 34255],
            ['level' => 25, 'total_exp' => 42865],
            ['level' => 26, 'total_exp' => 53625],
            ['level' => 27, 'total_exp' => 67075],
            ['level' => 28, 'total_exp' => 83885],
            ['level' => 29, 'total_exp' => 104895],
            ['level' => 30, 'total_exp' => 131155],
        ];

        foreach ($seeds as $seed) {
            Exp::create($seed);
        }

    }
}

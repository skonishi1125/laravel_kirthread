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
            ['level' => 4, 'total_exp' => 200],
            ['level' => 5, 'total_exp' => 350],
            ['level' => 6, 'total_exp' => 800],
            ['level' => 7, 'total_exp' => 1400],
            ['level' => 8, 'total_exp' => 2100],
            ['level' => 9, 'total_exp' => 3000],
            ['level' => 10, 'total_exp' => 4500],
            ['level' => 11, 'total_exp' => 4501],
            ['level' => 12, 'total_exp' => 4502],
            ['level' => 13, 'total_exp' => 4503],
            ['level' => 14, 'total_exp' => 4504],
            ['level' => 15, 'total_exp' => 4505],
            ['level' => 16, 'total_exp' => 4506],
            ['level' => 17, 'total_exp' => 4507],
            ['level' => 18, 'total_exp' => 4508],
            ['level' => 19, 'total_exp' => 4509],
            ['level' => 20, 'total_exp' => 4510],
            ['level' => 21, 'total_exp' => 4511],
            ['level' => 22, 'total_exp' => 4512],
            ['level' => 23, 'total_exp' => 4513],
            ['level' => 24, 'total_exp' => 4514],
            ['level' => 25, 'total_exp' => 4515],
            ['level' => 26, 'total_exp' => 4516],
            ['level' => 27, 'total_exp' => 4517],
            ['level' => 28, 'total_exp' => 4518],
            ['level' => 29, 'total_exp' => 4519],
            ['level' => 30, 'total_exp' => 4520],
        ];

        foreach ($seeds as $seed) {
            Exp::create($seed);
        }

    }
}

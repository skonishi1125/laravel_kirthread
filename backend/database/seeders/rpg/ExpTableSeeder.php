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
            ['level' => 2, 'total_exp' => 21], // スララを倒し切っても、ギリギリ上がらないくらい
            ['level' => 3, 'total_exp' => 80],
            ['level' => 4, 'total_exp' => 200],
            ['level' => 5, 'total_exp' => 500],
            ['level' => 6, 'total_exp' => 1200],
            ['level' => 7, 'total_exp' => 2400],
            ['level' => 8, 'total_exp' => 4000],
            ['level' => 9, 'total_exp' => 6500],
            ['level' => 10, 'total_exp' => 9000],
        ];

        foreach ($seeds as $seed) {
            Exp::create($seed);
        }

    }
}

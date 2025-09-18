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
            ['level' => 2, 'total_exp' => 15], // スララを2回倒せば、パーティ全員上がる感じ
            ['level' => 3, 'total_exp' => 80],
            ['level' => 4, 'total_exp' => 200],
            ['level' => 5, 'total_exp' => 350],
            ['level' => 6, 'total_exp' => 800], // 3刻みでスキルの都合上、めちゃ強くなるので抑える
            ['level' => 7, 'total_exp' => 1400],
            ['level' => 8, 'total_exp' => 2100],
            ['level' => 9, 'total_exp' => 3000],
            ['level' => 10, 'total_exp' => 4500],
        ];

        foreach ($seeds as $seed) {
            Exp::create($seed);
        }

    }
}

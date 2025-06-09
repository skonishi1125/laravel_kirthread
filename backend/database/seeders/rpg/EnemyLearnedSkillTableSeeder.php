<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\EnemyLearnedSkill;
use Illuminate\Database\Seeder;

class EnemyLearnedSkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EnemyLearnedSkill::truncate();

        $seeds = [
            // イッカク
            ['enemy_id' => 4, 'skill_id' => 102, 'skill_level' => 1],
            ['enemy_id' => 4, 'skill_id' => 103, 'skill_level' => 1],

            // オヤダマワニ
            ['enemy_id' => 5, 'skill_id' => 100, 'skill_level' => 1],
            ['enemy_id' => 5, 'skill_id' => 101, 'skill_level' => 1],

            // ハイスララ
            ['enemy_id' => 6, 'skill_id' => 104, 'skill_level' => 1],
            ['enemy_id' => 6, 'skill_id' => 105, 'skill_level' => 1],
            ['enemy_id' => 6, 'skill_id' => 106, 'skill_level' => 1],

        ];

        foreach ($seeds as $seed) {
            EnemyLearnedSkill::create($seed);
        }

    }
}

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
            // オヤダマワニ かみつく
            ['enemy_id' => 5, 'skill_id' => 100, 'skill_level' => 1],
        ];

        foreach ($seeds as $seed) {
            EnemyLearnedSkill::create($seed);
        }

    }
}

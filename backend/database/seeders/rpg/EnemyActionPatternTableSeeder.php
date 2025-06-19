<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\SkillDefinition;
use App\Models\Game\Rpg\EnemyActionPattern;
use Illuminate\Database\Seeder;

class EnemyActionPatternTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EnemyActionPattern::truncate();

        $seeds = [
            // オヤダマワニ
            [
                'id' => 1,
                'enemy_id' => 5,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Roar,
            ],
            [
                'id' => 2,
                'enemy_id' => 5,
                'turn_count' => 2,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'id' => 3,
                'enemy_id' => 5,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Bite,
            ],
            [
                'id' => 4,
                'enemy_id' => 5,
                'turn_count' => 4,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'id' => 5,
                'enemy_id' => 5,
                'turn_count' => 5,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'id' => 6,
                'enemy_id' => 5,
                'turn_count' => 6,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Rampage,
            ],
        ];

        foreach ($seeds as $seed) {
            EnemyActionPattern::create($seed);
        }

    }
}

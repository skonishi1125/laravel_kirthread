<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\EnemyData;
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
                'enemy_id' => EnemyData::Oyadamawani,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Roar,
            ],
            [
                'enemy_id' => EnemyData::Oyadamawani,
                'turn_count' => 2,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'enemy_id' => EnemyData::Oyadamawani,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Bite,
            ],
            [
                'enemy_id' => EnemyData::Oyadamawani,
                'turn_count' => 4,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'enemy_id' => EnemyData::Oyadamawani,
                'turn_count' => 5,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'enemy_id' => EnemyData::Oyadamawani,
                'turn_count' => 6,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Rampage,
            ],

            // スパイクホエール
            [
                'enemy_id' => EnemyData::SpikeWhale,
                'turn_count' => 1,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::SpikeWhale,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Wave,
            ],
            [
                'enemy_id' => EnemyData::SpikeWhale,
                'turn_count' => 3,
                'is_use_skill' => false,
            ],
        ];

        foreach ($seeds as $seed) {
            EnemyActionPattern::create($seed);
        }

    }
}

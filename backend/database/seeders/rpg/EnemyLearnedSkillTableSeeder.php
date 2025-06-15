<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\SkillDefinition;
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
            ['enemy_id' => 4, 'skill_id' => SkillDefinition::Biribiri, 'skill_level' => 1],
            ['enemy_id' => 4, 'skill_id' => SkillDefinition::Discharge, 'skill_level' => 1],

            // オヤダマワニ
            // ['enemy_id' => 5, 'skill_id' => SkillDefinition::Bite, 'skill_level' => 1],
            // ['enemy_id' => 5, 'skill_id' => SkillDefinition::Rampage, 'skill_level' => 1],

            // 検証系
            // 敵回復系 検証用 ハイスララ
            // ['enemy_id' => 6, 'skill_id' => SkillDefinition::EnemyHealing, 'skill_level' => 1],
            // ['enemy_id' => 6, 'skill_id' => SkillDefinition::EnemyAllHealing, 'skill_level' => 1],
            // ['enemy_id' => 6, 'skill_id' => SkillDefinition::Regeneration, 'skill_level' => 1],

            // 敵バフ系 検証用 オヤダマワニ
            // ['enemy_id' => 5, 'skill_id' => SkillDefinition::EnemyGuardSpell, 'skill_level' => 1],
            // ['enemy_id' => 5, 'skill_id' => SkillDefinition::EnemyAllGuardSpell, 'skill_level' => 1],
            ['enemy_id' => 5, 'skill_id' => SkillDefinition::Roar, 'skill_level' => 1],

        ];

        foreach ($seeds as $seed) {
            EnemyLearnedSkill::create($seed);
        }

    }
}

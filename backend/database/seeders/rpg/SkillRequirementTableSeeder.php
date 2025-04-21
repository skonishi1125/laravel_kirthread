<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\SkillDefinition;
use App\Models\Game\Rpg\Skill;
use App\Models\Game\Rpg\SkillRequirement;
use Illuminate\Database\Seeder;

class SkillRequirementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // データベースリセット
        SkillRequirement::truncate();

        $seeds = [
            // -------------------- 格闘家 --------------------
            // -------------------- 治療師 --------------------
            // オールヒーリング ヒーリング Lv2以上, pLv7以上
            [
                'acquired_skill_id' => SkillDefinition::AllHealing->value,
                'requirement_skill_id' => SkillDefinition::Healing->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 7,
            ],
            // -------------------- 重騎士 --------------------
            // ブレイヴスラッシュ ワイドスラッシュ Lv2以上, pLv12以上
            [
                'acquired_skill_id' => SkillDefinition::BraveSlash->value,
                'requirement_skill_id' => SkillDefinition::WideThrust->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 12,
            ],
            // -------------------- 魔導師 --------------------
            // ポップヒール ミニヒールLv2, pLv12以上
            [
                'acquired_skill_id' => SkillDefinition::PopHeal->value,
                'requirement_skill_id' => SkillDefinition::MiniHeal->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 12,
            ],
            // クラッシュボルト プチブラストLv1, pLv1以上
            [
                'acquired_skill_id' => SkillDefinition::CrashBolt->value,
                'requirement_skill_id' => SkillDefinition::PetitBlast->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 5,
            ],
            // マナエクスプロージョン プチブラストLv1, pLv12以上
            [
                'acquired_skill_id' => SkillDefinition::ManaExplosion->value,
                'requirement_skill_id' => SkillDefinition::PetitBlast->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 12,
            ],
            // バトルメイジ 該当スキルなし, pLv15以上
            [
                'acquired_skill_id' => SkillDefinition::BattleMage->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 15,
            ],
            // --------- テストスキルたち ---------
            // スキル47 スキル46 Lv1以上, pLv2以上
            // [
            //     'acquired_skill_id' => 47,
            //     'requirement_skill_id' => 46,
            //     'requirement_skill_level' => 1,
            //     'requirement_party_level' => 2,
            // ],
            // // スキル48 スキル46 Lv2以上, pLv2以上
            // [
            //     'acquired_skill_id' => 48,
            //     'requirement_skill_id' => 46,
            //     'requirement_skill_level' => 2,
            //     'requirement_party_level' => 2,
            // ],
            // // スキル49 スキル46 Lv3以上, pLv2以上
            // [
            //     'acquired_skill_id' => 49,
            //     'requirement_skill_id' => 46,
            //     'requirement_skill_level' => 3,
            //     'requirement_party_level' => 2,
            // ],
            // -------------------- 弓馭者 --------------------
            // -------------------- 理術師 --------------------

        ];

        foreach ($seeds as $seed) {
            SkillRequirement::create($seed);
        }

    }
}

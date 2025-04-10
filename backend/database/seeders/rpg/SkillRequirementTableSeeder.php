<?php

namespace Database\Seeders\rpg;

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

        $skill_requirements = [
            // -------------------- 格闘家 --------------------
            // -------------------- 治療師 --------------------
            // リカバリオール ライフリカバリ Lv2以上, pLv7以上
            [
                'acquired_skill_id' => 21,
                'requirement_skill_id' => 20,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 7,
            ],
            // -------------------- 重騎士 --------------------
            // -------------------- 魔導士 --------------------
            // ポップヒール プチヒールLv2, pLv10以上
            [
                'acquired_skill_id' => 41,
                'requirement_skill_id' => 40,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 10,
            ],
            // クラッシュボルト プチブラストLv1, pLv1以上
            [
                'acquired_skill_id' => 43,
                'requirement_skill_id' => 42,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 1,
            ],
            // マナエクスプロージョン プチブラストLv1, pLv10以上
            [
                'acquired_skill_id' => 44,
                'requirement_skill_id' => 42,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 10,
            ],
            // バトルメイジ 該当スキルなし, pLv15以上
            [
                'acquired_skill_id' => 45,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 15,
            ],
            // スキル47 スキル46 Lv1以上, pLv2以上
            [
                'acquired_skill_id' => 47,
                'requirement_skill_id' => 46,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 2,
            ],
            // スキル48 スキル46 Lv2以上, pLv2以上
            [
                'acquired_skill_id' => 48,
                'requirement_skill_id' => 46,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 2,
            ],
            // スキル49 スキル46 Lv3以上, pLv2以上
            [
                'acquired_skill_id' => 49,
                'requirement_skill_id' => 46,
                'requirement_skill_level' => 3,
                'requirement_party_level' => 2,
            ],
            // -------------------- 弓馭者 --------------------
            // -------------------- 理術士 --------------------

        ];

        foreach ($skill_requirements as $skill_requirement) {
            SkillRequirement::create($skill_requirement);
        }

    }
}

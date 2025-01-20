<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\SkillRequirement;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SkillRequirementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // データベースリセット
        SkillRequirement::truncate();

        $skill_requirements = [
            // ポップヒール プチヒールLv2, pLv10以上
            [
                'id' => 1,
                'acquired_skill_id' => 41,
                'requirement_skill_id' => 40,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 10,
                'created_at' => $now, 'updated_at' => $now,
            ],
            // クラッシュボルト プチブラストLv1, pLv1以上
            [
                'id' => 2,
                'acquired_skill_id' => 43,
                'requirement_skill_id' => 42,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ],
            // マナエクスプロージョン プチブラストLv1, pLv10以上
            [
                'id' => 3,
                'acquired_skill_id' => 44,
                'requirement_skill_id' => 42,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 10,
                'created_at' => $now, 'updated_at' => $now,
            ],
            // バトルメイジ 該当スキルなし, pLv15以上
            [
                'id' => 4,
                'acquired_skill_id' => 45,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 15,
                'created_at' => $now, 'updated_at' => $now,
            ],
            // スキル47 スキル46 Lv1以上, pLv2以上
            [
                'id' => 5,
                'acquired_skill_id' => 47,
                'requirement_skill_id' => 46,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 2,
                'created_at' => $now, 'updated_at' => $now,
            ],
            // スキル48 スキル46 Lv2以上, pLv2以上
            [
                'id' => 6,
                'acquired_skill_id' => 48,
                'requirement_skill_id' => 46,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 2,
                'created_at' => $now, 'updated_at' => $now,
            ],
            // スキル49 スキル46 Lv3以上, pLv2以上
            [
                'id' => 7,
                'acquired_skill_id' => 49,
                'requirement_skill_id' => 46,
                'requirement_skill_level' => 3,
                'requirement_party_level' => 2,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ];

        foreach ($skill_requirements as $skill_requirement) {
            SkillRequirement::create($skill_requirement);
        }

    }
}

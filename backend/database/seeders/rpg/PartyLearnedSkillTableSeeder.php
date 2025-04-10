<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\PartyLearnedSkill;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PartyLearnedSkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        PartyLearnedSkill::truncate();

        $party_learned_skills = [
            ['party_id' => 1, 'skill_id' => 40, 'skill_level' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 1, 'skill_id' => 41, 'skill_level' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 1, 'skill_id' => 42, 'skill_level' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 1, 'skill_id' => 43, 'skill_level' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 1, 'skill_id' => 44, 'skill_level' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 1, 'skill_id' => 45, 'skill_level' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 2, 'skill_id' => 30, 'skill_level' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 2, 'skill_id' => 31, 'skill_level' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 2, 'skill_id' => 32, 'skill_level' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 2, 'skill_id' => 33, 'skill_level' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 3, 'skill_id' => 10, 'skill_level' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 3, 'skill_id' => 11, 'skill_level' => 2, 'created_at' => $now, 'updated_at' => $now],

            // local スキルツリー確認用データ
            ['party_id' => 45, 'skill_id' => 40, 'skill_level' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 45, 'skill_id' => 41, 'skill_level' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['party_id' => 45, 'skill_id' => 42, 'skill_level' => 3, 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($party_learned_skills as $p) {
            PartyLearnedSkill::create($p);
        }

    }
}

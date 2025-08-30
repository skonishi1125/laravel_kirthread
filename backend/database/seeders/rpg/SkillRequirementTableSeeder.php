<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\SkillDefinition;
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
            // ヘビーナックル pLv6以上
            [
                'acquired_skill_id' => SkillDefinition::HeavyKnuckle->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 6,
            ],
            // ラピッドフィスト ヘビーナックルLv1以上, pLv15以上
            [
                'acquired_skill_id' => SkillDefinition::RapidFist->value,
                'requirement_skill_id' => SkillDefinition::HeavyKnuckle->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 15,
            ],
            // タイタンブレイク ヘビーナックルLv1以上, pLv15以上
            [
                'acquired_skill_id' => SkillDefinition::TitanBreak->value,
                'requirement_skill_id' => SkillDefinition::HeavyKnuckle->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 15,
            ],
            // トランスフォーム pLv15以上
            [
                'acquired_skill_id' => SkillDefinition::Transform->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 15,
            ],
            // -------------------- 治療師 --------------------
            // オールヒーリング ヒーリング Lv1以上, pLv6以上
            [
                'acquired_skill_id' => SkillDefinition::AllHealing->value,
                'requirement_skill_id' => SkillDefinition::Healing->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 7,
            ],
            // クイックヒール ヒーリング Lv1以上、 pLv9以上
            [
                'acquired_skill_id' => SkillDefinition::QuickHeal->value,
                'requirement_skill_id' => SkillDefinition::Healing->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 9,
            ],
            // ホーリーアロー ミニボルト Lv2以上, pLv6以上
            [
                'acquired_skill_id' => SkillDefinition::HolyArrow->value,
                'requirement_skill_id' => SkillDefinition::MiniVolt->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 6,
            ],
            // ヘヴンレイ ミニボルト Lv2以上, pLv10以上
            // さらに入れ子にすることはできないので、ミニボルトを前提条件にする。
            [
                'acquired_skill_id' => SkillDefinition::HeavenRay->value,
                'requirement_skill_id' => SkillDefinition::MiniVolt->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 10,
            ],
            // リザレクション pLv15以上
            [
                'acquired_skill_id' => SkillDefinition::Resurrection->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 15,
            ],
            // -------------------- 重騎士 --------------------
            // カースエッジ Lv9以上
            [
                'acquired_skill_id' => SkillDefinition::CurseEdge->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 9,
            ],
            // ブレイヴスラッシュ ワイドスラッシュ Lv1以上, pLv12以上
            [
                'acquired_skill_id' => SkillDefinition::BraveSlash->value,
                'requirement_skill_id' => SkillDefinition::WideThrust->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 12,
            ],
            // オーバープロテクト プロテクションSLv1, pLv12以上
            [
                'acquired_skill_id' => SkillDefinition::OverProtect->value,
                'requirement_skill_id' => SkillDefinition::Protection->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 12,
            ],
            // ブラッドムーン pLv15以上
            [
                'acquired_skill_id' => SkillDefinition::BloodMoon->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 15,
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

            // -------------------- 弓馭者 --------------------
            // フェアリーフォグ ファーストエイドLv2, pLv9以上
            [
                'acquired_skill_id' => SkillDefinition::FairyFog->value,
                'requirement_skill_id' => SkillDefinition::FirstAid->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 9,
            ],
            // バリスタショット pLv9以上
            [
                'acquired_skill_id' => SkillDefinition::BallistaShot->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 9,
            ],
            // -------------------- 理術師 --------------------

        ];

        foreach ($seeds as $seed) {
            SkillRequirement::create($seed);
        }

    }
}

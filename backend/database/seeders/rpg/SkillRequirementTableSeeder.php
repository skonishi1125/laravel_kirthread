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
            // ヘビーナックル pLv8以上
            [
                'acquired_skill_id' => SkillDefinition::HeavyKnuckle->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 8,
            ],
            // ラピッドフィスト ミドルブロウLv1以上, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::RapidFist->value,
                'requirement_skill_id' => SkillDefinition::MiddleBlow->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 16,
            ],
            // アックスシュート スピンキックLv1以上, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::AxeShoot->value,
                'requirement_skill_id' => SkillDefinition::SpinKick->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 16,
            ],
            // タイタンブレイク ミドルブロウLv1以上, pLv20以上
            [
                'acquired_skill_id' => SkillDefinition::TitanBreak->value,
                'requirement_skill_id' => SkillDefinition::MiddleBlow->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 20,
            ],
            // トランスフォーム ファイトソウルLv1以上, pLv20以上
            [
                'acquired_skill_id' => SkillDefinition::Transform->value,
                'requirement_skill_id' => SkillDefinition::FightSoul->value,
                'requirement_skill_level' => null,
                'requirement_party_level' => 20,
            ],
            // -------------------- 治療師 --------------------
            // クイックヒール Lv4以上
            [
                'acquired_skill_id' => SkillDefinition::QuickHeal->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 4,
            ],
            // ホーリーアロー ミニボルト Lv1以上, pLv10以上
            [
                'acquired_skill_id' => SkillDefinition::HolyArrow->value,
                'requirement_skill_id' => SkillDefinition::MiniVolt->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 10,
            ],
            // ヘヴンレイ ミニボルト Lv2以上, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::HeavenRay->value,
                'requirement_skill_id' => SkillDefinition::MiniVolt->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 16,
            ],
            // ルミナスリリーヴ ヒーリング Lv1以上, pLv10以上
            [
                'acquired_skill_id' => SkillDefinition::LuminousRelieve->value,
                'requirement_skill_id' => SkillDefinition::Healing->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 10,
            ],
            // オールリリーヴ オールヒーリング Lv1以上, pLv10以上
            [
                'acquired_skill_id' => SkillDefinition::AllRelieve->value,
                'requirement_skill_id' => SkillDefinition::AllHealing->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 10,
            ],
            // リザレクション pLv20以上
            [
                'acquired_skill_id' => SkillDefinition::Resurrection->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 20,
            ],
            // -------------------- 重騎士 --------------------
            // アドバンスドガード ワイドガードLv3, pLv6以上
            [
                'acquired_skill_id' => SkillDefinition::AdvancedGuard->value,
                'requirement_skill_id' => SkillDefinition::WideGuard->value,
                'requirement_skill_level' => 3,
                'requirement_party_level' => 6,
            ],
            // カースエッジ Lv6以上
            [
                'acquired_skill_id' => SkillDefinition::CurseEdge->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 6,
            ],
            // オーバープロテクト プロテクションSLv1, pLv10以上
            [
                'acquired_skill_id' => SkillDefinition::OverProtect->value,
                'requirement_skill_id' => SkillDefinition::Protection->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 10,
            ],
            // ブレイヴスラッシュ ワイドスラッシュ Lv1以上, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::BraveSlash->value,
                'requirement_skill_id' => SkillDefinition::WideThrust->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 16,
            ],
            // ブラッドムーン pLv20以上
            [
                'acquired_skill_id' => SkillDefinition::BloodMoon->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 20,
            ],
            // -------------------- 魔導師 --------------------
            // ポップヒール ミニヒールLv2, pLv1以上
            [
                'acquired_skill_id' => SkillDefinition::PopHeal->value,
                'requirement_skill_id' => SkillDefinition::MiniHeal->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 1,
            ],
            // エイルカリバー, pLv10以上
            [
                'acquired_skill_id' => SkillDefinition::AileCaliber->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 10,
            ],
            // クラッシュブラスト プチボルトLv1, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::CrashBlast->value,
                'requirement_skill_id' => SkillDefinition::PetitBolt->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 16,
            ],
            // マナエクスプロージョン ボルトストームLv1, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::ManaExplosion->value,
                'requirement_skill_id' => SkillDefinition::BoltStorm->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 16,
            ],
            // バトルメイジ 該当スキルなし, pLv20以上
            [
                'acquired_skill_id' => SkillDefinition::BattleMage->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 20,
            ],

            // -------------------- 弓馭者 --------------------
            // フェアリーフォグ ファーストエイドLv2, pLv10以上
            [
                'acquired_skill_id' => SkillDefinition::FairyFog->value,
                'requirement_skill_id' => SkillDefinition::FirstAid->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 10,
            ],
            // ゲイルストライク ウインドアクセルLv1, pLv10以上
            [
                'acquired_skill_id' => SkillDefinition::GaleStrike->value,
                'requirement_skill_id' => SkillDefinition::WindAccel->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 10,
            ],
            // バリスタショット pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::BallistaShot->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 16,
            ],
            // セイレーンオーラ pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::SirenAura->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 16,
            ],
            // ケルベロスフォース pLv20以上
            [
                'acquired_skill_id' => SkillDefinition::CerberusForce->value,
                'requirement_skill_id' => null,
                'requirement_skill_level' => null,
                'requirement_party_level' => 20,
            ],
            // -------------------- 理術師 --------------------
            // アクシオムストライク ブックスマッシュLv2, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::AxiomStrike->value,
                'requirement_skill_id' => SkillDefinition::BookSmash->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 16,
            ],
            // ロゴスレイ マジックミサイルLv2, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::LogosRay->value,
                'requirement_skill_id' => SkillDefinition::MagicMissile->value,
                'requirement_skill_level' => 2,
                'requirement_party_level' => 16,
            ],
            // ブレードフォース PowerEnt SLv1, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::BladeForce->value,
                'requirement_skill_id' => SkillDefinition::PowerEnt->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 16,
            ],
            // ディフェンドスリート ShieldEnt SLv1, pLv20以上
            [
                'acquired_skill_id' => SkillDefinition::DefendThreat->value,
                'requirement_skill_id' => SkillDefinition::ShieldEnt->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 20,
            ],
            // アークウィズダム MagicEnt SLv1, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::ArcWisdom->value,
                'requirement_skill_id' => SkillDefinition::MagicEnt->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 16,
            ],
            // ソニックトリミング SpeedEnt SLv1, pLv20以上
            [
                'acquired_skill_id' => SkillDefinition::SonicTrimming->value,
                'requirement_skill_id' => SkillDefinition::SpeedEnt->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 20,
            ],
            // フォーチュンスター LuckEnt SLv1, pLv16以上
            [
                'acquired_skill_id' => SkillDefinition::FortuneStar->value,
                'requirement_skill_id' => SkillDefinition::LuckEnt->value,
                'requirement_skill_level' => 1,
                'requirement_party_level' => 16,
            ],

        ];

        foreach ($seeds as $seed) {
            SkillRequirement::create($seed);
        }

    }
}

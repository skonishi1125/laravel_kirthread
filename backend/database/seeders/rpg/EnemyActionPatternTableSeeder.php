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
            // ノラワニ
            [
                'enemy_id' => EnemyData::Norawani,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Roar,
            ],
            [
                'enemy_id' => EnemyData::Norawani,
                'turn_count' => 2,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'enemy_id' => EnemyData::Norawani,
                'turn_count' => 3,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'enemy_id' => EnemyData::Norawani,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Bite,
            ],

            // マグマダイル
            [
                'enemy_id' => EnemyData::MagmaDile,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Roar,
            ],
            [
                'enemy_id' => EnemyData::MagmaDile,
                'turn_count' => 2,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'enemy_id' => EnemyData::MagmaDile,
                'turn_count' => 3,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'enemy_id' => EnemyData::MagmaDile,
                'turn_count' => 4,
                'is_use_skill' => false,
                'skill_id' => null,
            ],
            [
                'enemy_id' => EnemyData::MagmaDile,
                'turn_count' => 5,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Bite,
            ],
            [
                'enemy_id' => EnemyData::MagmaDile,
                'turn_count' => 6,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Rampage,
            ],

            // スパイクホエール
            [
                'enemy_id' => EnemyData::SpikeWhale,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Wave,
            ],
            [
                'enemy_id' => EnemyData::SpikeWhale,
                'turn_count' => 2,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::SpikeWhale,
                'turn_count' => 3,
                'is_use_skill' => false,
            ],

            // IceFairy
            [
                'enemy_id' => EnemyData::IceFairy,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::HailShot,
            ],

            // Eripen
            [
                'enemy_id' => EnemyData::Eripen,
                'turn_count' => 1,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::Eripen,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Prepare,
            ],
            [
                'enemy_id' => EnemyData::Eripen,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Rush,
            ],

            // ScissorFlipper
            [
                'enemy_id' => EnemyData::ScissorFlipper,
                'turn_count' => 1,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::ScissorFlipper,
                'turn_count' => 2,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::ScissorFlipper,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Prepare,
            ],
            [
                'enemy_id' => EnemyData::ScissorFlipper,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Rush,
            ],
            [
                'enemy_id' => EnemyData::ScissorFlipper,
                'turn_count' => 5,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Prepare,
            ],
            [
                'enemy_id' => EnemyData::ScissorFlipper,
                'turn_count' => 6,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Rush,
            ],

            // WandEater
            [
                'enemy_id' => EnemyData::WandEater,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::WeakPollen,
            ],
            [
                'enemy_id' => EnemyData::WandEater,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::DigestiveFluid,
            ],
            [
                'enemy_id' => EnemyData::WandEater,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::GrassWhip,
            ],

            // Twilight
            [
                'enemy_id' => EnemyData::Twilight,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Blink,
            ],
            [
                'enemy_id' => EnemyData::Twilight,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::StellarBlink,
            ],
            [
                'enemy_id' => EnemyData::Twilight,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::StellarBlink,
            ],
            [
                'enemy_id' => EnemyData::Twilight,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Discharge,
            ],
            [
                'enemy_id' => EnemyData::Twilight,
                'turn_count' => 5,
                'is_use_skill' => false,
            ],

            // BazaarLizard
            [
                'enemy_id' => EnemyData::BazaarLizard,
                'turn_count' => 1,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::BazaarLizard,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::UseAllPotion,
            ],
            [
                'enemy_id' => EnemyData::BazaarLizard,
                'turn_count' => 3,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::BazaarLizard,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::UseMiniBomb,
            ],

            // DustBomb
            [
                'enemy_id' => EnemyData::DustBomb,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Prepare,
            ],
            [
                'enemy_id' => EnemyData::DustBomb,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::SwellUp,
            ],
            [
                'enemy_id' => EnemyData::DustBomb,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Explosion,
            ],

            // WitherNepenthos
            [
                'enemy_id' => EnemyData::WitherNepenthos,
                'turn_count' => 1,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::WitherNepenthos,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::DigestiveFluid,
            ],
            [
                'enemy_id' => EnemyData::WitherNepenthos,
                'turn_count' => 3,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::WitherNepenthos,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Rampage,
            ],

            // PotDio
            [
                'enemy_id' => EnemyData::PotDio,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::WeakPollen,
            ],
            [
                'enemy_id' => EnemyData::PotDio,
                'turn_count' => 2,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::PotDio,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Rampage,
            ],
            [
                'enemy_id' => EnemyData::PotDio,
                'turn_count' => 4,
                'is_use_skill' => false,
            ],

            // StoneGolem
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::PhysicalMode,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 2,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 3,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 4,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 5,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::LuckBreak, // TODO; ロケットパンチとか
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 6,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::MagicMode,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 7,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 8,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 9,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 10,
                'is_use_skill' => false,
            ],

        ];

        foreach ($seeds as $seed) {
            EnemyActionPattern::create($seed);
        }

    }
}

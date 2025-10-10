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
                'skill_id' => SkillDefinition::RocketPunch,
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
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::RazerBeam,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 8,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::RazerBeam,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 9,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::RazerBeam,
            ],
            [
                'enemy_id' => EnemyData::StoneGolem,
                'turn_count' => 10,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::RazerSweep,
            ],

            // GaiaHand
            [
                'enemy_id' => EnemyData::GaiaHand,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Roar,
            ],
            [
                'enemy_id' => EnemyData::GaiaHand,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Prepare,
            ],
            [
                'enemy_id' => EnemyData::GaiaHand,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Squeeze,
            ],

            // MetalGecko
            [
                'enemy_id' => EnemyData::MetalGecko,
                'turn_count' => 1,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::MetalGecko,
                'turn_count' => 2,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::MetalGecko,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::CleaveArmor,
            ],
            [
                'enemy_id' => EnemyData::MetalGecko,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::SlashAll,
            ],

            // PlasmaBook
            [
                'enemy_id' => EnemyData::PlasmaBook,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::SlowWave,
            ],
            [
                'enemy_id' => EnemyData::PlasmaBook,
                'turn_count' => 2,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::PlasmaBook,
                'turn_count' => 3,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::PlasmaBook,
                'turn_count' => 4,
                'is_use_skill' => false,
            ],

            // FlareDrago
            [
                'enemy_id' => EnemyData::FlareDrago,
                'turn_count' => 1,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::FlareDrago,
                'turn_count' => 2,
                'is_use_skill' => false,
            ],
            [
                'enemy_id' => EnemyData::FlareDrago,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Bite,
            ],
            [
                'enemy_id' => EnemyData::FlareDrago,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::DragonHowling,
            ],
            [
                'enemy_id' => EnemyData::FlareDrago,
                'turn_count' => 5,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::FireBreath,
            ],

            // TraitorLordOfDragon
            // [
            //     'enemy_id' => EnemyData::TraitorLordOfDragon,
            //     'turn_count' => 1,
            //     'is_use_skill' => true,
            //     'skill_id' => SkillDefinition::OpenPotal,
            // ],
            // [
            //     'enemy_id' => EnemyData::TraitorLordOfDragon,
            //     'turn_count' => 2,
            //     'is_use_skill' => true,
            //     'skill_id' => SkillDefinition::AncientLightning,
            // ],
            // [
            //     'enemy_id' => EnemyData::TraitorLordOfDragon,
            //     'turn_count' => 3,
            //     'is_use_skill' => true,
            //     'skill_id' => SkillDefinition::DragonBite,
            // ],
            // [
            //     'enemy_id' => EnemyData::TraitorLordOfDragon,
            //     'turn_count' => 4,
            //     'is_use_skill' => true,
            //     'skill_id' => SkillDefinition::DragonHowling,
            // ],
            // [
            //     'enemy_id' => EnemyData::TraitorLordOfDragon,
            //     'turn_count' => 5,
            //     'is_use_skill' => true,
            //     'skill_id' => SkillDefinition::DragonTail,
            // ],
            [
                'enemy_id' => EnemyData::TraitorLordOfDragon,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::BloodSlurp,
            ],

            // CurseScareCrow
            [
                'enemy_id' => EnemyData::CurseScareCrow,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::StandStill,
            ],
            [
                'enemy_id' => EnemyData::CurseScareCrow,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::StandStill,
            ],
            [
                'enemy_id' => EnemyData::CurseScareCrow,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::StandStill,
            ],
            [
                'enemy_id' => EnemyData::CurseScareCrow,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::StandStill,
            ],
            [
                'enemy_id' => EnemyData::CurseScareCrow,
                'turn_count' => 5,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::CurseBreaker,
            ],

            // Narehate
            [
                'enemy_id' => EnemyData::Narehate,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::EnemyProtection,
            ],
            [
                'enemy_id' => EnemyData::Narehate,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::EnemyWideThrust,
            ],
            [
                'enemy_id' => EnemyData::Narehate,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::EnemyWindAccel,
            ],
            [
                'enemy_id' => EnemyData::Narehate,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::EnemyCurseEdge,
            ],

            // OriginSlum
            [
                'enemy_id' => EnemyData::OriginSlum,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Puyopuyo,
            ],
            [
                'enemy_id' => EnemyData::OriginSlum,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::SlumPopHeal,
            ],
            [
                'enemy_id' => EnemyData::OriginSlum,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Puyopuyo,
            ],
            [
                'enemy_id' => EnemyData::OriginSlum,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::SlumEscape,
            ],

            // OriginGwappa
            [
                'enemy_id' => EnemyData::OriginGwappa,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Observe,
            ],
            [
                'enemy_id' => EnemyData::OriginGwappa,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Running,
            ],
            [
                'enemy_id' => EnemyData::OriginGwappa,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::GwappaHealAP,
            ],
            [
                'enemy_id' => EnemyData::OriginGwappa,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Running,
            ],
            [
                'enemy_id' => EnemyData::OriginGwappa,
                'turn_count' => 5,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::GwappaEscape,
            ],

            // HollowHero
            [
                'enemy_id' => EnemyData::HollowHero,
                'turn_count' => 1,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::StandStill,
            ],
            [
                'enemy_id' => EnemyData::HollowHero,
                'turn_count' => 2,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::EnemySpeedEnt,
            ],
            [
                'enemy_id' => EnemyData::HollowHero,
                'turn_count' => 3,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::EnemyWeaponDemolish,
            ],
            [
                'enemy_id' => EnemyData::HollowHero,
                'turn_count' => 4,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::EnemyAileCaliber,
            ],
            [
                'enemy_id' => EnemyData::HollowHero,
                'turn_count' => 5,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::EnemyAxeShoot,
            ],
            [
                'enemy_id' => EnemyData::HollowHero,
                'turn_count' => 6,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::EnemyHealing,
            ],
            [
                'enemy_id' => EnemyData::HollowHero,
                'turn_count' => 7,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::Prepare,
            ],
            [
                'enemy_id' => EnemyData::HollowHero,
                'turn_count' => 8,
                'is_use_skill' => true,
                'skill_id' => SkillDefinition::SuperNova,
            ],

        ];

        foreach ($seeds as $seed) {
            EnemyActionPattern::create($seed);
        }

    }
}

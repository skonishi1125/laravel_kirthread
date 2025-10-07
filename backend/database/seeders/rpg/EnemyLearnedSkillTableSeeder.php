<?php

namespace Database\Seeders\rpg;

use App\Enums\Rpg\EnemyData;
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
            // BigSrara
            ['enemy_id' => EnemyData::BigSrara, 'skill_id' => SkillDefinition::DigestiveFluid, 'skill_level' => 1],

            // Bou
            ['enemy_id' => EnemyData::Bou, 'skill_id' => SkillDefinition::Fire, 'skill_level' => 1],

            // Norawani
            ['enemy_id' => EnemyData::Norawani, 'skill_id' => SkillDefinition::Roar, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Norawani, 'skill_id' => SkillDefinition::Bite, 'skill_level' => 1],

            // MagmaDile
            ['enemy_id' => EnemyData::MagmaDile, 'skill_id' => SkillDefinition::Bite, 'skill_level' => 1],
            ['enemy_id' => EnemyData::MagmaDile, 'skill_id' => SkillDefinition::Rampage, 'skill_level' => 1],
            ['enemy_id' => EnemyData::MagmaDile, 'skill_id' => SkillDefinition::Roar, 'skill_level' => 1],

            // MageSrara
            ['enemy_id' => EnemyData::MageSrara, 'skill_id' => SkillDefinition::Freeze, 'skill_level' => 1],

            // Clion
            ['enemy_id' => EnemyData::Clion, 'skill_id' => SkillDefinition::Bubble, 'skill_level' => 1],

            // Ikkaku
            ['enemy_id' => EnemyData::Ikkaku, 'skill_id' => SkillDefinition::Biribiri, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Ikkaku, 'skill_id' => SkillDefinition::Discharge, 'skill_level' => 1],

            // SpikeWhale
            ['enemy_id' => EnemyData::SpikeWhale, 'skill_id' => SkillDefinition::Wave, 'skill_level' => 1],

            // Nepenthos
            ['enemy_id' => EnemyData::Nepenthos, 'skill_id' => SkillDefinition::DigestiveFluid, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Nepenthos, 'skill_id' => SkillDefinition::Rampage, 'skill_level' => 1],

            // HazardBerry
            ['enemy_id' => EnemyData::HazardBerry, 'skill_id' => SkillDefinition::FreeToEat, 'skill_level' => 1],

            // Dionaea
            ['enemy_id' => EnemyData::Dionaea, 'skill_id' => SkillDefinition::WeakPollen, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Dionaea, 'skill_id' => SkillDefinition::Rampage, 'skill_level' => 1],

            // WandEater
            ['enemy_id' => EnemyData::WandEater, 'skill_id' => SkillDefinition::DigestiveFluid, 'skill_level' => 1],
            ['enemy_id' => EnemyData::WandEater, 'skill_id' => SkillDefinition::WeakPollen, 'skill_level' => 1],
            ['enemy_id' => EnemyData::WandEater, 'skill_id' => SkillDefinition::GrassWhip, 'skill_level' => 1],

            // IceFairy
            ['enemy_id' => EnemyData::IceFairy, 'skill_id' => SkillDefinition::HailShot, 'skill_level' => 1],

            // Eripen
            ['enemy_id' => EnemyData::Eripen, 'skill_id' => SkillDefinition::Prepare, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Eripen, 'skill_id' => SkillDefinition::Rush, 'skill_level' => 1],

            // ScissorFlipper
            ['enemy_id' => EnemyData::ScissorFlipper, 'skill_id' => SkillDefinition::Prepare, 'skill_level' => 1],
            ['enemy_id' => EnemyData::ScissorFlipper, 'skill_id' => SkillDefinition::Rush, 'skill_level' => 1],

            // HoshiHotaru
            ['enemy_id' => EnemyData::HoshiHotaru, 'skill_id' => SkillDefinition::StellarBlink, 'skill_level' => 1],
            ['enemy_id' => EnemyData::HoshiHotaru, 'skill_id' => SkillDefinition::Blink, 'skill_level' => 1],

            // Gyao
            ['enemy_id' => EnemyData::Gyao, 'skill_id' => SkillDefinition::MagicTackle, 'skill_level' => 1],

            // ShadowWeed
            ['enemy_id' => EnemyData::ShadowWeed, 'skill_id' => SkillDefinition::GrassWhip, 'skill_level' => 1],

            // Twilight
            ['enemy_id' => EnemyData::Twilight, 'skill_id' => SkillDefinition::Blink, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Twilight, 'skill_id' => SkillDefinition::StellarBlink, 'skill_level' => 3],
            ['enemy_id' => EnemyData::Twilight, 'skill_id' => SkillDefinition::Discharge, 'skill_level' => 2],

            // BazaarLizard
            ['enemy_id' => EnemyData::BazaarLizard, 'skill_id' => SkillDefinition::UseAllPotion, 'skill_level' => 1],
            ['enemy_id' => EnemyData::BazaarLizard, 'skill_id' => SkillDefinition::UseMiniBomb, 'skill_level' => 1],

            // DustBomb
            ['enemy_id' => EnemyData::DustBomb, 'skill_id' => SkillDefinition::Prepare, 'skill_level' => 1],
            ['enemy_id' => EnemyData::DustBomb, 'skill_id' => SkillDefinition::SwellUp, 'skill_level' => 1],
            ['enemy_id' => EnemyData::DustBomb, 'skill_id' => SkillDefinition::Explosion, 'skill_level' => 1],

            // WitherNepenthos
            ['enemy_id' => EnemyData::WitherNepenthos, 'skill_id' => SkillDefinition::DigestiveFluid, 'skill_level' => 1],
            ['enemy_id' => EnemyData::WitherNepenthos, 'skill_id' => SkillDefinition::Rampage, 'skill_level' => 1],

            // PotDio
            ['enemy_id' => EnemyData::PotDio, 'skill_id' => SkillDefinition::WeakPollen, 'skill_level' => 1],
            ['enemy_id' => EnemyData::PotDio, 'skill_id' => SkillDefinition::Rampage, 'skill_level' => 1],

            // GolemBall
            ['enemy_id' => EnemyData::GolemBall, 'skill_id' => SkillDefinition::RazerBeam, 'skill_level' => 3],
            ['enemy_id' => EnemyData::GolemBall, 'skill_id' => SkillDefinition::RazerSweep, 'skill_level' => 3],

            // Eliminator
            ['enemy_id' => EnemyData::Eliminator, 'skill_id' => SkillDefinition::PowerBreak, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Eliminator, 'skill_id' => SkillDefinition::DefenceBreak, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Eliminator, 'skill_id' => SkillDefinition::MagicBreak, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Eliminator, 'skill_id' => SkillDefinition::SpeedBreak, 'skill_level' => 1],
            ['enemy_id' => EnemyData::Eliminator, 'skill_id' => SkillDefinition::LuckBreak, 'skill_level' => 1],

            // StoneGolem
            ['enemy_id' => EnemyData::StoneGolem, 'skill_id' => SkillDefinition::PhysicalMode, 'skill_level' => 1],
            ['enemy_id' => EnemyData::StoneGolem, 'skill_id' => SkillDefinition::MagicMode, 'skill_level' => 1],
            ['enemy_id' => EnemyData::StoneGolem, 'skill_id' => SkillDefinition::RocketPunch, 'skill_level' => 1],
            ['enemy_id' => EnemyData::StoneGolem, 'skill_id' => SkillDefinition::RazerBeam, 'skill_level' => 1],
            ['enemy_id' => EnemyData::StoneGolem, 'skill_id' => SkillDefinition::RazerSweep, 'skill_level' => 1],

            // DarkSrara
            ['enemy_id' => EnemyData::DarkSrara, 'skill_id' => SkillDefinition::Freeze, 'skill_level' => 2],

            // Anima
            ['enemy_id' => EnemyData::Anima, 'skill_id' => SkillDefinition::StellarBlink, 'skill_level' => 1],

            // GaiaHand
            ['enemy_id' => EnemyData::GaiaHand, 'skill_id' => SkillDefinition::Roar, 'skill_level' => 1],
            ['enemy_id' => EnemyData::GaiaHand, 'skill_id' => SkillDefinition::Prepare, 'skill_level' => 1],
            ['enemy_id' => EnemyData::GaiaHand, 'skill_id' => SkillDefinition::Squeeze, 'skill_level' => 1],

            // MetalGecko
            ['enemy_id' => EnemyData::MetalGecko, 'skill_id' => SkillDefinition::CleaveArmor, 'skill_level' => 1],
            ['enemy_id' => EnemyData::MetalGecko, 'skill_id' => SkillDefinition::SlashAll, 'skill_level' => 1],

            // Stinger
            ['enemy_id' => EnemyData::Stinger, 'skill_id' => SkillDefinition::Biribiri, 'skill_level' => 2],
            ['enemy_id' => EnemyData::Stinger, 'skill_id' => SkillDefinition::Discharge, 'skill_level' => 2],

            // PlasmaBook
            ['enemy_id' => EnemyData::PlasmaBook, 'skill_id' => SkillDefinition::SlowWave, 'skill_level' => 2],

            // FlareDrago
            ['enemy_id' => EnemyData::FlareDrago, 'skill_id' => SkillDefinition::Bite, 'skill_level' => 1],
            ['enemy_id' => EnemyData::FlareDrago, 'skill_id' => SkillDefinition::DragonHowling, 'skill_level' => 1],
            ['enemy_id' => EnemyData::FlareDrago, 'skill_id' => SkillDefinition::FireBreath, 'skill_level' => 1],

            // TraitorLordOfDragon
            ['enemy_id' => EnemyData::TraitorLordOfDragon, 'skill_id' => SkillDefinition::OpenPotal, 'skill_level' => 1],
            ['enemy_id' => EnemyData::TraitorLordOfDragon, 'skill_id' => SkillDefinition::AncientLightning, 'skill_level' => 1],
            ['enemy_id' => EnemyData::TraitorLordOfDragon, 'skill_id' => SkillDefinition::DragonBite, 'skill_level' => 1],
            ['enemy_id' => EnemyData::TraitorLordOfDragon, 'skill_id' => SkillDefinition::DragonHowling, 'skill_level' => 1],
            ['enemy_id' => EnemyData::TraitorLordOfDragon, 'skill_id' => SkillDefinition::DragonTail, 'skill_level' => 1],
            ['enemy_id' => EnemyData::TraitorLordOfDragon, 'skill_id' => SkillDefinition::BloodSlurp, 'skill_level' => 1],

            // CurseScareCrow
            ['enemy_id' => EnemyData::CurseScareCrow, 'skill_id' => SkillDefinition::StandStill, 'skill_level' => 1],
            ['enemy_id' => EnemyData::CurseScareCrow, 'skill_id' => SkillDefinition::CurseBreaker, 'skill_level' => 1],

            // ZombieCrion
            ['enemy_id' => EnemyData::ZombieClion, 'skill_id' => SkillDefinition::Death, 'skill_level' => 1],

            // 検証系
            // 敵回復系 検証用 ハイスララ
            // ['enemy_id' => EnemyData::HighSrara, 'skill_id' => SkillDefinition::EnemyHealing, 'skill_level' => 1],
            // ['enemy_id' => EnemyData::HighSrara, 'skill_id' => SkillDefinition::EnemyAllHealing, 'skill_level' => 1],
            // ['enemy_id' => EnemyData::HighSrara, 'skill_id' => SkillDefinition::Regeneration, 'skill_level' => 1],
            // ['enemy_id' => EnemyData::HighSrara, 'skill_id' => SkillDefinition::DigestiveFluid, 'skill_level' => 1],

        ];

        foreach ($seeds as $seed) {
            EnemyLearnedSkill::create($seed);
        }

    }
}

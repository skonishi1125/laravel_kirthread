<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\rpg\EnemyActionPatternTableSeeder;
use Database\Seeders\rpg\EnemyLearnedSkillTableSeeder;
use Database\Seeders\rpg\EnemyTableSeeder;
use Database\Seeders\rpg\ExpTableSeeder;
use Database\Seeders\rpg\FieldTableSeeder;
use Database\Seeders\rpg\ItemTableSeeder;
use Database\Seeders\rpg\LibraryTableSeeder;
use Database\Seeders\rpg\PresetAppearingEnemyTableSeeder;
use Database\Seeders\rpg\RoleTableSeeder;
use Database\Seeders\rpg\SkillRequirementTableSeeder;
use Database\Seeders\rpg\SkillTableSeeder;
use Database\Seeders\thread\ReactionIconTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // スレッド関連
        $this->call(ReactionIconTableSeeder::class);

        // RPG関連
        $this->call(SkillTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(ItemTableSeeder::class);
        $this->call(SkillRequirementTableSeeder::class);
        $this->call(EnemyTableSeeder::class);
        $this->call(PresetAppearingEnemyTableSeeder::class);
        $this->call(FieldTableSeeder::class);
        $this->call(ExpTableSeeder::class);
        $this->call(EnemyLearnedSkillTableSeeder::class);
        $this->call(EnemyActionPatternTableSeeder::class);
        $this->call(LibraryTableSeeder::class);

        // local以外で実行すると、本番のスキルデータやアイテムデータも書き変わるので注意。
        if (config('app.env') === 'local') {
            // $this->call(\Database\Seeders\rpg\PartyLearnedSkillTableSeeder::class);
            // $this->call(\Database\Seeders\rpg\SavedataHasItemTableSeeder::class);
        }
    }
}

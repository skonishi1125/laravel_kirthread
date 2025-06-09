<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // スレッド関連
        $this->call(\Database\Seeders\thread\ReactionIconTableSeeder::class);

        // RPG関連
        $this->call(\Database\Seeders\rpg\SkillTableSeeder::class);
        $this->call(\Database\Seeders\rpg\RoleTableSeeder::class);
        $this->call(\Database\Seeders\rpg\ItemTableSeeder::class);
        $this->call(\Database\Seeders\rpg\SkillRequirementTableSeeder::class);
        $this->call(\Database\Seeders\rpg\EnemyTableSeeder::class);
        $this->call(\Database\Seeders\rpg\PresetAppearingEnemyTableSeeder::class);
        $this->call(\Database\Seeders\rpg\FieldTableSeeder::class);
        $this->call(\Database\Seeders\rpg\ExpTableSeeder::class);
        $this->call(\Database\Seeders\rpg\EnemyLearnedSkillTableSeeder::class);

        // local以外で実行すると、本番のスキルデータやアイテムデータも書き変わるので注意。
        if (config('app.env') === 'local') {
            // $this->call(\Database\Seeders\rpg\PartyLearnedSkillTableSeeder::class);
            // $this->call(\Database\Seeders\rpg\SavedataHasItemTableSeeder::class);
        }
    }
}

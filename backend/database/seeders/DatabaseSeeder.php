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
        $this->call(\Database\Seeders\rpg\PartyLearnedSkillTableSeeder::class);
        $this->call(\Database\Seeders\rpg\ItemTableSeeder::class);
        $this->call(\Database\Seeders\rpg\SavedataHasItemTableSeeder::class);
        $this->call(\Database\Seeders\rpg\SkillRequirementTableSeeder::class);
    }
}

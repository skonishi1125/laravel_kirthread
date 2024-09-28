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
        $this->call(\Database\Seeders\rpg\SkillTableSeeder::class);
        $this->call(\Database\Seeders\rpg\RoleTableSeeder::class);
        $this->call(\Database\Seeders\rpg\PartyLearnedSkillTableSeeder::class);
    }
}
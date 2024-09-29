<?php

namespace Database\Seeders\thread;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ReactionIcon;
use Carbon\Carbon;


class ReactionIconTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $now = Carbon::now();
      ReactionIcon::truncate();

      $reaction_icons = [
        [
          'id' => 1,
          'name' => 'eye',
          'name_plural' =>'eyes',
          'is_picture_icon' => false,
          'value' => '👀',
          'url' => null,
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 2,
          'name' => 'sad',
          'name_plural' =>'sads',
          'is_picture_icon' => false,
          'value' => '😭',
          'url' => null,
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 3,
          'name' => 'heart',
          'name_plural' =>'hearts',
          'is_picture_icon' => false,
          'value' => '💕',
          'url' => null,
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 4,
          'name' => 'question',
          'name_plural' =>'questions',
          'is_picture_icon' => false,
          'value' => '❓',
          'url' => null,
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 5,
          'name' => 'kaiddd_seijin',
          'name_plural' =>'kaiddd_seijins',
          'is_picture_icon' => true,
          'value' => null,
          'url' => 'pic_kaiddd_seijin.png',
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 6,
          'name' => 'honmaniyo_monster',
          'name_plural' =>'honmaniyo_monsters',
          'is_picture_icon' => true,
          'value' => null,
          'url' => 'pic_haha.png',
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 7,
          'name' => 'kaiddd_mii',
          'name_plural' =>'kaiddd_miies',
          'is_picture_icon' => true,
          'value' => null,
          'url' => 'pic_kaiddd_mii.png',
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 10,
          'name' => 'thumbs_up',
          'name_plural' =>'thumbs_ups',
          'is_picture_icon' => false,
          'value' => '👍',
          'url' => null,
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 11,
          'name' => 'mario_sad',
          'name_plural' =>'mario_sads',
          'is_picture_icon' => true,
          'value' => null,
          'url' => 'pic_mario_sad.png',
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 12,
          'name' => 'dossun',
          'name_plural' =>'dossunes',
          'is_picture_icon' => true,
          'value' => null,
          'url' => 'pic_dossun.png',
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 13,
          'name' => 'kirbis_nana',
          'name_plural' =>'kirbis_nanas',
          'is_picture_icon' => true,
          'value' => null,
          'url' => 'pic_kirbis_nana.png',
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],
        [
          'id' => 14,
          'name' => 'kirbis_in',
          'name_plural' =>'kirbis_ins',
          'is_picture_icon' => true,
          'value' => null,
          'url' => 'pic_kirbis_in.png',
          'description' => null, 'created_at' => $now, 'updated_at' => $now,'deleted_at' => null,
        ],

      ];

      foreach ($reaction_icons as $reaction_icon) {
        ReactionIcon::create($reaction_icon);
      }

    }
}

<?php

namespace Database\Seeders\rpg;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use \App\Models\Game\Rpg\SavedataHasItem;
use Carbon\Carbon;


class SavedataHasItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $now = Carbon::now();
      SavedataHasItem::truncate();

      $savedata_has_items = [
        ['savedata_id' => 1, 'item_id' =>  1, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' =>  2, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' =>  3, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' =>  4, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 11, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 12, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 13, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 14, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 21, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 22, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 31, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 32, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 33, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 34, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 2, 'item_id' =>  1, 'possesion_number' => 3, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 2, 'item_id' =>  2, 'possesion_number' => 1, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 2, 'item_id' => 21, 'possesion_number' => 1, 'created_at' => $now, 'updated_at' => $now],
      ];

      foreach ($savedata_has_items as $savedata_has_item) {
        SavedataHasItem::create($savedata_has_item);
      }


    }
}

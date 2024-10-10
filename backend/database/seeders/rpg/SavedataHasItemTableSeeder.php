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
        ['savedata_id' => 1, 'item_id' => 1, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 2, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 3, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 4, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 5, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 6, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 7, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 8, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 9, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
        ['savedata_id' => 1, 'item_id' => 90, 'possesion_number' => 2, 'created_at' => $now, 'updated_at' => $now],
      ];

      foreach ($savedata_has_items as $savedata_has_item) {
        SavedataHasItem::create($savedata_has_item);
      }


    }
}

<?php

namespace Database\Seeders\rpg;

use App\Models\Game\Rpg\SavedataHasItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

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
            ['savedata_id' => 1, 'item_id' => 1, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 2, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 3, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 4, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 11, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 12, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 13, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 14, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 21, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 22, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 31, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 32, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 33, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 1, 'item_id' => 34, 'possession_number' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 2, 'item_id' => 1, 'possession_number' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 2, 'item_id' => 2, 'possession_number' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['savedata_id' => 2, 'item_id' => 21, 'possession_number' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($savedata_has_items as $savedata_has_item) {
            SavedataHasItem::create($savedata_has_item);
        }

    }
}

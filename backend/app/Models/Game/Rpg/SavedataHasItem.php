<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedataHasItem extends Model
{
    use HasFactory;

    protected $table = 'rpg_savedata_has_items';

    protected $guarded = [
        'id',
    ];

    /**
     * @return belongsTo<Item, $this>
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * @return belongsTo<Savedata, $this>
     */
    public function savedata(): BelongsTo
    {
        return $this->belongsTo(Savedata::class, 'savedata_id');
    }

    public static function updateItemsAfterBattle($savedata_id, $json_items_data)
    {
        \Debugbar::debug('SavedataHasItem::updateItemsAfterBattle(): ------------------');
        // json_decodeしてArrayにする
        $json_items_data = json_decode($json_items_data, true);

        // これは戦闘開始時点でのアイテムデータと同じ
        $db_items_data = self::where('savedata_id', $savedata_id)->get();

        $json_items_data_ids = array_column($json_items_data, 'id');
        \Debugbar::debug($json_items_data_ids);

        foreach ($db_items_data as $db_item) {
            if (! in_array($db_item->item_id, $json_items_data_ids)) {
                \Debugbar::debug("ID:{$db_item->item_id} {$db_item->item->name} は戦闘で使い切ったため削除します。");
                SavedataHasItem::where('savedata_id', $savedata_id)
                    ->where('item_id', $db_item->item_id)
                    ->delete();
            } else {
                // JSONにある場合は、所持数を更新
                $updated_item = collect($json_items_data)->firstWhere('id', $db_item->item_id);
                if ($updated_item) {
                    SavedataHasItem::where('savedata_id', $savedata_id)
                        ->where('item_id', $db_item->item_id)
                        ->update(['possesion_number' => $updated_item['possesion_number']]);
                }
            }
        }
    }
}

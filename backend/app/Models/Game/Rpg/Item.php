<?php

namespace App\Models\Game\Rpg;

use App\Constants\Rpg\BattleData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Item extends Model
{
    use HasFactory;

    protected $table = 'rpg_items';

    // Savedata自体とは多対多だが、中間テーブルとは1:1の関係である
    public function savedata_has_item()
    {
        return $this->hasOne(SavedataHasItem::class);
    }

    public function Savedatas()
    {
        return $this
            ->belongsToMany(Savedata::class, 'rpg_savedata_has_items', 'item_id', 'savedata_id')
            ->withPivot('possession_number');
    }

    /**
     * ショップに並ぶアイテムを取得する。
     *
     * プレイヤーのクリアしたステージ数に応じて、陳列される数を増やす。
     */
    public static function getShopListItem(Savedata $savedata)
    {
        $cleared_count = $savedata->savedata_cleared_fields()->count();

        return self::where('is_buyable', true)
            ->where('required_clears', '<=', $cleared_count)
            ->get();
    }

    /**
     * players_json_dataのitemsに格納するアイテム情報を取得する
     *
     * @return Collection
     */
    public static function getBattleStateItemFromSavedata(int $savedata_id)
    {
        $items_data = collect(); // $enemiesを加工してjsonに入れるために用意している配列
        $current_savedata_has_items = SavedataHasItem::where('savedata_id', $savedata_id)->get();

        foreach ($current_savedata_has_items as $savedata_has_item) {
            $item = Item::where('id', $savedata_has_item->item_id)
                ->where('is_battle_available', true)
                ->first();

            // 戦闘中に使えないアイテムならスキップ
            if (is_null($item)) {
                continue;
            }

            $item_data = BattleData::ITEM_TEMPLATE;
            $item_data['id'] = $item['id'];
            $item_data['name'] = $item['name'];
            $item_data['attack_type'] = $item['attack_type'];
            $item_data['heal_type'] = $item['heal_type'];
            $item_data['effect_type'] = $item['effect_type'];
            $item_data['target_range'] = $item['target_range'];
            $item_data['is_percent_based'] = $item['is_percent_based'];
            $item_data['percent'] = $item['percent'];
            $item_data['fixed_value'] = $item['fixed_value'];
            $item_data['buff_turn'] = $item['buff_turn'];
            $item_data['description'] = $item['description'];
            $item_data['possession_number'] = $savedata_has_item->possession_number;

            $items_data->push($item_data);
        }

        return $items_data;

    }
}

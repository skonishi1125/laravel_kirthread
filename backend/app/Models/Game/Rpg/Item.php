<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'rpg_items';

    const ATTACK_NO_TYPE = 0; // 分類なし

    const ATTACK_PHYSICAL_TYPE = 1; // 物理

    const ATTACK_MAGIC_TYPE = 2; // 魔法

    const HEAL_NO_TYPE = 0; // 分類なし

    const HEAL_HP_TYPE = 1; // HP回復系アイテム

    const HEAL_AP_TYPE = 2; // AP回復系アイテム

    const EFFECT_SPECIAL_TYPE = 0; // 特殊系アイテム

    const EFFECT_DAMAGE_TYPE = 1; // 攻撃系アイテム

    const EFFECT_HEAL_TYPE = 2; // 治療系アイテム

    const EFFECT_BUFF_TYPE = 3; // バフ系アイテム

    const TARGET_RANGE_SELF = 0; // 自身を対象

    const TARGET_RANGE_SINGLE = 1; // 単体を対象

    const TARGET_RANGE_ALL = 2; // 全体を対象

    // Savedata自体とは多対多だが、中間テーブルとは1:1の関係である
    public function savedata_has_item()
    {
        return $this->hasOne(SavedataHasItem::class);
    }

    public function Savedatas()
    {
        return $this
            ->belongsToMany(SaveData::class, 'rpg_savedata_has_items', 'item_id', 'skill_id')
            ->withPivot('possesion_number');
    }

    public static function getShopListItem()
    {
        return self::where('is_buyable', true)->get();
    }

    public static function getBattleStateItemFromSavedata(int $savedata_id)
    {
        $items_data = collect(); // $enemiesを加工してjsonに入れるために用意している配列
        $current_savedata_has_items = SavedataHasItem::where('savedata_id', $savedata_id)->get();

        foreach ($current_savedata_has_items as $savedata_has_item) {
            $item = Item::where('id', $savedata_has_item->item_id)
                ->where('is_battle_available', true)
                ->first();

            if (is_null($item)) {
                continue;
            } // 戦闘中に使えないアイテムならスキップ

            $item_data = collect([
                'id' => $item->id,
                'name' => $item->name,
                'attack_type' => $item->attack_type,
                'heal_type' => $item->heal_type,
                'effect_type' => $item->effect_type,
                'target_range' => $item->target_range,
                'is_percent_based' => $item->is_percent_based,
                'percent' => $item->percent,
                'fixed_value' => $item->fixed_value,
                'buff_turn' => $item->buff_turn,
                'description' => $item->description,
                'possesion_number' => $savedata_has_item->possesion_number,
            ]);

            $items_data->push($item_data);
        }

        return $items_data;

    }
}

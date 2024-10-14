<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Game\Rpg\Item;
use App\Models\Game\Rpg\SavedataHasItem;
Use Auth;

class SaveData extends Model
{
    use HasFactory;
    protected $table = 'rpg_savedatas';
    protected $guarded = [
      'id',
    ];

    public function user() {
      return $this->belongsTo('App\User');
    }

    public function SavedataHasItem() {
      return $this->hasOne(SavedataHasItem::class);
    }

    // savedataの持つアイテムの所持数を確認したいとき、$s->items[0]->pivot->possesion_number で実現ができる
    public function Items() {
      return $this
        ->belongsToMany(Item::class, 'rpg_savedata_has_items', 'savedata_id', 'item_id')
        ->withPivot('possesion_number');
    }

    // リレーションメモ
    // 1:1でリレーションしているものは rpgSavedata で受け取る(単体取得になる)
    // 1:nでリレーションしているものは rpgSavedata()->get() みたいな形で受け取る(カッコつける)
    public static function getLoginUserCurrentSaveData() {
      return Auth::user()->rpgSavedata;
    }

}

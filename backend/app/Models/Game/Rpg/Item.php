<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Game\Rpg\SaveData;

class Item extends Model
{
  use HasFactory;
  protected $table = 'rpg_items';

  const ATTACK_NO_TYPE        = 0; // 分類なし
  const ATTACK_PHYSICAL_TYPE  = 1; // 物理
  const ATTACK_MAGIC_TYPE     = 2; // 魔法

  const EFFECT_SPECIAL_TYPE = 0; // 特殊系アイテム
  const EFFECT_DAMAGE_TYPE  = 1; // 攻撃系アイテム
  const EFFECT_HEAL_TYPE    = 2; // 治療系アイテム
  const EFFECT_BUFF_TYPE    = 3; // バフ系アイテム

  const TARGET_RANGE_SELF   = 0; // 自身を対象
  const TARGET_RANGE_SINGLE = 1; // 単体を対象
  const TARGET_RANGE_ALL    = 2; // 全体を対象

  public function Savedatas() {
    return $this
      ->belongsToMany(SaveData::class, 'rpg_savedata_has_items', 'item_id', 'skill_id')
      ->withPivot('possesion_number');
  }

  public static function getShopListItem() {
    return self::where('is_buyable', true)->get();
  }

}

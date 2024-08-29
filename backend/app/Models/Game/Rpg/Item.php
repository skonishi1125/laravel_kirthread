<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  use HasFactory;
  protected $table = 'rpg_items';

  public static function getShopListItem() {
    return self::where('is_buyable', true)->get();
  }

}

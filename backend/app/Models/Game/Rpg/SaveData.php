<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    // リレーションメモ
    // 1:1でリレーションしているものは rpgSavedata で受け取る(単体取得になる)
    // 1:nでリレーションしているものは rpgSavedata()->get() みたいな形で受け取る(カッコつける)
    public static function getLoginUserCurrentSaveData() {
      return Auth::user()->rpgSavedata;
    }

}

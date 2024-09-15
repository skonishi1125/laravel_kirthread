<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'rpg_roles';

    public function Parties() {
      // 親側は相手のクラスを指定し、自分の主キーと紐づけようと思っている相手のカラムを指定する
      return $this->hasMany(Party::class, 'rpg_role_id');
    }

    // レベルアップ時に使うステータス値を配列として出力する
    public function exportGrowthArray() {
      return collect([
        'growth_hp'  => $this->growth_hp,
        'growth_ap'  => $this->growth_ap,
        'growth_str' => $this->growth_str,
        'growth_def' => $this->growth_def,
        'growth_int' => $this->growth_int,
        'growth_spd' => $this->growth_spd,
        'growth_luc' => $this->growth_luc,
      ]);

    }

}

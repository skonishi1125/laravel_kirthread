<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'rpg_roles';

    // 職業id
    const ROLE_STRIKER = 1;
    const ROLE_MEDIC   = 2;
    const ROLE_PARADIN = 3;
    const ROLE_MAGE    = 4;
    const ROLE_RANGER  = 5;
    const ROLE_BUFFER  = 6;

    // 職業名
    const ROLE_STRIKER_CLASS_NAME   = 'striker';
    const ROLE_MEDIC_CLASS_NAME     = 'medic';
    const ROLE_PARADIN_CLASS_NAME   = 'paradin';
    const ROLE_MAGE_CLASS_NAME      = 'mage';
    const ROLE_RANGER_CLASS_NAME    = 'ranger';
    const ROLE_BUFFER_CLASS_NAME    = 'buffer';

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

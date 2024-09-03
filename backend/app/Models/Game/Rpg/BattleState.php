<?php

namespace App\Models\Game\Rpg;

use App\Models\Game\Rpg\Enemy;
use App\Models\Game\Rpg\Field;
use App\Models\Game\Rpg\Item;
use App\Models\Game\Rpg\Party;
use App\Models\Game\Rpg\SaveData;
use App\Models\Game\Rpg\Skill;
Use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BattleState extends Model
{
    use HasFactory;
    protected $table = 'rpg_battle_states';

    protected $guarded = [
      'id',
    ];

    // ATTACK選択時の攻撃力を計算
    public static function calculateAttackValue($str, $opponent_def) {
      $damage = $str - $opponent_def;
      return $damage;
    }


}

<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;
    protected $table = 'rpg_parties';

    protected $guarded = [
      'id',
    ];

    public function skills() {
      return $this->belongsToMany(Skill::class, 'rpg_party_learned_skills', 'rpg_party_id', 'rpg_skill_id');
    }

    // ガウス分布を使って成長を計算する関数
    public static function calculateGaussianGrowth($mean){
      // Box-Muller法でガウス分布に従う乱数を生成
      $u1 = mt_rand() / mt_getrandmax();
      $u2 = mt_rand() / mt_getrandmax();
      $z = sqrt(-2 * log($u1)) * cos(2 * pi() * $u2);

      // 平均が mean のガウス分布の値に変換（分散を調整）
      // 1.0で+9が出る確率 = 約0.00003% 2.0は0.6%, 3.0なら2.1%, 4.0なら4.7%
      $variance = 1.0; // 分散の調整
      $growth = $mean + $z * $variance;

      // 成長値が0以下にならないように制限
      return max(1, round($growth));
    }


}

<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Game\Rpg\Role;
use App\Models\Game\Rpg\Savedata;
use App\Models\Game\Rpg\PartyLearnedSkill;
use App\Models\Game\Rpg\Exp;

use Barryvdh\Debugbar\Facades\Debugbar;

class Party extends Model
{
    use HasFactory;
    protected $table = 'rpg_parties';

    protected $guarded = [
      'id',
    ];

    public static function boot() {
      parent::boot();

      // 削除した時、パーティの習得スキル情報だけ削除する
      static::deleting(function ($party) {
        $party->skills()->detach();
      });
    }

    public function savedata() {
      return $this->belongsTo(Savedata::class, 'savedata_id');
    }

    // $p->party_learned_skills
    public function party_learned_skills() {
      return $this->hasMany(PartyLearnedSkill::class, 'party_id');
    }

    public function skills() {
      // pivotの定義により、$party->skills[0]->pivot->skill_level というような形で中間tableの値を取得できる
      return $this
        ->belongsToMany(Skill::class, 'rpg_party_learned_skills', 'party_id', 'skill_id')
        ->withPivot('skill_level') 
        ;
    }

    public function role() {
      // 子側は自分の持つカラムを指定して、相手の主キーと紐づける
      return $this->belongsTo(Role::class, 'role_id');
    }

    public function getNextLevelUpExp() {
      // 次に必要なEXPを取得
      $exp_model = Exp::where('level', $this->level + 1)->first();

      // Level MAXの場合は'-'を返す
      if (is_null($exp_model)) return '-';

      $next_level_up_exp = ($exp_model->total_exp - $this->total_exp);
      // マイナスになるようなら、'-'を返す
      if ($next_level_up_exp < 0) {
        return '-';
      } else {
        return $next_level_up_exp;
      }
    }

    public static function calculateGaussianGrowth($party){
      // Box-Muller法でガウス分布に従う乱数を生成
      $u1 = mt_rand() / mt_getrandmax();
      $u2 = mt_rand() / mt_getrandmax();
      $z = sqrt(-2 * log($u1)) * cos(2 * pi() * $u2);

      // 平均が mean のガウス分布の値に変換（分散を調整）
      // 例えばvarianceと, 成長率5で+9が出る確率
      // 1.0の場合 0.00003%, 2.0は0.6%, 3.0なら2.1%, 4.0なら4.7%
      $variance = 1.0; // 分散の調整

      // 上昇するステータスの分だけ回す(hp, ap, str, def, int, spd, luc)
      $growth_array = $party->role->exportGrowthArray();
      $increase_values = collect();

      foreach ($growth_array as $stat => $growth_value) {
        // ガウス分布計算 最低上昇値が1以上になるようにしておく
        $increase_value = max(1, ceil($growth_value + $z * $variance));
        $increase_values[$stat] = $increase_value;
      }

      foreach($increase_values as $stat => $increase) {
        switch ($stat) {
          case 'growth_hp':
              $party->increment('value_hp', $increase);
              break;
          case 'growth_ap':
              $party->increment('value_ap', $increase);
              break;
          case 'growth_str':
              $party->increment('value_str', $increase);
              break;
          case 'growth_def':
              $party->increment('value_def', $increase);
              break;
          case 'growth_def':
              $party->increment('value_def', $increase);
              break;
          case 'growth_int':
              $party->increment('value_int', $increase);
              break;
          case 'growth_spd':
              $party->increment('value_spd', $increase);
              break;
          case 'growth_luc':
              $party->increment('value_luc', $increase);
              break;
        }
      }

      Debugbar::debug('calculateGaussianGrowth()---------------正常終了。');
      // レベルアップ上昇値をログに記載するため、上昇値の配列を返す
      return $increase_values;
    }

    // beginning画面で指定した情報から、パーティメンバーを作成する
    public static function generateRpgPartyMember($savedata_id, $role_id, $nickname) {
      switch ($role_id) {
        case Role::ROLE_STRIKER :
          return self::create([
            'savedata_id' => $savedata_id,
            'role_id' => Role::ROLE_STRIKER,
            'level' => 1,
            'nickname' => $nickname,
            'value_hp' => '40',
            'value_ap' => '10',
            'value_str' => '25',
            'value_def' => '5',
            'value_int' => '5',
            'value_spd' => '25',
            'value_luc' => '10',
            'total_exp' => '0',
          ]);
          break;
        case Role::ROLE_MEDIC :
          return self::create([
            'savedata_id' => $savedata_id,
            'role_id' => Role::ROLE_MEDIC,
            'level' => 1,
            'nickname' => $nickname,
            'value_hp' => '30',
            'value_ap' => '20',
            'value_str' => '10',
            'value_def' => '15',
            'value_int' => '20',
            'value_spd' => '10',
            'value_luc' => '10',
            'total_exp' => '0',
          ]);
          break;
        case Role::ROLE_PARADIN :
          return self::create([
            'savedata_id' => $savedata_id,
            'role_id' => Role::ROLE_PARADIN,
            'level' => 1,
            'nickname' => $nickname,
            'value_hp' => '50',
            'value_ap' => '15',
            'value_str' => '15',
            'value_def' => '25',
            'value_int' => '5',
            'value_spd' => '5',
            'value_luc' => '10',
            'total_exp' => '0',
          ]);
          break;
        case Role::ROLE_MAGE :
          return self::create([
            'savedata_id' => $savedata_id,
            'role_id' => Role::ROLE_MAGE,
            'level' => 1,
            'nickname' => $nickname,
            'value_hp' => '20',
            'value_ap' => '25',
            'value_str' => '5',
            'value_def' => '10',
            'value_int' => '25',
            'value_spd' => '15',
            'value_luc' => '10',
            'total_exp' => '0',
          ]);
          break;
        case Role::ROLE_RANGER :
          return self::create([
            'savedata_id' => $savedata_id,
            'role_id' => Role::ROLE_RANGER,
            'level' => 1,
            'nickname' => $nickname,
            'value_hp' => '40',
            'value_ap' => '15',
            'value_str' => '15',
            'value_def' => '10',
            'value_int' => '10',
            'value_spd' => '20',
            'value_luc' => '10',
            'total_exp' => '0',
          ]);
          break;
        case Role::ROLE_BUFFER :
          return self::create([
            'savedata_id' => $savedata_id,
            'role_id' => Role::ROLE_BUFFER,
            'level' => 1,
            'nickname' => $nickname,
            'value_hp' => '30',
            'value_ap' => '20',
            'value_str' => '5',
            'value_def' => '15',
            'value_int' => '15',
            'value_spd' => '20',
            'value_luc' => '10',
            'total_exp' => '0',
          ]);
          break;
      }
    }

    // デバッグ用 
    /*
      ステータス初期値設定
      App\Models\Game\Rpg\Party::debugSetDefaultStatus();
    */
    public static function debugSetDefaultStatus() {
      $all_parties = self::get();

      foreach($all_parties as $party) {
        $role_class = $party->role->class;
        switch ($role_class) {
          case Role::ROLE_STRIKER_CLASS_NAME :
            $party->update([
              'level' => 1,
              'value_hp' => '40',
              'value_ap' => '10',
              'value_str' => '25',
              'value_def' => '5',
              'value_int' => '5',
              'value_spd' => '25',
              'value_luc' => '10',
              'total_exp' => '0',
            ]);
            break;
          case Role::ROLE_MEDIC_CLASS_NAME :
            $party->update([
              'level' => 1,
              'value_hp' => '30',
              'value_ap' => '20',
              'value_str' => '10',
              'value_def' => '15',
              'value_int' => '20',
              'value_spd' => '10',
              'value_luc' => '10',
              'total_exp' => '0',
              ]);
              break;
          case Role::ROLE_PARADIN_CLASS_NAME :
            $party->update([
              'level' => 1,
              'value_hp' => '50',
              'value_ap' => '15',
              'value_str' => '15',
              'value_def' => '25',
              'value_int' => '5',
              'value_spd' => '5',
              'value_luc' => '10',
              'total_exp' => '0',
              ]);
              break;
          case Role::ROLE_MAGE_CLASS_NAME :
            $party->update([
              'level' => 1,
              'value_hp' => '20',
              'value_ap' => '25',
              'value_str' => '5',
              'value_def' => '10',
              'value_int' => '25',
              'value_spd' => '15',
              'value_luc' => '10',
              'total_exp' => '0',
            ]);
            break;
          case Role::ROLE_RANGER_CLASS_NAME :
            $party->update([
              'level' => 1,
              'value_hp' => '40',
              'value_ap' => '15',
              'value_str' => '15',
              'value_def' => '10',
              'value_int' => '10',
              'value_spd' => '20',
              'value_luc' => '10',
              'total_exp' => '0',
            ]);
            break;
          case Role::ROLE_BUFFER_CLASS_NAME :
            $party->update([
              'level' => 1,
              'value_hp' => '30',
              'value_ap' => '20',
              'value_str' => '5',
              'value_def' => '15',
              'value_int' => '15',
              'value_spd' => '20',
              'value_luc' => '10',
              'total_exp' => '0',
            ]);
            break;
        }
      }
      echo '初期値設定完了'. PHP_EOL;
    }

    // ガウス分布を使って成長を計算する関数
    // public static function calculateGaussianGrowth($mean){
    //   // Box-Muller法でガウス分布に従う乱数を生成
    //   $u1 = mt_rand() / mt_getrandmax();
    //   $u2 = mt_rand() / mt_getrandmax();
    //   $z = sqrt(-2 * log($u1)) * cos(2 * pi() * $u2);

    //   // 平均が mean のガウス分布の値に変換（分散を調整）
    //   // 1.0で+9が出る確率 = 約0.00003% 2.0は0.6%, 3.0なら2.1%, 4.0なら4.7%
    //   $variance = 1.0; // 分散の調整
    //   $growth = $mean + $z * $variance;

    //   // 成長値が0以下にならないように制限
    //   return max(1, round($growth));
    // }


}

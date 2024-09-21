<?php

namespace App\Models\Game\Rpg;

use App\Models\Game\Rpg\Enemy;
use App\Models\Game\Rpg\Exp;
use App\Models\Game\Rpg\Field;
use App\Models\Game\Rpg\Item;
use App\Models\Game\Rpg\Party;
use App\Models\Game\Rpg\Role;
use App\Models\Game\Rpg\SaveData;
use App\Models\Game\Rpg\Skill;
use App\Models\Game\Rpg\PresetAppearingEnemy;
Use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Barryvdh\Debugbar\Facades\Debugbar;

class BattleState extends Model
{
    use HasFactory;
    protected $table = 'rpg_battle_states';

    protected $guarded = [
      'id',
    ];

    // 戦闘後に回復させるHPの倍率
    // 基本的に最大体力の20%治療する。 戦闘不能の場合は10%だけ治療する。
    const AFTER_CLEARED_RECOVERY_HP_MULTIPLIER = 0.20;
    const AFTER_CLEARED_RESURRECTION_HP_MULTIPLIER = 0.10;

    // エンカウント時の処理
    public static function createPlayersData($user_id, $when_cleared_players_data = null) {
      Debugbar::debug("プレイヤーのエンカウントデータ(battlestates.playeys_json_data)を作成します。----------");
      $parties = Party::where('user_id', $user_id)->get();
      $players_data = collect();

      // クリア後にbattlestateのplayers_dataを作成する場合、HP/APを戦闘後の状態にしておく
      $players_hp_and_ap_status = collect();
      if (isset($when_cleared_players_data)) {
        Debugbar::debug("ステージクリア後の作成です。");
        foreach($when_cleared_players_data as $player_index => $player_data) {
          Debugbar::debug("################# {$player_data->name} | クリア時点でのHP: {$player_data->value_hp} AP: {$player_data->value_ap}");

          $buffed_hp = $player_data->value_hp;
          if ($player_data->is_defeated_flag) {
            Debugbar::debug("戦闘不能のため、最大HPの10%で回復させます。");
            $buffed_hp += ceil($player_data->max_value_hp * self::AFTER_CLEARED_RESURRECTION_HP_MULTIPLIER);
          } else {
            $buffed_hp += ceil($player_data->max_value_hp * self::AFTER_CLEARED_RECOVERY_HP_MULTIPLIER);
          }
          // 回復によって最大体力を超えた場合は最大体力にする
          if ($buffed_hp > $player_data->max_value_hp) {
            $buffed_hp = $player_data->max_value_hp;
          }

          $status = collect([
            'id' => $player_data->id,
            'name' => $player_data->name, // jsonから撮っているので、nicknameではなくname
            'current_hp' => $buffed_hp,
            'current_ap' => $player_data->value_ap,
          ]);
          $players_hp_and_ap_status->push($status);

          Debugbar::debug("調整後:{$status['name']} | HP: {$status['current_hp']} AP: {$status['current_ap']}");

        }
      // 新規戦闘の場合は、デフォルトのHP/APを格納する
      } else {
        Debugbar::debug('新規作成です');
        foreach($parties as $player_index => $player_data) {
          $status = collect([
            'id' => $player_data->id,
            'name' => $player_data->nickname,
            'current_hp' => $player_data->value_hp,
            'current_ap' => $player_data->value_ap,
          ]);
          $players_hp_and_ap_status->push($status);
        }
      }

      Debugbar::debug("players_json_data登録開始。");
      foreach ($parties as $player_index => $party) {
      Debugbar::debug("################# {$player_index} 人目");
        // 会得しているスキルの取得
        $learned_skills = Skill::getLearnedSkill($party);
        $items = [];
        $role = Role::find($party->rpg_role_id);
        $role_portrait = $role->portrait_image_path;
        // vue側に渡すデータ
        $player_data = collect([
          'id' => $party->id,
          'role_id' => $party->rpg_role_id,
          'name' => $party->nickname, // nicknameにすると敵との表記揺れが面倒。 (foreachで行動を回してる部分とかで。)
          'command' => null, // exec時に格納する
          'target_enemy_index' => null, // exec時に格納する, 味方の攻撃対象とする敵のindex。
          'max_value_hp' => $party->value_hp, // HP最大値
          'max_value_ap' => $party->value_ap, // AP最大値
          // 'value_hp' => $party->value_hp,
          // 'value_ap' => $party->value_ap,
          'value_hp' => $players_hp_and_ap_status[$player_index]['current_hp'],
          'value_ap' => $players_hp_and_ap_status[$player_index]['current_ap'],
          'value_str' => $party->value_str,
          'value_def' => $party->value_def,
          'value_int' => $party->value_int,
          'value_spd' => $party->value_spd,
          'value_luc' => $party->value_luc,
          'skills' => $learned_skills,
          'selected_skill_id' => null, // exec時に格納する、選択したスキルのID
          'items' => $items,
          'role_portrait' => $role_portrait,
          'is_defeated_flag' => false,
          'player_index' => $player_index, // 味方のパーティ中での並び。
          'is_enemy' => false,
        ]);
        $players_data->push($player_data);
        DebugBar::debug("{$player_data['name']} 登録完了。");
      }

      return $players_data;

    }

    public static function createEnemiesData($field_id, $stage_id) {

      $enemies = collect();
      $enemies_data = collect(); // $enemiesを加工してjsonに入れるために用意している配列

      Debugbar::debug('敵のプリセットデータを読み込みます。----------');
      $preset_appearing_enemies = PresetAppearingEnemy::where('field_id', $field_id)
        ->where('stage_id', $stage_id)
        ->get();
      foreach ($preset_appearing_enemies as $preset) {
        $preset_enemy = Enemy::find($preset->enemy_id);
        if ($preset_enemy) {
          for ($i = 0; $i < $preset->number; $i++) {
            $enemies->push($preset_enemy);
          }
        }
      }
      Debugbar::debug($preset_appearing_enemies, $enemies);

      foreach ($enemies as $enemy_index => $enemy) {
        $enemy_data = collect([
          'id' => $enemy->id,
          'name' => $enemy->name,
          'command' => null, // exec時に格納する
          'target_player_index' => null, // exec時に格納する, 敵の攻撃対象とする味方のindex。
          'max_value_hp' => $enemy->value_hp, // HP最大値
          'max_value_ap' => $enemy->value_ap, // AP最大値
          'value_hp' => $enemy->value_hp,
          'value_ap' => $enemy->value_ap,
          'value_str' => $enemy->value_str,
          'value_def' => $enemy->value_def,
          'value_int' => $enemy->value_int,
          'value_spd' => $enemy->value_spd,
          'value_luc' => $enemy->value_luc,
          'portrait' => $enemy->portrait_image_path,
          'is_defeated_flag' => false,
          'enemy_index' => $enemy_index, // 敵の並び。
          'is_enemy' => true, // 味方と敵で同じデータを呼んでいるので、敵フラグを立てておく
          'exp' => $enemy->exp,
          'drop_money' => $enemy->drop_money,
        ]);
        $enemies_data->push($enemy_data);
      }
      return $enemies_data;
    }

    public static function createBattleState($user_id, $players_data, $enemies_data, $field_id, $stage_id) {
      $session_id = \Str::uuid()->toString();
      $created_battle_state = BattleState::create([
        'user_id' => $user_id,
        'session_id' => $session_id,
        'players_json_data' => json_encode($players_data),
        'enemies_json_data' => json_encode($enemies_data),
        'current_field_id' => $field_id,
        'current_stage_id' => $stage_id,
      ]);

      return $created_battle_state;

    }



    // 敵と味方のデータを素早さなどを考慮し、戦闘を実行する順に並べる
    public static function sortByBattleExec($players_and_enemies_data) {
      // 同速の場合、現状は味方が優先される
      return $players_and_enemies_data->sortByDesc('value_spd')->values(); 
    }

    // 戦闘処理を実際に実行する。
    public static function execBattleCommand($battle_exec_array, $players_data, $enemies_data, $logs) {
      foreach($battle_exec_array as $index => $data) {
        Debugbar::debug("######### ループ: {$index}人目。 行動対象: {$data->name} 素早さ: {$data->value_spd} #########");
        // 味方の行動
        if ($data->is_enemy == false) {
          Debugbar::debug("味方( {$data->name} )行動開始");
          if ($data->is_defeated_flag == true) {
            Debugbar::debug("{$data->name}は戦闘不能のためスキップします。");
            continue; // 戦闘不能の場合は何も行わない
          } 
          if ($enemies_data->isEmpty()) {
            Debugbar::debug("敵を全員倒したのでスキップします。戦闘に勝利しています。");
            continue; // 敵が全滅している場合は何も行わない
          } 
          Debugbar::debug("やられ、敵全員討伐チェックOK");
          /* ATTACK */
          switch ($data->command) {
            case "ATTACK":
              self::execCommandAttack($data, $enemies_data, false, null, $logs);
              break;
            case "SKILL":
              self::execCommandSkill($data, $enemies_data, false, null, $logs);
              break;
            default:
              $logs->push("{$data->name}は攻撃以外を選択した。");
              break;
          }

        // 敵の行動
        } else {
          Debugbar::warning("敵( {$data->name} )行動開始");
          if ($data->is_defeated_flag == true) {
            Debugbar::warning("{$data->name}はすでにやられているので行動をスキップします。");
            continue; // 行動する敵がやられている場合は何も行わない
          } 
          if ($players_data->isEmpty()) {
            Debugbar::warning("味方はすべて倒れています。戦闘に敗北しています。");
            continue; // 敵が全滅している場合は何も行わない
          }
          Debugbar::warning("敵やられ、味方全員やられチェックOK");
          // コマンド対象となる相手をランダムに指定
          $index = rand(0, $players_data->count() - 1);
          // todo: 敵の行動コマンド指定方法を考える
          $data->command = "ATTACK";
          if ($data->command == "ATTACK") {
            self::execCommandAttack($data, $players_data, true, $index, $logs);
          // todo: 攻撃以外の選択肢を揃える
          } else {
              $logs->push("{$data->name}は攻撃以外を選択した。");
          }
        }
      }
    }


    /*
     * コマンドとして"ATTACK"を選択した時の処理
     * $self_data: 攻撃を行うキャラクター/敵のデータ
     * $opponent_data: 攻撃対象とするキャラクター/敵のデータ
     * $is_enemy: 敵の行動かどうかを判断するフラグ 敵がtrue.
     * $index: 敵が行動する際、対象とする相手のindex 味方行動の場合はnull
     * $logs: 戦闘結果を格納するログ
     * 
    */
    private static function execCommandAttack($self_data, $opponents_data, $is_enemy, $index, $logs){

      if ($is_enemy == false) {
        // 攻撃対象をすでに倒している場合、別の敵を指定する
        if ($opponents_data[$self_data->target_enemy_index]->is_defeated_flag == true) {
          $new_target_index = $opponents_data->search(function ($enemy) {
            return $enemy->is_defeated_flag == false;
          });
          if ($new_target_index !== false) {
            $self_data->target_enemy_index = $new_target_index;
            Debugbar::debug("攻撃対象がすでに討伐済みのため、対象を変更。改めて攻撃対象: {$opponents_data[$self_data->target_enemy_index]->name}");
          } else {
            Debugbar::debug("すべての敵が討伐済みになったので、ATTACKを終了します。敵数: {$opponents_data->count()}");
            return;
          }
        }
        // ATTACK時のダメージ計算
        $damage = self::calculateDamage($self_data->value_str, $opponents_data[$self_data->target_enemy_index]->value_def);
        Debugbar::debug("ダメージ実数値計算。 ダメージ: {$damage}");
        if ($damage > 0) {
          Debugbar::debug("ダメージが1以上なので攻撃。敵の現在体力: {$opponents_data[$self_data->target_enemy_index]->value_hp}");
          $opponents_data[$self_data->target_enemy_index]->value_hp -= $damage;
          Debugbar::debug("攻撃した。敵の残り体力: {$opponents_data[$self_data->target_enemy_index]->value_hp}");
          // 敵を倒した場合
          if ($opponents_data[$self_data->target_enemy_index]->value_hp <= 0 ) {
            $opponents_data[$self_data->target_enemy_index]->value_hp = 0; // マイナスになるのを防ぐ。
            $opponents_data[$self_data->target_enemy_index]->is_defeated_flag = true;
            $logs->push("{$self_data->name}の攻撃！{$opponents_data[$self_data->target_enemy_index]->name}に{$damage}のダメージ。");
            $logs->push("{$opponents_data[$self_data->target_enemy_index]->name}を倒した！");
            Debugbar::debug("{$opponents_data[$self_data->target_enemy_index]->name}を倒した。敵の残り体力: {$opponents_data[$self_data->target_enemy_index]->value_hp} 敵討伐フラグ: {$opponents_data[$self_data->target_enemy_index]->is_defeated_flag} ");
          } else {
            $logs->push("{$self_data->name}の攻撃！{$opponents_data[$self_data->target_enemy_index]->name}に{$damage}のダメージ。");
            Debugbar::debug("{$opponents_data[$self_data->target_enemy_index]->name}はまだ生存している。敵の残り体力: {$opponents_data[$self_data->target_enemy_index]->value_hp} 敵討伐フラグ: {$opponents_data[$self_data->target_enemy_index]->is_defeated_flag} ");
          }
        // ダメージを与えられなかった場合
        } else {
          Debugbar::debug("ダメージを与えられない。");
          $logs->push("{$self_data->name}の攻撃！しかし{$opponents_data[$self_data->target_enemy_index]->name}にダメージを与えられない！");
          Debugbar::debug("攻撃が通らなかった。{$opponents_data[$self_data->target_enemy_index]->name}は当然生存している。敵の残り体力: {$opponents_data[$self_data->target_enemy_index]->value_hp} 敵討伐フラグ: {$opponents_data[$self_data->target_enemy_index]->is_defeated_flag} ");
        }
      // 敵の場合
      } else {
        Debugbar::warning("------------{$self_data->name}:攻撃開始 攻撃対象: {$opponents_data[$index]->name}---------------");
        // 攻撃対象の味方がすでに倒れている場合、別の味方を指定する
        if ($opponents_data[$index]->is_defeated_flag == true) {
          $new_target_index = $opponents_data->search(function ($player) {
            return $player->is_defeated_flag == false;
          });
          if ($new_target_index !== false) {
            $index = $new_target_index;
            Debugbar::warning("攻撃対象の味方がすでに倒れているため、対象を変更。改めて攻撃対象: {$opponents_data[$index]->name}");
          } else {
            Debugbar::warning("すべての味方が倒れました。敵数: {$opponents_data->count()}");
          }
        } 
        $damage = self::calculateDamage($self_data->value_str, $opponents_data[$index]->value_def);
        Debugbar::warning("ダメージ実数値計算. ダメージ: {$damage}");
        if ($damage > 0) {
          Debugbar::warning("ダメージが1以上なので攻撃。味方の現在の体力: {$opponents_data[$index]->value_hp}");
          $opponents_data[$index]->value_hp -= $damage;
          Debugbar::warning("攻撃された。味方の残り体力: {$opponents_data[$index]->value_hp}");
          if ($opponents_data[$index]->value_hp <= 0 ) {
            $opponents_data[$index]->value_hp = 0;
            $opponents_data[$index]->is_defeated_flag = true;
            $logs->push("{$self_data->name}の攻撃！{$opponents_data[$index]->name}は{$damage}のダメージを受けた！");
            $logs->push("{$opponents_data[$index]->name}はやられてしまった！");
            Debugbar::warning("{$opponents_data[$index]->name}がやられた。味方の残り体力: {$opponents_data[$index]->value_hp} 味方やられフラグ: {$opponents_data[$index]->is_defeated_flag} ");
          } else {
            $logs->push("{$self_data->name}の攻撃！{$opponents_data[$index]->name}は{$damage}のダメージを受けた！");
            Debugbar::warning("{$opponents_data[$index]->name}はまだ生存している。味方の残り体力: {$opponents_data[$index]->value_hp} 味方やられフラグ: {$opponents_data[$index]->is_defeated_flag} ");
          }
        } else {
          $logs->push("{$self_data->name}の攻撃！しかし{$opponents_data[$index]->name}は攻撃を防いだ！");
          Debugbar::warning("攻撃が通らなかった。{$opponents_data[$index]->name}は当然生存している。味方の残り体力: {$opponents_data[$index]->value_hp} 味方やられフラグ: {$opponents_data[$index]->is_defeated_flag} ");
        }
      }
    }

    // ATTACK選択時の攻撃力を計算
    private static function calculateDamage($self_str, $opponent_def) {
      $damage = ceil($self_str - $opponent_def);
      return $damage;
    }

    /*
     コマンドとして"SKILL"を選択した時の処理
     * $self_data: 攻撃を行うキャラクター/敵のデータ
     * $opponent_data: 攻撃対象とするキャラクター/敵のデータ
    */
    private static function execCommandSkill($self_data, $opponents_data, $is_enemy, $index, $logs){
      Debugbar::debug("execCommandSkill(): ----------------------");
      $selected_skill = collect($self_data->skills)->firstWhere('id', $self_data->selected_skill_id);

      if ($is_enemy == false) {
        // どの職業の、どのスキル
        Skill::decideExecSkill($self_data->role_id, $selected_skill, $self_data, $opponents_data, $is_enemy, $index, $logs);
      } else {
        // todo: 敵もこの処理で技を使えるようにする
      }
  }

}

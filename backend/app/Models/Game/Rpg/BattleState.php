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
use Illuminate\Support\Collection;

use Barryvdh\Debugbar\Facades\Debugbar;

class BattleState extends Model
{
    use HasFactory;
    protected $table = 'rpg_battle_states';

    protected $guarded = [
      'id',
    ];

    // 戦闘後に回復させるHPの倍率
    // 基本的にmaxHPの20%, maxAPの30%分回復させる。 戦闘不能の場合は半減。
    const AFTER_CLEARED_RECOVERY_HP_MULTIPLIER = 0.20;
    const AFTER_CLEARED_RECOVERY_AP_MULTIPLIER = 0.30;
    const AFTER_CLEARED_RESURRECTION_HP_MULTIPLIER = 0.10;
    const AFTER_CLEARED_RESURRECTION_AP_MULTIPLIER = 0.15;

    // エンカウント時の処理
    public static function createPlayersData(int $user_id, Collection $when_cleared_players_data = null) {
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
          $buffed_ap = $player_data->value_ap;
          if ($player_data->is_defeated_flag) {
            Debugbar::debug("戦闘不能のため、最大HPの10%, 最大APの15%で回復させます。");
            $buffed_hp += ceil($player_data->max_value_hp * self::AFTER_CLEARED_RESURRECTION_HP_MULTIPLIER);
            $buffed_ap += ceil($player_data->max_value_ap * self::AFTER_CLEARED_RESURRECTION_AP_MULTIPLIER);
          } else {
            $buffed_hp += ceil($player_data->max_value_hp * self::AFTER_CLEARED_RECOVERY_HP_MULTIPLIER);
            $buffed_ap += ceil($player_data->max_value_ap * self::AFTER_CLEARED_RECOVERY_AP_MULTIPLIER);
          }
          // 回復によって最大体力を超えた場合は最大体力にする
          if ($buffed_hp > $player_data->max_value_hp) {
            $buffed_hp = $player_data->max_value_hp;
          }
          if ($buffed_ap > $player_data->max_value_ap) {
            $buffed_ap = $player_data->max_value_ap;
          }

          $status = collect([
            'id' => $player_data->id,
            'name' => $player_data->name, // jsonから撮っているので、nicknameではなくname
            'current_hp' => $buffed_hp,
            'current_ap' => $buffed_ap,
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
        $buffs = [
          // [
          //   'buffed_skill_id' => 31,
          //   'buffed_def' => 20,
          //   'buffed_int' => -10,
          //   'remaining_turn' => 5,
          // ],
          // [
          //   'buffed_skill_id' => 51,
          //   'buffed_hp' => 30,
          //   'remaining_turn' => 3,
          // ],
        ];
        $role = Role::find($party->rpg_role_id);
        $role_portrait = $role->portrait_image_path;
        // vue側に渡すデータ
        $player_data = collect([
          'id' => $party->id,
          'role_id' => $party->rpg_role_id,
          'name' => $party->nickname, // nicknameにすると敵との表記揺れが面倒。 (foreachで行動を回してる部分とかで。)
          'command' => null, // exec時に格納する
          'target_player_index' => null, // exec時に格納する, スキルやアイテムで味方を対象とした場合のindexを格納する
          'target_enemy_index' => null, // exec時に格納する, 味方の攻撃対象とする敵のindex。
          'max_value_hp' => $party->value_hp, // HP最大値
          'max_value_ap' => $party->value_ap, // AP最大値
          'value_hp' => $players_hp_and_ap_status[$player_index]['current_hp'],
          'value_ap' => $players_hp_and_ap_status[$player_index]['current_ap'],
          'value_str' => $party->value_str,
          'value_def' => $party->value_def,
          'value_int' => $party->value_int,
          'value_spd' => $party->value_spd,
          'value_luc' => $party->value_luc,
          'skills' => $learned_skills,
          'selected_skill_id' => null, // exec時に格納する、選択したスキルのID
          'buffs' => $buffs,
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

    public static function createEnemiesData(int $field_id, int $stage_id) {

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
        $buffs = [];

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
          'buffs' => $buffs,
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

    public static function createBattleState(
      int $user_id, Collection $players_data, Collection $enemies_data, int $field_id, int $stage_id
    ) {
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


    // 敵味方のデータを素早さなどを考慮し、戦闘を実行する順に並べる
    // 条件: DEFENCE選択 > 特殊スキル選択 > 速度順
    public static function sortByBattleExec(Collection $players_and_enemies_data) {
      // 同速の場合、現状は味方が優先される
      $sorted_data = $players_and_enemies_data->sort(function ($a, $b) {
        
        // 1. 'DEFENCE'コマンド選択
        if ($a->command === 'DEFENCE' && $b->command !== 'DEFENCE') {
          return -1; // $aが先に行動
        }
        if ($b->command === 'DEFENCE' && $a->command !== 'DEFENCE') {
          return 1;  // $bが先に行動
        }

        // 2. 特殊スキル選択
        $a_effect_type = $a->selected_skill_effect_type ?? null;
        $b_effect_type = $b->selected_skill_effect_type ?? null;
        if ($a_effect_type === Skill::EFFECT_SPECIAL_TYPE && $b_effect_type !== Skill::EFFECT_SPECIAL_TYPE) {
          return -1;
        }
        if ($b_effect_type === Skill::EFFECT_SPECIAL_TYPE && $a_effect_type !== Skill::EFFECT_SPECIAL_TYPE) {
          return 1;
        }

        // 3. 速度順で降順ソート
        return $b->value_spd <=> $a->value_spd;

      })->values();

      // デバッグ用
      $action_order = $sorted_data->map(function ($item) {
        return $item->name;
      })->implode(', ');
      Debugbar::debug("行動順決定。{$action_order}");

      return $sorted_data;
    }

    // 戦闘処理を実際に実行する。
    public static function execBattleCommand(
      Collection $sorted_players_and_enemies_data, Collection $players_data, Collection $enemies_data, Collection $logs
    ) {
      // Debugbar::debug(get_class($sorted_players_and_enemies_data)); // この時点ではCollection
      foreach($sorted_players_and_enemies_data as $index => $data) {
        Debugbar::debug("########################### ループ: {$index}人目。 行動対象: {$data->name} 素早さ: {$data->value_spd} ###########################");
        // Debugbar::debug(get_class($data)); // この時点ではstdClass (Object型)

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
          Debugbar::debug("やられ、敵全員討伐チェックOK。 コマンド: {$data->command}");
          /* ATTACK */
          switch ($data->command) {
            case "ATTACK":
              self::execCommandAttack($data, $enemies_data, false, $data->target_enemy_index, $logs);
              break;
            case "SKILL":
              // 対象が味方の場合があるので、$opponents_dataとして定義する
              $opponents_data = collect();
              if (($data->target_enemy_index !== null)) {
                Debugbar::debug("target_enemy_indexが入っているので敵グループを対象として格納。");
                $opponents_data = $enemies_data;
                $opponents_index = $data->target_enemy_index;
              } elseif (($data->target_player_index !== null)) {
                Debugbar::debug("target_player_indexが入っているので味方グループを対象として選択。");
                $opponents_data = $players_data;
                $opponents_index = $data->target_player_index;
              } else {
                // 敵味方ともに対象のindexが格納されていないなら、範囲系のスキル。
                // それぞれ$opponents_dataに条件に合うデータを格納。
                // 攻撃系スキルなら敵を, 回復またはバフ系スキルなら味方を入れる。
                // 範囲技の場合は$opponents_indexは格納せず、nullのままとする。
                $opponents_index = null;
                Debugbar::debug("target_player_indexが格納されていないため、範囲系のスキルが選択されました。");

                // $selected_skill_effect_type = collect($data->skills)
                //   ->firstWhere('id', $data->selected_skill_id)
                //   ->effect_type;
                switch ($data->selected_skill_effect_type) {
                  case Skill::EFFECT_SPECIAL_TYPE :
                    Debugbar::debug("特殊系範囲スキル。");
                    if ($data->selected_skill_id == 31) {
                      Debugbar::debug("ワイドガード: 味方情報をopponents_dataに格納。effect_type: {$data->selected_skill_effect_type}");
                      $opponents_data = $players_data;
                    }
                    break;
                  case Skill::EFFECT_DAMAGE_TYPE :
                    Debugbar::debug("攻撃系範囲スキルのため敵情報をopponents_dataに格納。effect_type: {$data->selected_skill_effect_type}");
                    $opponents_data = $enemies_data;
                    break;
                  case Skill::EFFECT_HEAL_TYPE :
                    Debugbar::debug("回復系範囲スキルのため味方情報をopponents_dataに格納。effect_type: {$data->selected_skill_effect_type}");
                    $opponents_data = $players_data;
                    break;
                  case Skill::EFFECT_BUFF_TYPE :
                    // todo: デバフを採用するなら敵データを入れたいかも。
                    Debugbar::debug("バフ系範囲スキルのため味方情報をopponents_dataに格納。effect_type: {$data->selected_skill_effect_type}");
                    $opponents_data = $players_data;
                    break;
                }
              }
              self::execCommandSkill($data, $opponents_data, false, $opponents_index, $logs);
              break;
            default:
              $logs->push("{$data->name}は攻撃とスキル以外を選択した。");
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
     * $self_data: 攻撃を行うキャラクター/敵のデータ foreach内の処理のため、stdClass
     * $opponent_data: 攻撃対象とするキャラクター/敵のデータ
     * $is_enemy: 敵の行動かどうかを判断するフラグ 敵がtrue.
     * $index: 敵が行動する際、対象とする相手のindex 味方行動の場合はnull
     * $logs: 戦闘結果を格納するログ
     * 
    */
    private static function execCommandAttack(Object $self_data, Collection $opponents_data, bool $is_enemy, int $opponents_index, Collection $logs){

      // Debugbar::info(get_class($self_data), get_class($opponents_data), gettype($is_enemy), gettype($opponents_index), get_class($logs));
      // Debugbar::info(get_class($opponents_data[$opponents_index])); // コレクションの中の値なので、stdClassと認識
      if ($is_enemy == false) {
        // 攻撃対象をすでに倒している場合、別の敵を指定する
        if ($opponents_data[$opponents_index]->is_defeated_flag == true) {
          $new_target_index = $opponents_data->search(function ($enemy) {
            return $enemy->is_defeated_flag == false;
          });
          if ($new_target_index !== false) {
            $opponents_index = $new_target_index;
            Debugbar::debug("攻撃対象がすでに討伐済みのため、対象を変更。改めて攻撃対象: {$opponents_data[$opponents_index]->name}");
          } else {
            Debugbar::debug("すべての敵が討伐済みになったので、ATTACKを終了します。敵数: {$opponents_data->count()}");
            return;
          }
        }
        // ATTACK時のダメージ計算 相手の防御力などは後で考慮する
        $damage = self::calculateActualStatusValue($self_data, 'str');

        Debugbar::debug("（味方）純粋なダメージ量(STRの値。) : {$damage}");
        // 単体・物理攻撃として扱う
        self::storePartyDamage(
          'ATTACK', $self_data, $opponents_data, $opponents_index, $logs, $damage, Skill::TARGET_RANGE_SINGLE, Skill::ATTACK_PHYSICAL_TYPE
        );

      // 敵の場合
      } else {
        Debugbar::warning("------------{$self_data->name}:攻撃開始 攻撃対象: {$opponents_data[$opponents_index]->name}---------------");
        // 攻撃対象の味方がすでに倒れている場合、別の味方を指定する
        if ($opponents_data[$opponents_index]->is_defeated_flag == true) {
          $new_target_index = $opponents_data->search(function ($player) {
            return $player->is_defeated_flag == false;
          });
          if ($new_target_index !== false) {
            $opponents_index = $new_target_index;
            Debugbar::warning("攻撃対象の味方がすでに倒れているため、対象を変更。改めて攻撃対象: {$opponents_data[$opponents_index]->name}");
          } else {
            Debugbar::warning("すべての味方が倒れました。敵数: {$opponents_data->count()}");
          }
        }
        // ATTACK時のダメージ計算 相手の防御力などは後で考慮する
        $damage = self::calculateActualStatusValue($self_data, 'str');
        Debugbar::warning("（敵）純粋なダメージ量(STRの値。) : {$damage}");

        // 画面に表示するログの記録
        self::storeEnemyDamage(
          'ATTACK', $self_data, $opponents_data, $opponents_index, $logs, $damage
        );
      }
    }


    /*
     コマンドとして"SKILL"を選択した時の処理
     * $self_data: 行動実行するキャラクター/敵のデータ
     * $opponent_data: 対象とするキャラクター/敵のデータ
     * $opponents_index: 
        対象とするキャラクター/敵のインデックス。 真ん中の味方に向けた場合は[1]などが入る
        全体攻撃スキルを使った場合, $opponents_indexはnullであるため許容しておく (?int)
    */
    private static function execCommandSkill(
      Object $self_data, Collection $opponents_data, bool $is_enemy, ?int $opponents_index, Collection $logs
    ){
      Debugbar::debug("execCommandSkill(): ----------------------");
      $selected_skill = collect($self_data->skills)->firstWhere('id', $self_data->selected_skill_id);
      // Debugbar::debug(get_class($selected_skill)); // stdClass

      // APがなければ、ログに入れて処理を終了する
      if ($self_data->value_ap < $selected_skill->ap_cost) {
        $logs->push("{$self_data->name}は{$selected_skill->name}を試みたがAPが足りなかった！");
        return;
      }

      if ($is_enemy == false) {
        // どの職業の、どのスキル
        Skill::decideExecSkill($self_data->role_id, $selected_skill, $self_data, $opponents_data, $is_enemy, $opponents_index, $logs);
      } else {
        // todo: 敵もこの処理で技を使えるようにする
      }
    }

    // コマンドを実行した際、画面に表示させるダメージなどのログ入力
    // opponents_dataは攻撃する敵のデータが入る
    public static function storePartyDamage(
      string $command, Object $self_data, 
      Collection $opponents_data, int $opponents_index, Collection $logs, 
      int $damage, int $target_range, int $attack_type
    ) {
      switch ($command) {
        case "ATTACK":
          Debugbar::debug("storePartyDamage(): ATTACK");

          // 通常攻撃力: 自分のstr - 相手のdef (安直すぎるので今後変更する予定)
          $calculated_damage = self::calculatePhysicalDamage(
            $damage, 
            self::calculateActualStatusValue($opponents_data[$opponents_index], 'def')
          );

          if ($calculated_damage > 0) {
            Debugbar::debug("【ATTACK】ダメージが1以上。敵の現在体力: {$opponents_data[$opponents_index]->value_hp}");
            $opponents_data[$opponents_index]->value_hp -= $calculated_damage;
            Debugbar::debug("攻撃した。敵の残り体力: {$opponents_data[$opponents_index]->value_hp}");
            // 敵を倒した場合
            if ($opponents_data[$opponents_index]->value_hp <= 0 ) {
              $opponents_data[$opponents_index]->value_hp = 0; // マイナスになるのを防ぐ。
              $opponents_data[$opponents_index]->is_defeated_flag = true;
              self::clearBuff($opponents_data[$opponents_index]);
              $logs->push("{$self_data->name}の攻撃！{$opponents_data[$opponents_index]->name}に{$calculated_damage}のダメージ。");
              $logs->push("{$opponents_data[$opponents_index]->name}を倒した！");
              Debugbar::debug("{$opponents_data[$opponents_index]->name}を倒した。敵の残り体力: {$opponents_data[$opponents_index]->value_hp} 敵討伐フラグ: {$opponents_data[$opponents_index]->is_defeated_flag} ");
            } else {
              $logs->push("{$self_data->name}の攻撃！{$opponents_data[$opponents_index]->name}に{$calculated_damage}のダメージ。");
              Debugbar::debug("{$opponents_data[$opponents_index]->name}はまだ生存している。敵の残り体力: {$opponents_data[$opponents_index]->value_hp} 敵討伐フラグ: {$opponents_data[$opponents_index]->is_defeated_flag} ");
            }
          // ダメージを与えられなかった場合
          } else {
            Debugbar::debug("ダメージを与えられない。");
            $logs->push("{$self_data->name}の攻撃！しかし{$opponents_data[$opponents_index]->name}にダメージを与えられない！");
            Debugbar::debug("攻撃が通らなかった。{$opponents_data[$opponents_index]->name}は当然生存している。敵の残り体力: {$opponents_data[$opponents_index]->value_hp} 敵討伐フラグ: {$opponents_data[$opponents_index]->is_defeated_flag} ");
          }
          break;
        case "SKILL":
          Debugbar::debug("storePartyDamage(): SKILL");
          // 単体攻撃の場合
          if ($target_range == Skill::TARGET_RANGE_SINGLE) {
            Debugbar::debug("単体攻撃。");

            // ダメージ計算 物理か魔法攻撃かで変える
            if ($attack_type == Skill::ATTACK_PHYSICAL_TYPE) {
              Debugbar::debug("物理。");
              $calculated_damage = self::calculatePhysicalDamage(
                $damage, 
                self::calculateActualStatusValue($opponents_data[$opponents_index], 'def')
              );
            } else if ($attack_type == Skill::ATTACK_MAGIC_TYPE) {
              Debugbar::debug("魔法。");
              $opponent_mdef = self::calculateMagicDefenceValue(
                self::calculateActualStatusValue($opponents_data[$opponents_index], 'def'),
                self::calculateActualStatusValue($opponents_data[$opponents_index], 'int')
              );
              $calculated_damage = $damage -= $opponent_mdef;
            }

            if ($calculated_damage > 0) {
              Debugbar::debug("【SKILL】ダメージが1以上。敵の現在体力: {$opponents_data[$opponents_index]->value_hp}");
              $opponents_data[$opponents_index]->value_hp -= $calculated_damage;
              Debugbar::debug("攻撃した。敵の残り体力: {$opponents_data[$opponents_index]->value_hp}");
              // 敵を倒した場合
              if ($opponents_data[$opponents_index]->value_hp <= 0 ) {
                $opponents_data[$opponents_index]->value_hp = 0; // マイナスになるのを防ぐ。
                $opponents_data[$opponents_index]->is_defeated_flag = true;
                self::clearBuff($opponents_data[$opponents_index]);
                $logs->push("{$opponents_data[$opponents_index]->name}に{$calculated_damage}のダメージ！");
                $logs->push("{$opponents_data[$opponents_index]->name}を倒した！");
                Debugbar::debug("{$opponents_data[$opponents_index]->name}を倒した。敵の残り体力: {$opponents_data[$opponents_index]->value_hp} 敵討伐フラグ: {$opponents_data[$opponents_index]->is_defeated_flag} ");
              } else {
                $logs->push("{$opponents_data[$opponents_index]->name}に{$calculated_damage}のダメージ！");
                Debugbar::debug("{$opponents_data[$opponents_index]->name}はまだ生存している。敵の残り体力: {$opponents_data[$opponents_index]->value_hp} 敵討伐フラグ: {$opponents_data[$opponents_index]->is_defeated_flag} ");
              }
            // ダメージを与えられなかった場合
            } else {
              Debugbar::debug("ダメージを与えられない。");
              $logs->push("しかし{$opponents_data[$opponents_index]->name}にダメージは与えられなかった！");
              Debugbar::debug("攻撃が通らなかった。{$opponents_data[$opponents_index]->name}は当然生存している。敵の残り体力: {$opponents_data[$opponents_index]->value_hp} 敵討伐フラグ: {$opponents_data[$opponents_index]->is_defeated_flag} ");
            }
          // 全体攻撃の場合
          } else {
            Debugbar::debug("全体攻撃ループ開始。#########");
            // ループ内で書くと攻撃のたびに威力が弱まってしまうので、個別で防御などを改めて取得して処理する。
            $base_damage = $damage;
            foreach ($opponents_data as $opponent_data) {
              // 討伐判定チェック
              if ($opponent_data->is_defeated_flag == true) {
                Debugbar::debug("{$opponent_data->name}はすでに戦闘不能フラグが立っているため、スキップ");
                continue; // returnにするとforeach自体が終了するがcontinueだと次のforeachの処理に移行する
              }
  
              // ダメージ計算 物理か魔法攻撃かで変える
              if ($attack_type == Skill::ATTACK_PHYSICAL_TYPE) {
                Debugbar::debug("物理。");
                $calculated_damage = self::calculatePhysicalDamage(
                  $base_damage, 
                  self::calculateActualStatusValue($opponent_data, 'def')
                );
              } else if ($attack_type == Skill::ATTACK_MAGIC_TYPE) {
                Debugbar::debug("魔法。");
                $opponent_mdef = self::calculateMagicDefenceValue(
                  self::calculateActualStatusValue($opponent_data, 'def'),
                  self::calculateActualStatusValue($opponent_data, 'int')
                );
                $calculated_damage = $base_damage - $opponent_mdef;
              }

              if ($calculated_damage > 0) {
                Debugbar::debug("【SKILL】ダメージが1以上。敵の現在体力: {$opponent_data->value_hp}");
                $opponent_data->value_hp -= $calculated_damage;
                Debugbar::debug("攻撃した。敵の残り体力: {$opponent_data->value_hp}");
                // 敵を倒した場合
                if ($opponent_data->value_hp <= 0 ) {
                  $opponent_data->value_hp = 0; // マイナスになるのを防ぐ。
                  $opponent_data->is_defeated_flag = true;
                  self::clearBuff($opponent_data);
                  $logs->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                  $logs->push("{$opponent_data->name}を倒した！");
                  Debugbar::debug("{$opponent_data->name}を倒した。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                } else {
                  $logs->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                  Debugbar::debug("{$opponent_data->name}はまだ生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                }
              // ダメージを与えられなかった場合
              } else {
                Debugbar::debug("ダメージを与えられない。");
                $logs->push("しかし{$opponent_data->name}にダメージは与えられなかった！");
                Debugbar::debug("攻撃が通らなかった。{$opponent_data->name}は当然生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
              }
            }
            Debugbar::debug("全体攻撃ループ完了。#########");

          }
          break;
        default:
          break;
      }
    }

    // opponents_dataは攻撃されるプレイヤーのデータが入る
    public static function storeEnemyDamage(
      string $command, Object $self_data, Collection $opponents_data, 
      int $opponents_index, Collection $logs, int $damage
    ) {
      switch ($command) {
        case "ATTACK":
          Debugbar::warning("storeEnemyDamage(): ATTACK");

          // 通常攻撃力: 自分のstr - 相手のdef (安直すぎるので今後変更する予定)
          $calculated_damage = self::calculatePhysicalDamage(
            $damage, 
            self::calculateActualStatusValue($opponents_data[$opponents_index], 'def')
          );

          if ($calculated_damage > 0) {
            Debugbar::warning("ダメージが1以上なので攻撃。味方の現在の体力: {$opponents_data[$opponents_index]->value_hp}");
            $opponents_data[$opponents_index]->value_hp -= $calculated_damage;
            Debugbar::warning("攻撃された。味方の残り体力: {$opponents_data[$opponents_index]->value_hp}");
            if ($opponents_data[$opponents_index]->value_hp <= 0 ) {
              $opponents_data[$opponents_index]->value_hp = 0;
              $opponents_data[$opponents_index]->is_defeated_flag = true;
              self::clearBuff($opponents_data[$opponents_index]);
              $logs->push("{$self_data->name}の攻撃！{$opponents_data[$opponents_index]->name}は{$calculated_damage}のダメージを受けた！");
              $logs->push("{$opponents_data[$opponents_index]->name}はやられてしまった！");
              Debugbar::warning("{$opponents_data[$opponents_index]->name}がやられた。味方の残り体力: {$opponents_data[$opponents_index]->value_hp} 味方やられフラグ: {$opponents_data[$opponents_index]->is_defeated_flag} ");
            } else {
              $logs->push("{$self_data->name}の攻撃！{$opponents_data[$opponents_index]->name}は{$calculated_damage}のダメージを受けた！");
              Debugbar::warning("{$opponents_data[$opponents_index]->name}はまだ生存している。味方の残り体力: {$opponents_data[$opponents_index]->value_hp} 味方やられフラグ: {$opponents_data[$opponents_index]->is_defeated_flag} ");
            }
          } else {
            $logs->push("{$self_data->name}の攻撃！しかし{$opponents_data[$opponents_index]->name}は攻撃を防いだ！");
            Debugbar::warning("攻撃が通らなかった。{$opponents_data[$opponents_index]->name}は当然生存している。味方の残り体力: {$opponents_data[$opponents_index]->value_hp} 味方やられフラグ: {$opponents_data[$opponents_index]->is_defeated_flag} ");
          }
          break;
        case "SKILL":
          break;
        default:
          break;
      }
    }

    // スキル回復時もしくは、アイテム回復の時に使う
    public static function storePartyHeal(
      string $command, Object $self_data, Collection $opponents_data, 
      ?int $opponents_index, Collection $logs, ?int $heal_point, int $target_range
    ) {
      switch ($command) {
        case "SKILL":
          Debugbar::debug("回復スキル発動。");
          if ($target_range == Skill::TARGET_RANGE_SINGLE) {
            Debugbar::debug("【単体回復】回復量: {$heal_point} 使用者: {$self_data->name} 対象者: {$opponents_data[$opponents_index]->name}");

            // 戦闘不能ならスキップ
            if ($opponents_data[$opponents_index]->is_defeated_flag == true) {
              $logs->push("しかし{$opponents_data[$opponents_index]->name}は戦闘不能のため効果が無かった！");
            } else {
              $opponents_data[$opponents_index]->value_hp += $heal_point;
              if ($opponents_data[$opponents_index]->value_hp > $opponents_data[$opponents_index]->max_value_hp) {
                $opponents_data[$opponents_index]->value_hp = $opponents_data[$opponents_index]->max_value_hp;
              }
              $logs->push("{$opponents_data[$opponents_index]->name}のHPが{$heal_point}ポイント回復！");
            }

          } elseif ($target_range == Skill::TARGET_RANGE_ALL) {
            // $opponents_dataに対象が全て入っているはずなので、それで回復を回すと良い
            Debugbar::debug("【全体回復】回復量: {$heal_point} 使用者: {$self_data->name}");
            foreach ($opponents_data as $opponent_data) {
              // 戦闘不能ならスキップ
              if ($opponent_data->is_defeated_flag == true) {
                Debugbar::debug("{$opponent_data->name}は戦闘不能のため回復対象としません。");
              } else {
                $opponent_data->value_hp += $heal_point;
                if ($opponent_data->value_hp > $opponent_data->max_value_hp) {
                  $opponent_data->value_hp = $opponent_data->max_value_hp;
                }
                Debugbar::debug("{$opponent_data->name}回復。");
              }
            }

            $logs->push("全員のHPを{$heal_point}ポイント回復！");
          }
          break;
        case "ITEM":
          break;
        default:
          break;
      }
    }

    // バフスキルもしくは、アイテムでバフをかける時に使う
    public static function storePartyBuff(
      string $command, Object $self_data, Collection $opponents_data, 
      ?int $opponents_index, Collection $logs, array $new_buff, int $target_range
    ) {
      switch ($command) {
        case "SKILL":
          Debugbar::debug("バフスキル発動。");
          if ($target_range == Skill::TARGET_RANGE_SINGLE) {
            Debugbar::debug("【単体バフ】使用者: {$self_data->name} 対象者: {$opponents_data[$opponents_index]->name} 使用スキルID: {$new_buff['buffed_skill_id']}");

            // 戦闘不能ならスキップ
            if ($opponents_data[$opponents_index]->is_defeated_flag == true) {
              $logs->push("しかし{$opponents_data[$opponents_index]->name}は戦闘不能のため効果が無かった！");
            } else {
              // 同じバフがかかっているかどうかをチェック
              Debugbar::debug($opponents_data[$opponents_index]->buffs);
              $buff_exists = false;

              // $new_buffの方は配列なので['']で呼ばないとエラーになる
              foreach ($opponents_data[$opponents_index]->buffs as &$already_buff) {
                $already_buff = (array)$already_buff;
                Debugbar::debug($new_buff['buffed_skill_id']); // 型キャストチェック
                Debugbar::debug($already_buff['buffed_skill_id']); // 型キャストチェック

                if ($already_buff['buffed_skill_id'] === $new_buff['buffed_skill_id']) {
                  Debugbar::debug("既にバフが付与されているためターン数を更新します。");
                  $already_buff['remaining_turn'] = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                  $buff_exists = true;
                  break;
                }
              }

              // 同じ buffed_skill_id がなければ、新しいバフを追加
              if (!$buff_exists) {
                Debugbar::debug('新しいバフ追加');
                $opponents_data[$opponents_index]->buffs[] = $new_buff;
              }

              $logs->push("{$opponents_data[$opponents_index]->name}のステータスが向上！");

            }

          } elseif ($target_range == Skill::TARGET_RANGE_ALL) {
            // $opponents_dataに対象が全て入っているはずなので、それで回復を回すと良い
            Debugbar::debug("【全体バフ】使用者: {$self_data->name} 使用スキルID: {$new_buff['buffed_skill_id']}");
            foreach ($opponents_data as $opponent_data) {
              // 戦闘不能ならスキップ
              if ($opponent_data->is_defeated_flag == true) {
                Debugbar::debug("{$opponent_data->name}は戦闘不能のため付与対象としません。");
              } else {
                // 同じバフがかかっているかどうかをチェック
                Debugbar::debug($opponent_data->buffs);
                $buff_exists = false;

                // $new_buffの方は配列なので['']で呼ばないとエラーになる
                foreach ($opponent_data->buffs as &$already_buff) {
                  if ($already_buff->buffed_skill_id === $new_buff['buffed_skill_id']) {
                    Debugbar::debug("既にバフが付与されているためターン数を更新します。");
                    $already_buff->remaining_turn = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                    $buff_exists = true;
                    break;
                  }
                }

                // 同じ buffed_skill_id がなければ、新しいバフを追加
                if (!$buff_exists) {
                  Debugbar::debug('新しいバフ追加');
                  $opponent_data->buffs[] = $new_buff;
                }

              }
            }

            $logs->push("全員の特定の能力値が向上！");
          } else if ($target_range == Skill::TARGET_RANGE_SELF) {
            // 自分に付与する
            Debugbar::debug("【自分自身へのバフ】使用者: {$self_data->name} 使用スキルID: {$new_buff['buffed_skill_id']}");
            // 同じバフがかかっているかどうかをチェック
            Debugbar::debug($self_data->buffs);
            $buff_exists = false;

            // $new_buffの方は配列なので['']で呼ばないとエラーになる
            foreach ($self_data->buffs as &$already_buff) {
              $already_buff = (array)$already_buff;
              if ($already_buff['buffed_skill_id'] === $new_buff['buffed_skill_id']) {
                Debugbar::debug("既にバフが付与されているためターン数を更新します。");
                $already_buff['remaining_turn'] = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                $buff_exists = true;
                break;
              }
            }

            // 同じ buffed_skill_id がなければ、新しいバフを追加
            if (!$buff_exists) {
              Debugbar::debug('新しいバフ追加');
              $self_data->buffs[] = $new_buff;
            }

            $logs->push("{$self_data->name}のステータスが向上！");
          }
          break;
        case "ITEM":
          break;
        default:
          break;
      }
    }

    public static function storePartySpecialSkill(
      Object $self_data, Object $opponents_data, ?int $opponents_index, 
      Collection $logs, array $new_buff, Object $selected_skill
    ) {

      Debugbar::debug("【特殊スキル】使用者: {$self_data->name} 使用スキル: 【{$new_buff['buffed_skill_id']}】{$new_buff['buffed_skill_name']} ");

      // スキル別に個別の処理を回す。
      switch ($selected_skill->id) {
        case 31 :
          Debugbar::debug($opponents_data);
          foreach ($opponents_data as $opponent_data) {
            Debugbar::debug("付与対象:{$opponent_data->name}");
            // 戦闘不能ならスキップ
            if ($opponent_data->is_defeated_flag == true) {
              Debugbar::debug("{$opponent_data->name}は戦闘不能のため付与対象としません。");
            } else {
              $buff_exists = false; // 同じバフがかかっているかどうかをチェック
              foreach ($opponent_data->buffs as &$already_buff) {
                if ($already_buff->buffed_skill_id === $new_buff['buffed_skill_id']) {
                  Debugbar::debug("既にバフが付与されているためターン数を更新します。");
                  $already_buff->remaining_turn = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                  $buff_exists = true;
                  break;
                }
              }
              // 同じ buffed_skill_id がなければ、新しいバフを追加
              if (!$buff_exists) {
                Debugbar::debug('新しいバフ追加');
                $opponent_data->buffs[] = $new_buff;
              }
            }
          }
          break;

        default:
          break;
      }

      // 個別に処理を書いていったほうがいいかもしれない。
      /*
      if ($target_range == Skill::TARGET_RANGE_SINGLE) {
        Debugbar::debug("【単体特殊】使用者: {$self_data->name} 対象者: {$opponents_data[$opponents_index]->name} 使用スキルID: {$new_buff['buffed_skill_id']}");

        // 戦闘不能ならスキップ
        if ($opponents_data[$opponents_index]->is_defeated_flag == true) {
          $logs->push("しかし{$opponents_data[$opponents_index]->name}は戦闘不能のため効果が無かった！");
        } else {
          // 同じバフがかかっているかどうかをチェック
          Debugbar::debug($opponents_data[$opponents_index]->buffs);
          $buff_exists = false;

          // $new_buffの方は配列なので['']で呼ばないとエラーになる
          foreach ($opponents_data[$opponents_index]->buffs as &$already_buff) {
            $already_buff = (array)$already_buff;
            Debugbar::debug($new_buff['buffed_skill_id']); // 型キャストチェック
            Debugbar::debug($already_buff['buffed_skill_id']); // 型キャストチェック

            if ($already_buff['buffed_skill_id'] === $new_buff['buffed_skill_id']) {
              Debugbar::debug("既にバフが付与されているためターン数を更新します。");
              $already_buff['remaining_turn'] = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
              $buff_exists = true;
              break;
            }
          }

          // 同じ buffed_skill_id がなければ、新しいバフを追加
          if (!$buff_exists) {
            Debugbar::debug('新しいバフ追加');
            $opponents_data[$opponents_index]->buffs[] = $new_buff;
          }

          $logs->push("{$opponents_data[$opponents_index]->name}のステータスが向上！");

        }

      } elseif ($target_range == Skill::TARGET_RANGE_ALL) {
        // $opponents_dataに対象が全て入っているはずなので、それで回復を回すと良い
        Debugbar::debug("【全体バフ】使用者: {$self_data->name} 使用スキルID: {$new_buff['buffed_skill_id']}");
        foreach ($opponents_data as $opponent_data) {
          // 戦闘不能ならスキップ
          if ($opponent_data->is_defeated_flag == true) {
            Debugbar::debug("{$opponent_data->name}は戦闘不能のため付与対象としません。");
          } else {
            // 同じバフがかかっているかどうかをチェック
            Debugbar::debug($opponent_data->buffs);
            $buff_exists = false;

            // $new_buffの方は配列なので['']で呼ばないとエラーになる
            foreach ($opponent_data->buffs as &$already_buff) {
              if ($already_buff->buffed_skill_id === $new_buff['buffed_skill_id']) {
                Debugbar::debug("既にバフが付与されているためターン数を更新します。");
                $already_buff->remaining_turn = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                $buff_exists = true;
                break;
              }
            }

            // 同じ buffed_skill_id がなければ、新しいバフを追加
            if (!$buff_exists) {
              Debugbar::debug('新しいバフ追加');
              $opponent_data->buffs[] = $new_buff;
            }

          }
        }

        $logs->push("全員の特定の能力値が向上！");
      } else if ($target_range == Skill::TARGET_RANGE_SELF) {
        // 自分に付与する
        Debugbar::debug("【自分自身へのバフ】使用者: {$self_data->name} 使用スキルID: {$new_buff['buffed_skill_id']}");
        // 同じバフがかかっているかどうかをチェック
        Debugbar::debug($self_data->buffs);
        $buff_exists = false;

        // $new_buffの方は配列なので['']で呼ばないとエラーになる
        foreach ($self_data->buffs as &$already_buff) {
          if ($already_buff->buffed_skill_id === $new_buff['buffed_skill_id']) {
            Debugbar::debug("既にバフが付与されているためターン数を更新します。");
            $already_buff->remaining_turn = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
            $buff_exists = true;
            break;
          }
        }

        // 同じ buffed_skill_id がなければ、新しいバフを追加
        if (!$buff_exists) {
          Debugbar::debug('新しいバフ追加');
          $self_data->buffs[] = $new_buff;
        }

        $logs->push("{$self_data->name}のステータスが向上！");
      }
      */

    }




    // 攻撃で倒した時 / やられた時にバフを消す時の処理を書く
    public static function clearBuff(Object $data) {
      $remaining_buffs = [];
      if (count($data->buffs) > 0) {
        $data->buffs = $remaining_buffs;
      }
      Debugbar::debug("戦闘不能となったので{$data->name}のバフをクリア。");
    }

    // 戦闘処理実行後に呼び出す、バフのターンを1減らす関数
    public static function afterExecCommandCalculateBuff(Collection $players_and_enemies_data, Collection $logs) {
      foreach ($players_and_enemies_data as $data) {
        $remaining_buffs = [];
  
        // 戦闘不能の場合は全てのバフを解除する
        // todo: 戦闘不能→生き返りの挙動があった時、バフが続いてしまうので戦闘不能になった瞬間にバフを外す修正が必要
        if (count($data->buffs) > 0) {
          Debugbar::debug("バフが1つ以上存在するので、バフに関する処理を開始。");
          if($data->is_defeated_flag == true) {
            Debugbar::debug("{$data->name}は戦闘不能のため全てのバフを削除しました");
            $data->buffs = $remaining_buffs;
            continue;
          }
          // 1人ずつ、付与されているバフのターン数を削っていく
          foreach ($data->buffs as $buff) {
            $buff = (object)$buff; // -> で値を呼べるようにオブジェクトにキャストしとく
            $buff->remaining_turn -= 1;
            if ($buff->remaining_turn > 0) {
              Debugbar::debug("{$data->name}の バフ 【{$buff->buffed_skill_id}】{$buff->buffed_skill_name} : 残り {$buff->remaining_turn}ターン");
              $remaining_buffs[] = $buff;
            } else {
              Debugbar::debug("{$data->name}の バフ 【{$buff->buffed_skill_id}】{$buff->buffed_skill_name} が消えました。");
              // 切れた時の表示は不要か？
              $logs->push("{$data->name}に付与されていた{$buff->buffed_skill_name}の効果が切れた。");
            }
          }
          $data->buffs = $remaining_buffs;
        }
      }
    }

    // 通常攻撃 (物理・単体)や物理スキルの計算
    // 攻撃する側のSTR - 相手のDEF 安直すぎるので調整すること。
    public static function calculatePhysicalDamage(int $pure_damage, int $opponent_def) {
      Debugbar::debug("calculatePhysicalDamage(): --- 純粋なダメージ: {$pure_damage} 相手DEF: {$opponent_def}");
      return ceil($pure_damage - $opponent_def);
    }

    // 魔法防御力 = (def * 0.25) + (int * 0.75)
    public static function calculateMagicDefenceValue(int $opponent_def, int $opponent_int) {
      $mdef = ceil(($opponent_def * 0.25) + ($opponent_int * 0.75));
      Debugbar::debug("calculateMagicDefenceValue(): --- 魔法防御計算。DEF: {$opponent_def} INT: {$opponent_int} MDEF: {$mdef}");
      return $mdef;
    }

    // 指定したのステータスのバフを含めた合計値を返す
    public static function calculateActualStatusValue(Object $data, String $status_name) {

      // Debugbar::debug(get_class($data), gettype($data)); 
      $actual_status_value = 0; // バフを考慮したステータスが入る
      $actual_status_name = '';

      Debugbar::debug("calculateActualStatusValue(): --- {$data->name}のバフ含めたステータス {$status_name} の計算");

      switch($status_name) {
        case 'hp' :
          $actual_status_value = $data->value_hp;
          $actual_status_name = 'buffed_hp';
          break;
        case 'ap' :
          $actual_status_value = $data->value_ap;
          $actual_status_name = 'buffed_ap';
          break;
        case 'str' :
          $actual_status_value = $data->value_str;
          $actual_status_name = 'buffed_str';
          break;
        case 'def' :
          $actual_status_value = $data->value_def;
          $actual_status_name = 'buffed_def';
          break;
        case 'int' :
          $actual_status_value = $data->value_int;
          $actual_status_name = 'buffed_int';
          break;
        case 'spd' :
          $actual_status_value = $data->value_spd;
          $actual_status_name = 'buffed_spd';
          break;
        case 'luc' :
          $actual_status_value = $data->value_luc;
          $actual_status_name = 'buffed_luc';
          break;
      }

      // $data->buffsとして処理をすると型がバラけてデバッグが面倒になるので、Collectionで固定させる。
      $data_buffs_collection = collect($data->buffs) ?? collect();

      //   // Debugbar::debug(gettype($data->buffs)); // array
      //   // Debugbar::debug(gettype($data['buffs'])); // エラーになる
      //   // Debugbar::debug(get_class($data->buffs)); // arrayなのでエラー(get_classにはObjectを渡さないといけない)

      // if (!empty($data->buffs)) {
      //   foreach ($data->buffs as $buff) {
      //     // buffed_defが存在する場合だけ加算
      //     if (isset($buff[$actual_status_name])) {
      //       $actual_status_value += $buff[$actual_status_name];
      //       Debugbar::debug("バフ値: {$buff[$actual_status_name]} ");
      //     }
      //   }

      if (!$data_buffs_collection->isEmpty()) {
        // Debugbar::debug(gettype($data_buffs_collection), $data_buffs_collection); // Object
        foreach ($data_buffs_collection as $buff) {

          // $buffを付与した時点でのターンでは typeはarray だが、
          // 次ターンからは$buffのtypeはObject(Collection)となっている。
          // そのためCollectionだった場合は、arrayに戻しておかないとエラーになる
          if (gettype($buff) !== 'array') {
            $buff = (array) $buff; 
          }
          // Debugbar::debug("---------------------------------");
          // Debugbar::debug($buff, gettype($buff)); // $buffはarray
          // Debugbar::debug(
          //   $buff[$actual_status_name], isset($buff[$actual_status_name]),  // バフの値, true
          //   isset($buff->$actual_status_name), isset($buff->buffed_def) // false, false
          // );
          // Debugbar::debug("---------------------------------");
          if (isset($buff[$actual_status_name])) {
            $actual_status_value += $buff[$actual_status_name];
            Debugbar::debug("バフ値: {$buff[$actual_status_name]} ");
          }
        }

        Debugbar::debug("バフを考慮した合計 {$status_name} : {$actual_status_value}");
      } else {
        Debugbar::debug("付与されたバフはありません。合計 {$status_name} : {$actual_status_value} ");
      }

      // Debugbar::debug(gettype($actual_status_value)); // int
      return $actual_status_value;

    }

}

<?php

namespace App\Http\Controllers\Game\Rpg;

use App\Http\Controllers\Controller;
use App\Models\Game\Rpg\BattleState;
use App\Models\Game\Rpg\Enemy;
use App\Models\Game\Rpg\Field;
use App\Models\Game\Rpg\Item;
use App\Models\Game\Rpg\Party;
use App\Models\Game\Rpg\Role;
use App\Models\Game\Rpg\SaveData;
use App\Models\Game\Rpg\Skill;
Use Auth;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Http\Request;

class ApiController extends Controller
{

  // TODO: 
  // POSTのページに直接アクセスしたときエラーログに残るのでリダイレクトされるようにしたい

  // ショップ
  public function shopList() {
    $shop_list_items = Item::getShopListItem();
    return $shop_list_items;
  }
  public function paymentItem(Request $request) {
    $money = $request->money;
    $item_price = $request->price;
    $number = (int)$request->number;

    // 決済処理
    // エラー時はthrow new Exceptionだとうまくvue側でcatchできないので、responseで返して受け取る。
    try {
      // 数量が0, マイナスで指定された場合はその旨を返す。
      if ($number < 1) {
        return response()->json(['error' => '数量を1以上指定してください'], 400);
      }
      $total_price = $item_price * $number;
      $after_payment_money = $money - $total_price;

      if ($after_payment_money < 0) {
        return response()->json(['error' => '所持金額が足りません。'], 400);
      }

      $savedata = SaveData::getLoginUserCurrentSaveData();

      // TODO: アイテムが増える挙動も書く。その際はトランザクションを使う。
      $savedata->update([
        'money' => $after_payment_money
      ]);

      Debugbar::debug($money, $item_price, $number, $total_price, $after_payment_money, $savedata);


    } catch (Exception $e) {

    }
  }

  // ログインユーザーの現在のステータス
  public function loginUserCurrentSaveData() {
    $current_save_data = SaveData::getLoginUserCurrentSaveData();
    return $current_save_data;
  }

  // フィールド
  public function fieldList() {
    return Field::get();
  }

  // 戦闘
  // 戦闘開始
  public function setEncountElement(Request $request) {
    $field_id = 1; // todo:$requestから取得する
    $user_id = Auth::id();

    // 戦闘中かどうかを判断する
    $is_user_battle = BattleState::where('user_id', $user_id)->exists();
    if(!$is_user_battle) {
      $parties = Party::where('user_id', $user_id)->get();
  
      // パーティ3人の名前, HP/MP, スキルを格納する
      $players_data = collect();
      foreach ($parties as $player_index => $party) {
        $learned_skill_ids = $party->skills()->pluck('rpg_skills.id');
        $learned_skill = Skill::select('name', 'description')
          ->whereIn('id', $learned_skill_ids)
          ->get();
        $items = [];
        $role = Role::find($party->rpg_role_id);
        $role_portrait = $role->portrait_image_path;
        DebugBar::debug($role_portrait);
  
        // vue側に渡すデータ
        $player_data = collect([
          'id' => $party->id,
          'name' => $party->nickname, // nicknameにすると敵との表記揺れが面倒。 (foreachで行動を回してる部分とかで。)
          'command' => null, // exec時に格納する
          'target_enemy_index' => null, // exec時に格納する, 味方の攻撃対象とする敵のindex。
          'max_value_hp' => $party->value_hp, // HP最大値
          'max_value_ap' => $party->value_ap, // AP最大値
          'value_hp' => $party->value_hp,
          'value_ap' => $party->value_ap,
          'value_str' => $party->value_str,
          'value_def' => $party->value_def,
          'value_int' => $party->value_int,
          'value_spd' => $party->value_spd,
          'value_luc' => $party->value_luc,
          'skills' => $learned_skill,
          'items' => $items,
          'role_portrait' => $role_portrait,
          'is_defeated_flag' => false,
          'player_index' => $player_index, // 味方のパーティ中での並び。
        ]);
        $players_data->push($player_data);
      }
  
      // 敵の名前、HP/MPを格納する
      // todo: 出現させる敵は何かとか考えとく。
      $enemies_data = collect();
      $enemies = Enemy::where('appear_field_id', $field_id)->get();
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
        ]);
        $enemies_data->push($enemy_data);
      }
  
      // 戦闘データをセッションIDで一意に管理する
      $session_id = \Str::uuid()->toString();
      $battle_state = BattleState::create([
        'user_id' => $user_id,
        'session_id' => $session_id,
        'players_json_data' => json_encode($players_data),
        'enemies_json_data' => json_encode($enemies_data),
        'status' => 'encount',
      ]);

    } else {
      // 戦闘中のデータを取得する
      $battle_state = BattleState::where('user_id', $user_id)->first();
      $session_id = $battle_state['session_id'];
      $players_data = json_decode($battle_state['players_json_data']);
      $enemies_data = json_decode($battle_state['enemies_json_data']);
    }

    // vueに渡すデータ
    $all_data = collect();
    $all_data->push($players_data);
    $all_data->push($enemies_data);
    $all_data->push($session_id);

    return $all_data;
  }

  // 選択されたデータを元に、コマンド実行
  public function execBattleCommand(Request $request) {

    Debugbar::debug("vueからデータを受け取る。---------------");
    $session_id = $request->session_id;
    $battle_state = BattleState::where('session_id', $session_id)->first();
    $battle_logs = collect(); // 結果を格納していく
    $current_players_data = collect(json_decode($battle_state['players_json_data']));
    $current_enemies_data = collect(json_decode($battle_state['enemies_json_data']));
    $commands = collect($request->selectedCommands);


    Debugbar::debug("コマンド情報格納-------------");
    // コマンド情報格納 players_json_data['id']と$coomands['partyId']を紐づける。
    $current_players_data->transform(function ($data) use ($commands) {
      $command = $commands->firstWhere('partyId', $data->id);
      if ($command) {
          $data->command = $command['command'];
          $data->target_enemy_index = $command['enemyIndex'];
      }
      return $data;
    });

    Debugbar::debug("速度順に整理-------------");
    $all_data = $current_players_data->concat($current_enemies_data);
    $all_data_sorted_by_speed = $all_data->sortByDesc('value_spd')->values(); // 同速の場合、現状は味方が優先される
    Debugbar::debug("戦闘実行！-------------");
    foreach($all_data_sorted_by_speed as $index => $data) {
      Debugbar::debug("##### ループ: {$index}人目。 行動対象: {$data->name} 素早さ: {$data->value_spd}");
      if (isset($data->target_enemy_index)) {
        Debugbar::debug("味方( {$data->name} )行動開始");
        if ($data->is_defeated_flag == true) {
          Debugbar::debug("{$data->name}は戦闘不能のためスキップします。");
          continue; // 戦闘不能の場合は何も行わない
        } 
        if ($current_enemies_data->isEmpty()) {
          Debugbar::debug("敵を全員倒したのでスキップします。");
          continue; // 敵が全滅している場合は何も行わない
        } 
        Debugbar::debug("やられ、敵全員討伐チェックOK");
        if($data->command == "ATTACK") {
          // 攻撃対象をすでに倒している場合、別の敵を指定する
          if ($current_enemies_data[$data->target_enemy_index]->is_defeated_flag == true) {
            $new_target_index = $current_enemies_data->search(function ($enemy) {
              return $enemy->is_defeated_flag == false;
            });
            if ($new_target_index !== false) {
              $data->target_enemy_index = $new_target_index;
              Debugbar::debug("攻撃対象がすでに討伐済みのため、対象を変更。改めて攻撃対象: {$current_enemies_data[$data->target_enemy_index]->name}");
            } else {
              Debugbar::debug("すべての敵が討伐済みになりました。敵数: {$current_enemies_data->count()}");
            }
          }
          // ATTACK時のダメージ計算
          $damage = BattleState::calculateAttackValue(
            $data->value_str, 
            $current_enemies_data[$data->target_enemy_index]->value_def
          );
          Debugbar::debug("ダメージ実数値計算。 ダメージ: {$damage}");
          if ($damage > 0) {
            Debugbar::debug("ダメージが1以上なので攻撃。敵の現在体力: {$current_enemies_data[$data->target_enemy_index]->value_hp}");
            $current_enemies_data[$data->target_enemy_index]->value_hp -= $damage;
            Debugbar::debug("攻撃した。敵の残り体力: {$current_enemies_data[$data->target_enemy_index]->value_hp}");
            // 敵を倒した場合
            if ($current_enemies_data[$data->target_enemy_index]->value_hp <= 0 ) {
              $current_enemies_data[$data->target_enemy_index]->value_hp = 0; // マイナスになるのを防ぐ。
              $current_enemies_data[$data->target_enemy_index]->is_defeated_flag = true;
              $battle_logs->push("{$data->name}の攻撃！{$current_enemies_data[$data->target_enemy_index]->name}に{$damage}のダメージ。");
              $battle_logs->push("{$current_enemies_data[$data->target_enemy_index]->name}を倒した！");
              Debugbar::debug("{$current_enemies_data[$data->target_enemy_index]->name}を倒した。敵の残り体力: {$current_enemies_data[$data->target_enemy_index]->value_hp} 敵討伐フラグ: {$current_enemies_data[$data->target_enemy_index]->is_defeated_flag} ");
            } else {
              $battle_logs->push("{$data->name}の攻撃！{$current_enemies_data[$data->target_enemy_index]->name}に{$damage}のダメージ。");
              Debugbar::debug("{$current_enemies_data[$data->target_enemy_index]->name}はまだ生存している。敵の残り体力: {$current_enemies_data[$data->target_enemy_index]->value_hp} 敵討伐フラグ: {$current_enemies_data[$data->target_enemy_index]->is_defeated_flag} ");
            }
          // ダメージを与えられなかった場合
          } else {
            Debugbar::debug("ダメージを与えられない。");
            $battle_logs->push("{$data->name}の攻撃！しかし{$current_enemies_data[$data->target_enemy_index]->name}にダメージを与えられない！");
            Debugbar::debug("攻撃が通らなかった。{$current_enemies_data[$data->target_enemy_index]->name}は当然生存している。敵の残り体力: {$current_enemies_data[$data->target_enemy_index]->value_hp} 敵討伐フラグ: {$current_enemies_data[$data->target_enemy_index]->is_defeated_flag} ");
          }
        // ATTACK以外
        // todo:アイテムとかバフなら味方を選べるようにする
        } else {
          $battle_logs->push("{$data->name}は攻撃以外を選択した。");
        }
      // 敵の行動の場合
      } else {
        Debugbar::warning("敵( {$data->name} )行動開始");
        // ATTACK時の対象味方をランダムに指定
        if ($data->is_defeated_flag == true) {
          Debugbar::warning("{$data->name}はすでにやられているので行動をスキップします。");
          continue; // 行動する敵がやられている場合は何も行わない
        } 
        if ($current_enemies_data->isEmpty()) {
          Debugbar::warning("敵はすべて討伐済みです。");
          continue; // 敵が全滅している場合は何も行わない
        }
        $index = rand(0, $current_players_data->count() - 1);
        Debugbar::warning("------------{$data->name}:攻撃開始 攻撃対象: {$current_players_data[$index]->name}---------------");
        // 攻撃対象の味方がすでに倒れている場合、別の味方を指定する
        if ($current_players_data[$index]->is_defeated_flag == true) {
          $new_target_index = $current_players_data->search(function ($player) {
            return $player->is_defeated_flag == false;
          });
          if ($new_target_index !== false) {
            $index = $new_target_index;
            Debugbar::warning("攻撃対象の味方がすでに倒れているため、対象を変更。改めて攻撃対象: {$current_players_data[$index]->name}");
          } else {
            Debugbar::warning("すべての味方が倒れました。敵数: {$current_players_data->count()}");
          }
        } 
        $damage = BattleState::calculateAttackValue(
          $data->value_str, 
          $current_players_data[$index]->value_def
        );
        Debugbar::warning("ダメージ実数値計算. ダメージ: {$damage}");
        if ($damage > 0) {
          Debugbar::warning("ダメージが1以上なので攻撃。味方の現在の体力: {$current_players_data[$index]->value_hp}");
          $current_players_data[$index]->value_hp -= $damage;
          Debugbar::warning("攻撃された。味方の残り体力: {$current_players_data[$index]->value_hp}");
          if ($current_players_data[$index]->value_hp <= 0 ) {
            $current_players_data[$index]->value_hp = 0;
            $current_players_data[$index]->is_defeated_flag = true;
            $battle_logs->push("{$data->name}の攻撃！{$current_players_data[$index]->name}は{$damage}のダメージを受けた！");
            $battle_logs->push("{$current_players_data[$index]->name}はやられてしまった！");
            Debugbar::warning("{$current_players_data[$index]->name}がやられた。味方の残り体力: {$current_players_data[$index]->value_hp} 味方やられフラグ: {$current_players_data[$index]->is_defeated_flag} ");
          } else {
            $battle_logs->push("{$data->name}の攻撃！{$current_players_data[$index]->name}は{$damage}のダメージを受けた！");
            Debugbar::warning("{$current_players_data[$index]->name}はまだ生存している。味方の残り体力: {$current_players_data[$index]->value_hp} 味方やられフラグ: {$current_players_data[$index]->is_defeated_flag} ");
          }
        } else {
          $battle_logs->push("{$data->name}の攻撃！しかし{$current_players_data[$index]->name}は攻撃を防いだ！");
          Debugbar::warning("攻撃が通らなかった。{$current_players_data[$index]->name}は当然生存している。味方の残り体力: {$current_players_data[$index]->value_hp} 味方やられフラグ: {$current_players_data[$index]->is_defeated_flag} ");
        }
      }
    }

    Debugbar::debug("--------------戦闘処理完了(ステータス一覧)----------------");
    Debugbar::debug($current_players_data, $current_enemies_data, $battle_logs);
    Debugbar::debug("----------------------------------------------------------");

    // rpg_battle_states更新
    $updated_battle_state = $battle_state->update([
        'players_json_data' => json_encode($current_players_data),
        'enemies_json_data' => json_encode($current_enemies_data),
    ]);

    // vueに渡すデータ
    $all_vue_data = collect();
    $all_vue_data->push($current_players_data);
    $all_vue_data->push($current_enemies_data);
    $all_vue_data->push($battle_logs);

    return $all_vue_data;
  }

  // 戦闘途中終了をした場合、戦闘データを消す
  public function escapeBattle(Request $request) {
    $session_id = $request->session_id;
    $battle_state = BattleState::where('session_id', $session_id)->first();
    Debugbar::debug($battle_state);
    if(!$battle_state) return new Response('', 404);
    $battle_state->delete();
  }



}

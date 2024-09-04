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

      Debugbar::info($money, $item_price, $number, $total_price, $after_payment_money, $savedata);


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
    $fieid_id = 3; // todo:$requestから取得する
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
        DebugBar::info($role_portrait);
  
        // vue側に渡すデータ
        $player_data = collect([
          'id' => $party->id,
          'nickname' => $party->nickname,
          'command' => null, // exec時に格納する
          'target_enemy_index' => null, // exec時に格納する, 味方の攻撃対象とする敵のindex。
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
      $enemies = Enemy::where('appear_field_id', $fieid_id)->get();
      foreach ($enemies as $enemy_index => $enemy) {
        $enemy_data = collect([
          'id' => $enemy->id,
          'name' => $enemy->name,
          'command' => null, // exec時に格納する
          'target_player_index' => null, // exec時に格納する, 敵の攻撃対象とする味方のindex。
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

    $session_id = $request->session_id;
    $battle_state = BattleState::where('session_id', $session_id)->first();
    $battle_logs = collect(); // 結果を格納していく

    $current_players_data = collect(json_decode($battle_state['players_json_data']));
    $current_enemies_data = collect(json_decode($battle_state['enemies_json_data']));
    $commands = collect($request->selectedCommands);

    // 戦闘不能の味方を取り除く
    $current_players_data =  $current_players_data->filter(function($member) {
      return !$member->is_defeated_flag;
    });
    // インデックスの再割り当て。
    // [0,1,2]というメンバーで1が戦闘不能になった場合、[0,2]となるが、それを[0,1]に再割り当てしておく
    // あとで敵が対象を選ぶときのtarget_index rand()で使う。
    $current_players_data = $current_players_data->values(); 

    // 倒した敵を取り除く
    $current_enemies_data =  $current_enemies_data->filter(function($member) {
      return !$member->is_defeated_flag;
    });
    $current_enemies_data = $current_enemies_data->values();

    // players_json_data['id']と$coomands['partyId']を紐づける。
    $current_players_data->transform(function ($data) use ($commands) {
      // $commandsの中から、現在回しているjsonデータのidとpartyIdが最初に一致する配列を$commandとして入れる
      $command = $commands->firstWhere('partyId', $data->id);
      if ($command) {
          // コマンドとenemyIndexを格納する
          $data->command = $command['command'];
          $data->target_enemy_index = $command['enemyIndex'];
      }
      return $data;
    });
    // Debugbar::info('------------------------');
    $all_data = $current_players_data->concat($current_enemies_data);
    // 速度順に並べる。
    $all_data_sorted_by_speed = $all_data->sortByDesc('value_spd');

    foreach($all_data_sorted_by_speed as $data) {
      if (isset($data->target_enemy_index)) {
        // 味方の行動の場合
        // 戦闘不能の場合は何も行わない
        if ($data->is_defeated_flag == true) continue;
        if($data->command == "ATTACK") {
          // ATTACK時のダメージ計算
          $damage = BattleState::calculateAttackValue(
            $data->value_str, 
            $current_enemies_data[$data->target_enemy_index]->value_def
          );
          if ($damage > 0) {
            $current_enemies_data[$data->target_enemy_index]->value_hp -= $damage;
            // 敵を倒した場合
            if ($current_enemies_data[$data->target_enemy_index]->value_hp <= 0 ) {
              $current_enemies_data[$data->target_enemy_index]->value_hp = 0; // マイナスになるのを防ぐ。
              $current_enemies_data[$data->target_enemy_index]->is_defeated_flag = true;
              $battle_logs->push("{$data->nickname}の攻撃！{$current_enemies_data[$data->target_enemy_index]->name}に{$damage}のダメージ。");
              $battle_logs->push("{$current_enemies_data[$data->target_enemy_index]->name}を倒した！");
            } else {
              $battle_logs->push("{$data->nickname}の攻撃！{$current_enemies_data[$data->target_enemy_index]->name}に{$damage}のダメージ。");
            }
          // ダメージを与えられなかった場合
          } else {
            $battle_logs->push("{$data->nickname}の攻撃！しかし{$current_enemies_data[$data->target_enemy_index]->name}に刃が立たなかった！");
          }
        // ATTACK以外
        // todo:アイテムとかバフなら味方を選べるようにする
        } else {
          $battle_logs->push("{$data->nickname}は攻撃以外を選択した。");
        }
      // 敵の行動の場合
      } else {
        // ATTACK時の対象味方をランダムに指定
        $index = rand(0, $current_players_data->count() - 1);
        $damage = BattleState::calculateAttackValue(
          $data->value_str, 
          $current_players_data[$index]->value_def // とりあえず最初のメンバーに攻撃させる
        );
        if ($damage > 0) {
          $current_players_data[$index]->value_hp -= $damage;
          if ($current_players_data[$index]->value_hp <= 0 ) {
            $current_players_data[$index]->value_hp = 0;
            $current_players_data[$index]->is_defeated_flag = true;
            $battle_logs->push("{$data->name}の攻撃！{$current_players_data[$index]->nickname}に{$damage}のダメージ。");
            $battle_logs->push("{$current_players_data[$index]->nickname}はやられてしまった！");
          } else {
            $battle_logs->push("{$data->name}の攻撃！{$current_players_data[$index]->nickname}に{$damage}のダメージ。");
          }
        } else {
          $battle_logs->push("{$data->name}の攻撃！しかし{$current_players_data[$index]->nickname}は攻撃を防いだ！");
        }
      }
    }


    // 戦闘終了後、改めて戦闘不能の味方/敵を取り除く
    $current_players_data =  $current_players_data->filter(function($member) {
      return !$member->is_defeated_flag;
    })->values(); 
    $current_enemies_data =  $current_enemies_data->filter(function($member) {
      return !$member->is_defeated_flag;
    })->values();

    Debugbar::info('-----------戦闘終了後-------------');
    Debugbar::info($current_players_data, $current_enemies_data, $battle_logs);

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
    Debugbar::info($battle_state);
    if(!$battle_state) return new Response('', 404);
    $battle_state->delete();
  }



}

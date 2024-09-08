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
    $field_id = $request->field_id;
    Debugbar::debug("setEncountElement(). field_id: {$field_id} ---------------");
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
          'is_enemy' => false,
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
          'is_enemy' => true, // 味方と敵で同じデータを呼んでいるので、敵フラグを立てておく
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
    $players_and_enemies_data = $current_players_data->concat($current_enemies_data);
    $battle_exec_array = BattleState::sortByBattleExec($players_and_enemies_data);

    Debugbar::debug("戦闘実行！ BattleState::execBattleCommand()----------------");
    BattleState::execBattleCommand(
      $battle_exec_array, $current_players_data, $current_enemies_data, $battle_logs
    );

    Debugbar::debug("--------------戦闘処理完了(ステータス一覧)----------------");
    Debugbar::debug($current_players_data, $current_enemies_data, $battle_logs);
    Debugbar::debug("----------------------------------------------------------");

    // rpg_battle_states更新
    $updated_battle_state = $battle_state->update([
        'players_json_data' => json_encode($current_players_data),
        'enemies_json_data' => json_encode($current_enemies_data),
    ]);

    // vueに渡すデータ
    $all_vue_data = collect()
      ->push($current_players_data)
      ->push($current_enemies_data)
      ->push($battle_logs);

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

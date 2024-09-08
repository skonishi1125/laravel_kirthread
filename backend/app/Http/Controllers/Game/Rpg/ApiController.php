<?php

namespace App\Http\Controllers\Game\Rpg;

use App\Http\Controllers\Controller;
use App\Models\Game\Rpg\BattleState;
use App\Models\Game\Rpg\Enemy;
use App\Models\Game\Rpg\Exp;
use App\Models\Game\Rpg\Field;
use App\Models\Game\Rpg\Item;
use App\Models\Game\Rpg\Party;
use App\Models\Game\Rpg\Role;
use App\Models\Game\Rpg\SaveData;
use App\Models\Game\Rpg\Skill;
use App\Models\Game\Rpg\PresetAppearingEnemy;
use Illuminate\Support\Facades\DB;
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
    $stage_id = $request->stage_id;
    Debugbar::debug("setEncountElement(). field_id: {$field_id}, stage_id: {$stage_id}  ---------------");
    $user_id = Auth::id();

    // 戦闘中かどうかを判断する
    $is_user_battle = BattleState::where('user_id', $user_id)->exists();
    if(!$is_user_battle) {
      Debugbar::debug("戦闘中のデータが存在しないため、新規戦闘として扱います。  ---------------");
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
      Debugbar::debug("戦闘中です。セッションIDから戦闘履歴を取得します。  ---------------");
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

  // 戦闘勝利の処理
  public function resultWinBattle(Request $request) {
    Debugbar::debug("resultWinBattle(): ---------------");
    $session_id = $request->session_id;
    $is_win = $request->is_win;
    // 戦闘が正常に終了したかのチェック
    // vue側でresultWinBattle()からもらえるis_winか、もしくはjsonの敵情報のis_defeated_flagを全て見るかどっちか。
    // 一旦リクエストでもらえる値を参考にする
    if (!$is_win) return redirect()->route('game_rpg_index');

    $battle_state = BattleState::where('session_id', $session_id)->first();

    // ※ 戦闘勝利の処理を繰り返せてしまうとリロードなどで稼がれる可能性がある。
    // そのためトランザクションで挟んだのち、処理後に戦闘キャッシュを消して管理する。
    if (!$battle_state) return redirect()->route('game_rpg_index'); 

    $savedata = SaveData::where('user_id', Auth::id())->first();
    $parties = Party::where('user_id', Auth::id())->get();
    $exp_tables = Exp::get();
    $result_logs = collect();

    // 合計獲得ゴールド,expを取得する
    $enemies_json_data = collect(json_decode($battle_state['enemies_json_data']));
    $total_aquire_exp = 0;
    $total_aquire_money = 0;
    foreach ($enemies_json_data as $enemy) {
      $total_aquire_exp += $enemy->exp;
      $total_aquire_money += $enemy->drop_money;
    }

    // 一人当たりの経験値を計算
    $per_exp = ceil($total_aquire_exp / $parties->count());

    Debugbar::debug("獲得経験値:{$total_aquire_exp}(一人当たり:{$per_exp}) 獲得ゴールド:{$total_aquire_money} ");
    $result_logs->push("敵を倒した！{$total_aquire_money}Gとそれぞれ経験値{$per_exp}を獲得。");

    try {
      DB::transaction(function () use ($savedata, $parties, $total_aquire_money, $per_exp, $exp_tables, $result_logs) {
        // 金額処理
        $savedata->increment('money', $total_aquire_money);
        Debugbar::debug("ゴールド加算完了。 現在金額: {$savedata->money}");

        Debugbar::debug("ループ対象のパーティの数: {$parties->count()} 人");
        // 経験値処理
        foreach ($parties as $party) {
          Debugbar::debug("ループ処理開始: {$party->nickname}に経験値:{$per_exp}を振り分けます。##############");
          $party->increment('total_exp', $per_exp);
          $current_party_exp = $party->total_exp;
          $current_party_level = $party->level;
          $new_level = null;

          Debugbar::debug("現在経験値: {$party->total_exp}");

          // 経験値テーブルと現在の総合獲得経験値を比較して、そのレベルにする
          foreach ($exp_tables as $exp_table) {
            if ($current_party_exp >= $exp_table->total_exp) {
              $new_level = $exp_table->level;
            } else {
              break;  // 必要経験値を超えたらループを終了
            }
          }
          // レベルアップ時の処理
          // やることはレベル自体の数字反映と、上がったレベルに応じてステータス上昇の処理
          if ($new_level && $new_level > $party->level) {
            Debugbar::debug("レベルアップしました。{$current_party_level} -> {$new_level}");
            // レベルが上がった分だけforで回す。
            // 例えばlv1からlv4に上がった時、 lv2, lv3, lv4としてステータスを回したい。
            //  ($i = 2; $i <= 4; $i++) で合計3回。
            for ($i = $current_party_level + 1; $i <= $new_level; $i++) {
              // todo: 各ステータスを上げる処理を作る。今はとりあえず力を上げるだけ。
              $str_increase = rand(1, 3);
              $party->increment('value_str', $str_increase);
              Debugbar::debug("STRが{$str_increase}アップ。");
            }
            // レベル反映
            $party->update(['level' => $new_level]);
            $result_logs->push("{$party->nickname}はレベルが{$current_party_level}から{$new_level}にアップ！");
          }
        }
      });

      // 各種処理が終わったらセッションデータを破棄する。
      $battle_state->delete();
      Debugbar::debug("戦闘データを削除しました。");

    } catch(\Exception $e) {
      Debugbar::debug("例外処理を検知しました。");
      \Log::error('resultWinBattle() でエラーが発生しました。', ['error' => $e->getMessage()]);
    }
    Debugbar::debug("獲得金額、獲得経験値処理完了。");
    Debugbar::debug($result_logs);
    return response()->json($result_logs);

  }

  // 戦闘途中終了をした場合、戦闘データを消す
  public function escapeBattle(Request $request) {
    $session_id = $request->session_id;
    $battle_state = BattleState::where('session_id', $session_id)->first();
    Debugbar::debug($battle_state);
    return response()->json(
      [], 
      404, 
      ['Content-Type' => 'application/json'], 
      JSON_UNESCAPED_UNICODE
    );
    $battle_state->delete();
  }



}

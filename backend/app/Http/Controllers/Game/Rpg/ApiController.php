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
    $shop_element_data = collect();
    $shop_list_items = Item::getShopListItem();
    $savedata = SaveData::getLoginUserCurrentSaveData();

    foreach ($shop_list_items as $item) {
      $data = collect([
        'id' => $item->id,
        'name' => $item->name,
        'price' => $item->price,
        'description' => $item->description,
        'max_possesion_number' => $item->max_possesion_number,
        'money' => $savedata->money
      ]);
      $shop_element_data->push($data);
    }

    return $shop_element_data;
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
    $fields = Field::get();
    $field_json_data = collect();

    foreach ($fields as $field) {
      $field_json_data->push([
        'id' => $field->id,
        'name' => $field->name,
        'difficulty' => $field->convertDifficultyStars(),
      ]);
    }

    return $field_json_data;
  }

  // 戦闘
  // 戦闘開始
  public function setEncountElement(Request $request) {
    $field_id = $request->field_id;
    $stage_id = $request->stage_id;
    Debugbar::debug("setEncountElement(). field_id: {$field_id}, stage_id: {$stage_id}  ---------------");
    $user_id = Auth::id();

    // 戦闘中かどうかの判定
    $is_user_battle = BattleState::where('user_id', $user_id)->exists();

    // stage_idが1以外なら、URLベタ打ちでなく戦闘後に正しく遷移してきたかを確認する。
    // 現在1-2なら、clearStageに'1-1'が入っていたら通せば良い。1-3なら、'1-2'をクリアしていたら通す
    // ただしstage_id = 2以降の時点で間違えて画面更新をした場合とかはclear_stageがnullになるのでスルーする
    // todo: オムニバス形式にするなら、火山に入ったときに「草原をクリアしたか」とかも設定する必要あり。
    if ($stage_id != 1 && $is_user_battle == null) {
      Debugbar::debug("ステージ2以降の処理。 ---------------");
      $clear_stage = $request->clear_stage;
      Debugbar::debug($clear_stage);
      if ($clear_stage == null) {
        Debugbar::debug("$clear_stage null error");
        return abort(500,'clearStageがnullです');
      } 
      // クリアしたfield_idと現在のfield_idが一致する場合 &&
      // クリアしたstage_idと現在のstage_id - 1の値が一致する場合は処理を継続。
      // そうでない場合は500レスポンスを返す。
      // 1-3 -> 1-4に遷移したならば、 1 == 1 || 3 == (4-1) になるはず
      $explode_clear_stage = explode('-', $clear_stage);
      if ($explode_clear_stage[0] == $field_id && $explode_clear_stage[1] == ($stage_id - 1)) {
        Debugbar::debug("クリア判定しました。現在: {$field_id}-{$stage_id} クリア: {$clear_stage} ---------------");
      } else {
        return abort(500,'予期しない形でステージ遷移が行われました');
      }
    }

    if(!$is_user_battle) {
      Debugbar::debug("戦闘中のデータが存在しないため、新規戦闘として扱います。  ---------------");

      // パーティ3人の情報(ステータス,スキル,アイテム)を格納する
      $players_data = BattleState::createPlayersData($user_id, null);

      // ステージの敵情報を読み込む
      $enemies_data = BattleState::createEnemiesData($field_id, $stage_id);
  
      // 戦闘データをセッションIDで一意に管理する
      $battle_state = BattleState::createBattleState($user_id, $players_data, $enemies_data, $field_id, $stage_id);

    } else {
      // 戦闘中のデータを取得する
      Debugbar::debug("戦闘中です。セッションIDから戦闘履歴を取得します。  ---------------");
      $battle_state = BattleState::where('user_id', $user_id)->first();

      // $battle_stateの情報と現在のfield_id, stage_idが一致しているか確認する
      // 例えば /game/rpg/battle/1/2 に正常進行 -> 1/3にURLベタ打ちすると1-2のstateがある状態で1-3に遷移できる
      // この場合、1-2の敵データをクリアすると1-4に進行することができてしまう
      if ($battle_state->current_field_id == $field_id && $battle_state->current_stage_id == $stage_id) {
        Debugbar::debug("現URLとbattle_stateのデータの整合性が確認できました。現在: {$field_id}-{$stage_id} battle_state: {$battle_state->current_field_id}-{$battle_state->current_stage_id}");
      } else {
        return abort(500, '現URLとbattle_stateのデータの整合性が確認できませんでした。');
      }

      $players_data = json_decode($battle_state['players_json_data']);
      $enemies_data = json_decode($battle_state['enemies_json_data']);
    }

    // vueに渡すデータ
    // [0]プレイヤー情報 [1]敵情報 [2]セッションID
    $all_data = collect()->push($players_data)->push($enemies_data)->push($battle_state->session_id);

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
        $data->target_enemy_index = $command['enemyIndex'] ?? null;
        $data->target_player_index = $command['playerIndex'] ?? null;
        $data->selected_skill_id = $command['skillId'] ?? null;
        
        // スキルを選んでいたなら、そのeffect_typeを取得しておく(行動順決定で使う)
        if (!is_null($data->selected_skill_id)) {
          Debugbar::debug("{$data->name}はスキルid: {$data->selected_skill_id}を選択。 ");
          $selected_skill_effect_type = collect($data->skills)
            ->firstWhere('id', $data->selected_skill_id)
            ->effect_type;
          $data->selected_skill_effect_type = $selected_skill_effect_type;
        } else {
          $data->selected_skill_effect_type = null;
        }

        // todo: item。
      }
      return $data;
    });

    Debugbar::debug("速度順に整理-------------");
    $players_and_enemies_data = $current_players_data->concat($current_enemies_data);
    $sorted_players_and_enemies_data = BattleState::sortByBattleExec($players_and_enemies_data);

    // Debugbar::debug(get_class($sorted_players_and_enemies_data), get_class($current_enemies_data), get_class( $current_players_data), get_class($battle_logs));

    Debugbar::debug("戦闘実行！ BattleState::execBattleCommand()----------------");
    BattleState::execBattleCommand(
      $sorted_players_and_enemies_data, $current_players_data, $current_enemies_data, $battle_logs
    );

    Debugbar::debug("--------------戦闘処理完了(ステータス一覧)----------------");
    Debugbar::debug($current_players_data, $current_enemies_data, $battle_logs);
    Debugbar::debug("----------------------------------------------------------");

    Debugbar::debug("バフターン数計算処理-------------------------------");
    $players_and_enemies_data = $current_players_data->concat($current_enemies_data);
    BattleState::afterExecCommandCalculateBuff($players_and_enemies_data, $battle_logs);

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
    if (!$is_win) return abort(500, 'is_win error');

    $battle_state = BattleState::where('session_id', $session_id)->first();

    // ※ 戦闘勝利の処理を繰り返せてしまうとリロードなどで稼がれる可能性がある。
    // そのためトランザクションで挟んだのち、処理後に戦闘キャッシュを消して管理する。
    if (!$battle_state) return abort(500, 'not exist battle state');

    $savedata = SaveData::where('user_id', Auth::id())->first();
    $parties = Party::where('user_id', Auth::id())->get();
    $exp_tables = Exp::get();
    $result_logs = collect();

    $when_cleared_players_data = collect(json_decode($battle_state->players_json_data));

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
      DB::transaction(function () use (
        $savedata, $parties, $total_aquire_money, $per_exp, $exp_tables, $result_logs, $when_cleared_players_data
      ) {
        // 金額処理
        $savedata->increment('money', $total_aquire_money);
        Debugbar::debug("ゴールド加算完了。 現在金額: {$savedata->money}");

        Debugbar::debug("ループ対象のパーティの数: {$parties->count()} 人");
        // 経験値処理
        foreach ($parties as $index => $party) {
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
            $total_growth = [
              'hp' => 0, 'ap' => 0, 'str' => 0,
              'def' => 0, 'int' => 0, 'spd' => 0, 'luc' => 0,
            ];
            for ($i = $current_party_level + 1; $i <= $new_level; $i++) {
              // ステータス上昇処理
              $increase_values = Party::calculateGaussianGrowth($party);
              Debugbar::debug("HPが{$increase_values['growth_hp']}, apが{$increase_values['growth_ap']}, strが{$increase_values['growth_str']}, defが{$increase_values['growth_def']}, intが{$increase_values['growth_int']}, spdが{$increase_values['growth_spd']}, lucが{$increase_values['growth_luc']}アップ。");

              $total_growth = [
                'hp' => $total_growth['hp'] += $increase_values['growth_hp'],
                'ap' => $total_growth['ap'] += $increase_values['growth_ap'],
                'str' => $total_growth['str'] += $increase_values['growth_str'],
                'def' => $total_growth['def'] += $increase_values['growth_def'],
                'int' => $total_growth['int'] += $increase_values['growth_int'],
                'spd' => $total_growth['spd'] += $increase_values['growth_spd'],
                'luc' => $total_growth['luc'] += $increase_values['growth_luc'],
              ];

            }
            // レベル反映
            $party->update(['level' => $new_level]);
            $result_logs->push("{$party->nickname}はレベルが{$current_party_level}から{$new_level}にアップ！  HP +{$total_growth['hp']} AP +{$total_growth['ap']} STR +{$total_growth['str']} DEF +{$total_growth['def']} INT +{$total_growth['int']} SPD +{$total_growth['spd']} LUC +{$total_growth['luc']}");

            // レベルが上がった時、減っているHP/APも回復させてあげたい
            // 選択中のキャラの現在のjsonデータの max_value_hp/ap と value_hp/ap を調整してやれば良い
            $when_cleared_players_data[$index]->max_value_hp = $party->value_hp;
            $when_cleared_players_data[$index]->value_hp = $party->value_hp;
            $when_cleared_players_data[$index]->max_value_ap = $party->value_ap;
            $when_cleared_players_data[$index]->value_ap = $party->value_ap;

            Debugbar::debug("
              HPとAPを全回復。
              HP:{$when_cleared_players_data[$index]->value_hp} 
              AP:{$when_cleared_players_data[$index]->value_ap}
            ");
            // Debugbar::info($when_cleared_players_data[$index]);
            // Debugbar::info($party);

          }
        }
      });

      // 各種処理が終わったらセッションデータを破棄する。
      // ここの処理をコメントアウトすれば戦闘勝利部分のデバッグができる

      $field_id = $battle_state->current_field_id;
      $next_stage_id = $battle_state->current_stage_id + 1;

      $next_players_data = BattleState::createPlayersData(Auth::id(), $when_cleared_players_data);
      $next_enemies_data = BattleState::createEnemiesData($field_id, $next_stage_id);
      $create_next_battle_state = BattleState::createBattleState(
        Auth::id(), $next_players_data, $next_enemies_data, $field_id, $next_stage_id
      );
      $battle_state->delete();
      Debugbar::debug("現在の戦闘データを削除しました。");

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
    Debugbar::debug("escapeBattle(): ---------------------");
    $session_id = $request->session_id;
    $battle_state = BattleState::where('session_id', $session_id)->first();

    // 現在のセッションIDで見つからなければ、ユーザーIDで検索をかけて削除する
    if (!$battle_state) {
      Debugbar::debug("セッションID {$session_id} から情報を見つけられないため、ユーザーIDで検索をかけ削除します。");
      $battle_state = BattleState::where('user_id', Auth::id())->get();
      foreach ($battle_state as $b) {
        $b->delete();
      }
    } else {
      $battle_state->delete();
    }

    Debugbar::debug("戦闘セッションを削除しました。");
  }



}

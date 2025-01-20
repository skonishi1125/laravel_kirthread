<?php

namespace App\Http\Controllers\Game\Rpg;

use App\Http\Controllers\Controller;
use App\Models\Game\Rpg\BattleState;
use App\Models\Game\Rpg\Exp;
use App\Models\Game\Rpg\Field;
use App\Models\Game\Rpg\Item;
use App\Models\Game\Rpg\Party;
use App\Models\Game\Rpg\Role;
use App\Models\Game\Rpg\Savedata;
use App\Models\Game\Rpg\Skill;
use App\Models\Profile;
use App\User;
use Auth;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    // todo: constructなどでログインしているユーザーがアクセスできる前提とする

    /**
     * タイトル画面。ユーザーの状態に応じてパターンを分ける。
     * ・未ログイン: ログインまたはユーザー登録をしてもらうようモーダルを出す。
     * ・ログイン済 && データなし: 「最初から」ボタンを出す。
     * ・ログイン済 && データあり && パーティ登録なし: 「最初から」ボタンを出す。
     * ・ログイン済 && データあり && パーティ登録あり: 「街に戻る」ボタンを出す。
     */
    public function checkSituation()
    {
        ! Auth::check() ? $status = 'unsigned' : $status = 'signed';
        if (Savedata::checkSavedataHasParties()) {
            $status = 'ready';
        }

        return response()->json(['status' => $status, 'user_id' => Auth::id()]);

    }

    // すぐ作る機能で作成
    public function createRpgUser(Request $request)
    {
        Debugbar::debug('createRpgUser():------------------------');

        // メールアドレスチェック
        $is_exist_email = User::where('email', $request->email)->exists();
        if ($is_exist_email) {
            return response()->json([
                'message' => 'このemailはすでに使われています。 再生成または別のアドレスの記入をお試しください。',
            ], 409);
        }

        \DB::transaction(function () use ($request) {
            Debugbar::debug("{$request['name']}");
            $create_user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
            ]);
            Debugbar::debug('ユーザー作成OK');
            // プロフィールも一緒に作る
            $create_profile = Profile::create([
                'user_id' => $create_user->id,
                'message' => 'よろしくお願いします。',
            ]);
            // 作成したユーザーでログイン
            Auth::login($create_user);
        });

        // 成功メッセージまたはユーザー情報を返す
        return response()->json([
            'message' => 'ユーザーが作成され、ログインしました。',
        ]);
    }

    // 削除予定のデータ関連情報を返す
    public function checkSavedataInfo(Request $request)
    {
        $savedata = Savedata::getLoginUserCurrentSavedata();
        $parties = $savedata->parties;
        $parties = $parties->map(function ($party) {
            $party['class_japanese'] = $party->role->class_japanese;

            return $party;
        });
        $return_infos = collect([
            'money' => $savedata->money,
            'parties' => $parties,
        ]);

        return response()->json($return_infos);
    }

    public function deleteSavedata(Request $request)
    {
        // 紐づくデータの削除を行う
        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'このセーブデータはすでに削除されています。画面のリロードをお試しください。',
            ], 409);
        }

        \DB::transaction(function () use ($savedata) {
            $savedata->delete();
        });

        return response()->json([
            'message' => 'データを削除しました。',
        ]);
    }

    /**
     * 「最初から」選択時の初期処理。
     *  セーブデータの作成チェック・職業情報をvue側に渡す。
     */
    public function prepareBeginning()
    {
        Debugbar::debug('prepareBeginning():------------------------');
        $roles = Role::get();
        $is_exist_data = false;

        // Debugbar::debug("prepareBeginning():------------------------ data: {$return_data}");

        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            $savedata = Savedata::create([
                'user_id' => Auth::id(),
                'money' => '300',
            ]);
        }
        // セーブデータを作っただけのユーザーがいるかのチェック。紐づくメンバーがいる場合trueにする。
        $parties = $savedata->parties;
        $parties->isEmpty() ? $is_exist_data = false : $is_exist_data = true;

        $return_data = collect()
            ->push($is_exist_data)
            ->push($roles);

        Debugbar::debug("return_data: {$return_data}");

        return $return_data;
    }

    public function createParties(Request $request)
    {
        Debugbar::debug('createParties():------------------------');
        $selected_info = $request->selected_info;
        // 送られるデータ: "roleId" => 4, "roleClassJapanese" => "魔導士", "partyName" => "メイ" というArrayが3つ
        // Debugbar::debug($selected_info, gettype($selected_info));
        $savedata = Savedata::getLoginUserCurrentSavedata();
        Debugbar::debug($savedata, count($selected_info));

        $created_parties = collect();

        try {
            DB::transaction(function () use ($savedata, $selected_info, $created_parties) {
                // 想定していないケースの場合に500エラーを返す
                // if (this.currentDecidedMemberIndex <= 3) にすれば検証できる
                if (count($selected_info) > 3) {
                    throw new \Exception('選択されたデータが3件以上存在します。もう一度お試しください。');
                }
                // 適当なデータを1件作れば検証できる
                if (! $savedata->parties->isEmpty()) {
                    throw new \Exception('すでにパーティメンバーが作成されています。ページ更新をお試しください。');
                }
                foreach ($selected_info as $party) {
                    // inputのmaxlengthを調整すれば検証できる
                    if (mb_strlen($party['partyName']) > 6) {
                        throw new \Exception('パーティメンバーの名前は6文字以下でなければなりません。もう一度お試しください。');
                    }

                    $created_party = Party::generateRpgPartyMember($savedata->id, $party['roleId'], $party['partyName']);
                    Debugbar::debug("作成完了。id: {$created_party->id} nickname: {$created_party->nickname}");
                    Debugbar::debug($created_party);
                    $created_parties->push($created_party);
                }
            });
        } catch (\Exception $e) {
            Debugbar::debug('createParties() でエラーが発生しました。');

            // \Log::error('createParties() でエラーが発生しました。', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        // 作成したパーティの情報をvueに返す。
        return $created_parties;
    }

    // TODO:
    // POSTのページに直接アクセスしたときエラーログに残るのでリダイレクトされるようにしたい

    // ショップ
    public function shopList()
    {
        $shop_element_data = collect();
        $shop_list_items = Item::getShopListItem();
        $savedata = Savedata::getLoginUserCurrentSavedata();

        foreach ($shop_list_items as $item) {
            $data = collect([
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'description' => $item->description,
                'max_possesion_number' => $item->max_possesion_number,
                'money' => $savedata->money,
            ]);
            $shop_element_data->push($data);
        }

        return $shop_element_data;
    }

    public function paymentItem(Request $request)
    {
        $money = $request->money;
        $item_price = $request->price;
        $number = (int) $request->number;

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

            $savedata = Savedata::getLoginUserCurrentSavedata();

            // TODO: アイテムが増える挙動も書く。その際はトランザクションを使う。
            $savedata->update([
                'money' => $after_payment_money,
            ]);

            Debugbar::debug($money, $item_price, $number, $total_price, $after_payment_money, $savedata);

        } catch (Exception $e) {

        }
    }

    // ステータス及びスキルの確認
    public function getPartiesInfo()
    {
        // Savedataからパーティを取得し、パーティに合ったスキルツリー情報の取得を行う
        $savedata = Savedata::getLoginUserCurrentSavedata();
        $parties = $savedata->parties; // collectionとして取得
        $parties_data_collection = collect(); // パーティについてのスキル情報を格納していく

        foreach ($parties as $party) {
            $party_data_collection = collect([
                'party_id' => $party->id,
                'level' => $party->level,
                'nickname' => $party->nickname,
                'role_id' => $party->role_id,
                'role_class' => $party->role->class,
                'role_class_japanese' => $party->role->class_japanese,
                'freely_skill_point' => $party->freely_skill_point,
                'freely_status_point' => $party->freely_status_point,
                'total_exp' => $party->total_exp,
                'next_level_up_exp' => $party->getNextLevelUpExp(), // 次のレベルアップまでの経験値
                'status' => [
                    'level' => $party->level,
                    'value_hp' => $party->value_hp,
                    'value_ap' => $party->value_ap,
                    'value_str' => $party->value_str,
                    'value_def' => $party->value_def,
                    'value_int' => $party->value_int,
                    'value_spd' => $party->value_spd,
                    'value_luc' => $party->value_luc,
                ],
                'skill_tree' => Skill::aquireSkillTreeCollection($party),
            ]
            );

            $parties_data_collection->push($party_data_collection);
        }

        return response()->json($parties_data_collection);

    }

    public function incrementStatus(Request $request)
    {
        $party_id = $request->party_id;
        $input_point = $request->input_point;
        $status_type = $request->status_type; // 'HP', 'AP', 'STR' など

        $party = Party::find($party_id);

        try {
            DB::transaction(function () use ($party, $input_point, $status_type) {
                // 存在しないスキルが選択された場合、エラーを返す
                if (is_null($party)) {
                    throw new \Exception('パーティメンバーの情報を参照できませんでした。リロードをお試しください。');
                }
                $party->allocateStatusPoint($input_point, $status_type);
            });
        } catch (\Exception $e) {
            Debugbar::debug('incrementStatus でエラーが発生しました。');

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => '習得処理を正常に完了しました。',
        ]);

    }

    public function learnSkill(Request $request)
    {
        $party_id = $request->party_id;
        $skill_id = $request->skill_id;
        Debugbar::debug("learnSkill(): {$party_id} {$skill_id} ------------------------");
        $learned_skill = Skill::find($skill_id);
        $learned_party = Party::find($party_id);

        try {
            DB::transaction(function () use ($learned_skill, $learned_party) {
                // 存在しないスキルが選択された場合、エラーを返す
                if (is_null($learned_skill) || is_null($learned_party)) {
                    throw new \Exception('指定したスキルとパーティメンバーの情報が存在しませんでした。もう一度お試しください。');
                }
                Skill::learnPartySkill($learned_party, $learned_skill);
            });
        } catch (\Exception $e) {
            Debugbar::debug('learnSkill() でエラーが発生しました。');

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => '習得処理を正常に完了しました。',
        ]);
    }

    // ログインユーザーの現在のステータス
    public function loginUserCurrentSavedata()
    {
        $current_save_data = Savedata::getLoginUserCurrentSavedata();

        return $current_save_data;
    }

    // フィールド
    public function fieldList()
    {
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
    public function setEncountElement(Request $request)
    {
        $field_id = $request->field_id;
        $stage_id = $request->stage_id;
        Debugbar::debug("setEncountElement(). field_id: {$field_id}, stage_id: {$stage_id}  ---------------");
        $savedata = Savedata::getLoginUserCurrentSavedata();

        // 戦闘中かどうかの判定
        $is_user_battle = BattleState::where('savedata_id', $savedata->id)->exists();

        // stage_idが1以外なら、URLベタ打ちでなく戦闘後に正しく遷移してきたかを確認する。
        // 現在1-2なら、clearStageに'1-1'が入っていたら通せば良い。1-3なら、'1-2'をクリアしていたら通す
        // ただしstage_id = 2以降の時点で間違えて画面更新をした場合とかはclear_stageがnullになるのでスルーする
        // todo: オムニバス形式にするなら、火山に入ったときに「草原をクリアしたか」とかも設定する必要あり。
        if ($stage_id != 1 && $is_user_battle == null) {
            Debugbar::debug('ステージ2以降の処理。 ---------------');
            $clear_stage = $request->clear_stage;
            Debugbar::debug($clear_stage);
            if ($clear_stage == null) {
                Debugbar::debug("$clear_stage null error");

                return abort(500, 'clearStageがnullです');
            }
            // クリアしたfield_idと現在のfield_idが一致する場合 &&
            // クリアしたstage_idと現在のstage_id - 1の値が一致する場合は処理を継続。
            // そうでない場合は500レスポンスを返す。
            // 1-3 -> 1-4に遷移したならば、 1 == 1 || 3 == (4-1) になるはず
            $explode_clear_stage = explode('-', $clear_stage);
            if ($explode_clear_stage[0] == $field_id && $explode_clear_stage[1] == ($stage_id - 1)) {
                Debugbar::debug("クリア判定しました。現在: {$field_id}-{$stage_id} クリア: {$clear_stage} ---------------");
            } else {
                return abort(500, '予期しない形でステージ遷移が行われました');
            }
        }

        if (! $is_user_battle) {
            Debugbar::debug('戦闘中のデータが存在しないため、新規戦闘として扱います。  ---------------');

            // パーティ3人の情報(ステータス,スキル,アイテム)を格納する
            $players_data = BattleState::createPlayersData($savedata->id, null);

            // ステージの敵情報を読み込む
            $enemies_data = BattleState::createEnemiesData($field_id, $stage_id);

            // アイテムデータを読み込む
            $savedata = Savedata::getLoginUserCurrentSavedata();
            $items_data = BattleState::createItemsData($savedata->id);

            // 戦闘データをセッションIDで一意に管理する
            $battle_state = BattleState::createBattleState(
                $savedata->id, $players_data, $enemies_data, $items_data, $field_id, $stage_id
            );

        } else {
            // 戦闘中のデータを取得する
            Debugbar::debug('戦闘中です。セッションIDから戦闘履歴を取得します。  ---------------');
            $battle_state = BattleState::where('savedata_id', $savedata->id)->first();

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
            $items_data = json_decode($battle_state['items_json_data']);
        }

        // vueに渡すデータ
        // [0]プレイヤー情報 [1]敵情報 [2]セッションID [3]アイテム
        $all_data = collect()
            ->push($players_data)
            ->push($enemies_data)
            ->push($battle_state->session_id)
            ->push($items_data);

        return $all_data;
    }

    // 選択されたデータを元に、コマンド実行
    public function execBattleCommand(Request $request)
    {

        Debugbar::debug('vueからデータを受け取る。---------------');
        $session_id = $request->session_id;
        $battle_state = BattleState::where('session_id', $session_id)->first();
        $battle_logs = collect(); // 結果を格納していく
        $current_players_data = collect(json_decode($battle_state['players_json_data']));
        $current_enemies_data = collect(json_decode($battle_state['enemies_json_data']));
        $current_items_data = collect(json_decode($battle_state['items_json_data']));
        $commands = collect($request->selectedCommands);

        Debugbar::debug('コマンド情報格納-------------');
        // コマンド情報格納 players_json_data['id']と$coomands['partyId']を紐づける。
        $current_players_data->transform(function ($data) use ($commands) {
            $command = $commands->firstWhere('partyId', $data->id);
            if ($command) {
                $data->command = $command['command'];
                $data->target_enemy_index = $command['enemyIndex'] ?? null;
                $data->target_player_index = $command['playerIndex'] ?? null;
                $data->selected_skill_id = $command['skillId'] ?? null;
                $data->selected_item_id = $command['itemId'] ?? null;

                // スキルを選んでいたなら、そのeffect_typeを取得しておく(行動順決定で使う)
                if (! is_null($data->selected_skill_id)) {
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

        Debugbar::debug('速度順に整理-------------');
        $players_and_enemies_data = $current_players_data->concat($current_enemies_data);
        $sorted_players_and_enemies_data = BattleState::sortByBattleExec($players_and_enemies_data);

        // Debugbar::debug(get_class($sorted_players_and_enemies_data), get_class($current_enemies_data), get_class( $current_players_data), get_class($battle_logs));

        Debugbar::debug('戦闘実行！ BattleState::execBattleCommand()----------------');
        BattleState::execBattleCommand(
            $sorted_players_and_enemies_data, $current_players_data, $current_enemies_data, $current_items_data, $battle_logs
        );

        Debugbar::debug('--------------戦闘処理完了(ステータス一覧)----------------');
        Debugbar::debug($current_players_data, $current_enemies_data, $current_items_data, $battle_logs);
        Debugbar::debug('----------------------------------------------------------');

        Debugbar::debug('バフターン数計算処理-------------------------------');
        $players_and_enemies_data = $current_players_data->concat($current_enemies_data);
        BattleState::afterExecCommandCalculateBuff($players_and_enemies_data, $battle_logs);

        // rpg_battle_states更新
        $updated_battle_state = $battle_state->update([
            'players_json_data' => json_encode($current_players_data),
            'items_json_data' => json_encode($current_items_data),
            'enemies_json_data' => json_encode($current_enemies_data),
        ]);

        // vueに渡すデータ
        $all_vue_data = collect()
            ->push($current_players_data)
            ->push($current_enemies_data)
            ->push($battle_logs)
            ->push($current_items_data);

        return $all_vue_data;
    }

    // 戦闘勝利の処理
    // 下記の挙動を実現したいので、battle_statesテーブルのjsonデータのみでレベルアップ処理を完結させる。
    // 全滅: 元のパラメータに戻す
    // 逃げる: そのパラメータをpartiesテーブル保持して戦闘処理を終了させる(パラメータを保存)
    public function resultWinBattle(Request $request)
    {
        Debugbar::debug('resultWinBattle(): ---------------');
        // 戦闘が正常に終了したかのチェック
        // vue側でresultWinBattle()からもらえるis_winか、もしくはjsonの敵情報のis_defeated_flagを全て見るかどっちか。
        // 一旦リクエストでもらえる値を参考にする
        $is_win = $request->is_win;
        if (! $is_win) {
            return abort(500, 'is_win error');
        }

        // 戦闘勝利の処理を繰り返せてしまうとリロードなどで経験値を稼がれる可能性がある
        // そのためトランザクションで挟んだのち、処理後に戦闘キャッシュを消して管理する。
        $session_id = $request->session_id;
        $battle_state = BattleState::where('session_id', $session_id)->first();
        if (! $battle_state) {
            return abort(500, 'not exist battle state');
        }

        // 戦闘終了時の処理開始
        // 獲得した合計経験値・ゴールドの計算
        $result_logs = collect();
        $exp_tables = Exp::get();
        $enemies_json_data = collect(json_decode($battle_state['enemies_json_data']));
        $total_aquire_exp = 0;
        $total_aquire_money = 0;
        foreach ($enemies_json_data as $enemy) {
            $total_aquire_exp += $enemy->exp;
            $total_aquire_money += $enemy->drop_money;
        }

        // 戦闘不能のユーザーを除外し、振り分ける(戦闘不能のキャラクターはEXPが0)
        $cleared_players_data = collect(json_decode($battle_state->players_json_data));
        $cleared_no_defeated_players_data = $cleared_players_data->filter(function ($item) {
            return $item->is_defeated_flag === false;
        });
        Debugbar::debug($cleared_no_defeated_players_data);

        // 一人当たりの経験値(切り上げ)
        $per_exp = (int) ceil($total_aquire_exp / $cleared_no_defeated_players_data->count());
        Debugbar::debug("獲得経験値:{$total_aquire_exp}(一人当たり:{$per_exp}) 獲得ゴールド:{$total_aquire_money} ");
        $result_logs->push("敵を倒した！{$total_aquire_money}Gとそれぞれ経験値{$per_exp}を獲得。");

        // 戦闘後のアイテム状況
        $cleared_items_data = collect(json_decode($battle_state['items_json_data']));
        Debugbar::debug('アイテムデータ');
        Debugbar::debug(gettype($cleared_items_data));

        $savedata = Savedata::getLoginUserCurrentSavedata();
        try {
            DB::transaction(function () use ($savedata, $total_aquire_money, $cleared_players_data, $cleared_items_data, $per_exp, $exp_tables, $battle_state, $result_logs) {
                // 金額処理
                $savedata->increment('money', $total_aquire_money);
                Debugbar::debug("ゴールド加算完了。 現在金額: {$savedata->money}");

                // レベル処理
                Debugbar::debug("ループ対象のパーティメンバーの数: {$cleared_players_data->count()} 人");
                foreach ($cleared_players_data as $index => $party) {
                    Debugbar::debug("ループ処理開始: {$party->name}  ##############################");
                    if ($party->is_defeated_flag === true) {
                        Debugbar::debug("戦闘不能状態なので、経験値振り分けをスキップ。 {$party->name}");

                        continue;
                    }

                    $party->total_exp += $per_exp;
                    $current_party_exp = $party->total_exp;
                    $current_party_level = $party->level;
                    $new_level = null;

                    DebugBar::debug("経験値:{$per_exp}を振り分けます。現在経験値: {$party->total_exp}");

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
                            'status_point' => 0, 'skill_point' => 0,
                        ];
                        for ($i = $current_party_level + 1; $i <= $new_level; $i++) {
                            // ステータス上昇処理
                            $increase_values = Party::calculateGaussianGrowth($party);
                            Debugbar::debug("HPが{$increase_values['growth_hp']}, apが{$increase_values['growth_ap']}, strが{$increase_values['growth_str']}, defが{$increase_values['growth_def']}, intが{$increase_values['growth_int']}, spdが{$increase_values['growth_spd']}, lucが{$increase_values['growth_luc']}アップ。");

                            // ステータス・スキルポイント付与
                            Debugbar::debug('ステータス・スキルポイントの付与開始');
                            $increase_values['growth_status_point'] = 4;
                            $increase_values['growth_skill_point'] = 0;
                            $party->freely_status_point += $increase_values['growth_status_point'];
                            Debugbar::debug('ステータスポイント付与OK');
                            // Lvが３の倍数の時(3,6,9,12,15,18,21,24,27,30), スキルポイント付与
                            if ($i % 3 === 0) {
                                Debugbar::debug('Lvが3の倍数のため、スキルポイントを付与します。');
                                $increase_values['growth_skill_point'] = 1;
                                $party->freely_skill_point += $increase_values['growth_skill_point'];
                            }

                            // レベルが飛び級した場合でも、画面のログにはまとめてステータスを出す
                            $total_growth = [
                                'hp' => $total_growth['hp'] += $increase_values['growth_hp'],
                                'ap' => $total_growth['ap'] += $increase_values['growth_ap'],
                                'str' => $total_growth['str'] += $increase_values['growth_str'],
                                'def' => $total_growth['def'] += $increase_values['growth_def'],
                                'int' => $total_growth['int'] += $increase_values['growth_int'],
                                'spd' => $total_growth['spd'] += $increase_values['growth_spd'],
                                'luc' => $total_growth['luc'] += $increase_values['growth_luc'],
                                'status_point' => $total_growth['status_point'] += $increase_values['growth_status_point'],
                                'skill_point' => $total_growth['skill_point'] += $increase_values['growth_skill_point'],
                            ];

                            Debugbar::debug('total_growth配列 OK');

                        }
                        // レベル反映
                        $party->level = $new_level;
                        $result_logs->push("{$party->name}はレベルが{$current_party_level}から{$new_level}にアップ！  HP +{$total_growth['hp']} AP +{$total_growth['ap']} STR +{$total_growth['str']} DEF +{$total_growth['def']} INT +{$total_growth['int']} SPD +{$total_growth['spd']} LUC +{$total_growth['luc']} ステータスポイント+{$total_growth['status_point']}");

                        if ($increase_values['growth_skill_point'] !== 0) {
                            $result_logs->push('スキルポイントを獲得！');
                        }

                        // レベルが上がった時、減っているHP/APも回復させてあげたい
                        // 選択中のキャラの現在のjsonデータの max_value_hp/ap と value_hp/ap を調整してやれば良い
                        $party->value_hp = $party->max_value_hp;
                        $party->value_ap = $party->max_value_ap;

                        Debugbar::debug("HPとAPを全回復。HP:{$party->value_hp} AP:{$party->value_ap}");
                    }
                }

                Debugbar::debug('処理後');
                Debugbar::debug($cleared_players_data);

                $field_id = $battle_state->current_field_id;
                $next_stage_id = $battle_state->current_stage_id + 1;
                $next_enemies_data = BattleState::createEnemiesData($field_id, $next_stage_id);

                $create_next_battle_state = BattleState::createBattleState(
                    $savedata->id, $cleared_players_data, $next_enemies_data, $cleared_items_data, $field_id, $next_stage_id
                );
                $battle_state->delete();
                Debugbar::debug('現在の戦闘データを削除しました。');

            });
        } catch (\Exception $e) {
            Debugbar::debug('戦闘完了アクションで不具合。');
            \Log::error('resultWinBattle() でエラーが発生しました。', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        Debugbar::debug('ゴールド | 経験値獲得処理完了。');
        Debugbar::debug($result_logs);

        return response()->json($result_logs);

    }

    // 戦闘途中終了をした場合、戦闘データを消す
    public function escapeBattle(Request $request)
    {
        Debugbar::debug('escapeBattle(): ---------------------');
        $session_id = $request->session_id;
        $battle_state = BattleState::where('session_id', $session_id)->first();

        // 現在のセッションIDで見つからなければ、ユーザーIDで検索をかけて削除する
        if (! $battle_state) {
            $savedata = Savedata::getLoginUserCurrentSavedata();
            Debugbar::debug("セッションID {$session_id} から情報を見つけられないため、セーブデータIDで検索をかけ削除します。");
            $battle_state = BattleState::where('savedata_id', $savedata->id)->get();
            foreach ($battle_state as $b) {
                $b->delete();
            }
        } else {
            $battle_state->delete();
        }

        Debugbar::debug('戦闘セッションを削除しました。');
    }
}

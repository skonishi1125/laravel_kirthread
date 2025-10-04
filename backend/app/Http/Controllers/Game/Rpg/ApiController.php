<?php

namespace App\Http\Controllers\Game\Rpg;

use App\Constants\Rpg\BattleData;
use App\Enums\Rpg\AfterCleared;
use App\Enums\Rpg\FieldData;
use App\Http\Controllers\Controller;
use App\Models\Game\Rpg\BattleState;
use App\Models\Game\Rpg\Board;
use App\Models\Game\Rpg\Exp;
use App\Models\Game\Rpg\Field;
use App\Models\Game\Rpg\Item;
use App\Models\Game\Rpg\Job;
use App\Models\Game\Rpg\Library;
use App\Models\Game\Rpg\Party;
use App\Models\Game\Rpg\Role;
use App\Models\Game\Rpg\Savedata;
use App\Models\Game\Rpg\SavedataHasItem;
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
    // 5つ以上クリアすることが条件
    public const PLAZA_ENABLE_CLEAR_FIELD = 5;

    // TODO:
    // * constructなどでログインしているユーザーがアクセスできる前提とする
    // * 肥大化しているので余力があればControllerを分割する

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
                'money' => Savedata::DEFAULT_MONEY,
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
        // 送られるデータ: "roleId" => 4, "roleClassJapanese" => "魔導師", "partyName" => "メイ" というArrayが3つ
        // Debugbar::debug($selected_info, gettype($selected_info));
        $savedata = Savedata::getLoginUserCurrentSavedata();
        Debugbar::debug([
            'savedata' => $savedata,
            'selected_info_count' => count($selected_info),
        ]);

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
                    Debugbar::debug("作成完了。id: {$savedata->id} nickname: {$party['partyName']}");
                    Debugbar::debug($created_party);
                    $created_parties->push($created_party);
                }
            });
        } catch (\Exception $e) {
            Debugbar::debug('createParties() でエラーが発生しました。');
            \Log::error("{$e->getMessage()} {$e->getFile()}:{$e->getLine()}");

            return response()->json([
                'message' => 'キャラクター作成処理でエラーが発生しました。再度お試しいただくか、改善しない場合は管理者に連絡ください。',
            ], 422);
        }

        // 作成したパーティの情報をvueに返す。
        return $created_parties;
    }

    // TODO: POSTのページに直接アクセスしたときエラーログに残るのでリダイレクトされるようにしたい

    /**
     * ショップ画面に必要な情報をjsonで返す。
     *
     *  購入/売却可能なアイテムや、現在の所持数、現在の所持金など。
     */
    public function getItemInfo()
    {
        $savedata = Savedata::getLoginUserCurrentSavedata();
        $current_possession = $savedata->savedata_has_items->sum('possession_number');
        $return_list = [
            'current_possession' => $current_possession,
            'money' => 0,
            'buyItemList' => [],
            'sellItemList' => [],
        ];

        // パーティメンバーのLUC合計値を取得(割引に使う)
        $parties_sum_luc = $savedata->parties->sum('value_luc') + $savedata->parties->sum('allocated_luc');
        $rate = min(Item::DISCOUNT_MAX_LUC_RATE, $parties_sum_luc * Item::PER_POINT_LUC_RATE); // 割引率
        $factor = 1 - $rate; // 実際に価格にかける倍率 $rateが0.2なら、 1 - 0.2で0.8 (80%の値段で売る)

        // --------- 所持金の取得 ---------
        $return_list['money'] = $savedata->money;

        //  --------- 購入アイテムの取得 ---------
        $buyable_items = Item::getShopListItem($savedata);
        $owned_items = $savedata->items()->withPivot('possession_number')->get(); // 所持中のアイテム一覧
        // 所持数を確認し、配列にその値を格納
        $buyable_items->map(function ($item) use ($owned_items, $factor) {
            $owned = $owned_items->firstWhere('id', $item->id);
            $item->possession_number = $owned ? $owned->pivot->possession_number : 0;

            // 価格の割引
            $discounted = (int) floor($item->price * $factor);
            $item->discounted_price = max(1, $discounted);

            return $item;
        });
        $buyable_items = $buyable_items
            ->select('id', 'name', 'price', 'discounted_price', 'description', 'possession_number', 'max_possession_number');
        $return_list['buyItemList'] = $buyable_items;

        //  --------- 売却アイテムの取得 ---------
        $base = 0.5;    // ベースはpriceの半額として、それからLUCに応じて金額を引き上げる
        $bonus = $parties_sum_luc * Item::PER_POINT_LUC_RATE;
        $sellFactor = $base * (1 + $bonus);         // 例: LUC 100 なら 0.5 * 1.02 = 0.51

        // pivotの値を取る時は、mapで加工する
        $sellable_items = $owned_items->map(function ($item) use ($sellFactor) {
            $listPrice = $item->price;
            $raw = $listPrice * $sellFactor;

            // 丸め規則（10G単位で切り捨て）
            $rounded = (int) round($raw);

            // 最低1G保証
            $sellPrice = max(1, $rounded);

            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $sellPrice,
                'description' => $item->description,
                'possession_number' => $item->pivot->possession_number,
                'max_possession_number' => $item->max_possession_number,
            ];
        })
            ->sortBy('id')
            ->values();

        $return_list['sellItemList'] = $sellable_items;

        return $return_list;
    }

    // ショップ アイテム購入処理
    public function paymentItem(Request $request)
    {
        $savedata = Savedata::getLoginUserCurrentSavedata();
        $money = $savedata->money;

        $item_id = $request->item_id;
        $item_price = $request->price;
        $number = (int) $request->number;

        $total_price = $item_price * $number;
        $after_payment_money = $money - $total_price;

        // 決済処理
        // エラー時はthrow new Exceptionだとうまくvue側でcatchできないので、responseで返して受け取る。
        try {
            // 数量が0, マイナスで指定された場合はその旨を返す。
            if ($number < 1) {
                return response()->json(['error' => '数量を1以上指定してください'], 400);
            }
            if ($after_payment_money < 0) {
                return response()->json(['error' => '所持金額が足りません。'], 400);
            }

            // 金額反映処理
            $savedata->update([
                'money' => $after_payment_money,
            ]);

            // アイテム反映処理
            $savedata_has_item = SavedataHasItem::where('savedata_id', $savedata->id)
                ->where('item_id', $item_id)
                ->first();
            if ($savedata_has_item) {
                // 既に持ってる場合、所持数を加算
                $savedata_has_item->increment('possession_number', $number);
            } else {
                // 未所持の場合、新規作成
                SavedataHasItem::create([
                    'savedata_id' => $savedata->id,
                    'item_id' => $item_id,
                    'possession_number' => $number,
                ]);
            }

        } catch (Exception $e) {
            return response()
                ->json(['error' => 'エラーが発生しました。時間を置く、もしくは再ログインをお試しください。'], 500);
        }
    }

    /**
     * アイテム売却処理
     */
    public function sellOffItem(Request $request)
    {
        $savedata = Savedata::getLoginUserCurrentSavedata();
        $money = $savedata->money;

        $item_id = $request->item_id;
        $item_price = $request->price;
        $number = (int) $request->number;

        $total_price = $item_price * $number;
        $after_sell_off_money = $money + $total_price;

        try {
            // 金額反映処理
            $savedata->update([
                'money' => $after_sell_off_money,
            ]);

            // アイテム反映処理
            // 0個になった場合、deleteする
            $savedata_has_item = SavedataHasItem::where('savedata_id', $savedata->id)
                ->where('item_id', $item_id)
                ->first();
            if ($savedata_has_item->possession_number - $number < 1) {
                Debugbar::debug('全て売却されたため、deleteします。');
                $savedata_has_item->delete();
            } else {
                Debugbar::debug('一部の売却のため、decrementします。');
                $savedata_has_item->decrement('possession_number', $number);
            }
        } catch (Exception $e) {
            return response()
                ->json(['error' => 'エラーが発生しました。時間を置く、もしくは再ログインをお試しください。'], 500);
        }

    }

    /**
     * ステータス・スキル画面で確認できるステータス、スキルツリーの取得
     *
     * 初回時、また振り分け後の更新時に叩かれる。
     */
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
                'next_level_up_exp' => $party->fetchNextLevelUpExp(), // 次のレベルアップまでの経験値
                'status' => [
                    'level' => $party->level,
                    'value_hp' => $party->value_hp + $party->allocated_hp,
                    'value_ap' => $party->value_ap + $party->allocated_ap,
                    'value_str' => $party->value_str + $party->allocated_str,
                    'value_def' => $party->value_def + $party->allocated_def,
                    'value_int' => $party->value_int + $party->allocated_int,
                    'value_spd' => $party->value_spd + $party->allocated_spd,
                    'value_luc' => $party->value_luc + $party->allocated_luc,
                ],
                'skill_tree' => Skill::acquireSkillTreeCollection($party),
            ]
            );

            $parties_data_collection->push($party_data_collection);
        }

        return response()->json($parties_data_collection);

    }

    /**
     * Status.vueから渡されたリクエストを参照し、ステータスポイントの割り振りを行う
     */
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
        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        $selectable_fields = Field::acquireCurrentSelectableFieldList($savedata);
        $field_json_data = collect();

        foreach ($selectable_fields as $field) {
            $field_json_data->push([
                'id' => $field->id,
                'name' => $field->name,
                'difficulty' => $field->convertDifficultyStars(),
                'is_cleared' => $field->is_cleared,
            ]);
        }

        return $field_json_data;
    }

    /**
     * 戦闘画面時にはじめに叩かれる関数
     *
     * まずそのフィールドが開放されているかどうか確認。
     * その後、戦闘中か初回戦闘かを判断してjsonとして戦闘に関するデータを返す。
     */
    public function setEncountElement(Request $request)
    {
        $field_id = $request->field_id;
        $stage_id = $request->stage_id;
        Debugbar::debug("setEncountElement(). field_id: {$field_id}, stage_id: {$stage_id}  ---------------");
        $savedata = Savedata::getLoginUserCurrentSavedata();

        // そのフィールドが開放されているかどうかの確認 (URLベタ打ち対策)
        $selectable_fields = Field::acquireCurrentSelectableFieldList($savedata)->pluck('id');
        if (! $selectable_fields->contains($field_id)) {
            // 存在を隠したいなら 404、正直にアクセス不可なら 403
            return response()->json([
                'message' => '予期しない形でステージ遷移が行われました。',
            ], 422);
        }

        $current_turn = 1;

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

            // -------------- jsonのベースとなるデータの取得 --------------
            $players_collection = BattleState::createPlayersCollection($savedata->id);
            $enemies_collection = BattleState::createEnemiesCollection($field_id, $stage_id);
            $items_collection = BattleState::createItemsCollection($savedata->id);
            $enemy_drops_collection = collect(BattleData::ENEMY_DROPS_TEMPLATE);

            // 各データをbattle_statesテーブルに格納
            $battle_state = BattleState::createBattleState(
                $savedata->id, $players_collection, $enemies_collection, $items_collection, $enemy_drops_collection, $field_id, $stage_id
            );

        } else {
            Debugbar::debug('戦闘中です。セーブデータIDから直近の戦闘履歴を取得します。  ---------------');
            $battle_state = BattleState::where('savedata_id', $savedata->id)->first();

            // $battle_stateの情報と現在のfield_id, stage_idが一致しているか確認する
            // 例えば /game/rpg/battle/1/2 に正常進行 -> 1/3にURLベタ打ちすると1-2のstateがある状態で1-3に遷移できる
            // この場合、1-2の敵データをクリアすると1-4に進行することができてしまう
            if ($battle_state->current_field_id == $field_id && $battle_state->current_stage_id == $stage_id) {
                Debugbar::debug("現URLとbattle_stateのデータの整合性が確認できました。現在: {$field_id}-{$stage_id} battle_state: {$battle_state->current_field_id}-{$battle_state->current_stage_id}");
            } else {
                return abort(500, '現URLとbattle_stateのデータの整合性が確認できませんでした。');
            }

            // 初回戦闘時、各データをCollectionとして返しているため、そちらの形に合わせてCollection型に変換しておく
            $players_collection = collect(json_decode($battle_state->players_json_data));
            $enemies_collection = collect(json_decode($battle_state->enemies_json_data));
            $items_collection = collect(json_decode($battle_state->items_json_data));

            // current_turnを戦闘履歴から取得して格納
            $current_turn = $battle_state->current_turn;

        }

        // 戦闘フィールドのbackground_image_pathを取得
        $background_field_path = Field::find($field_id)->background_image_path;

        // vueに渡すデータ
        // [0]プレイヤー情報 [1]敵情報 [2]セッションID [3]アイテム [4]ターン数 [5]フィールドの背景
        $all_data = collect()
            ->push($players_collection)
            ->push($enemies_collection)
            ->push($battle_state->session_id)
            ->push($items_collection)
            ->push($current_turn)
            ->push($background_field_path);

        return $all_data;
    }

    /**
     * 選択されたデータを元に、コマンドを実行。戦闘部分のメイン処理。
     */
    public function execBattleCommand(Request $request)
    {
        Debugbar::debug('execBattleCommand(): ---------------');
        $session_id = $request->session_id;
        $battle_state = BattleState::where('session_id', $session_id)->first();
        $battle_logs_collection = collect(); // 結果を格納していく
        $battle_state_players_collection = collect(json_decode($battle_state['players_json_data']));
        $battle_state_enemies_collection = collect(json_decode($battle_state['enemies_json_data']));
        $battle_state_items_collection = collect(json_decode($battle_state['items_json_data']));
        $current_turn = $battle_state['current_turn'];

        // ["partyId" => 1, "command" => "ATTACK","enemyIndex" => 0],[...]といった、パーティ人数分のコマンド配列
        $selected_commands_collection = collect($request->selectedCommands);

        // コマンド情報格納
        // $battle_state_players_collection['id']と$selected_commands_collection['partyId']を紐づける。
        // transform()で使用する $data はstdClass型のため、-> でアクセスする
        $battle_state_players_collection->transform(function ($data) use ($selected_commands_collection) {
            $command = $selected_commands_collection->firstWhere('partyId', $data->id);
            if ($command) {
                $data->command = $command['command'];
                $data->target_enemy_index = $command['enemyIndex'] ?? null;
                $data->target_player_index = $command['playerIndex'] ?? null;
                $data->selected_skill_id = $command['skillId'] ?? null;
                $data->selected_item_id = $command['itemId'] ?? null;

                // スキルを選んでいたなら、そのeffect_typeを取得しておく(行動順決定で使う)
                if (! is_null($data->selected_skill_id)) {
                    // Debugbar::debug("{$data->name}はスキルid: {$data->selected_skill_id}を選択。 ");
                    /** @var bool $selected_skill_is_first */
                    $selected_skill_is_first =
                        // firstWhereを使うため、Collectionに変換している
                        collect($data->skills)->firstWhere('id', $data->selected_skill_id)->is_first;
                    $data->selected_skill_is_first = $selected_skill_is_first;

                    /** @var bool $selected_skill_is_slow */
                    $selected_skill_is_slow =
                        // firstWhereを使うため、Collectionに変換している
                        collect($data->skills)->firstWhere('id', $data->selected_skill_id)->is_slow;
                    $data->selected_skill_is_slow = $selected_skill_is_slow;
                } else {
                    $data->selected_skill_is_first = null;
                    $data->selected_skill_is_slow = null;
                }
            }

            return $data;
        });

        // enemy is_first, is_slow用の処理
        foreach ($battle_state_enemies_collection as $enemy_data) {
            // 敵コマンドを決定しておく
            BattleState::determineEnemyCommand($enemy_data, $current_turn);
            if (! is_null($enemy_data->selected_skill_id)) {
                // Debugbar::debug("{$data->name}はスキルid: {$data->selected_skill_id}を選択。 ");
                /** @var bool $selected_skill_is_first */
                $selected_skill_is_first = collect($enemy_data->skills)->firstWhere('id', $enemy_data->selected_skill_id)->is_first; // firstWhereを使うため、Collectionに変換している
                $enemy_data->selected_skill_is_first = $selected_skill_is_first;

                /** @var bool $selected_skill_is_slow */
                $selected_skill_is_slow = collect($enemy_data->skills)->firstWhere('id', $enemy_data->selected_skill_id)->is_slow; // firstWhereを使うため、Collectionに変換している
                $enemy_data->selected_skill_is_slow = $selected_skill_is_slow;
            } else {
                $enemy_data->selected_skill_is_first = null;
                $enemy_data->selected_skill_is_slow = null;
            }
        }

        Debugbar::debug('行動順決定処理 BattleState::sortByBattleExec() -------------');
        $battle_state_players_and_enemies_collection =
            $battle_state_players_collection->concat($battle_state_enemies_collection);
        $sorted_battle_state_players_and_enemies_collection =
            BattleState::sortByBattleExec($battle_state_players_and_enemies_collection);

        Debugbar::debug('戦闘実行！ BattleState::execBattleCommand()----------------');
        BattleState::execBattleCommand(
            $sorted_battle_state_players_and_enemies_collection, $battle_state_players_collection, $battle_state_enemies_collection, $battle_state_items_collection, $battle_logs_collection,
            $current_turn
        );

        Debugbar::debug('--------------戦闘処理完了(ステータス一覧)----------------');
        Debugbar::debug([
            'battle_state_players_collection' => $battle_state_players_collection,
            'battle_state_enemies_collection' => $battle_state_enemies_collection,
            'battle_state_items_collection' => $battle_state_items_collection,
            'battle_logs_collection' => $battle_logs_collection,
        ]);
        Debugbar::debug('----------------------------------------------------------');

        Debugbar::debug('バフターン数計算処理 BattleState::afterExecCommandCalculateBuff ------------------------');
        // $battle_state_players_and_enemies_collection = $battle_state_players_collection->concat($battle_state_enemies_collection);
        BattleState::afterExecCommandCalculateBuff($battle_state_players_and_enemies_collection, $battle_logs_collection);

        // ターン数更新
        $next_turn = $battle_state->current_turn + 1;

        // 選択したコマンド、スキルなどをデフォルト状態に
        // (これをしないと、蘇生後に最後に使った行動が実行されてしまう)
        // TODO: 敵も必要な場合(蘇生とかで)、この値が必要かチェックする
        Debugbar::debug('コマンドデフォルト設定対応 ------------------------');
        foreach ($battle_state_players_collection as $player_data) {
            $player_data->selected_skill_id = null;
            $player_data->command = '';
        }
        foreach ($battle_state_enemies_collection as $enemy_data) {
            $enemy_data->selected_skill_id = null;
            $enemy_data->command = '';
        }

        // rpg_battle_states更新
        $updated_battle_state = $battle_state->update([
            'players_json_data' => json_encode($battle_state_players_collection),
            'items_json_data' => json_encode($battle_state_items_collection),
            'enemies_json_data' => json_encode($battle_state_enemies_collection),
            'current_turn' => $next_turn,
        ]);

        // vueに渡すデータ
        $all_vue_data = collect()
            ->push($battle_state_players_collection)
            ->push($battle_state_enemies_collection)
            ->push($battle_logs_collection)
            ->push($battle_state_items_collection)
            ->push($next_turn);

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
        $battle_logs_collection = collect();
        $exp_tables = Exp::get();
        $battle_state_enemies_collection = collect(json_decode($battle_state['enemies_json_data']));
        $total_acquire_exp = 0;
        $total_acquire_money = 0;

        // money計算処理
        $battle_state_enemy_drops_collection = collect(json_decode($battle_state['enemy_drops_json_data']));
        foreach ($battle_state_enemies_collection as $enemy) {
            $total_acquire_exp += $enemy->exp;
            $total_acquire_money += $enemy->drop_money;
        }
        $battle_state_enemy_drops_collection['money'] += $total_acquire_money;
        Debugbar::debug("money加算完了。合計金額: {$battle_state_enemy_drops_collection['money']}");

        // 戦闘不能のユーザーを除外し、振り分ける(戦闘不能のキャラクターはEXPが0)
        $cleared_players_collection = collect(json_decode($battle_state->players_json_data));
        $cleared_no_defeated_players_collection = $cleared_players_collection->filter(function ($item) {
            return $item->is_defeated_flag === false;
        });
        Debugbar::debug($cleared_no_defeated_players_collection);

        // 一人当たりの経験値(切り上げ)
        $per_exp = (int) ceil($total_acquire_exp / $cleared_no_defeated_players_collection->count());
        Debugbar::debug("獲得EXP:{$total_acquire_exp}(一人当たり:{$per_exp}) 獲得ゴールド:{$total_acquire_money} ");
        $battle_logs_collection->push("敵を倒した！{$total_acquire_money}Gとそれぞれ{$per_exp}ポイントのEXPを獲得。");

        // 戦闘後のアイテム状況
        $cleared_items_collection = collect(json_decode($battle_state['items_json_data']));

        $savedata = Savedata::getLoginUserCurrentSavedata();
        try {
            DB::transaction(function () use (
                $savedata,
                $cleared_players_collection,
                $cleared_items_collection,
                $battle_state_enemy_drops_collection,
                $per_exp,
                $exp_tables,
                $battle_state,
                $battle_logs_collection
            ) {
                $increase_values = [];

                // レベル処理
                Debugbar::debug("ループ対象のパーティメンバーの数: {$cleared_players_collection->count()} 人");
                foreach ($cleared_players_collection as $player_data) {
                    Debugbar::debug("ループ処理開始: {$player_data->name}  ##############################");
                    if ($player_data->is_defeated_flag === true) {
                        Debugbar::debug("戦闘不能状態なので、経験値振り分けをスキップ。 {$player_data->name}");

                        continue;
                    }

                    $player_data->total_exp += $per_exp;
                    $current_party_exp = $player_data->total_exp;
                    $current_party_level = $player_data->level;
                    $new_level = null;

                    DebugBar::debug("経験値:{$per_exp}を振り分けます。現在経験値: {$player_data->total_exp}");

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
                    if ($new_level && $new_level > $player_data->level) {
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
                            $increase_values = Party::calculateGaussianGrowth($player_data);
                            Debugbar::debug("HPが{$increase_values['growth_hp']}, apが{$increase_values['growth_ap']}, strが{$increase_values['growth_str']}, defが{$increase_values['growth_def']}, intが{$increase_values['growth_int']}, spdが{$increase_values['growth_spd']}, lucが{$increase_values['growth_luc']}アップ。");

                            // ステータス・スキルポイント付与
                            Debugbar::debug('ステータス・スキルポイントの付与開始');
                            $increase_values['growth_status_point'] = 4;
                            $increase_values['growth_skill_point'] = 0;
                            $player_data->freely_status_point += $increase_values['growth_status_point'];
                            Debugbar::debug('ステータスポイント付与OK');
                            // Lvが2の倍数の時, スキルポイント付与
                            if ($i % 2 === 0) {
                                Debugbar::debug('Lvが2の倍数のため、スキルポイントを付与します。');
                                $increase_values['growth_skill_point'] = 1;
                                $player_data->freely_skill_point += $increase_values['growth_skill_point'];
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
                        $player_data->level = $new_level;
                        $battle_logs_collection->push("{$player_data->name}はレベルが{$current_party_level}から{$new_level}にアップ！  HP +{$total_growth['hp']} AP +{$total_growth['ap']} STR +{$total_growth['str']} DEF +{$total_growth['def']} INT +{$total_growth['int']} SPD +{$total_growth['spd']} LUC +{$total_growth['luc']} ステータスポイント+{$total_growth['status_point']}");

                        if ($increase_values['growth_skill_point'] !== 0) {
                            $battle_logs_collection->push("{$player_data->name}はスキルポイントを獲得！");
                        }

                        // レベルが上がった時、減っているHP/APも回復させてあげたい
                        // 選択中のキャラの現在のjsonデータの max_value_hp/ap と value_hp/ap を調整してやれば良い
                        $player_data->value_hp = $player_data->max_value_hp;
                        $player_data->value_ap = $player_data->max_value_ap;

                        Debugbar::debug("HPとAPを全回復。HP:{$player_data->value_hp} AP:{$player_data->value_ap}");
                    }
                }

                $field_id = $battle_state->current_field_id;
                $next_stage_id = $battle_state->current_stage_id + 1;
                $next_enemies_data = BattleState::createEnemiesCollection($field_id, $next_stage_id);

                // レベル処理終了後、各種ステータス調整処理
                Debugbar::debug('レベル処理完了。続いて、ステータス調整処理(戦闘後体力回復・戦闘不能解除・バフ解除など)');
                foreach ($cleared_players_collection as $player_data) {
                    $buffed_hp = $player_data->value_hp;
                    $buffed_ap = $player_data->value_ap;
                    if ($player_data->is_defeated_flag) {
                        Debugbar::debug('戦闘不能のため、最大HPの10%, 最大APの5%で回復させます。');
                        $player_data->is_defeated_flag = false;
                        $buffed_hp += (int) ceil($player_data->max_value_hp * AfterCleared::ResurrectionHp->Multiplier());
                        $buffed_ap += (int) ceil($player_data->max_value_ap * AfterCleared::ResurrectionAp->Multiplier());
                    } else {
                        Debugbar::debug('最大HPの20%, 最大APの10%回復。');
                        $buffed_hp += (int) ceil($player_data->max_value_hp * AfterCleared::RecoveryHp->Multiplier());
                        $buffed_ap += (int) ceil($player_data->max_value_ap * AfterCleared::RecoveryAp->Multiplier());
                    }
                    // 回復によって最大体力を超えた場合は最大体力にする
                    if ($buffed_hp > $player_data->max_value_hp) {
                        $buffed_hp = $player_data->max_value_hp;
                    }
                    if ($buffed_ap > $player_data->max_value_ap) {
                        $buffed_ap = $player_data->max_value_ap;
                    }

                    $player_data->value_hp = $buffed_hp;
                    $player_data->value_ap = $buffed_ap;
                    $player_data->buffs = [];
                }

                // TODO: ボス討伐時は作らなくて良い
                $create_next_battle_state = BattleState::createBattleState(
                    $savedata->id, $cleared_players_collection, $next_enemies_data, $cleared_items_collection, $battle_state_enemy_drops_collection, $field_id, $next_stage_id
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
        Debugbar::debug($battle_logs_collection);

        // ボス討伐処理。
        // 敵の中にボスが1人でもいた場合、現在のステージをクリアとする。
        $is_beat_boss = $battle_state_enemies_collection->contains(function ($enemy) {
            return $enemy->is_boss === true;
        });
        if ($is_beat_boss) {
            $battle_logs_collection->push('ボスを討伐し、この周辺の地形の探索を終えた！');
            // 重複チェックし、存在しなければクリアしたフィールドを保存。
            if (! $savedata->savedata_cleared_fields()->where('field_id', $battle_state->current_field_id)->exists()) {
                $savedata->savedata_cleared_fields()->create([
                    'field_id' => $battle_state->current_field_id,
                ]);
            }
            Debugbar::debug("ボス討伐処理完了。savedata_id: {$savedata->id} field_id: {$battle_state->current_field_id}");
        } else {
            // 次の経験値を表示させる処理
            Debugbar::debug('経験値表示処理');
            $exp_table = Exp::all()->keyBy('level'); // level => Exp 行

            $parts = $cleared_players_collection->map(function ($player) use ($exp_table) {
                $next = $exp_table->get($player->level + 1);
                $next_exp = $next ? ($next->total_exp - $player->total_exp) : '-';

                return "{$player->name}の次のLvまで: {$next_exp} pt";
            });
            $exp_text = '【'.$parts->implode(' | ').'】';
            $battle_logs_collection->push($exp_text);
        }

        // vueに渡すデータ
        $vue_data = collect()
            ->push($battle_logs_collection)
            ->push($is_beat_boss);

        return $vue_data;

    }

    /**
     * 戦闘逃走時、エラーメッセージからの遷移時、フィールド自体のクリア時の処理
     *
     * 戦闘で変化したステータスやアイテム等のデータをデータベースに格納し反映させる
     */
    public function finishBattle(Request $request)
    {
        Debugbar::debug('finishBattle(): ---------------------');
        $savedata = Savedata::getLoginUserCurrentSavedata();
        $session_id = $request->session_id;

        try {
            $battle_state = BattleState::where('session_id', $session_id)->first();

            // 現在のセッションIDで見つからなければ、ユーザーIDで検索をかけて処理
            if (is_null($battle_state)) {
                Debugbar::debug("セッションID {$session_id} から情報を見つけられないため、セーブデータIDで検索をかけ削除します。");
                $battle_state = BattleState::where('savedata_id', $savedata->id)->first();
            }
            // このケースでbattle_stateが存在しなかった場合は、例外として処理（有り得ないが、書いておく）
            if (is_null($battle_state)) {
                throw new \RuntimeException('Failed to find battle state.');
            }
            Debugbar::debug('---------- ステータス反映 ----------');
            $current_battle_state_players_collection = collect(json_decode($battle_state['players_json_data']));
            foreach ($current_battle_state_players_collection as $player_data) {
                $updated_party = Party::find($player_data->id);
                if (is_null($updated_party)) {
                    throw new \RuntimeException('Failed to find party for player_data->id: '.$player_data->id);
                }
                $updated_party->level = $player_data->level;
                // valueの値 = valueとallocatedが既に足されているjsonの値 - allocatedで割り振った値
                // 例えば、HPのjsonの値が value 100 allocated 20 で、合計120だったとする
                // その場合、120の値をそのまま反映してはいけないので、 120 - 20 = 100として反映
                // レベルアップで+5された場合は、jsonの値が125になるので、125 - 20 = 105 という上昇分を反映できる
                $updated_party->value_hp = $player_data->max_value_hp - $updated_party->allocated_hp;
                $updated_party->value_ap = $player_data->max_value_ap - $updated_party->allocated_ap;
                $updated_party->value_str = $player_data->value_str - $updated_party->allocated_str;
                $updated_party->value_def = $player_data->value_def - $updated_party->allocated_def;
                $updated_party->value_int = $player_data->value_int - $updated_party->allocated_int;
                $updated_party->value_spd = $player_data->value_spd - $updated_party->allocated_spd;
                $updated_party->value_luc = $player_data->value_luc - $updated_party->allocated_luc;

                $updated_party->total_exp = $player_data->total_exp;
                $updated_party->freely_skill_point = $player_data->freely_skill_point;
                $updated_party->freely_status_point = $player_data->freely_status_point;

                $updated_party->save();

                Debugbar::debug($updated_party);
            }

            Debugbar::debug('---------- アイテム反映 ----------');
            $battle_state_items_collection = collect(json_decode($battle_state['items_json_data']));
            $battle_item_ids = $battle_state_items_collection->pluck('id')->all();
            $savedata_has_items = SavedataHasItem::where('savedata_id', $savedata->id)->get();

            // foreachが重複しているので、できればjson側に使い終わったアイテムのIDを持たせたりして、一度のループで調整したい
            // 1. JSONに含まれているアイテム → 数量を更新
            foreach ($battle_state_items_collection as $item_data) {
                $item = Item::find($item_data->id);
                $item->savedata_has_item()->update([
                    'possession_number' => $item_data->possession_number,
                ]);
            }
            // 2. JSONに含まれていないアイテム → 所持数が0と判断して削除
            foreach ($savedata_has_items as $savedata_has_item) {
                if (! in_array($savedata_has_item->item_id, $battle_item_ids)) {
                    Debugbar::debug("{$savedata_has_item->item->name}が使い切られたため、削除します。");
                    $savedata_has_item->delete();
                }
            }

            Debugbar::debug('---------- ゴールドやドロップ品(現状は未実装)の反映 ----------');
            $battle_state_enemy_drops_collection = collect(json_decode($battle_state['enemy_drops_json_data']));
            $savedata->increment('money', $battle_state_enemy_drops_collection['money']);
            Debugbar::debug("moneyをセーブデータに加算完了。 金額: {$savedata->money}");
            // TODO: アイテム
            // TODO: ドロップ品

        } catch (\RuntimeException $e) {
            \Log::error("{$e->getMessage()} {$e->getFile()}:{$e->getLine()}");

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

        // 終わった後は、戦闘セッションを削除。
        $battle_state->delete();

        Debugbar::debug('---------- 戦闘終了処理 完了 ----------');
    }

    /**
     * 戦闘に敗北した場合、または、戦闘不具合時のエラーボタンからの処理
     *
     * battle_stateの情報を保存せずに削除する。
     */
    public function refreshBattleState(Request $request)
    {
        Debugbar::debug('refreshBattleState(): ---------------------');
        $session_id = $request->session_id;
        $battle_state = BattleState::where('session_id', $session_id)->first();

        // 現在のセッションIDで見つからなければ、ユーザーIDで検索をかけて削除する
        // エラー画面からの遷移時は、基本的にこの処理になる
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

        Debugbar::debug('敗北または戦闘エラー処理対応。戦闘セッションをデータとして保存せず削除しました。');
    }

    /**
     * メニュー画面
     *
     * 古城クリア済み(財宝ボタンの表示）かどうかの判定値を返す
     */
    public function canBeClear()
    {
        Debugbar::debug('canBeClear(): ---------------------');

        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        $is_cleared = $savedata->savedata_cleared_fields()
            ->where('field_id', FieldData::AncientCastle->value)
            ->exists();
        $is_cleared_vast_expanse = $savedata->savedata_cleared_fields()
            ->where('field_id', FieldData::VastExpanse->value)
            ->exists();

        return response()->json([
            'is_cleared' => $is_cleared,
            'is_cleared_vast_expanse' => $is_cleared_vast_expanse,
        ], 200);
    }

    /**
     * エンディング時、初めに呼ばれる関数
     *
     * 隠し面をクリアしているかどうかを判定してjsonで返す。
     */
    public function canBeClearVastExpanse()
    {
        Debugbar::debug('canBeClearVastExpanse(): ---------------------');

        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        // URLベタ打ち対策
        $check_is_cleared_ancient_castle = $savedata->savedata_cleared_fields()
            ->where('field_id', FieldData::AncientCastle->value)
            ->exists();
        if ($check_is_cleared_ancient_castle === false) {
            return response()->json([
                'message' => '未クリアのデータが参照されています。',
            ], 409);
        }

        $is_cleared_vast_expanse = $savedata->savedata_cleared_fields()
            ->where('field_id', FieldData::VastExpanse->value)
            ->exists();

        return response()->json([
            'is_cleared_vast_expanse' => $is_cleared_vast_expanse,
        ], 200);
    }

    /**
     * セーブデータにクリアしたことを格納する
     */
    public function storeEndingClear()
    {
        Debugbar::debug('storeEndingClear(): ---------------------');

        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        $savedata->is_game_cleared = true;
        $savedata->save();

        return response()->json([
            'is_game_cleared' => true,
        ], 200);

    }

    /**
     * 中心広場 アクセス時のチェック処理
     *
     * 癒しの館など、クリアステージによって解放される施設のフラグを返す
     */
    public function checkPlazaStatus()
    {
        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        $is_enabled = false;
        $cleared_count = $savedata->savedata_cleared_fields()->count();
        if ($cleared_count >= self::PLAZA_ENABLE_CLEAR_FIELD) {
            $is_enabled = true;
        }

        $vue_data = collect()->push($is_enabled);

        return $vue_data;

    }

    /**
     * 図書館で表示する本の情報を取得 進行度に応じて増減する。
     */
    public function fetchLibraryBook()
    {
        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        $readable_adventure_libraries = Library::fetchReadableLibraryList($savedata, Library::CATEGORY_ADVENTURE);
        $readable_job_libraries = Library::fetchReadableLibraryList($savedata, Library::CATEGORY_JOB);
        $readable_enemy_libraries = Library::fetchReadableLibraryList($savedata, Library::CATEGORY_ENEMY);
        $readable_history_libraries = Library::fetchReadableLibraryList($savedata, Library::CATEGORY_HISTORY);

        // vueに渡すデータ
        // [0]冒険指南 [1]職能編纂 [2]魔物図譜 [3]歴史神話学
        $all_data = collect()
            ->push($readable_adventure_libraries)
            ->push($readable_job_libraries)
            ->push($readable_enemy_libraries)
            ->push($readable_history_libraries);

        return $all_data;
    }

    /**
     * 本を読み終えた時、既読テーブルに値を格納する。
     */
    public function markFinishedBook(Request $request)
    {
        Debugbar::debug('markFinishedBook(): ---------------------');
        $book_id = $request->book_id;
        Debugbar::debug($book_id);

        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        // 重複チェックし、存在しなければ読んだ本を保存。
        if (! $savedata->savedata_read_libraries()->where('library_id', $book_id)->exists()) {
            $savedata->savedata_read_libraries()->firstOrCreate([
                'library_id' => $book_id,
            ]);
        }

        return response()->noContent(); // 204

    }

    /**
     * 冒険者掲示板で表示する投稿の情報を取得する。
     */
    public function fetchBbsPost()
    {
        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        $posts = Board::fetchPostWithBanPolicy($savedata, Board::BBS_POST_NUM);
        $formatted_posts = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'savedata_id' => $post->savedata_id,
                'message' => $post->message,
                'is_spoiled' => $post->is_spoiled,
                'is_banned' => $post->is_banned,
                'created_at' => optional($post->created_at)->format('Y-m-d H:i:s'), // 2025-07-07T12:23:02.000000Z という表記になるのを防ぐ
            ];
        });

        $all_data = collect()
            ->push($savedata->id)
            ->push($formatted_posts);

        return $all_data;
    }

    /**
     * 掲示板新規投稿格納処理
     *
     * 投稿は1日1回まで。AM 7:00 リセット。
     */
    public function storeBbsPost(Request $request)
    {
        $board_content = [
            'savedata_id' => $request->get('savedata_id'),
            'message' => $request->get('message'),
            'is_spoiled' => $request->get('is_spoiled'),
        ];

        // 1日1回の書き込みチェック
        $is_already_writtened = Board::checkIsAlreadyWrittenDay($board_content['savedata_id']);
        if ($is_already_writtened === true) {
            return response()->json([
                'errorMessage' => '冒険者掲示板への書き込みは1日1回までです。毎朝7時にリセットされます。',
            ], 429);
        }

        // Vue側でもチェックしているが、改めてバリデーションする
        if (mb_strlen($board_content['message']) > 20) {
            return response()->json([
                'errorMessage' => '投稿が20文字以上です。',
            ], 500);
        }

        // こちらで失敗した場合でもaxiosでは500エラーが発生する
        $created_board = Board::create($board_content);

        return response()->json([
            'successMessage' => '投稿が完了しました！',
        ], 200);
    }

    public function deleteBbsPost(Request $request)
    {
        $board_id = $request->get('id');

        if (is_null($board_id)) {
            return response()->json([
                'errorMessage' => '削除対象のidが渡されていません。',
            ], 500);
        }

        $deleted_board = Board::find($board_id)->delete();

        return response()->json([
            'successMessage' => "書き込み:[{$board_id}]の削除処理が正常に完了しました。",
        ], 200);
    }

    public function fetchJobStatus()
    {
        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        // ログイン中ユーザーのJobに関するデータをまとめる
        $job = $savedata->job;
        $vue_data = collect(
            [
                'grade' => $job->grade,
                'grade_label' => Job::GRADE_LABELS[$job->grade],
                'payment_rate' => Job::PAYMENT_RATES[$job->grade],
            ]
        );

        return $vue_data;
    }

    // Job クリック数に応じた決済と、ユーザーランキングを返す
    public function calculateJobResult(Request $request)
    {
        $push_count = $request->get('push_count');
        $earned_money = $request->get('earned_money');

        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        // 金額加算処理
        $savedata->update([
            'money' => $savedata->money + $earned_money,
        ]);

        // トータルクリック回数、及びgradeのアップグレード
        $job = $savedata->job;
        $total_count = $job->total_count + $push_count;
        $new_grade = Job::calculateGradeByCount($total_count);
        $job->update(
            [
                'grade' => $new_grade,
                'total_count' => $job->total_count + $push_count,
            ]
        );

        // TOP10のランキング取得
        $job_ranking = Job::orderBy('total_count', 'DESC')->limit(10)->get();

        $vue_data = collect()
            ->push($job)
            ->push($job_ranking);

        return $vue_data;
    }

    /**
     * 癒しの館で使用する情報の取得
     */
    public function fetchRefreshPartiesInfo()
    {
        // Savedataからパーティを取得し、パーティに合ったスキルツリー情報の取得を行う
        $savedata = Savedata::getLoginUserCurrentSavedata();
        if (is_null($savedata)) {
            return response()->json([
                'message' => 'セーブデータが存在しません。再度ログインをお試しください。',
            ], 409);
        }

        // URLをベタ打ちして遷移していないか。
        $cleared_count = $savedata->savedata_cleared_fields()->count();
        if ($cleared_count < self::PLAZA_ENABLE_CLEAR_FIELD) {
            return response()->json([
                'message' => '癒しの館を使用する条件を満たしていません。',
            ], 403); // 403: Forbidden などでOK
        }

        $money = $savedata->money;
        $parties = $savedata->parties; // collectionとして取得
        $parties_data_collection = collect(); // パーティについてのスキル情報を格納していく

        foreach ($parties as $party) {
            $party_data_collection = collect([
                'party_id' => $party->id,
                'level' => $party->level,
                'nickname' => $party->nickname,
                'status' => [
                    'value_hp' => $party->value_hp,
                    'allocated_hp' => $party->allocated_hp,
                    'value_ap' => $party->value_ap,
                    'allocated_ap' => $party->allocated_ap,
                    'value_str' => $party->value_str,
                    'allocated_str' => $party->allocated_str,
                    'value_def' => $party->value_def,
                    'allocated_def' => $party->allocated_def,
                    'value_int' => $party->value_int,
                    'allocated_int' => $party->allocated_int,
                    'value_spd' => $party->value_spd,
                    'allocated_spd' => $party->allocated_spd,
                    'value_luc' => $party->value_luc,
                    'allocated_luc' => $party->allocated_luc,
                ],
                'skills' => Skill::generateSkillCollection($party),
            ]);

            $parties_data_collection->push($party_data_collection);
        }

        $vue_data = collect()
            ->push($parties_data_collection)
            ->push($money);

        return response()->json($vue_data);
    }

    /**
     * ステータス・スキルポイントの振り分けをリセットする
     */
    public function resetStatusAndSkillPoint(Request $request)
    {
        $savedata = Savedata::getLoginUserCurrentSavedata();
        $payment_money = $request->payment_money;
        $party_id = $request->party_id;
        Debugbar::debug("resetStatusAndSkillPoint(): {$party_id} ------------------------");
        $party = Party::find($party_id);

        try {
            DB::transaction(function () use ($party, $savedata, $payment_money) {
                if (is_null($party)) {
                    throw new \Exception('パーティメンバーの情報を参照できませんでした。リロードをお試しください。');
                }
                $party->resetStautsAndSkillPoint();
                $savedata->money = $savedata->money - $payment_money;
                $savedata->save();
            });
        } catch (\Exception $e) {
            Debugbar::debug('resetStatusAndSkillPoint でエラーが発生しました。');

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'パーティメンバーのステータス・スキルポイントをリセットしました。',
        ]);
    }
}

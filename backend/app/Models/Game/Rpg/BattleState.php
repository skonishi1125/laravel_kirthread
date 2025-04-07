<?php

namespace App\Models\Game\Rpg;

use App\Constants\Rpg\BattleData;
use App\Enums\Rpg\AttackType;
use App\Enums\Rpg\EffectType;
use App\Enums\Rpg\HealType;
use App\Enums\Rpg\TargetRange;
use App\Helpers\DataHelper;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * コレクションなどから値を呼ぶ時、"->"と"['...']"のどちらでも呼ぶことができるが、
 * 戦闘中は値の呼び出しが多数発生する。
 * stdClassの参照エラー、及びarrayの参照エラーが開発中に頻発したため、
 * 極力コレクションで値を呼べる時であっても、"['...']"で呼ぶようにする。
 * また、returnされる想定の値も極力配列の形で返すようにする。
 */
class BattleState extends Model
{
    use HasFactory;

    protected $table = 'rpg_battle_states';

    protected $guarded = [
        'id',
    ];

    public function savedata()
    {
        return $this->belongsTo(Savedata::class);
    }

    private const BASE_ESCAPE_CHANCE = 0.1; // 逃走の基礎成功率 （SPD 1ごとに、2%ずつ変化していく）

    /**
     * 戦闘初回時のplayers_json_dataに格納する値を作成して返す。
     *
     * @return Collection
     */
    public static function createPlayersCollection(int $savedata_id)
    {
        Debugbar::debug('createPlayersCollection(): jsonのベースとなるplayers_data 作成。----------');
        $parties = Party::where('savedata_id', $savedata_id)->get();

        // id,name,curent_hp,current_apを配列ベースで格納するCollection
        // ステージクリア後に、HPとAPを引き継ぐために使用する
        // 0 => ["id" => 72,"name" => "パラ", "current_hp" => 50,"current_ap" => 15],  1 => [...], 2 => [...]
        $players_hp_and_ap_status = collect();

        Debugbar::debug('新規作成. (player_dataはCollection想定)');
        foreach ($parties as $player_index => $party) {
            $status = [
                'id' => $party['id'],
                'name' => $party['nickname'],
                'current_hp' => $party['value_hp'],
                'current_ap' => $party['value_ap'],
            ];
            $players_hp_and_ap_status->push($status);
        }

        $players_collection = collect();
        Debugbar::debug('players_json_data登録開始。');
        foreach ($parties as $player_index => $party) {
            Debugbar::debug("################# {$player_index} 人目 #################");
            // 会得スキルの取得 (Collection想定)
            $learned_skills = Skill::getLearnedSkill($party);
            $role_portrait = Role::where('id', $party['role_id'])->value('portrait_image_path');

            // vue側に渡すデータ
            $player_data = BattleData::PARTY_TEMPLATE;

            $player_data['id'] = $party['id'];
            $player_data['role_id'] = $party['role_id'];
            $player_data['name'] = $party['nickname']; // nicknameにすると敵との表記揺れが面倒。 (foreachで行動を回してる部分とかで。)
            $player_data['max_value_hp'] = $party['value_hp']; // HP最大値
            $player_data['max_value_ap'] = $party['value_ap']; // AP最大値
            $player_data['value_hp'] = $players_hp_and_ap_status[$player_index]['current_hp'];
            $player_data['value_ap'] = $players_hp_and_ap_status[$player_index]['current_ap'];
            $player_data['value_str'] = $party['value_str'];
            $player_data['value_def'] = $party['value_def'];
            $player_data['value_int'] = $party['value_int'];
            $player_data['value_spd'] = $party['value_spd'];
            $player_data['value_luc'] = $party['value_luc'];
            $player_data['level'] = $party['level'];
            $player_data['total_exp'] = $party['total_exp'];
            $player_data['freely_status_point'] = $party['freely_status_point'];
            $player_data['freely_skill_point'] = $party['freely_skill_point'];
            $player_data['skills'] = $learned_skills;
            $player_data['role_portrait'] = $role_portrait;
            $player_data['player_index'] = $player_index; // 味方のパーティ中での並び。

            $players_collection->push($player_data);
            DebugBar::debug("{$player_data['name']} 登録完了。");
        }

        return $players_collection;

    }

    /**
     * 指定したフィールドID、及びステージIDから敵の情報を読み込んで返す。
     *
     * @return Collection
     */
    public static function createEnemiesCollection(int $field_id, int $stage_id)
    {
        Debugbar::debug('createEnemiesCollection(): jsonのベースとなるenemies_data 作成。----------');
        $enemies_collection = collect();
        Debugbar::debug('敵のプリセットデータを読み込みます。----------');
        $preset_appearing_enemies = PresetAppearingEnemy::where('field_id', $field_id)
            ->where('stage_id', $stage_id)
            ->get();
        foreach ($preset_appearing_enemies as $preset) {
            $preset_enemy = Enemy::find($preset['enemy_id']);
            if ($preset_enemy) {
                for ($i = 0; $i < $preset['number']; $i++) {
                    $enemies_collection->push($preset_enemy);
                }
            }
        }

        // $enemies_collectionを加工して格納するためのCollection
        $adjusted_enemies_collection = collect();
        foreach ($enemies_collection as $enemy_index => $enemy) {
            $enemy_data = BattleData::ENEMY_TEMPLATE;

            $enemy_data['id'] = $enemy['id'];
            $enemy_data['name'] = $enemy['name'];
            $enemy_data['max_value_hp'] = $enemy['value_hp']; // HP最大値
            $enemy_data['max_value_ap'] = $enemy['value_ap']; // AP最大値
            $enemy_data['value_hp'] = $enemy['value_hp'];
            $enemy_data['value_ap'] = $enemy['value_ap'];
            $enemy_data['value_str'] = $enemy['value_str'];
            $enemy_data['value_def'] = $enemy['value_def'];
            $enemy_data['value_int'] = $enemy['value_int'];
            $enemy_data['value_spd'] = $enemy['value_spd'];
            $enemy_data['value_luc'] = $enemy['value_luc'];
            $enemy_data['portrait'] = $enemy['portrait_image_path'];
            $enemy_data['enemy_index'] = $enemy_index; // 敵の並び。
            $enemy_data['is_boss'] = $enemy['is_boss'];
            $enemy_data['exp'] = $enemy['exp'];
            $enemy_data['drop_money'] = $enemy['drop_money'];

            $adjusted_enemies_collection->push($enemy_data);
        }

        Debugbar::debug($adjusted_enemies_collection);

        return $adjusted_enemies_collection;
    }

    /**
     * 指定したセーブデータIDから、アイテムの情報を読み込んで返す。
     *
     * 処理自体はItem::getBattleStateItemFromSavedataがほぼほぼ担っている。
     *
     * @return Collection
     */
    public static function createItemsCollection(int $savedata_id)
    {
        Debugbar::debug('createItemsCollection(): ------------');
        $items_collection = Item::getBattleStateItemFromSavedata($savedata_id);
        Debugbar::debug($items_collection);

        return $items_collection;
    }

    /**
     * 作成した各データをDBに格納し、以降セッションIDで呼び出せるようにする。
     *
     * @return BattleState
     */
    public static function createBattleState(
        int $savedata_id,
        Collection $players_collection,
        Collection $enemies_collection,
        Collection $items_collection,
        Collection $enemy_drops_collection,
        int $field_id,
        int $stage_id
    ) {
        $session_id = Str::uuid()->toString();
        $created_battle_state = BattleState::create([
            'savedata_id' => $savedata_id,
            'session_id' => $session_id,
            'players_json_data' => json_encode($players_collection),
            'items_json_data' => json_encode($items_collection),
            'enemies_json_data' => json_encode($enemies_collection),
            'enemy_drops_json_data' => json_encode($enemy_drops_collection),
            'current_field_id' => $field_id,
            'current_stage_id' => $stage_id,
        ]);

        return $created_battle_state;

    }

    /**
     * 敵味方のデータの並びを、行動実行順に並べる
     *
     * 素早さや特殊スキル等を考慮させた順番とする
     * DEFENCE選択 > 特殊スキル選択 > 速度順
     *
     * @return Collection
     */
    public static function sortByBattleExec(Collection $battle_state_players_and_enemies_collection)
    {
        // 敵味方が同速の場合、現状は味方が優先される
        $sorted_battle_state_players_and_enemies_collection =
            $battle_state_players_and_enemies_collection->sort(function ($a, $b) {

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
                if ($a_effect_type === EffectType::Special->value && $b_effect_type !== EffectType::Special->value) {
                    return -1;
                }
                if ($b_effect_type === EffectType::Special->value && $a_effect_type !== EffectType::Special->value) {
                    return 1;
                }

                // 3. 速度順で降順ソート
                return $b->value_spd <=> $a->value_spd;

            })->values();

        // デバッグ用
        $action_order = $sorted_battle_state_players_and_enemies_collection->map(function ($item) {
            return $item->name;
        })->implode(', ');
        Debugbar::debug("行動順決定。{$action_order}");

        return $sorted_battle_state_players_and_enemies_collection;
    }

    /**
     * コマンド実行時の一番最初に呼ばれる処理
     *
     * ダメージ計算やバフの適用等の全てのコマンドは、この関数から呼び出されることになる。
     *
     * 【流れ】
     * 1. 敵か味方の行動かを確認
     * 2. 行動者が戦闘不能状態かどうか、対象グループが全滅しているかどうか、逃走済みかを確認
     * 3. switch文で分岐してコマンドを実行
     * 4. 各コマンドが別のメソッドを通じて実行され、終わったらreturnする
     */
    public static function execBattleCommand(
        Collection $sorted_battle_state_players_and_enemies_collection,
        Collection $battle_state_players_collection,
        Collection $battle_state_enemies_collection,
        Collection &$battle_state_items_collection,
        Collection $battle_logs_collection
    ) {
        // $actor_dataはstdClass Object型となるため、"->"で参照する。
        /** @var \stdClass $actor_data */
        foreach ($sorted_battle_state_players_and_enemies_collection as $action_index => $actor_data) {
            Debugbar::debug("########################### ループ: {$action_index}人目。 行動対象: {$actor_data->name} 素早さ: {$actor_data->value_spd} ###########################");

            if ($actor_data->is_enemy == false) {
                /**
                 * コマンド実行に移る前に、下記内容を確認する
                 * * $actor_dataが戦闘不能かどうか。
                 * * 敵は全滅しているかどうか。
                 * * $actor_data以外の味方が、闘争を成功しているかどうか。
                 */
                Debugbar::debug("--------------------味方( {$actor_data->name} )行動開始--------------------");
                // 戦闘不能の場合は何も行わない
                if ($actor_data->is_defeated_flag == true) {
                    Debugbar::debug("{$actor_data->name}は戦闘不能のためスキップします。");

                    continue;
                }

                // 敵全滅チェック
                if (BattleState::confirmDataIsAllDefeated($battle_state_enemies_collection)) {
                    Debugbar::debug("敵は全員討伐済みのため、行動をスキップします。 コマンド: {$actor_data->command}");

                    continue;
                }

                // 逃走チェック
                if (self::isPlayerSuccessEscape($battle_state_players_collection)) {
                    Debugbar::debug("味方がESCAPEコマンドを選択し、成功しているため行動をスキップします。 コマンド: {$actor_data->command} escapedフラグ: {$actor_data->is_escaped}");

                    continue;
                }

                Debugbar::debug("行動者やられ、敵全員討伐チェックOK。 コマンド: {$actor_data->command}");
                // switch文で曖昧な判定となるが、一旦問題ないはず。
                // (matchで書くと難解になるかもしれないので、switch文を採用)
                switch ($actor_data->command) {
                    case 'ATTACK':
                        self::execCommandAttack($actor_data, $battle_state_enemies_collection, false, $actor_data->target_enemy_index, $battle_logs_collection);
                        break;
                    case 'SKILL':
                        $battle_state_opponents_collection = collect();
                        if (($actor_data->target_enemy_index !== null)) {
                            Debugbar::debug('target_enemy_indexが入っているので敵グループを対象として格納。');
                            $battle_state_opponents_collection = $battle_state_enemies_collection;
                            $opponents_index = $actor_data->target_enemy_index;
                        } elseif (($actor_data->target_player_index !== null)) {
                            Debugbar::debug('target_player_indexが入っているので味方グループを対象として選択。');
                            $battle_state_opponents_collection = $battle_state_players_collection;
                            $opponents_index = $actor_data->target_player_index;
                        } else {
                            // 敵味方ともに対象のindexが格納されていないなら、それ以外。
                            // 範囲, もしくは自己強化(target_indexを指定する必要がない)。
                            // それぞれ$battle_state_opponents_collectionに条件に合うデータを格納。
                            // 攻撃系なら敵を, 回復またはバフ系なら味方を入れる。
                            // それ以外技の場合は$opponents_indexは格納せず、nullのままとする。
                            $opponents_index = null;
                            Debugbar::debug('target_player_indexが格納されていないため、それ以外のスキルが選択されました。');

                            switch ($actor_data->selected_skill_effect_type) {
                                case EffectType::Special->value:
                                    // ----------------- 特殊系スキル(分岐が難しいので、個別に対象処理をする) -----------------
                                    Debugbar::debug('特殊系スキル。');
                                    if ($actor_data->selected_skill_id == 31) {
                                        Debugbar::debug("ワイドガード: 味方情報をopponents_dataに格納。effect_type: {$actor_data->selected_skill_effect_type}");
                                        $battle_state_opponents_collection = $battle_state_players_collection;
                                    }
                                    break;
                                case EffectType::Damage->value:
                                    Debugbar::debug("攻撃系スキルのため敵情報をopponents_dataに格納。effect_type: {$actor_data->selected_skill_effect_type}");
                                    $battle_state_opponents_collection = $battle_state_enemies_collection;
                                    break;
                                case EffectType::Heal->value:
                                    Debugbar::debug("回復系スキルのため味方情報をopponents_dataに格納。effect_type: {$actor_data->selected_skill_effect_type}");
                                    $battle_state_opponents_collection = $battle_state_players_collection;
                                    break;
                                case EffectType::Buff->value:
                                    // TODO: デバフを採用するならさらに分岐して、敵データを入れる。
                                    Debugbar::debug("バフ系スキルのため味方情報をopponents_dataに格納。effect_type: {$actor_data->selected_skill_effect_type}");
                                    $battle_state_opponents_collection = $battle_state_players_collection;
                                    break;
                            }
                        }
                        self::execCommandSkill($actor_data, $battle_state_opponents_collection, false, $opponents_index, $battle_logs_collection);
                        break;
                    case 'ITEM':
                        // 選択したアイテムの情報を$battle_state_items_collectionから取得する
                        $selected_item_data = $battle_state_items_collection->firstWhere('id', $actor_data->selected_item_id);
                        // 選択アイテムが無い場合、スキップする(残り2個のアイテムを3人選択していた場合など)
                        if (is_null($selected_item_data)) {
                            $battle_logs_collection->push("{$actor_data->name}はアイテムを使おうと試みたが、手持ちに用意がなかった！");
                            break;
                        }
                        Debugbar::debug("【ITEM】選択アイテムID: {$selected_item_data->id},  {$selected_item_data->name}");

                        // 対象決定処理 (SKILLと同じなので、統一化できそう。)
                        $battle_state_opponents_collection = collect();
                        if (($actor_data->target_enemy_index !== null)) {
                            Debugbar::debug('【ITEM】target_enemy_indexが入っているので敵グループを対象として格納。');
                            $battle_state_opponents_collection = $battle_state_enemies_collection;
                            $opponents_index = $actor_data->target_enemy_index;
                        } elseif (($actor_data->target_player_index !== null)) {
                            Debugbar::debug('【ITEM】target_player_indexが入っているので味方グループを個別対象として選択。');
                            $battle_state_opponents_collection = $battle_state_players_collection;
                            $opponents_index = $actor_data->target_player_index;
                        } else {
                            // 敵味方ともに対象のindexが格納されていないなら、それ以外。
                            // 範囲, もしくは自己強化(target_indexを指定する必要がない)。
                            // それぞれ$battle_state_opponents_collectionに条件に合うデータを格納。
                            // 攻撃系なら敵を, 回復またはバフ系なら味方を入れる。
                            // それ以外技の場合は$opponents_indexは格納せず、nullのままとする。
                            $opponents_index = null;
                            Debugbar::debug('【ITEM】target_player_indexが格納されていないため、それ以外のアイテムが選択されました。');

                            switch ($selected_item_data->effect_type) {
                                case EffectType::Special->value:
                                    // ----------------- 特殊系スキル(分岐が難しいので、個別に対象処理をする) -----------------
                                    // 今のところ実装なし
                                    Debugbar::debug('特殊系アイテム');
                                    break;
                                case EffectType::Damage->value:
                                    Debugbar::debug("攻撃系アイテムのため敵情報をopponents_dataに格納。effect_type: {$selected_item_data->effect_type}");
                                    $battle_state_opponents_collection = $battle_state_enemies_collection;
                                    break;
                                case EffectType::Heal->value:
                                    Debugbar::debug("回復系アイテムのため味方情報をopponents_dataに格納。effect_type: {$selected_item_data->effect_type}");
                                    $battle_state_opponents_collection = $battle_state_players_collection;
                                    break;
                                case EffectType::Buff->value:
                                    // TODO: デバフを採用するならさらに分岐して、敵データを入れる。
                                    Debugbar::debug("バフ系アイテムのため味方情報をopponents_dataに格納。effect_type: {$selected_item_data->effect_type}");
                                    $battle_state_opponents_collection = $battle_state_players_collection;
                                    break;
                            }
                        }
                        self::execCommandItem($actor_data, $battle_state_opponents_collection, false, $opponents_index, $selected_item_data, $battle_logs_collection);

                        // アイテムの数を減らす
                        Debugbar::debug('アイテム処理完了。所持数調整....');
                        $selected_item_id = $selected_item_data->id;
                        $battle_state_items_collection = $battle_state_items_collection->map(function ($item) use ($selected_item_id) {
                            if ($item->id === $selected_item_id) {
                                $item->possession_number -= 1;
                                // 所持数が0以下になったら、return $itemとしてではなくnullで返すようにする
                                if ($item->possession_number <= 0) {
                                    Debugbar::debug("{$item->name}が0個になったためitems_json_dataからは取り除きます。");

                                    return null;
                                }
                            }

                            return $item;
                        })
                        // filter()メソッドはコレクションの要素を真偽値でフィルタリングする
                        // nullとして返された要素(所持数が0となった要素)はfalseで返り、コレクションから取り除かれる
                        // フィルタリング結果は元々の$current_battle_state_items_collectionと同期する
                            ->filter();
                        break;
                    case 'DEFENCE':
                        // "防御"バフを 1ターン、def * 0.5の補正として付与する。
                        // 例: value_defが60の場合、バフは30となり合計DEFは90となる
                        Debugbar::debug("【防御】使用者: {$actor_data->name} ");
                        $new_buff = BattleData::BUFF_TEMPLATE;
                        $new_buff['buffed_def'] = (int) ceil($actor_data->value_def * 0.5);
                        $new_buff['remaining_turn'] = 1;
                        $new_buff['buffed_from'] = 'DEFENCE';

                        $actor_data->buffs[] = $new_buff;
                        Debugbar::debug([
                            'message' => 'CASE: DEFENCE ---------------------- ',
                            'new_buff' => $new_buff,
                            'gettype($new_buff)' => gettype($new_buff),
                            'actor_data' => $actor_data,
                            'actor_data->buffs' => $actor_data->buffs,
                        ]);
                        $battle_logs_collection->push("{$actor_data->name}は防御の構えを取った！");
                        break;
                    case 'ESCAPE':
                        // 対象者の素早さを取得
                        $actual_speed = self::calculateActualStatusValue($actor_data, 'spd');
                        Debugbar::debug("【ESCAPE】使用者: {$actor_data->name} 基礎 + バフの合計スピード: {$actual_speed}");
                        // 相手の素早さの平均値をチェック。
                        $total_enemy_spd = 0;
                        $average_enemy_spd = 0;
                        foreach ($battle_state_enemies_collection as $enemy_data) {
                            $total_enemy_spd += $enemy_data->value_spd;
                        }
                        if ($battle_state_enemies_collection->count() > 0) {
                            $average_enemy_spd = $total_enemy_spd / $battle_state_enemies_collection->count();
                        }
                        Debugbar::debug(" 敵人数: {$battle_state_enemies_collection->count()} 合計SPD: {$total_enemy_spd} 平均値: {$average_enemy_spd} ");

                        /**
                         * 逃走成功確率の計算
                         * (基礎成功率 + (spdの差 * 2) ) * 100
                         * なお最低値10%, 最大値90%
                         */
                        $spd_diff = $actor_data->value_spd - $average_enemy_spd;
                        $escape_chance = self::BASE_ESCAPE_CHANCE + ($spd_diff * 0.02);
                        $escape_chance = max(0.1, min(0.9, $escape_chance));

                        $random_int = random_int(0, 100);
                        Debugbar::debug("逃走判定: SPD差 = {$spd_diff}、逃走確率 = ".($escape_chance * 100).'%');
                        Debugbar::debug($random_int);

                        if ($random_int < ($escape_chance * 100)) {
                            Debugbar::debug('逃走成功！');
                            $actor_data->is_escaped = true;
                            $battle_logs_collection->push("{$actor_data->name}は逃走を試みた！うまく逃げ切れた。");
                        } else {
                            Debugbar::debug('逃走失敗...');
                            $battle_logs_collection->push("{$actor_data->name}は逃走を試みた！しかし回り込まれてしまった！");
                        }
                        break;
                    default:
                        $battle_logs_collection->push("【debug】{$actor_data->name} 無効なコマンドです。");
                        break;
                }
            } else {
                Debugbar::warning("--------------------敵( {$actor_data->name} )行動開始--------------------");
                if ($actor_data->is_defeated_flag == true) {
                    Debugbar::warning("{$actor_data->name}はすでにやられているので行動をスキップします。");

                    continue; // 行動する敵がやられている場合は何も行わない
                }

                // プレイヤー全滅チェック
                if (BattleState::confirmDataIsAllDefeated($battle_state_players_collection)) {
                    Debugbar::debug("パーティは全員戦闘不能のため、敵の行動をスキップします。 コマンド: {$actor_data->command}");

                    continue; // 敵が全滅していたら、コマンドを実行せずスキップ
                }

                // プレイヤー逃走チェック
                if (self::isPlayerSuccessEscape($battle_state_players_collection)) {
                    Debugbar::debug("パーティ側がESCAPEコマンドを選択し、成功しているため敵の行動をスキップします。 コマンド: {$actor_data->command}");

                    continue;
                }

                Debugbar::warning('敵やられ、味方全員やられチェックOK');
                // コマンド対象となる相手をランダムに指定
                $index = rand(0, $battle_state_players_collection->count() - 1);

                // TODO: 敵の行動コマンド ATTACK以外の選択肢を揃える
                // TODO: ボスの場合だけ、この辺りは個別に調整できるようにしておく(固定行動にしたい。)
                $actor_data->command = 'ATTACK';
                switch ($actor_data->command) {
                    case 'ATTACK':
                        Debugbar::warning("【ATTACK】{$actor_data->name} ");
                        self::execCommandAttack($actor_data, $battle_state_players_collection, true, $index, $battle_logs_collection);
                        break;
                    case 'SKILL':
                        Debugbar::warning("【SKILL】{$actor_data->name} ");
                        break;
                    case 'ITEM':
                        // 実装予定はないが、使う想定をしておく
                        Debugbar::warning("【ITEM】{$actor_data->name} ");
                        break;
                    case 'DEFENCE':
                        // 実装予定はないが、使う想定をしておく
                        Debugbar::warning("【DEFENCE】{$actor_data->name} ");
                        break;
                    case 'ESCAPE':
                        // 実装予定はないが、使う想定をしておく
                        Debugbar::warning("【ESCAPE】{$actor_data->name} ");
                        break;
                    default:
                        Debugbar::warning("【debug】{$actor_data->name} 無効なコマンドです。");
                        break;
                }

            }
        }
    }

    /**
     * コマンド "ATTACK" 選択時の処理
     *
     * @param  object  $actor_data  Collectionをforeachで回しているうちのデータの1つ stdClass Object型
     * @param  bool  $is_enemy  $battle_state_opponents_collectionが敵かどうかの判定フラグ
     * @param  int  $opponents_index  $actor_dataが対象とした相手のindex (ここで言うインデックスは、画面上の並びのこと)
     * @param  Collection  $battle_logs_collection  画面に表示させるログを格納するコレクション
     */
    private static function execCommandAttack(
        object $actor_data,
        Collection $battle_state_opponents_collection,
        bool $is_enemy,
        int $opponents_index,
        Collection $battle_logs_collection
    ) {
        if ($is_enemy == false) {
            // 攻撃対象が既に戦闘不能の場合、別の敵を指定する
            if ($battle_state_opponents_collection[$opponents_index]->is_defeated_flag == true) {
                $new_target_index = $battle_state_opponents_collection->search(function ($enemy) {
                    return $enemy->is_defeated_flag == false;
                });
                if ($new_target_index !== false) {
                    $opponents_index = $new_target_index;
                    Debugbar::debug("攻撃対象がすでに討伐済みのため、対象を変更。改めて攻撃対象: {$battle_state_opponents_collection[$opponents_index]->name}");
                } else {
                    Debugbar::debug("すべての敵が討伐済みになったので、ATTACKを終了します。敵数: {$battle_state_opponents_collection->count()}");

                    return;
                }
            }
            // ATTACK時の基礎ダメージ: 基礎STRとバフのSTRの合計値
            $pure_damage = self::calculateActualStatusValue($actor_data, 'str');

            Debugbar::debug("【味方】STRの実数値。（ATTACKの場合、これが純粋なダメージ量となる。） : {$pure_damage}");
            // 単体・物理攻撃として扱う
            self::storePartyDamage(
                'ATTACK', $actor_data, $battle_state_opponents_collection, null, $opponents_index, $battle_logs_collection, $pure_damage, TargetRange::Single->value, AttackType::Physical->value
            );

            // 敵の場合
        } else {
            Debugbar::warning("------------{$actor_data->name}:攻撃開始 攻撃対象: {$battle_state_opponents_collection[$opponents_index]->name}---------------");
            // 攻撃対象の味方がすでに倒れている場合、別の味方を指定する
            if ($battle_state_opponents_collection[$opponents_index]->is_defeated_flag == true) {
                $new_target_index = $battle_state_opponents_collection->search(function ($player) {
                    return $player->is_defeated_flag == false;
                });
                if ($new_target_index !== false) {
                    $opponents_index = $new_target_index;
                    Debugbar::warning("攻撃対象の味方がすでに倒れているため、対象を変更。改めて攻撃対象: {$battle_state_opponents_collection[$opponents_index]->name}");
                } else {
                    Debugbar::warning("すべての味方が倒れました。敵数: {$battle_state_opponents_collection->count()}");
                }
            }
            // ATTACK時の基礎ダメージ: 基礎STRとバフのSTRの合計値
            $pure_damage = self::calculateActualStatusValue($actor_data, 'str');
            Debugbar::debug("【味方】STRの実数値。（ATTACKの場合、これが純粋なダメージ量となる。） : {$pure_damage}");

            // 画面に表示するログの記録
            self::storeEnemyDamage(
                'ATTACK', $actor_data, $battle_state_opponents_collection, $opponents_index, $battle_logs_collection, $pure_damage
            );
        }
        // execBattleCommandに戻る
    }

    /**
     * コマンド "SKILL" 選択時の処理
     *
     * $opponents_indexは 並び中央の味方に向けた場合は[1]が入るが、
     * 全体攻撃スキルを使った場合, $opponents_indexはnullであるため?で許容しておく。
     *
     * @param  object  $actor_data  Collectionをforeachで回しているうちのデータの1つ stdClass Object型
     * @param  bool  $is_enemy  $battle_state_opponents_collectionが敵かどうかの判定フラグ
     * @param  ?int  $opponents_index  $actor_dataが対象とした相手のindex (ここで言うインデックスは、画面上の並びのこと)
     * @param  Collection  $battle_logs_collection  画面に表示させるログを格納するコレクション
     */
    private static function execCommandSkill(
        object $actor_data,
        Collection $battle_state_opponents_collection,
        bool $is_enemy,
        ?int $opponents_index,
        Collection $battle_logs_collection
    ) {
        Debugbar::debug('execCommandSkill(): ----------------------');
        // firstWhereを使用するため、collectionとして一時的にキャスト
        /** @var \stdClass $selected_skill_data BattleData::SKILL_TEMPLATE に各データが格納されたオブジェクト。 */
        $selected_skill_data = collect($actor_data->skills)->firstWhere('id', $actor_data->selected_skill_id);
        // Debugbar::debug($selected_skill_data);
        // APがなければ、ログに入れて処理を終了する
        if ($actor_data->value_ap < $selected_skill_data->ap_cost) {
            $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}の発動を試みたがAPが足りなかった！");

            return;
        }

        if ($is_enemy == false) {
            // スキルごとに効果・ログ・ダメージ計算・バフ付与などを行う
            Skill::decideExecSkill($actor_data->role_id, $selected_skill_data, $actor_data, $battle_state_opponents_collection, $is_enemy, $opponents_index, $battle_logs_collection);
        } else {
            // TODO: 敵もこの処理で技を使えるようにする
        }
        // execBattleCommandに戻る
    }

    /**
     * コマンド "ITEM" 選択時の処理
     *
     * $actor_data は敵味方考慮しないが、現状アイテムは味方だけしか使えないため基本味方データが入る。
     * $opponents_indexは 並び中央の味方に向けた場合は[1]が入るが、
     * 全体攻撃アイテムなどを使った場合, $opponents_indexはnullであるため?で許容しておく。
     * $selected_item_data は$battle_state_items_collectionから取得した一部データのため、stdClass Object型。
     */
    private static function execCommandItem(
        object $actor_data,
        Collection $battle_state_opponents_collection,
        bool $is_enemy,
        ?int $opponents_index,
        object $selected_item_data,
        Collection $battle_logs_collection
    ) {
        Debugbar::debug('execCommandItem(): ---------------------- ');

        // 味方の場合
        // ※ただし、アイテムを使えるのは現状味方だけの想定であるが。
        if ($is_enemy == false) {

            // 使用するアイテムに応じて、ダメージ・回復量・付与されるバフを設定。
            $damage = null;
            $heal_point = null;

            $new_buff = BattleData::BUFF_TEMPLATE;
            $new_buff['buffed_from'] = 'ITEM';
            $new_buff['buffed_item_id'] = $selected_item_data->id;
            $new_buff['buffed_item_name'] = $selected_item_data->name;
            $new_buff['remaining_turn'] = $selected_item_data->buff_turn;

            $battle_logs_collection->push("{$actor_data->name}は{$selected_item_data->name}を使った！");

            switch ($selected_item_data->effect_type) {
                case EffectType::Special->value:
                    Debugbar::debug('特殊系※現状考えていない。');
                    break;
                case EffectType::Damage->value:
                    Debugbar::debug('攻撃系アイテム');
                    if (! $selected_item_data->is_percent_based) {
                        $damage = $selected_item_data->fixed_value;
                    } else {
                        // 攻撃系倍率系のアイテムの場合※現状考えていない
                    }
                    BattleState::storePartyDamage(
                        'ITEM', $actor_data, $battle_state_opponents_collection, $selected_item_data, $opponents_index, $battle_logs_collection, $damage, $selected_item_data->target_range, $selected_item_data->attack_type
                    );
                    break;
                case EffectType::Heal->value:
                    if (! $selected_item_data->is_percent_based) {
                        $heal_point = $selected_item_data->fixed_value;
                    }
                    // 倍率系アイテムの場合はnullが入るがstorePartyHeal側で向こうの体力と合わせて計算する
                    Debugbar::debug('回復系アイテム');
                    BattleState::storePartyHeal(
                        'ITEM', $actor_data, $battle_state_opponents_collection,
                        $opponents_index, $battle_logs_collection, $heal_point, $selected_item_data->target_range, $selected_item_data->percent, $selected_item_data->heal_type
                    );
                    break;
                case EffectType::Buff->value:
                    // バフは個別に処理
                    switch ($selected_item_data->id) {
                        case 21:
                            Debugbar::debug('アタックドロップ');
                            $new_buff['buffed_str'] = $selected_item_data->fixed_value;
                            break;
                        case 22:
                            Debugbar::debug('アタックミスト');
                            $new_buff['buffed_str'] = $selected_item_data->fixed_value;
                            break;
                    }

                    BattleState::storePartyBuff(
                        'ITEM', $actor_data, $battle_state_opponents_collection, $opponents_index, $battle_logs_collection, $new_buff, $selected_item_data->target_range
                    );
                    break;
            }
        }
        // execBattleCommandに戻る
    }

    /**
     * ダメージ計算処理 併せて、画面に表示させるログも記入する
     *
     * @param  ?object  $selected_item  $commandがITEMの場合、そのアイテムの情報
     * @param  ?int  $pure_damage  敵の守備力などを考慮しない、純粋なダメージ量 nullのケースがある(ITEMの%依存の攻撃など)
     * @param  int  $target_range  コマンド対象範囲 TARGET_RANGE_SINGLE 等
     * @param  int  $attack_type  コマンド攻撃タイプ AttackType::Physical->valueや、AttackType::Physical->value 等
     */
    public static function storePartyDamage(
        string $command,
        object $actor_data,
        Collection $battle_state_opponents_collection,
        ?object $selected_item,
        ?int $opponents_index,
        Collection $battle_logs_collection,
        ?int $pure_damage,
        int $target_range,
        int $attack_type
    ) {
        $calculated_damage = 0;
        switch ($command) {
            case 'ATTACK':
                // 攻撃対象となるopponents_collectionを、1つの変数に格納して呼びやすくしておく
                // CollectionはObjectの参照を保持するため、この変数の内容が変わると、元のCollectionも変わる
                // (こういった仕様があるので、純粋にコピーして使いたい場合は copy という文法がある)

                /** @var \stdClass $opponent_data */
                $opponent_data = $battle_state_opponents_collection[$opponents_index];
                Debugbar::debug("storePartyDamage(): ATTACK {$actor_data->name} → {$opponent_data->name}");

                // $pure_damageをベースに、相手のバフ後DEFを考慮してのダメージ計算
                $calculated_damage = self::calculatePhysicalDamage(
                    $pure_damage,
                    self::calculateActualStatusValue($opponent_data, 'def')
                );

                if ($calculated_damage > 0) {
                    Debugbar::debug("【ATTACK】ダメージが1以上。相手の現在体力: {$opponent_data->value_hp}");
                    $opponent_data->value_hp -= $calculated_damage;
                    Debugbar::debug("攻撃した。相手の残り体力: {$opponent_data->value_hp}");
                    // 相手を倒した時、戦闘不能フラグを有効化し、バフをリセット
                    if ($opponent_data->value_hp <= 0) {
                        $opponent_data->value_hp = 0; // マイナスになるのを防ぐ。
                        $opponent_data->is_defeated_flag = true;
                        self::clearBuff($opponent_data);
                        $battle_logs_collection->push("{$actor_data->name}の攻撃！{$opponent_data->name}に{$calculated_damage}のダメージ。");
                        $battle_logs_collection->push("{$opponent_data->name}を倒した！");
                        Debugbar::debug("{$opponent_data->name}を倒した。相手の残り体力: {$opponent_data->value_hp} 相手討伐フラグ: {$opponent_data->is_defeated_flag} ");
                    } else {
                        $battle_logs_collection->push("{$actor_data->name}の攻撃！{$opponent_data->name}に{$calculated_damage}のダメージ。");
                        Debugbar::debug("{$opponent_data->name}はまだ生存している。相手の残り体力: {$opponent_data->value_hp} 相手討伐フラグ: {$opponent_data->is_defeated_flag} ");
                    }
                } else {
                    // ダメージを与えられなかった場合
                    $battle_logs_collection->push("{$actor_data->name}の攻撃！しかし{$opponent_data->name}にダメージを与えられない！");
                    Debugbar::debug("攻撃が通らなかった。{$opponent_data->name}は生存。相手の残り体力: {$opponent_data->value_hp} 相手討伐フラグ: {$opponent_data->is_defeated_flag} ");
                }
                break;
            case 'SKILL':
                Debugbar::debug('storePartyDamage(): SKILL');
                // 単体攻撃の場合
                if ($target_range === TargetRange::Single->value) {
                    Debugbar::debug('単体攻撃。');
                    $opponent_data = $battle_state_opponents_collection[$opponents_index];

                    // ダメージ計算 物理か魔法攻撃かで変える
                    if ($attack_type === AttackType::Physical->value) {
                        Debugbar::debug('物理。');
                        $calculated_damage = self::calculatePhysicalDamage(
                            $pure_damage,
                            self::calculateActualStatusValue($opponent_data, 'def')
                        );
                    } elseif ($attack_type === AttackType::Magic->value) {
                        Debugbar::debug('魔法。');
                        $opponent_mdef = self::calculateMagicDEFENCEValue(
                            self::calculateActualStatusValue($opponent_data, 'def'),
                            self::calculateActualStatusValue($opponent_data, 'int')
                        );
                        $calculated_damage = self::calculateMagicDamage($pure_damage, $opponent_mdef);
                    }

                    if ($calculated_damage > 0) {
                        Debugbar::debug("【SKILL】ダメージが1以上。敵の現在体力: {$opponent_data->value_hp}");
                        $opponent_data->value_hp -= $calculated_damage;
                        Debugbar::debug("攻撃した。敵の残り体力: {$opponent_data->value_hp}");
                        // 敵を倒した場合
                        if ($opponent_data->value_hp <= 0) {
                            $opponent_data->value_hp = 0; // マイナスになるのを防ぐ。
                            $opponent_data->is_defeated_flag = true;
                            self::clearBuff($opponent_data);
                            $battle_logs_collection->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                            $battle_logs_collection->push("{$opponent_data->name}を倒した！");
                            Debugbar::debug("{$opponent_data->name}を倒した。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                        } else {
                            $battle_logs_collection->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                            Debugbar::debug("{$opponent_data->name}はまだ生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                        }
                    } else {
                        // ダメージを与えられなかった場合
                        Debugbar::debug('ダメージを与えられない。');
                        $battle_logs_collection->push("しかし{$opponent_data->name}にダメージは与えられなかった！");
                        Debugbar::debug("攻撃が通らなかった。{$opponent_data->name}は当然生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                    }
                    // 全体攻撃の場合
                } else {
                    Debugbar::debug('全体攻撃ループ開始。#########');
                    // ループ内で書くと攻撃のたびに威力が弱まってしまうので、個別で防御などを改めて取得して処理する。
                    $base_damage = $pure_damage;
                    foreach ($battle_state_opponents_collection as $opponent_data) {
                        // 討伐判定チェック
                        if ($opponent_data->is_defeated_flag == true) {
                            Debugbar::debug("{$opponent_data->name}はすでに戦闘不能フラグが立っているため、スキップ");

                            // returnにした場合は、foreach自体が終了する
                            // continueだと次のforeachのループ処理に移行する。今回の場合はスキップしたいので、continueとしておく。
                            continue;
                        }

                        // ダメージ計算 物理か魔法攻撃かで変える
                        if ($attack_type === AttackType::Physical->value) {
                            Debugbar::debug('物理。');
                            $calculated_damage = self::calculatePhysicalDamage(
                                $base_damage,
                                self::calculateActualStatusValue($opponent_data, 'def')
                            );
                        } elseif ($attack_type === AttackType::Magic->value) {
                            Debugbar::debug('魔法。');
                            $opponent_mdef = self::calculateMagicDEFENCEValue(
                                self::calculateActualStatusValue($opponent_data, 'def'),
                                self::calculateActualStatusValue($opponent_data, 'int')
                            );
                            $calculated_damage = self::calculateMagicDamage(
                                $base_damage,
                                $opponent_mdef
                            );
                        }

                        if ($calculated_damage > 0) {
                            Debugbar::debug("【SKILL】ダメージが1以上。敵の現在体力: {$opponent_data->value_hp}");
                            $opponent_data->value_hp -= $calculated_damage;
                            Debugbar::debug("攻撃した。敵の残り体力: {$opponent_data->value_hp}");
                            // 敵を倒した場合
                            if ($opponent_data->value_hp <= 0) {
                                $opponent_data->value_hp = 0; // マイナスになるのを防ぐ。
                                $opponent_data->is_defeated_flag = true;
                                self::clearBuff($opponent_data);
                                $battle_logs_collection->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                                $battle_logs_collection->push("{$opponent_data->name}を倒した！");
                                Debugbar::debug("{$opponent_data->name}を倒した。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                            } else {
                                $battle_logs_collection->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                                Debugbar::debug("{$opponent_data->name}はまだ生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                            }
                        } else {
                            // ダメージを与えられなかった場合
                            Debugbar::debug('ダメージを与えられない。');
                            $battle_logs_collection->push("しかし{$opponent_data->name}にダメージは与えられなかった！");
                            Debugbar::debug("攻撃が通らなかった。{$opponent_data->name}は当然生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                        }
                    }
                    Debugbar::debug('全体攻撃ループ完了。#########');

                }
                break;

            case 'ITEM':
                Debugbar::debug('storePartyDamage(): ITEM');
                // 単体攻撃の場合
                if ($target_range === TargetRange::Single->value) {
                    Debugbar::debug('単体攻撃。');
                    $opponent_data = $battle_state_opponents_collection[$opponents_index];

                    // is_percent_basedのアイテムの場合は、相手の現在体力に合わせたダメージを与える
                    if ($pure_damage == null || $selected_item->is_percent_based) {
                        $calculated_damage = ceil($opponent_data->value_hp * $selected_item->percent);
                    } else {
                        // ダメージ計算 物理か魔法攻撃かで変える
                        if ($attack_type === AttackType::Physical->value) {
                            Debugbar::debug('物理。');
                            $calculated_damage = self::calculatePhysicalDamage(
                                $pure_damage,
                                self::calculateActualStatusValue($opponent_data, 'def')
                            );
                        } elseif ($attack_type === AttackType::Magic->value) {
                            Debugbar::debug('魔法。');
                            $opponent_mdef = self::calculateMagicDEFENCEValue(
                                self::calculateActualStatusValue($opponent_data, 'def'),
                                self::calculateActualStatusValue($opponent_data, 'int')
                            );
                            $calculated_damage = self::calculateMagicDamage(
                                $pure_damage,
                                $opponent_mdef
                            );
                        }
                    }

                    if ($calculated_damage > 0) {
                        Debugbar::debug("【ITEM】ダメージが1以上。敵の現在体力: {$opponent_data->value_hp}");
                        $opponent_data->value_hp -= $calculated_damage;
                        Debugbar::debug("アイテムで攻撃した。敵の残り体力: {$opponent_data->value_hp}");
                        // 敵を倒した場合
                        if ($opponent_data->value_hp <= 0) {
                            $opponent_data->value_hp = 0; // マイナスになるのを防ぐ。
                            $opponent_data->is_defeated_flag = true;
                            self::clearBuff($opponent_data);
                            $battle_logs_collection->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                            $battle_logs_collection->push("{$opponent_data->name}を倒した！");
                            Debugbar::debug("{$opponent_data->name}を倒した。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                        } else {
                            $battle_logs_collection->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                            Debugbar::debug("{$opponent_data->name}はまだ生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                        }
                        // ダメージを与えられなかった場合
                    } else {
                        Debugbar::debug('ダメージを与えられない。');
                        $battle_logs_collection->push("しかし{$opponent_data->name}にダメージは与えられなかった！");
                        Debugbar::debug("攻撃が通らなかった。{$opponent_data->name}は当然生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                    }
                    // 全体攻撃の場合
                } else {
                    Debugbar::debug('全体攻撃ループ開始。#########');
                    // ループ内で書くと攻撃のたびに威力が弱まってしまうので、個別で防御などを改めて取得して処理する。
                    $base_damage = $pure_damage;
                    foreach ($battle_state_opponents_collection as $opponent_data) {
                        // 討伐判定チェック
                        if ($opponent_data->is_defeated_flag === true) {
                            Debugbar::debug("{$opponent_data->name}はすでに戦闘不能フラグが立っているため、スキップ");

                            // returnにした場合は、foreach自体が終了する
                            // continueだと次のforeachのループ処理に移行する。今回の場合はスキップしたいので、continueとしておく。
                            continue;
                        }

                        // is_percent_basedのアイテムの場合は、相手の現在体力に合わせたダメージを与える
                        if ($base_damage == null || $selected_item->is_percent_based) {
                            $calculated_damage = ceil($opponent_data->value_hp * $selected_item->percent);
                        } else {
                            // ダメージ計算 物理か魔法攻撃かで変える
                            if ($attack_type === AttackType::Physical->value) {
                                Debugbar::debug('物理。');
                                $calculated_damage = self::calculatePhysicalDamage(
                                    $base_damage,
                                    self::calculateActualStatusValue($opponent_data, 'def')
                                );
                            } elseif ($attack_type === AttackType::Magic->value) {
                                Debugbar::debug('魔法。');
                                $opponent_mdef = self::calculateMagicDEFENCEValue(
                                    self::calculateActualStatusValue($opponent_data, 'def'),
                                    self::calculateActualStatusValue($opponent_data, 'int')
                                );
                                $calculated_damage = self::calculateMagicDamage(
                                    $base_damage,
                                    $opponent_mdef
                                );
                            }
                        }

                        if ($calculated_damage > 0) {
                            Debugbar::debug("【ITEM】ダメージが1以上。敵の現在体力: {$opponent_data->value_hp}");
                            $opponent_data->value_hp -= $calculated_damage;
                            Debugbar::debug("攻撃した。敵の残り体力: {$opponent_data->value_hp}");
                            // 敵を倒した場合
                            if ($opponent_data->value_hp <= 0) {
                                $opponent_data->value_hp = 0; // マイナスになるのを防ぐ。
                                $opponent_data->is_defeated_flag = true;
                                self::clearBuff($opponent_data);
                                $battle_logs_collection->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                                $battle_logs_collection->push("{$opponent_data->name}を倒した！");
                                Debugbar::debug("{$opponent_data->name}を倒した。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                            } else {
                                $battle_logs_collection->push("{$opponent_data->name}に{$calculated_damage}のダメージ！");
                                Debugbar::debug("{$opponent_data->name}はまだ生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                            }
                        } else {
                            // ダメージを与えられなかった場合
                            Debugbar::debug('ダメージを与えられない。');
                            $battle_logs_collection->push("しかし{$opponent_data->name}にダメージは与えられなかった！");
                            Debugbar::debug("攻撃が通らなかった。{$opponent_data->name}は当然生存している。敵の残り体力: {$opponent_data->value_hp} 敵討伐フラグ: {$opponent_data->is_defeated_flag} ");
                        }
                    }
                    Debugbar::debug('全体攻撃ループ完了。#########');
                }
                break;

            default:
                break;
        }
    }

    // opponents_dataは攻撃されるプレイヤーのデータが入る
    public static function storeEnemyDamage(
        string $command, object $self_data, Collection $opponents_data,
        int $opponents_index, Collection $logs, int $damage
    ) {
        switch ($command) {
            case 'ATTACK':
                Debugbar::warning('storeEnemyDamage(): ATTACK');

                // ダメージ計算
                $calculated_damage = self::calculatePhysicalDamage(
                    $damage,
                    self::calculateActualStatusValue($opponents_data[$opponents_index], 'def')
                );

                if ($calculated_damage > 0) {
                    Debugbar::warning("ダメージが1以上なので攻撃。味方の現在の体力: {$opponents_data[$opponents_index]->value_hp}");
                    $opponents_data[$opponents_index]->value_hp -= $calculated_damage;
                    Debugbar::warning("攻撃された。味方の残り体力: {$opponents_data[$opponents_index]->value_hp}");
                    if ($opponents_data[$opponents_index]->value_hp <= 0) {
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
            case 'SKILL':
                break;
            default:
                break;
        }
    }

    /**
     * 回復処理 併せて、画面に表示させるログも記入する
     *
     * スキルまたはアイテムを使用しての回復処理。アイテムを使ってのAP回復処理にも対応。
     */
    public static function storePartyHeal(
        string $command,
        object $actor_data,
        Collection $battle_state_opponents_collection,
        ?int $opponents_index,
        Collection $battle_logs_collection,
        ?int $heal_point,
        int $target_range,
        int|float|null $percent,
        ?int $heal_type
    ) {
        switch ($command) {
            case 'SKILL':
                Debugbar::debug('storePartyHeal(): SKILL ------------------------------');
                if ($target_range === TargetRange::Single->value) {
                    /** @var \stdClass $opponent_data */
                    $opponent_data = $battle_state_opponents_collection[$opponents_index];
                    Debugbar::debug("【単体回復】回復量: {$heal_point} 使用者: {$actor_data->name} 対象者: {$opponent_data->name}");
                    // 戦闘不能ならスキップ
                    if ($opponent_data->is_defeated_flag == true) {
                        $battle_logs_collection->push("しかし{$opponent_data->name}は戦闘不能のため効果が無かった！");
                    } else {
                        $opponent_data->value_hp += $heal_point;
                        if ($opponent_data->value_hp > $opponent_data->max_value_hp) {
                            $opponent_data->value_hp = $opponent_data->max_value_hp;
                        }
                        $battle_logs_collection->push("{$opponent_data->name}のHPが{$heal_point}ポイント回復！");
                    }

                } elseif ($target_range == TargetRange::All->value) {
                    // $battle_state_opponents_collectionに対象が全て入っているはずなので、それで回復を回すと良い
                    Debugbar::debug("【全体回復】回復量: {$heal_point} 使用者: {$actor_data->name}");
                    foreach ($battle_state_opponents_collection as $opponent_data) {
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

                    $battle_logs_collection->push("全員のHPを{$heal_point}ポイント回復！");
                }
                break;
            case 'ITEM':
                Debugbar::debug('storePartyHeal(): ITEM ------------------------------');

                if ($target_range == TargetRange::Single->value) {
                    /** @var \stdClass $opponent_data */
                    $opponent_data = $battle_state_opponents_collection[$opponents_index];
                    Debugbar::debug("【単体回復】回復量: {$heal_point} 使用者: {$actor_data->name} 対象者: {$opponent_data->name}");
                    // 戦闘不能ならスキップ
                    if ($opponent_data->is_defeated_flag == true) {
                        $battle_logs_collection->push("しかし{$opponent_data->name}は戦闘不能のため効果が無かった！");
                    } else {
                        switch ($heal_type) {
                            case HealType::Hp->value:
                                Debugbar::debug('HP回復アイテム');
                                // % 回復系のアイテムなら、対象者の体力を参考に改めて回復量を決める
                                if (is_null($heal_point)) {
                                    $heal_point = (int) ceil($opponent_data->max_value_hp * $percent);
                                    Debugbar::debug("回復量nullのため、percentを参照。回復量:  {$heal_point} ");
                                }
                                $opponent_data->value_hp += $heal_point;
                                if ($opponent_data->value_hp > $opponent_data->max_value_hp) {
                                    $opponent_data->value_hp = $opponent_data->max_value_hp;
                                }
                                $battle_logs_collection->push("{$opponent_data->name}のHPが{$heal_point}ポイント回復！");
                                break;

                            case HealType::Ap->value:
                                Debugbar::debug('AP回復アイテム');
                                // % 回復系のアイテムなら、対象者の体力を参考に改めて回復量を決める
                                if (is_null($heal_point)) {
                                    $heal_point = (int) ceil($opponent_data->max_value_ap * $percent);
                                    Debugbar::debug("回復量nullのため、percentを参照。回復量:  {$heal_point} ");
                                }
                                $opponent_data->value_ap += $heal_point;
                                if ($opponent_data->value_ap > $opponent_data->max_value_ap) {
                                    $opponent_data->value_ap = $opponent_data->max_value_ap;
                                }
                                $battle_logs_collection->push("{$opponent_data->name}のAPが{$heal_point}ポイント回復！");
                                break;
                        }
                    }

                } elseif ($target_range == TargetRange::All->value) {
                    Debugbar::debug("【全体回復】回復量: {$heal_point} 使用者: {$actor_data->name}");
                    foreach ($battle_state_opponents_collection as $opponent_data) {
                        $calculated_heal_point = $heal_point;
                        // 戦闘不能ならスキップ
                        if ($opponent_data->is_defeated_flag == true) {
                            Debugbar::debug("{$opponent_data->name}は戦闘不能のため回復対象としません。");
                        } else {
                            switch ($heal_type) {
                                case HealType::Hp->value:
                                    // % 回復系のアイテムなら、対象者の体力を参考に改めて回復量を決める
                                    if (is_null($heal_point)) {
                                        $calculated_heal_point = (int) ceil($opponent_data->max_value_hp * $percent);
                                        Debugbar::debug("回復量nullのため、percentを参照。回復量:{$calculated_heal_point} ");
                                    }
                                    $opponent_data->value_hp += $calculated_heal_point;
                                    if ($opponent_data->value_hp > $opponent_data->max_value_hp) {
                                        $opponent_data->value_hp = $opponent_data->max_value_hp;
                                    }
                                    Debugbar::debug("{$opponent_data->name}のHPを{$calculated_heal_point}ポイント回復。");
                                    $battle_logs_collection->push("{$opponent_data->name}のHPを{$calculated_heal_point}ポイント回復！");
                                    break;
                                case HealType::Ap->value:
                                    // % 回復系のアイテムなら、対象者の体力を参考に改めて回復量を決める
                                    if (is_null($heal_point)) {
                                        $calculated_heal_point = (int) ceil($opponent_data->max_value_ap * $percent);
                                        Debugbar::debug("回復量nullのため、percentを参照。回復量: {$calculated_heal_point} ");
                                    }
                                    $opponent_data->value_ap += $calculated_heal_point;
                                    if ($opponent_data->value_ap > $opponent_data->max_value_ap) {
                                        $opponent_data->value_ap = $opponent_data->max_value_ap;
                                    }
                                    Debugbar::debug("{$opponent_data->name}のAPを{$calculated_heal_point}ポイント回復。");
                                    $battle_logs_collection->push("{$opponent_data->name}のAPを{$calculated_heal_point}ポイント回復！");
                                    break;
                            }
                        }
                    }
                }
                break;
        }
    }

    /**
     * スキルまたはアイテムを使用してのバフ付与処理
     *
     * $battle_state_opponents_collectionは、battle_stateのCollection化したjsonデータ。
     * 複数データ入っているので、単体で使いたい場合は$opponents_indexで添字を指定して使う。
     */
    public static function storePartyBuff(
        string $command,
        object $actor_data,
        Collection $battle_state_opponents_collection,
        ?int $opponents_index,
        Collection $battle_logs_collection,
        array $new_buff,
        int $target_range
    ) {
        switch ($command) {
            case 'SKILL':
                Debugbar::debug('storePartyBuff(): SKILL ------------------------------');
                if ($target_range === TargetRange::Single->value) {
                    /** @var \stdClass $opponent_data */
                    $opponent_data = $battle_state_opponents_collection[$opponents_index];
                    Debugbar::debug("【単体バフ】使用者: {$actor_data->name} 対象者: {$opponent_data->name} 使用スキルID: {$new_buff['buffed_skill_id']}");
                    self::adjustBuffFromSituation($opponent_data, $new_buff, $battle_logs_collection, $target_range);
                } elseif ($target_range === TargetRange::All->value) {
                    Debugbar::debug("【全体バフ】使用者: {$actor_data->name} 使用スキルID: {$new_buff['buffed_skill_id']}");
                    foreach ($battle_state_opponents_collection as $opponent_data) {
                        self::adjustBuffFromSituation($opponent_data, $new_buff, $battle_logs_collection, $target_range);
                    }
                    $battle_logs_collection->push('全員のステータスが向上した！');
                } elseif ($target_range === TargetRange::Self->value) {
                    Debugbar::debug("【自分自身へのバフ】使用者: {$actor_data->name} 使用スキルID: {$new_buff['buffed_skill_id']}");
                    self::adjustBuffFromSituation($actor_data, $new_buff, $battle_logs_collection, $target_range);
                }
                break;
            case 'ITEM':
                Debugbar::debug('バフアイテム使用。');
                if ($target_range === TargetRange::Single->value) {
                    /** @var \stdClass $opponent_data */
                    $opponent_data = $battle_state_opponents_collection[$opponents_index];
                    Debugbar::debug("【単体バフ】使用者: {$actor_data->name} 対象者: {$opponent_data->name} 使用アイテムID: {$new_buff['buffed_item_id']}");

                    self::adjustBuffFromSituation($opponent_data, $new_buff, $battle_logs_collection, $target_range);

                } elseif ($target_range == TargetRange::All->value) {
                    // $battle_state_opponents_collectionに対象が全て入っているはずなので、それで回復を回すと良い
                    Debugbar::debug("【全体バフ】使用者: {$actor_data->name} 使用アイテムID: {$new_buff['buffed_item_id']}");
                    foreach ($battle_state_opponents_collection as $opponent_data) {
                        self::adjustBuffFromSituation($opponent_data, $new_buff, $battle_logs_collection, $target_range);
                    }
                    // foreach内でpushすると、同じ記述が人数分出てくるので、ループ外でpushする
                    $battle_logs_collection->push('全員のステータスが向上した！');
                }
                break;
            default:
                break;
        }
    }

    /**
     * 固有の能力を持つスキル処理メソッド。
     *
     * パラ の ワイドガード など、使いまわせない固有スキルの処理をswitch文で対応する。
     */
    public static function storePartySpecialSkill(
        object $actor_data,
        Collection $battle_state_opponents_collection,
        ?int $opponents_index,
        Collection $battle_logs_collection,
        array $new_buff,
        object $selected_skill_data
    ) {

        Debugbar::debug("【特殊スキル】使用者: {$actor_data->name} 使用スキル: 【{$new_buff['buffed_skill_id']}】{$new_buff['buffed_skill_name']} ");

        // スキル別に個別の処理を回す。
        switch ($selected_skill_data->id) {
            case 31 : // ワイドガード
                foreach ($battle_state_opponents_collection as $opponent_data) {
                    Debugbar::debug("付与対象:{$opponent_data->name}");
                    self::adjustBuffFromSituation($opponent_data, $new_buff, $battle_logs_collection, $selected_skill_data->target_range);
                }
                break;

            default:
                break;
        }
    }

    /**
     * 状況に応じて、バフを上書きするか追加する処理
     *
     * バフスキル,アイテムを使用した時、戦闘不能な場合やすでに同じバフが付与されている時に重複させるかなどを調整する
     */
    private static function adjustBuffFromSituation(
        object $opponent_data,
        array $new_buff,
        Collection $battle_logs_collection,
        int $target_range
    ) {
        Debugbar::debug("adjustBuffFromSituation() {$opponent_data->name}のバフを調整します。");
        // 戦闘不能ならスキップ
        if ($opponent_data->is_defeated_flag == true) {
            if ($target_range === TargetRange::Single->value) {
                Debugbar::debug("{$opponent_data->name}は戦闘不能のため付与対象としません。");
                $battle_logs_collection->push("しかし{$opponent_data->name}は戦闘不能のため効果が無かった！");
            }
            // 全体バフの場合は、バフがつかなかったということをbattle_collection_logsには記載しない
            Debugbar::debug("{$opponent_data->name} は戦闘不能のため不発。(ただしAPは減衰する) 処理終了");

            return;
        }

        // TARGET_RANGE_ALLの場合はこの関数内ではメッセージは表示せず、呼び出し後に個別で処理する
        if ($target_range === TargetRange::Single->value || $target_range === TargetRange::Self->value) {
            $battle_logs_collection->push("{$opponent_data->name}のステータスが向上！");
        }

        // すでに存在しているバフを重複して付与した場合は、ターン数を伸ばしてreturnする
        foreach ($opponent_data->buffs as &$already_buff) {
            // 配列・オブジェクトどちらにも対応させるためキャスト
            // afterExecCommandCalculateBuff()の方ではバフをobjectにキャストしているが、今回はarrayの$new_buffの方に合わせてみる。
            // どっちがいいのかは判断が現状難しいので、また決める。
            $already_buff = (array) $already_buff;
            if ($already_buff['buffed_skill_id'] === $new_buff['buffed_skill_id']) {
                Debugbar::debug('既にバフが付与されているためターン数を更新します。');
                $already_buff['remaining_turn'] = $new_buff['remaining_turn']; // 新しいバフターン数で上書き

                return;
            }
        }

        // 同じ buffed_skill_id がなければ、新しいバフを追加
        Debugbar::debug('新しいバフ追加');
        $opponent_data->buffs[] = $new_buff;

    }

    /**
     * 指定した対象のバフをクリアする処理
     *
     * 攻撃で倒した時の相手側、自分が倒された時に付与されていたバフをリセットする処理
     * $collection[$index]もしくは, stdClass objectを渡す。
     */
    public static function clearBuff(object $player_or_enemy_data)
    {
        Debugbar::debug("clearBuff(): is_defeated_flag: {$player_or_enemy_data->is_defeated_flag} {$player_or_enemy_data->name}のバフをクリアします。");
        if ($player_or_enemy_data->is_defeated_flag === false) {
            Debugbar::debug('戦闘不能ではありませんでした。（本来はこの関数内では、trueであることが前提のため正しくない挙動です）');

            return;
        }
        $player_or_enemy_data->buffs = BattleData::BUFF_TEMPLATE;
        Debugbar::debug("戦闘不能となったので{$player_or_enemy_data->name}のバフをクリア。");
    }

    /**
     * コマンド実行処理後に呼び出す、バフのターンを1減らす関数
     *
     * ターンが0になった場合は、バフを削除する。SKILL, ITEM, DEFENCEのバフを考慮
     */
    public static function afterExecCommandCalculateBuff(
        Collection $battle_state_players_and_enemies_collection,
        Collection $battle_logs_collection
    ) {

        foreach ($battle_state_players_and_enemies_collection as $player_or_enemy_data) {
            $name = $player_or_enemy_data->name;

            // 戦闘不能時：すべてのバフを削除
            if ($player_or_enemy_data->is_defeated_flag === true) {
                Debugbar::debug("{$name}は戦闘不能のため全てのバフを削除しました");
                $player_or_enemy_data->buffs = [];

                continue;
            }

            $remaining_buffs = [];

            foreach ($player_or_enemy_data->buffs as $buff) {
                $buff = (object) $buff; // 配列・オブジェクトどちらにも対応させるためキャスト
                $buff_source = $buff->buffed_from ?? '';
                $remaining_turn = $buff->remaining_turn ?? 0;

                // 防御コマンドのバフは1ターンで消える
                if ($buff_source === 'DEFENCE') {
                    Debugbar::debug("{$name} 防御バフ解除。");

                    continue;
                }

                // 残りターンを減らす
                $buff->remaining_turn = max(0, $remaining_turn - 1);

                if ($buff->remaining_turn > 0) {
                    $remaining_buffs[] = $buff;

                    if ($buff_source === 'SKILL') {
                        Debugbar::debug("{$name}の バフ（スキル）【{$buff->buffed_skill_id}】{$buff->buffed_skill_name} : 残り {$buff->remaining_turn}ターン");
                    } elseif ($buff_source === 'ITEM') {
                        Debugbar::debug("{$name}の バフ（アイテム）【{$buff->buffed_item_id}】{$buff->buffed_item_name} : 残り {$buff->remaining_turn}ターン");
                    }
                } else {
                    // 効果が切れたバフのログ出力
                    $log_name = $buff->buffed_skill_name ?? $buff->buffed_item_name ?? 'バフ';
                    $battle_logs_collection->push("{$name}に付与されていた{$log_name}の効果が切れた。");
                    Debugbar::debug("{$name}のバフ {$log_name} が消えました。");
                }
            }

            // 更新後のバフ一覧を反映
            $player_or_enemy_data->buffs = $remaining_buffs;
        }
    }

    /**
     * 通常攻撃 (物理・単体)や物理スキルのダメージ計算
     *
     * 基礎計算式: damage² / (damage + def) ※ただし、多少のばらつきを入れる。
     */
    public static function calculatePhysicalDamage(int $pure_damage, int $opponent_def): int
    {
        Debugbar::debug("calculatePhysicalDamage(): --- pure_damage: {$pure_damage} 対象DEF: {$opponent_def}");

        // ゼロ除算対策
        if ($pure_damage <= 0) {
            return 0;
        }

        // 非線形のベースダメージ計算 atk² / (atk + def)
        $base_damage = $pure_damage * $pure_damage / ($pure_damage + $opponent_def);
        Debugbar::debug("{$pure_damage} x {$pure_damage} / ({$pure_damage} + {$opponent_def}) ");

        // ダメージにばらつきを加える（±10%）
        $variance_rate = random_int(95, 105) / 100;
        $final_damage = round($base_damage * $variance_rate); // ceilでなく、roundで0のケースが発生するようにする
        Debugbar::debug("計算結果: ダメージ = {$base_damage}, ばらつき = {$variance_rate}, 最終ダメージ: {$final_damage}");

        // ダメージが0の場合、確率で1ダメージにする
        // いわゆるメタル系の敵には、ダメージが足りない場合でも1ダメージ入るような形にできる
        if ($final_damage < 1) {
            $random = random_int(1, 100);
            $chance = 60; // 60%で1ダメージ
            if ($chance <= $random) {
                $final_damage = 1;
            } else {
                $final_damage = 0;
            }
            Debugbar::debug("基礎ダメージが0だったため、確率で1ダメージ。random: {$random} final_damage: {$final_damage}");
        }

        return $final_damage;
    }

    /**
     * 魔法攻撃 ,及びスキルのダメージ計算
     *
     * 基礎計算式: damage² / (damage + mdef) ※ただし、多少のばらつきを入れる。
     * 物理と同じなのでメソッドを統一してもいいが、今後の拡張性を持たせるために分割しておく
     */
    public static function calculateMagicDamage(int $pure_damage, int $opponent_mdef): int
    {
        Debugbar::debug("calculateMagicDamage(): --- pure_damage: {$pure_damage} 対象DEF: {$opponent_mdef}");

        // ゼロ除算対策
        if ($pure_damage <= 0) {
            return 0;
        }

        // 非線形のベースダメージ計算 atk² / (atk + mdef)
        $base_damage = $pure_damage * $pure_damage / ($pure_damage + $opponent_mdef);
        Debugbar::debug("{$pure_damage} x {$pure_damage} / ({$pure_damage} + {$opponent_mdef}) ");

        // ダメージにばらつきを加える（±10%）
        $variance_rate = random_int(95, 105) / 100;
        $final_damage = round($base_damage * $variance_rate); // ceilでなく、roundで0のケースが発生するようにする
        Debugbar::debug("計算結果: ダメージ = {$base_damage}, ばらつき = {$variance_rate}, 最終ダメージ: {$final_damage}");

        // ダメージが0の場合、確率で1ダメージにする
        // いわゆるメタル系の敵には、ダメージが足りない場合でも1ダメージ入るような形にできる
        if ($final_damage < 1) {
            $random = random_int(1, 100);
            $chance = 60; // 60%で1ダメージ
            if ($chance <= $random) {
                $final_damage = 1;
            } else {
                $final_damage = 0;
            }
            Debugbar::debug("基礎ダメージが0だったため、確率で1ダメージ。random: {$random} final_damage: {$final_damage}");
        }

        return $final_damage;
    }

    /**
     * 魔法防御力の計算
     */
    public static function calculateMagicDEFENCEValue(int $opponent_def, int $opponent_int): int
    {
        $mdef = ceil(($opponent_def * 0.25) + ($opponent_int * 0.75));
        Debugbar::debug("calculateMagicDEFENCEValue(): --- 魔法防御計算。DEF: {$opponent_def} INT: {$opponent_int} MDEF: {$mdef}");

        // cast float to int
        return (int) $mdef;
    }

    /**
     * $status_nameで指定したステータスの、基礎値 + バフで加算された値の合計値を返す。
     *
     * @return int
     */
    public static function calculateActualStatusValue(object $actor_or_opponent_data, string $status_name)
    {
        Debugbar::debug("calculateActualStatusValue(): --- {$actor_or_opponent_data->name}のバフ含めたステータス {$status_name} の計算");
        $buffed_status_name = 'buffed_'.$status_name;

        // 前提として、$actor_or_opponent_data->buffsが空配列[]ならそのままステータスの値を返す。
        if (empty($actor_or_opponent_data->buffs)) {
            Debugbar::debug('付与されているバフがないため、ステータス基準の値をそのまま返します。');

            return $actor_or_opponent_data->{'value_'.$status_name};
        }

        // 素の値を取得
        $actual_status_value = $actor_or_opponent_data->{'value_'.$status_name};

        // 各バフの合計値を加算する
        foreach ($actor_or_opponent_data->buffs as $buff) {
            $actual_status_value += (int) DataHelper::getValueFlexible($buff, $buffed_status_name, 0);
        }

        Debugbar::debug("バフを考慮した合計 {$status_name} : {$actual_status_value}");

        return $actual_status_value;
    }

    /**
     * 渡した配列が全滅済みかどうかの判定敵を全滅しているかどうかをboolで判定する
     *
     * @param  Collection  $player_or_enemy_collection  敵もしくは味方のCollection配列
     */
    public static function confirmDataIsAllDefeated(Collection $player_or_enemy_collection): bool
    {
        /** @var \stdClass $data */
        foreach ($player_or_enemy_collection as $data) {
            // 回しているデータに1つでもフラグが立っていなければ、falseを返せば良い
            if (! $data->is_defeated_flag) {
                return false;
            }
        }

        return true;
    }

    /**
     * player側がESCAPEが成功している状況であるかどうかをboolで返す。※現状playersにのみ有効
     */
    public static function isPlayerSuccessEscape(Collection $players_collection): bool
    {
        /** @var \stdClass $data */
        foreach ($players_collection as $data) {
            // パーティメンバーにESCAPEが成功している人物がいた場合、true。
            if ($data->is_escaped) {
                return true;
            }
        }

        return false;
    }
}

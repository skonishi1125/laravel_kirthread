<?php

namespace App\Models\Game\Rpg;

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

    public const ENEMY_DROPS_DEFAULT_DATA = [
        'money' => 0,
        'drop_item_id' => [],
        'drop_weapon_id' => [],
    ];

    // jsonデータに格納する、playersの基本要素
    public const PLAYERS_JSON_DEFAULT_DATA = [
        'id' => null,
        'role_id' => null,
        'name' => '',
        'command' => '',
        'target_player_index' => null, // exec時に格納する, スキルやアイテムで味方を対象とした場合のindexを格納する
        'target_enemy_index' => null, // exec時に格納する, 味方の攻撃対象とする敵のindex。
        'max_value_hp' => 0, // HP最大値
        'max_value_ap' => 0, // AP最大値
        'value_hp' => 0,
        'value_ap' => 0,
        'value_str' => 0,
        'value_def' => 0,
        'value_int' => 0,
        'value_spd' => 0,
        'value_luc' => 0,
        'level' => 0,
        'total_exp' => 0,
        'freely_status_point' => 0,
        'freely_skill_point' => 0,
        'skills' => 0,
        'selected_skill_id' => null, // exec時に格納する、選択したスキルのID
        'buffs' => self::BUFFS_DEFAULT_DATA,
        'role_portrait' => null,
        'is_defeated_flag' => false,
        'is_escaped' => false,
        'player_index' => null,
        'is_enemy' => false,
    ];

    public const ENEMIES_JSON_DEFAULT_DATA = [
        'id' => null,
        'name' => null,
        'command' => null, // exec時に格納する
        'target_player_index' => null, // exec時に格納する, 敵の攻撃対象とする味方のindex。
        'max_value_hp' => 0, // HP最大値
        'max_value_ap' => 0, // AP最大値
        'value_hp' => 0,
        'value_ap' => 0,
        'value_str' => 0,
        'value_def' => 0,
        'value_int' => 0,
        'value_spd' => 0,
        'value_luc' => 0,
        'portrait' => null,
        'buffs' => self::BUFFS_DEFAULT_DATA,
        'is_defeated_flag' => false,
        'is_escaped' => false,
        'enemy_index' => null, // 敵の並び。
        'is_enemy' => true, // 味方と敵で同じデータを呼んでいるので、敵フラグを立てておく
        'is_boss' => false,
        'exp' => 0,
        'drop_money' => 0,
    ];

    // jsonデータに格納する、buffsの基本要素
    public const BUFFS_DEFAULT_DATA = [
        'buffed_skill_id' => null,
        'buffed_item_id' => null,
        'buffed_skill_name' => null,
        'buffed_item_name' => null,
        'buffed_str' => 15,
        'buffed_def' => null,
        'buffed_int' => null,
        'buffed_spd' => null,
        'buffed_luc' => null,
        'remaining_turn' => 0,
        'buffed_from' => '', // 'DEFENSE', 'ITEM', 'SKILL'など、どのコマンドで付与されたものか。
    ];

    // 戦闘後に回復させるHPの倍率
    // 基本的にmaxHPの20%, maxAPの30%分回復させる。 戦闘不能の場合は半減。
    private const AFTER_CLEARED_RECOVERY_HP_MULTIPLIER = 0.20;

    private const AFTER_CLEARED_RECOVERY_AP_MULTIPLIER = 0.30;

    private const AFTER_CLEARED_RESURRECTION_HP_MULTIPLIER = 0.10;

    private const AFTER_CLEARED_RESURRECTION_AP_MULTIPLIER = 0.15;

    private const BASE_ESCAPE_CHANCE = 0.1; // 逃走の基礎成功率 （SPD 1ごとに、2%ずつ変化していく）

    /**
     * 戦闘初回時と、2回目以降のplayers_json_dataに格納する値を作成して返す。
     *
     * @return Collection
     */
    public static function createPlayersData(int $savedata_id, ?Collection $when_cleared_players_data = null)
    {
        Debugbar::debug('createPlayersData(): jsonのベースとなるplayers_data 作成。----------');
        $parties = Party::where('savedata_id', $savedata_id)->get();

        // id,name,curent_hp,current_apを配列ベースで格納するCollection
        // ステージクリア後に、HPとAPを引き継ぐために使用する
        // 0 => ["id" => 72,"name" => "パラ", "current_hp" => 50,"current_ap" => 15],  1 => [...], 2 => [...]
        $players_hp_and_ap_status = collect();

        // TODO: ->を['']を使った形に直す
        if (isset($when_cleared_players_data)) {
            Debugbar::debug('ステージクリア後の作成です。');
            foreach ($when_cleared_players_data as $player_index => $player_data) {
                Debugbar::debug("################# {$player_data->name} | クリア時点でのHP: {$player_data->value_hp} AP: {$player_data->value_ap}");

                $buffed_hp = $player_data->value_hp;
                $buffed_ap = $player_data->value_ap;
                if ($player_data->is_defeated_flag) {
                    Debugbar::debug('戦闘不能のため、最大HPの10%, 最大APの15%で回復させます。');
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
        } else {
            // 初回戦闘時
            Debugbar::debug('新規作成. (player_dataはCollection想定)');
            foreach ($parties as $player_index => $player_data) {
                $status = [
                    'id' => $player_data['id'],
                    'name' => $player_data['nickname'],
                    'current_hp' => $player_data['value_hp'],
                    'current_ap' => $player_data['value_ap'],
                ];
                $players_hp_and_ap_status->push($status);
            }
        }

        $players_data = collect();
        Debugbar::debug('players_json_data登録開始。');
        foreach ($parties as $player_index => $party) {
            Debugbar::debug("################# {$player_index} 人目 #################");
            // 会得スキルの取得 (Collection想定)
            $learned_skills = Skill::getLearnedSkill($party);
            $role_portrait = Role::where('id', $party['role_id'])->value('portrait_image_path');

            // vue側に渡すデータ
            $player_data = self::PLAYERS_JSON_DEFAULT_DATA;
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

            $players_data->push($player_data);
            DebugBar::debug("{$player_data['name']} 登録完了。");
        }

        return $players_data;

    }

    /**
     * 指定したフィールドID、及びステージIDから敵の情報を読み込んで返す。
     *
     * @return Collection
     */
    public static function createEnemiesData(int $field_id, int $stage_id)
    {
        Debugbar::debug('createEnemiesData(): jsonのベースとなるenemies_data 作成。----------');
        $enemies = collect();
        Debugbar::debug('敵のプリセットデータを読み込みます。----------');
        $preset_appearing_enemies = PresetAppearingEnemy::where('field_id', $field_id)
            ->where('stage_id', $stage_id)
            ->get();
        foreach ($preset_appearing_enemies as $preset) {
            $preset_enemy = Enemy::find($preset['enemy_id']);
            if ($preset_enemy) {
                for ($i = 0; $i < $preset['number']; $i++) {
                    $enemies->push($preset_enemy);
                }
            }
        }
        // Debugbar::debug([
        //     'preset_appearing_enemies' => $preset_appearing_enemies,
        //     'enemies' => $enemies,
        // ]);

        // $enemiesを加工して格納するためのCollection
        $enemies_data = collect();
        foreach ($enemies as $enemy_index => $enemy) {
            $enemy_data = self::ENEMIES_JSON_DEFAULT_DATA;
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

            $enemies_data->push($enemy_data);
        }

        Debugbar::debug($enemies_data);

        return $enemies_data;
    }

    /**
     * 指定したセーブデータIDから、アイテムの情報を読み込んで返す。
     *
     * 処理自体はItem::getBattleStateItemFromSavedataがほぼほぼ担っている。
     *
     * @return Collection
     */
    public static function createItemsData(int $savedata_id)
    {
        Debugbar::debug('createItemsData(): ------------');
        $items_data = Item::getBattleStateItemFromSavedata($savedata_id);
        Debugbar::debug($items_data);

        return $items_data;
    }

    /**
     * 作成した各データをDBに格納し、以降セッションIDで呼び出せるようにする。
     *
     * @return BattleState
     */
    public static function createBattleState(
        int $savedata_id,
        Collection $players_data, Collection $enemies_data, Collection $items_data, Collection $enemy_drops_data,
        int $field_id, int $stage_id
    ) {
        $session_id = Str::uuid()->toString();
        $created_battle_state = BattleState::create([
            'savedata_id' => $savedata_id,
            'session_id' => $session_id,
            'players_json_data' => json_encode($players_data),
            'items_json_data' => json_encode($items_data),
            'enemies_json_data' => json_encode($enemies_data),
            'enemy_drops_json_data' => json_encode($enemy_drops_data),
            'current_field_id' => $field_id,
            'current_stage_id' => $stage_id,
        ]);

        return $created_battle_state;

    }

    /**
     * 敵味方のデータの並びを、行動実行順に並べる
     *
     * 素早さや特殊スキル等を考慮させた順番とする
     * DEFENSE選択 > 特殊スキル選択 > 速度順
     *
     * @return Collection
     */
    public static function sortByBattleExec(Collection $battle_state_players_and_enemies_collection)
    {
        // 敵味方が同速の場合、現状は味方が優先される
        $sorted_data = $battle_state_players_and_enemies_collection->sort(function ($a, $b) {

            // 1. 'DEFENSE'コマンド選択
            if ($a->command === 'DEFENSE' && $b->command !== 'DEFENSE') {
                return -1; // $aが先に行動
            }
            if ($b->command === 'DEFENSE' && $a->command !== 'DEFENSE') {
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

    /**
     * コマンド実行処理
     *
     * コマンドの処理に移る前に、下記内容を確認する
     * 【敵/味方の行動か, 行動者は戦闘不能状態ではないか, 敵/味方は全滅していないか】
     *  &$battle_state_items_collection: 通常これはjsonの$current_items_dataを使用する想定
     *  こちらのメソッド上で使った数に応じて配列の加工を行なっているため、参照渡しとすることで反映させている
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
                        // 対象が味方の場合があるので、$opponents_dataとして定義する
                        $opponents_data = collect();
                        if (($actor_data->target_enemy_index !== null)) {
                            Debugbar::debug('target_enemy_indexが入っているので敵グループを対象として格納。');
                            $opponents_data = $battle_state_enemies_collection;
                            $opponents_index = $actor_data->target_enemy_index;
                        } elseif (($actor_data->target_player_index !== null)) {
                            Debugbar::debug('target_player_indexが入っているので味方グループを対象として選択。');
                            $opponents_data = $battle_state_players_collection;
                            $opponents_index = $actor_data->target_player_index;
                        } else {
                            // 敵味方ともに対象のindexが格納されていないなら、範囲系のスキル。
                            // それぞれ$opponents_dataに条件に合うデータを格納。
                            // 攻撃系スキルなら敵を, 回復またはバフ系スキルなら味方を入れる。
                            // 範囲技の場合は$opponents_indexは格納せず、nullのままとする。
                            $opponents_index = null;
                            Debugbar::debug('target_player_indexが格納されていないため、範囲系のスキルが選択されました。');

                            switch ($actor_data->selected_skill_effect_type) {
                                case Skill::EFFECT_SPECIAL_TYPE:
                                    // ----------------- 特殊系スキル(分岐が難しいので、個別に対象処理をする) -----------------
                                    Debugbar::debug('特殊系範囲スキル。');
                                    if ($actor_data->selected_skill_id == 31) {
                                        Debugbar::debug("ワイドガード: 味方情報をopponents_dataに格納。effect_type: {$actor_data->selected_skill_effect_type}");
                                        $opponents_data = $battle_state_players_collection;
                                    }
                                    break;
                                case Skill::EFFECT_DAMAGE_TYPE:
                                    Debugbar::debug("攻撃系範囲スキルのため敵情報をopponents_dataに格納。effect_type: {$actor_data->selected_skill_effect_type}");
                                    $opponents_data = $battle_state_enemies_collection;
                                    break;
                                case Skill::EFFECT_HEAL_TYPE:
                                    Debugbar::debug("回復系範囲スキルのため味方情報をopponents_dataに格納。effect_type: {$actor_data->selected_skill_effect_type}");
                                    $opponents_data = $battle_state_players_collection;
                                    break;
                                case Skill::EFFECT_BUFF_TYPE:
                                    // TODO: デバフを採用するならさらに分岐して、敵データを入れる。
                                    Debugbar::debug("バフ系範囲スキルのため味方情報をopponents_dataに格納。effect_type: {$actor_data->selected_skill_effect_type}");
                                    $opponents_data = $battle_state_players_collection;
                                    break;
                            }
                        }
                        self::execCommandSkill($actor_data, $opponents_data, false, $opponents_index, $battle_logs_collection);
                        break;
                    case 'ITEM':
                        // 選択したアイテムの情報を$battle_state_items_collectionから取得する
                        $selected_item = $battle_state_items_collection->firstWhere('id', $actor_data->selected_item_id);
                        // 選択アイテムが無い場合、スキップする(残り2個のアイテムを3人選択していた場合など)
                        if (is_null($selected_item)) {
                            $battle_logs_collection->push("{$actor_data->name}はアイテムを使おうと試みたが、手持ちには用意がなかった！");
                            break;
                        }
                        Debugbar::debug([
                            'message' => "【ITEM】選択アイテムID: {$selected_item->id},  {$selected_item->name}",
                            'selected_item' => $selected_item,
                        ]);

                        // 対象決定処理 (SKILLと同じなので、統一化できそう。)
                        $opponents_data = collect();
                        if (($actor_data->target_enemy_index !== null)) {
                            Debugbar::debug('【ITEM】target_enemy_indexが入っているので敵グループを対象として格納。');
                            $opponents_data = $battle_state_enemies_collection;
                            $opponents_index = $actor_data->target_enemy_index;
                        } elseif (($actor_data->target_player_index !== null)) {
                            Debugbar::debug('【ITEM】target_player_indexが入っているので味方グループを個別対象として選択。');
                            $opponents_data = $battle_state_players_collection;
                            $opponents_index = $actor_data->target_player_index;
                        } else {
                            // 敵味方ともに対象のindexが格納されていないなら、範囲系のアイテム
                            // それぞれ$opponents_dataに条件に合うデータを格納。
                            // 攻撃系の全体攻撃アイテムなら敵を, 回復またはバフ系の範囲アイテムなら味方を入れる。
                            // 範囲技の場合は$opponents_indexは格納せず、nullのままとする。
                            $opponents_index = null;
                            Debugbar::debug('【ITEM】target_player_indexが格納されていないため、範囲系のアイテムが選択されました。');

                            switch ($selected_item->effect_type) {
                                case Item::EFFECT_SPECIAL_TYPE:
                                    // ----------------- 特殊系スキル(分岐が難しいので、個別に対象処理をする) -----------------
                                    // 今のところ実装なし
                                    Debugbar::debug('特殊系範囲アイテム');
                                    break;
                                case Item::EFFECT_DAMAGE_TYPE:
                                    Debugbar::debug("攻撃系範囲アイテムのため敵情報をopponents_dataに格納。effect_type: {$selected_item->effect_type}");
                                    $opponents_data = $battle_state_enemies_collection;
                                    break;
                                case Item::EFFECT_HEAL_TYPE:
                                    Debugbar::debug("回復系範囲アイテムのため味方情報をopponents_dataに格納。effect_type: {$selected_item->effect_type}");
                                    $opponents_data = $battle_state_players_collection;
                                    break;
                                case Item::EFFECT_BUFF_TYPE:
                                    // TODO: デバフを採用するならさらに分岐して、敵データを入れる。
                                    Debugbar::debug("バフ系範囲アイテムのため味方情報をopponents_dataに格納。effect_type: {$selected_item->effect_type}");
                                    $opponents_data = $battle_state_players_collection;
                                    break;
                            }
                        }
                        self::execCommandItem($actor_data, $opponents_data, false, $opponents_index, $selected_item, $battle_logs_collection);

                        // アイテムの数を減らす
                        Debugbar::debug('アイテム処理完了。所持数調整....');
                        $selected_item_id = $selected_item->id;
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
                        // &$item_dataと参照渡しとしているので、フィルタリング結果は元々の$current_battle_state_items_collectionと同期する
                            ->filter();
                        Debugbar::debug($battle_state_items_collection);

                        break;
                    case 'DEFENSE':
                        // 防御というバフを1ターン、150%の補正でかけておく
                        Debugbar::debug("【防御】使用者: {$actor_data->name} ");
                        $buffs = [
                            // 10の場合、+5されて合計15になる
                            'buffed_def' => ceil((int) $actor_data->value_def * 0.5),
                            'remaining_turn' => 1,
                            'buffed_from' => 'DEFENSE',
                        ];
                        $actor_data->buffs[] = $buffs;
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
                        $battle_logs_collection->push("【ATTACK】{$actor_data->name} ");
                        self::execCommandAttack($actor_data, $battle_state_players_collection, true, $index, $battle_logs_collection);
                        break;
                    case 'SKILL':
                        $battle_logs_collection->push("【SKILL】{$actor_data->name} ");
                        break;
                    case 'ITEM':
                        // 実装予定はないが、使う想定をしておく
                        $battle_logs_collection->push("【ITEM】{$actor_data->name} ");
                        break;
                    case 'DEFENSE':
                        // 実装予定はないが、使う想定をしておく
                        $battle_logs_collection->push("【DEFENSE】{$actor_data->name} ");
                        break;
                    case 'ESCAPE':
                        // 実装予定はないが、使う想定をしておく
                        $battle_logs_collection->push("【ESCAPE】{$actor_data->name} ");
                        break;
                    default:
                        $battle_logs_collection->push("【debug】{$actor_data->name} 無効なコマンドです。");
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
            // ATTACK時のダメージ計算
            $pure_damage = self::calculateActualStatusValue($actor_data, 'str');

            Debugbar::debug("【味方】STRの実数値。（ATTACKの場合、これが純粋なダメージ量となる。） : {$pure_damage}");
            // 単体・物理攻撃として扱う
            self::storePartyDamage(
                'ATTACK', $actor_data, $battle_state_opponents_collection, null, $opponents_index, $battle_logs_collection, $pure_damage, Skill::TARGET_RANGE_SINGLE, Skill::ATTACK_PHYSICAL_TYPE
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
            // ATTACK時のダメージ計算 相手の防御力などは後で考慮する
            $damage = self::calculateActualStatusValue($actor_data, 'str');
            Debugbar::warning("（敵）純粋なダメージ量(STRの値。) : {$damage}");

            // 画面に表示するログの記録
            self::storeEnemyDamage(
                'ATTACK', $actor_data, $battle_state_opponents_collection, $opponents_index, $battle_logs_collection, $damage
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
        object $self_data, Collection $opponents_data, bool $is_enemy, ?int $opponents_index, Collection $logs
    ) {
        Debugbar::debug('execCommandSkill(): ----------------------');
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

    /*
     コマンドとして"ITEM"を選択した時の処理
     * $self_data: 行動実行するキャラクター/敵のデータ ※現状アイテムは味方だけしか使えないため、基本味方データが入る。
     * $opponent_data: 対象とするキャラクター/敵のデータ
     * $opponents_index:
        対象とするキャラクター/敵のインデックス。 真ん中の味方に向けた場合は[1]などが入る
        全体攻撃スキルを使った場合, $opponents_indexはnullであるため許容しておく (?int)
     * $selected_item: items_json_dataからfirstWhereで絞り込んで取得したので、Object(stdClass)である
    */
    private static function execCommandItem(
        object $self_data, Collection $opponents_data, bool $is_enemy,
        ?int $opponents_index, object $selected_item, Collection $logs
    ) {
        Debugbar::debug([
            'message' => 'execCommandItem(): ---------------------- ',
            'class' => get_class($selected_item),
        ]);

        // 味方の場合※ただし、アイテムを使えるのは現状味方だけの想定であるが。
        if ($is_enemy == false) {
            $damage = null;
            $heal_point = null;
            $buffs = null;

            $logs->push("{$self_data->name}は{$selected_item->name}を使った！");

            switch ($selected_item->effect_type) {
                case Item::EFFECT_SPECIAL_TYPE:
                    Debugbar::debug('特殊系アイテム※現状考えていない。');
                    break;
                case Item::EFFECT_DAMAGE_TYPE:
                    Debugbar::debug('攻撃系アイテム');
                    if (! $selected_item->is_percent_based) {
                        $damage = $selected_item->fixed_value;
                    } else {
                        // 攻撃系倍率系のアイテムの場合※現状実装はしていない
                    }
                    BattleState::storePartyDamage(
                        'ITEM', $self_data, $opponents_data, $selected_item, $opponents_index, $logs, $damage, $selected_item->target_range, $selected_item->attack_type
                    );
                    break;
                case Item::EFFECT_HEAL_TYPE:
                    if (! $selected_item->is_percent_based) {
                        $heal_point = $selected_item->fixed_value;
                    }
                    // 倍率系アイテムの場合はnullが入るがstorePartyHeal側で向こうの体力と合わせて計算する
                    Debugbar::debug('回復系アイテム');
                    BattleState::storePartyHeal(
                        'ITEM', $self_data, $opponents_data,
                        $opponents_index, $logs, $heal_point, $selected_item->target_range, $selected_item->percent, $selected_item->heal_type
                    );
                    break;
                case Item::EFFECT_BUFF_TYPE:
                    // バフは個別に処理
                    switch ($selected_item->id) {
                        case 21:
                            Debugbar::debug('アタックドロップ');
                            $buffs = [
                                'buffed_skill_id' => null,
                                'buffed_item_id' => $selected_item->id,
                                'buffed_skill_name' => null,
                                'buffed_item_name' => $selected_item->name,
                                'buffed_str' => $selected_item->fixed_value,
                                'remaining_turn' => $selected_item->buff_turn,
                                'buffed_from' => 'ITEM',
                            ];
                            break;
                        case 22:
                            Debugbar::debug('アタックミスト');
                            $buffs = [
                                'buffed_skill_id' => null,
                                'buffed_item_id' => $selected_item->id,
                                'buffed_skill_name' => null,
                                'buffed_item_name' => $selected_item->name,
                                'buffed_str' => $selected_item->fixed_value,
                                'remaining_turn' => $selected_item->buff_turn,
                                'buffed_from' => 'ITEM',
                            ];
                            break;
                    }

                    BattleState::storePartyBuff(
                        'ITEM', $self_data, $opponents_data, $opponents_index, $logs, $buffs, $selected_item->target_range
                    );
                    break;
            }
        }
        // execBattleCommandに戻る
    }

    /**
     * コマンドを実行した際、画面に表示させるダメージなどのログ入力
     *
     * @param  ?object  $selected_item  $commandがITEMの場合、そのアイテムの情報
     * @param  ?int  $pure_damage  敵の守備力などを考慮しない、純粋なダメージ量 nullのケースがある(ITEMの%依存の攻撃など)
     * @param  int  $target_range  コマンド対象範囲 TARGET_RANGE_SINGLE 等
     * @param  int  $attack_type  コマンド攻撃タイプ Skill::ATTACK_PHYSICAL_TYPEや、Item::ATTACK_PHYSICAL_TYPE 等
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
                if ($target_range === Skill::TARGET_RANGE_SINGLE) {
                    Debugbar::debug('単体攻撃。');

                    // ダメージ計算 物理か魔法攻撃かで変える
                    if ($attack_type === Skill::ATTACK_PHYSICAL_TYPE) {
                        Debugbar::debug('物理。');
                        $calculated_damage = self::calculatePhysicalDamage(
                            $pure_damage,
                            self::calculateActualStatusValue($battle_state_opponents_collection[$opponents_index], 'def')
                        );
                    } elseif ($attack_type === Skill::ATTACK_MAGIC_TYPE) {
                        Debugbar::debug('魔法。');
                        $opponent_mdef = self::calculateMagicDefenseValue(
                            self::calculateActualStatusValue($battle_state_opponents_collection[$opponents_index], 'def'),
                            self::calculateActualStatusValue($battle_state_opponents_collection[$opponents_index], 'int')
                        );
                        $calculated_damage = self::calculateMagicDamage($pure_damage, $opponent_mdef);
                    }

                    if ($calculated_damage > 0) {
                        Debugbar::debug("【SKILL】ダメージが1以上。敵の現在体力: {$battle_state_opponents_collection[$opponents_index]->value_hp}");
                        $battle_state_opponents_collection[$opponents_index]->value_hp -= $calculated_damage;
                        Debugbar::debug("攻撃した。敵の残り体力: {$battle_state_opponents_collection[$opponents_index]->value_hp}");
                        // 敵を倒した場合
                        if ($battle_state_opponents_collection[$opponents_index]->value_hp <= 0) {
                            $battle_state_opponents_collection[$opponents_index]->value_hp = 0; // マイナスになるのを防ぐ。
                            $battle_state_opponents_collection[$opponents_index]->is_defeated_flag = true;
                            self::clearBuff($battle_state_opponents_collection[$opponents_index]);
                            $battle_logs_collection->push("{$battle_state_opponents_collection[$opponents_index]->name}に{$calculated_damage}のダメージ！");
                            $battle_logs_collection->push("{$battle_state_opponents_collection[$opponents_index]->name}を倒した！");
                            Debugbar::debug("{$battle_state_opponents_collection[$opponents_index]->name}を倒した。敵の残り体力: {$battle_state_opponents_collection[$opponents_index]->value_hp} 敵討伐フラグ: {$battle_state_opponents_collection[$opponents_index]->is_defeated_flag} ");
                        } else {
                            $battle_logs_collection->push("{$battle_state_opponents_collection[$opponents_index]->name}に{$calculated_damage}のダメージ！");
                            Debugbar::debug("{$battle_state_opponents_collection[$opponents_index]->name}はまだ生存している。敵の残り体力: {$battle_state_opponents_collection[$opponents_index]->value_hp} 敵討伐フラグ: {$battle_state_opponents_collection[$opponents_index]->is_defeated_flag} ");
                        }
                    } else {
                        // ダメージを与えられなかった場合
                        Debugbar::debug('ダメージを与えられない。');
                        $battle_logs_collection->push("しかし{$battle_state_opponents_collection[$opponents_index]->name}にダメージは与えられなかった！");
                        Debugbar::debug("攻撃が通らなかった。{$battle_state_opponents_collection[$opponents_index]->name}は当然生存している。敵の残り体力: {$battle_state_opponents_collection[$opponents_index]->value_hp} 敵討伐フラグ: {$battle_state_opponents_collection[$opponents_index]->is_defeated_flag} ");
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
                        if ($attack_type === Skill::ATTACK_PHYSICAL_TYPE) {
                            Debugbar::debug('物理。');
                            $calculated_damage = self::calculatePhysicalDamage(
                                $base_damage,
                                self::calculateActualStatusValue($opponent_data, 'def')
                            );
                        } elseif ($attack_type === Skill::ATTACK_MAGIC_TYPE) {
                            Debugbar::debug('魔法。');
                            $opponent_mdef = self::calculateMagicDefenseValue(
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
                if ($target_range === Item::TARGET_RANGE_SINGLE) {
                    Debugbar::debug('単体攻撃。');

                    // is_percent_basedのアイテムの場合は、相手の現在体力に合わせたダメージを与える
                    if ($pure_damage == null || $selected_item->is_percent_based) {
                        $calculated_damage = ceil($battle_state_opponents_collection[$opponents_index]->value_hp * $selected_item->percent);
                    } else {
                        // ダメージ計算 物理か魔法攻撃かで変える
                        if ($attack_type === Item::ATTACK_PHYSICAL_TYPE) {
                            Debugbar::debug('物理。');
                            $calculated_damage = self::calculatePhysicalDamage(
                                $pure_damage,
                                self::calculateActualStatusValue($battle_state_opponents_collection[$opponents_index], 'def')
                            );
                        } elseif ($attack_type === Item::ATTACK_MAGIC_TYPE) {
                            Debugbar::debug('魔法。');
                            $opponent_mdef = self::calculateMagicDefenseValue(
                                self::calculateActualStatusValue($battle_state_opponents_collection[$opponents_index], 'def'),
                                self::calculateActualStatusValue($battle_state_opponents_collection[$opponents_index], 'int')
                            );
                            $calculated_damage = self::calculateMagicDamage(
                                $pure_damage,
                                $opponent_mdef
                            );
                        }
                    }

                    if ($calculated_damage > 0) {
                        Debugbar::debug("【ITEM】ダメージが1以上。敵の現在体力: {$battle_state_opponents_collection[$opponents_index]->value_hp}");
                        $battle_state_opponents_collection[$opponents_index]->value_hp -= $calculated_damage;
                        Debugbar::debug("アイテムで攻撃した。敵の残り体力: {$battle_state_opponents_collection[$opponents_index]->value_hp}");
                        // 敵を倒した場合
                        if ($battle_state_opponents_collection[$opponents_index]->value_hp <= 0) {
                            $battle_state_opponents_collection[$opponents_index]->value_hp = 0; // マイナスになるのを防ぐ。
                            $battle_state_opponents_collection[$opponents_index]->is_defeated_flag = true;
                            self::clearBuff($battle_state_opponents_collection[$opponents_index]);
                            $battle_logs_collection->push("{$battle_state_opponents_collection[$opponents_index]->name}に{$calculated_damage}のダメージ！");
                            $battle_logs_collection->push("{$battle_state_opponents_collection[$opponents_index]->name}を倒した！");
                            Debugbar::debug("{$battle_state_opponents_collection[$opponents_index]->name}を倒した。敵の残り体力: {$battle_state_opponents_collection[$opponents_index]->value_hp} 敵討伐フラグ: {$battle_state_opponents_collection[$opponents_index]->is_defeated_flag} ");
                        } else {
                            $battle_logs_collection->push("{$battle_state_opponents_collection[$opponents_index]->name}に{$calculated_damage}のダメージ！");
                            Debugbar::debug("{$battle_state_opponents_collection[$opponents_index]->name}はまだ生存している。敵の残り体力: {$battle_state_opponents_collection[$opponents_index]->value_hp} 敵討伐フラグ: {$battle_state_opponents_collection[$opponents_index]->is_defeated_flag} ");
                        }
                        // ダメージを与えられなかった場合
                    } else {
                        Debugbar::debug('ダメージを与えられない。');
                        $battle_logs_collection->push("しかし{$battle_state_opponents_collection[$opponents_index]->name}にダメージは与えられなかった！");
                        Debugbar::debug("攻撃が通らなかった。{$battle_state_opponents_collection[$opponents_index]->name}は当然生存している。敵の残り体力: {$battle_state_opponents_collection[$opponents_index]->value_hp} 敵討伐フラグ: {$battle_state_opponents_collection[$opponents_index]->is_defeated_flag} ");
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

                        // is_percent_basedのアイテムの場合は、相手の現在体力に合わせたダメージを与える
                        if ($base_damage == null || $selected_item->is_percent_based) {
                            $calculated_damage = ceil($opponent_data->value_hp * $selected_item->percent);
                        } else {
                            // ダメージ計算 物理か魔法攻撃かで変える
                            if ($attack_type == Skill::ATTACK_PHYSICAL_TYPE) {
                                Debugbar::debug('物理。');
                                $calculated_damage = self::calculatePhysicalDamage(
                                    $base_damage,
                                    self::calculateActualStatusValue($opponent_data, 'def')
                                );
                            } elseif ($attack_type == Skill::ATTACK_MAGIC_TYPE) {
                                Debugbar::debug('魔法。');
                                $opponent_mdef = self::calculateMagicDefenseValue(
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
     * スキルまたはアイテムを使用しての回復処理
     */
    public static function storePartyHeal(
        string $command, object $self_data, Collection $opponents_data,
        ?int $opponents_index, Collection $logs, ?int $heal_point, int $target_range, int|float|null $percent, ?int $heal_type
    ) {
        switch ($command) {
            case 'SKILL':
                Debugbar::debug('storePartyHeal(): SKILL ------------------------------');
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
            case 'ITEM':
                Debugbar::debug('storePartyHeal(): ITEM ------------------------------');

                if ($target_range == Item::TARGET_RANGE_SINGLE) {
                    Debugbar::debug("【単体回復】回復量: {$heal_point} 使用者: {$self_data->name} 対象者: {$opponents_data[$opponents_index]->name}");
                    // 戦闘不能ならスキップ
                    if ($opponents_data[$opponents_index]->is_defeated_flag == true) {
                        $logs->push("しかし{$opponents_data[$opponents_index]->name}は戦闘不能のため効果が無かった！");
                    } else {
                        switch ($heal_type) {
                            case Item::HEAL_HP_TYPE:
                                Debugbar::debug('HP回復アイテム');
                                // % 回復系のアイテムなら、対象者の体力を参考に改めて回復量を決める
                                if (is_null($heal_point)) {
                                    $heal_point = ceil($opponents_data[$opponents_index]->max_value_hp * $percent);
                                    Debugbar::debug("回復量nullのため、percentを参照。回復量:  {$heal_point} ");
                                }
                                $opponents_data[$opponents_index]->value_hp += $heal_point;
                                if ($opponents_data[$opponents_index]->value_hp > $opponents_data[$opponents_index]->max_value_hp) {
                                    $opponents_data[$opponents_index]->value_hp = $opponents_data[$opponents_index]->max_value_hp;
                                }
                                $logs->push("{$opponents_data[$opponents_index]->name}のHPが{$heal_point}ポイント回復！");
                                break;

                            case Item::HEAL_AP_TYPE:
                                Debugbar::debug('AP回復アイテム');
                                // % 回復系のアイテムなら、対象者の体力を参考に改めて回復量を決める
                                if (is_null($heal_point)) {
                                    $heal_point = ceil($opponents_data[$opponents_index]->max_value_ap * $percent);
                                    Debugbar::debug("回復量nullのため、percentを参照。回復量:  {$heal_point} ");
                                }
                                $opponents_data[$opponents_index]->value_ap += $heal_point;
                                if ($opponents_data[$opponents_index]->value_ap > $opponents_data[$opponents_index]->max_value_ap) {
                                    $opponents_data[$opponents_index]->value_ap = $opponents_data[$opponents_index]->max_value_ap;
                                }
                                $logs->push("{$opponents_data[$opponents_index]->name}のAPが{$heal_point}ポイント回復！");
                                break;
                        }
                    }

                } elseif ($target_range == Item::TARGET_RANGE_ALL) {
                    Debugbar::debug("【全体回復】回復量: {$heal_point} 使用者: {$self_data->name}");
                    foreach ($opponents_data as $opponent_data) {
                        $calculated_heal_point = $heal_point;
                        // 戦闘不能ならスキップ
                        if ($opponent_data->is_defeated_flag == true) {
                            Debugbar::debug("{$opponent_data->name}は戦闘不能のため回復対象としません。");
                        } else {

                            switch ($heal_type) {
                                case Item::HEAL_HP_TYPE:
                                    // % 回復系のアイテムなら、対象者の体力を参考に改めて回復量を決める
                                    if (is_null($heal_point)) {
                                        $calculated_heal_point = ceil($opponent_data->max_value_hp * $percent);
                                        Debugbar::debug("回復量nullのため、percentを参照。回復量:{$calculated_heal_point} ");
                                    }
                                    $opponent_data->value_hp += $calculated_heal_point;
                                    if ($opponent_data->value_hp > $opponent_data->max_value_hp) {
                                        $opponent_data->value_hp = $opponent_data->max_value_hp;
                                    }
                                    Debugbar::debug("{$opponent_data->name}のHPを{$calculated_heal_point}ポイント回復。");
                                    $logs->push("{$opponent_data->name}のHPを{$calculated_heal_point}ポイント回復！");
                                    break;
                                case Item::HEAL_AP_TYPE:
                                    // % 回復系のアイテムなら、対象者の体力を参考に改めて回復量を決める
                                    if (is_null($heal_point)) {
                                        $calculated_heal_point = ceil($opponent_data->max_value_ap * $percent);
                                        Debugbar::debug("回復量nullのため、percentを参照。回復量: {$calculated_heal_point} ");
                                    }
                                    $opponent_data->value_ap += $calculated_heal_point;
                                    if ($opponent_data->value_ap > $opponent_data->max_value_ap) {
                                        $opponent_data->value_ap = $opponent_data->max_value_ap;
                                    }
                                    Debugbar::debug("{$opponent_data->name}のAPを{$calculated_heal_point}ポイント回復。");
                                    $logs->push("{$opponent_data->name}のAPを{$calculated_heal_point}ポイント回復！");
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
     */
    public static function storePartyBuff(
        string $command, object $self_data, Collection $opponents_data,
        ?int $opponents_index, Collection $logs, array $new_buff, int $target_range
    ) {
        switch ($command) {
            case 'SKILL':
                Debugbar::debug('バフスキル発動。');
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
                            $already_buff = (array) $already_buff;
                            Debugbar::debug($new_buff['buffed_skill_id']); // 型キャストチェック
                            Debugbar::debug($already_buff['buffed_skill_id']); // 型キャストチェック

                            if ($already_buff['buffed_skill_id'] === $new_buff['buffed_skill_id']) {
                                Debugbar::debug('既にバフが付与されているためターン数を更新します。');
                                $already_buff['remaining_turn'] = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                                $buff_exists = true;
                                break;
                            }
                        }

                        // 同じ buffed_skill_id がなければ、新しいバフを追加
                        if (! $buff_exists) {
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
                                $already_buff = (array) $already_buff;
                                if ($already_buff['buffed_skill_id'] === $new_buff['buffed_skill_id']) {
                                    Debugbar::debug('既にバフが付与されているためターン数を更新します。');
                                    $already_buff['remaining_turn'] = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                                    $buff_exists = true;
                                    break;
                                }
                            }

                            // 同じ buffed_skill_id がなければ、新しいバフを追加
                            if (! $buff_exists) {
                                Debugbar::debug('新しいバフ追加');
                                $opponent_data->buffs[] = $new_buff;
                            }

                        }
                    }

                    $logs->push('全員の特定の能力値が向上！');
                } elseif ($target_range == Skill::TARGET_RANGE_SELF) {
                    // 自分に付与する
                    Debugbar::debug("【自分自身へのバフ】使用者: {$self_data->name} 使用スキルID: {$new_buff['buffed_skill_id']}");
                    // 同じバフがかかっているかどうかをチェック
                    Debugbar::debug($self_data->buffs);
                    $buff_exists = false;

                    // $new_buffの方は配列なので['']で呼ばないとエラーになる
                    foreach ($self_data->buffs as &$already_buff) {
                        $already_buff = (array) $already_buff;
                        if ($already_buff['buffed_skill_id'] === $new_buff['buffed_skill_id']) {
                            Debugbar::debug('既にバフが付与されているためターン数を更新します。');
                            $already_buff['remaining_turn'] = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                            $buff_exists = true;
                            break;
                        }
                    }

                    // 同じ buffed_skill_id がなければ、新しいバフを追加
                    if (! $buff_exists) {
                        Debugbar::debug('新しいバフ追加');
                        $self_data->buffs[] = $new_buff;
                    }

                    $logs->push("{$self_data->name}のステータスが向上！");
                }
                break;
            case 'ITEM':
                Debugbar::debug('バフアイテム使用。');
                if ($target_range == Item::TARGET_RANGE_SINGLE) {
                    Debugbar::debug("【単体バフ】使用者: {$self_data->name} 対象者: {$opponents_data[$opponents_index]->name} 使用アイテムID: {$new_buff['buffed_item_id']}");

                    // 戦闘不能ならスキップ
                    if ($opponents_data[$opponents_index]->is_defeated_flag == true) {
                        $logs->push("しかし{$opponents_data[$opponents_index]->name}は戦闘不能のため効果が無かった！");
                    } else {
                        // 同じバフがかかっているかどうかをチェック
                        Debugbar::debug($opponents_data[$opponents_index]->buffs);
                        $buff_exists = false;

                        // $new_buffの方は配列なので['']で呼ばないとエラーになる
                        foreach ($opponents_data[$opponents_index]->buffs as &$already_buff) {
                            $already_buff = (array) $already_buff;
                            Debugbar::debug($new_buff['buffed_item_id']); // 型キャストチェック
                            Debugbar::debug($already_buff['buffed_item_id']); // 型キャストチェック

                            if ($already_buff['buffed_item_id'] === $new_buff['buffed_item_id']) {
                                Debugbar::debug('既にバフが付与されているためターン数を更新します。');
                                $already_buff['remaining_turn'] = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                                $buff_exists = true;
                                break;
                            }
                        }

                        // 同じ buffed_skill_id がなければ、新しいバフを追加
                        if (! $buff_exists) {
                            Debugbar::debug('新しいバフ追加');
                            $opponents_data[$opponents_index]->buffs[] = $new_buff;
                        }

                        $logs->push("{$opponents_data[$opponents_index]->name}のステータスが向上！");

                    }

                } elseif ($target_range == Item::TARGET_RANGE_ALL) {
                    // $opponents_dataに対象が全て入っているはずなので、それで回復を回すと良い
                    Debugbar::debug("【全体バフ】使用者: {$self_data->name} 使用アイテムID: {$new_buff['buffed_item_id']}");
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
                                $already_buff = (array) $already_buff;
                                if ($already_buff['buffed_item_id'] === $new_buff['buffed_item_id']) {
                                    Debugbar::debug('既にバフが付与されているためターン数を更新します。');
                                    $already_buff['remaining_turn'] = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                                    $buff_exists = true;
                                    break;
                                }
                            }

                            // 同じ buffed_item_id がなければ、新しいバフを追加
                            if (! $buff_exists) {
                                Debugbar::debug('新しいバフ追加');
                                $opponent_data->buffs[] = $new_buff;
                            }
                        }
                    }
                    $logs->push('全員の特定の能力値が向上！');
                }
                break;
            default:
                break;
        }
    }

    /**
     * パラ の ワイドガード など、固有の能力を持つスキル処理。
     *
     * switch文で独自の処理を書いて対応する。
     */
    public static function storePartySpecialSkill(
        object $self_data, object $opponents_data, ?int $opponents_index,
        Collection $logs, array $new_buff, object $selected_skill
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
                                Debugbar::debug('既にバフが付与されているためターン数を更新します。');
                                $already_buff->remaining_turn = $new_buff['remaining_turn']; // 新しいバフターン数で上書き
                                $buff_exists = true;
                                break;
                            }
                        }
                        // 同じ buffed_skill_id がなければ、新しいバフを追加
                        if (! $buff_exists) {
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
        $player_or_enemy_data->buffs = self::BUFFS_DEFAULT_DATA;
        Debugbar::debug("戦闘不能となったので{$player_or_enemy_data->name}のバフをクリア。");
    }

    /**
     * コマンド実行処理後に呼び出す、バフのターンを1減らす関数
     *
     * TODO: 現状バグってるので改修 !!!! buffのリファクタリングの時に。
     */
    public static function afterExecCommandCalculateBuff(
        Collection $battle_state_players_and_enemies_collection,
        Collection $battle_logs_collection
    ) {
        foreach ($battle_state_players_and_enemies_collection as $player_or_enemy_data) {
            // 戦闘不能の場合は全てのバフを解除する
            // TODO: 戦闘不能→生き返りの挙動が今後あった時、一度リセットする必要がある
            // そちらには現状対応しておらずバフが続いてしまうので、必要なら戦闘不能になった瞬間にバフを外す修正をする。
            // (→と思ったが、もしかしてclearBuff()でやってる？)
            if ($player_or_enemy_data->is_defeated_flag == true) {
                Debugbar::debug("{$player_or_enemy_data->name}は戦闘不能のため全てのバフを削除しました");
                $player_or_enemy_data->buffs = self::BUFFS_DEFAULT_DATA;

                continue;
            }

            // バフに対して1つずつ付与されているバフのターン数を削っていく
            foreach ($player_or_enemy_data->buffs as $buff) {
                // $buff = (object) $buff; // -> で値を呼べるようにオブジェクトにキャストしとく
                Debugbar::debug([
                    'data' => $player_or_enemy_data,
                    'buff' => $buff,
                ]);
                $buff->remaining_turn -= 1;
                switch ($buff->buffed_from) {
                    case 'SKILL':
                        if ($buff->remaining_turn > 0) {
                            Debugbar::debug("{$player_or_enemy_data->name}の バフ 【{$buff->buffed_skill_id}】{$buff->buffed_skill_name} : 残り {$buff->remaining_turn}ターン");
                            $remaining_buffs[] = $buff;
                        } else {
                            Debugbar::debug("{$player_or_enemy_data->name}の バフ 【{$buff->buffed_skill_id}】{$buff->buffed_skill_name} が消えました。");
                            // 切れた時の表示は不要か？
                            $battle_logs_collection->push("{$player_or_enemy_data->name}に付与されていた{$buff->buffed_skill_name}の効果が切れた。");
                        }
                        break;
                    case 'ITEM':
                        if ($buff->remaining_turn > 0) {
                            Debugbar::debug("{$player_or_enemy_data->name}の バフ 【{$buff->buffed_item_id}】{$buff->buffed_item_name} : 残り {$buff->remaining_turn}ターン");
                            $remaining_buffs[] = $buff;
                        } else {
                            Debugbar::debug("{$player_or_enemy_data->name}の バフ 【{$buff->buffed_item_id}】{$buff->buffed_item_name} が消えました。");
                            // 切れた時の表示は不要か？
                            $battle_logs_collection->push("{$player_or_enemy_data->name}に付与されていた{$buff->buffed_item_name}の効果が切れた。");
                        }
                        break;
                    case 'DEFENSE':
                        Debugbar::debug("{$player_or_enemy_data->name}の 防御コマンドを解除。");
                        break;

                }
            }

            // if (count($player_or_enemy_data->buffs) > 0) {
            //     Debugbar::debug('バフが1つ以上存在するので、バフに関する処理を開始。');
            //     if ($player_or_enemy_data->is_defeated_flag == true) {
            //         Debugbar::debug("{$player_or_enemy_data->name}は戦闘不能のため全てのバフを削除しました");
            //         $player_or_enemy_data->buffs = $remaining_buffs;

            //         continue;
            //     }
            // 1人ずつ、付与されているバフのターン数を削っていく
            // foreach ($player_or_enemy_data->buffs as $buff) {
            //     $buff = (object) $buff; // -> で値を呼べるようにオブジェクトにキャストしとく
            //     Debugbar::debug([
            //         'data' => $player_or_enemy_data,
            //         'buff' => $buff,
            //     ]);
            //     $buff->remaining_turn -= 1;
            //     switch ($buff->buffed_from) {
            //         case 'SKILL':
            //             if ($buff->remaining_turn > 0) {
            //                 Debugbar::debug("{$player_or_enemy_data->name}の バフ 【{$buff->buffed_skill_id}】{$buff->buffed_skill_name} : 残り {$buff->remaining_turn}ターン");
            //                 $remaining_buffs[] = $buff;
            //             } else {
            //                 Debugbar::debug("{$player_or_enemy_data->name}の バフ 【{$buff->buffed_skill_id}】{$buff->buffed_skill_name} が消えました。");
            //                 // 切れた時の表示は不要か？
            //                 $battle_logs_collection->push("{$player_or_enemy_data->name}に付与されていた{$buff->buffed_skill_name}の効果が切れた。");
            //             }
            //             break;
            //         case 'ITEM':
            //             if ($buff->remaining_turn > 0) {
            //                 Debugbar::debug("{$player_or_enemy_data->name}の バフ 【{$buff->buffed_item_id}】{$buff->buffed_item_name} : 残り {$buff->remaining_turn}ターン");
            //                 $remaining_buffs[] = $buff;
            //             } else {
            //                 Debugbar::debug("{$player_or_enemy_data->name}の バフ 【{$buff->buffed_item_id}】{$buff->buffed_item_name} が消えました。");
            //                 // 切れた時の表示は不要か？
            //                 $battle_logs_collection->push("{$player_or_enemy_data->name}に付与されていた{$buff->buffed_item_name}の効果が切れた。");
            //             }
            //             break;
            //         case 'DEFENSE':
            //             Debugbar::debug("{$player_or_enemy_data->name}の 防御コマンドを解除。");
            //             break;

            //     }
            // }
            // $data->buffs = $remaining_buffs;
            // }
        }
    }

    /**
     * 通常攻撃 (物理・単体)や物理スキルのダメージ計算
     *
     * 基礎計算式: damage² / (damage + def) ※ただし、多少のばらつきを入れる。
     */
    public static function calculatePhysicalDamage(int $pure_damage, int $opponent_def): int
    {
        Debugbar::debug("calculatePhysicalDamage(): --- 純粋なダメージ: {$pure_damage} 相手DEF: {$opponent_def}");

        // ゼロ除算対策
        if ($pure_damage <= 0) {
            return 0;
        }

        // 非線形のベースダメージ計算 atk² / (atk + def)
        $base_damage = $pure_damage * $pure_damage / ($pure_damage + $opponent_def);

        // ダメージにばらつきを加える（±10%）
        $variance_rate = random_int(95, 105) / 100;
        $final_damage = round($base_damage * $variance_rate); // ceilでなく、roundで0のケースが発生するようにする
        Debugbar::debug("計算結果: base = {$base_damage}, variance = {$variance_rate}, final = {$final_damage}");

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
        Debugbar::debug("calculateMagicDamage(): --- 純粋なダメージ: {$pure_damage} 相手DEF: {$opponent_mdef}");

        // ゼロ除算対策
        if ($pure_damage <= 0) {
            return 0;
        }

        // 非線形のベースダメージ計算 atk² / (atk + mdef)
        $base_damage = $pure_damage * $pure_damage / ($pure_damage + $opponent_mdef);

        // ダメージにばらつきを加える（±10%）
        $variance_rate = random_int(95, 105) / 100;
        $final_damage = round($base_damage * $variance_rate); // ceilでなく、roundで0のケースが発生するようにする
        Debugbar::debug("計算結果: base = {$base_damage}, variance = {$variance_rate}, final = {$final_damage}");

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
    public static function calculateMagicDefenseValue(int $opponent_def, int $opponent_int): int
    {
        $mdef = ceil(($opponent_def * 0.25) + ($opponent_int * 0.75));
        Debugbar::debug("calculateMagicDefenseValue(): --- 魔法防御計算。DEF: {$opponent_def} INT: {$opponent_int} MDEF: {$mdef}");

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
        // returnする、基礎値 + バフが加算された実数値
        $actual_status_value = 0;
        $buffed_status_name = '';

        Debugbar::debug("calculateActualStatusValue(): --- {$actor_or_opponent_data->name}のバフ含めたステータス {$status_name} の計算");

        switch ($status_name) {
            case 'hp':
                $actual_status_value = $actor_or_opponent_data->value_hp;
                $buffed_status_name = 'buffed_hp';
                break;
            case 'ap':
                $actual_status_value = $actor_or_opponent_data->value_ap;
                $buffed_status_name = 'buffed_ap';
                break;
            case 'str':
                $actual_status_value = $actor_or_opponent_data->value_str;
                $buffed_status_name = 'buffed_str';
                break;
            case 'def':
                $actual_status_value = $actor_or_opponent_data->value_def;
                $buffed_status_name = 'buffed_def';
                break;
            case 'int':
                $actual_status_value = $actor_or_opponent_data->value_int;
                $buffed_status_name = 'buffed_int';
                break;
            case 'spd':
                $actual_status_value = $actor_or_opponent_data->value_spd;
                $buffed_status_name = 'buffed_spd';
                break;
            case 'luc':
                $actual_status_value = $actor_or_opponent_data->value_luc;
                $buffed_status_name = 'buffed_luc';
                break;
        }

        // Debugbar::debug($actor_or_opponent_data->buffs, $actor_or_opponent_data->buffs->$buffed_status_name);
        $actual_status_value += $actor_or_opponent_data->buffs->$buffed_status_name;
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

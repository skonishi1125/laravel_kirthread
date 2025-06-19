<?php

declare(strict_types=1);

namespace App\Constants\Rpg;

class BattleData
{
    /**
     * パーティメンバー1人のテンプレートデータ
     *
     * jsonとして使う時もあれば、collectionで使う時もある
     */
    public const PARTY_TEMPLATE = [
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
        'skills' => null, // buffと同じく、配列を格納するための親配列が入る。 ただしこれは= Skill::generateSkillCollection($party);みたいな感じでそのまま上書きされる
        'selected_skill_id' => null, // exec時に格納する、選択したスキルのID
        'buffs' => [], // '[ [バフ1], [バフ2], [バフ3], ... ]'というように、配列を格納するための親配列を空で定義しておく
        'role_portrait' => null,
        'is_defeated_flag' => false,
        'is_escaped' => false,
        'player_index' => null,
        'is_enemy' => false,
    ];

    /**
     * 敵1体のテンプレートデータ
     *
     * jsonとして使う時もあれば、collectionで使う時もある
     */
    public const ENEMY_TEMPLATE = [
        'id' => null, // enemy_id
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
        'skills' => null, // buffと同じく、配列を格納するための親配列が入る。 ただしこれは= Skill::generateSkillCollection($enemy);みたいな感じでそのまま上書きされる
        'selected_skill_id' => null, // exec時に格納する、選択したスキルのID
        'buffs' => [], // '[ [バフ1], [バフ2], [バフ3], ... ]'というように、配列を格納するための親配列を空で定義しておく
        'is_defeated_flag' => false,
        'is_escaped' => false,
        'enemy_index' => null, // 敵の並び。
        'is_enemy' => true, // 味方と敵で同じデータ構造をベースとしているので、判別するためのフラグ
        'is_boss' => false,
        'has_pattern' => false,
        'exp' => 0,
        'drop_money' => 0,
    ];

    /**
     * アイテム1つ当たりのテンプレートデータ
     */
    public const ITEM_TEMPLATE = [
        'id' => null,
        'name' => null,
        'attack_type' => null,
        'heal_type' => null,
        'effect_type' => null,
        'target_range' => null,
        'is_percent_based' => null,
        'percent' => null,
        'fixed_value' => null,
        'buff_turn' => null,
        'description' => null,
        'possession_number' => null,
    ];

    /**
     * バフ1件あたりのテンプレートデータ
     *
     * 実際はこの配列が、PARTY_TEMPLATE のbuffs[]に次々と格納されていく形になる。
     * '[ [バフ1], [バフ2], [バフ3], ... ]'
     */
    public const BUFF_TEMPLATE = [
        'buffed_skill_id' => null,
        'buffed_item_id' => null,
        'buffed_skill_name' => null,
        'buffed_item_name' => null,
        'buffed_hp' => null,
        'buffed_ap' => null,
        'buffed_str' => null,
        'buffed_def' => null,
        'buffed_int' => null,
        'buffed_spd' => null,
        'buffed_luc' => null,
        'remaining_turn' => 0,
        'buffed_from' => '', // 'DEFENCE', 'ITEM', 'SKILL'など、どのコマンドで付与されたものか。
    ];

    /**
     * スキル1件あたりのテンプレートデータ
     *
     * 実際はこの配列が、PARTY_TEMPLATE のskills[]に次々と格納されていく形になる。
     * '[ [スキル1], [スキル2], [スキル3], ... ]'
     */
    public const SKILL_TEMPLATE = [
        'id' => null,
        'name' => null,
        'description' => null,
        'attack_type' => null,
        'effect_type' => null,
        'target_range' => null,
        'is_target_enemy' => null,
        'is_first' => null,
        'skill_level' => null,
        'ap_cost' => null,
        'buff_turn' => null,
        'elemental_id' => null,
        'skill_percent' => null,
    ];

    /**
     * 戦闘で得られるドロップ品のテンプレートデータ
     *
     * battle_state.enemy_drops_json_dataに格納されて使われる
     * ドロップ品を複数纏める想定の配列なので、DROP'S'と複数形にしておく
     */
    public const ENEMY_DROPS_TEMPLATE = [
        'money' => 0,
        'drop_item_id' => [],
        'drop_weapon_id' => [],
    ];
}

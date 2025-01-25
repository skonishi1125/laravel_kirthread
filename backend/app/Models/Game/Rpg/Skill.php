<?php

namespace App\Models\Game\Rpg;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'rpg_skills';

    const ATTACK_NO_TYPE = 0; // 分類なし(ワイドガードなど)

    const ATTACK_PHYSICAL_TYPE = 1; // 物理

    const ATTACK_MAGIC_TYPE = 2; // 魔法

    const EFFECT_SPECIAL_TYPE = 0; // 特殊系スキル(ワイドガードなど)

    const EFFECT_DAMAGE_TYPE = 1; // 攻撃系スキル

    const EFFECT_HEAL_TYPE = 2; // 治療系スキル

    const EFFECT_BUFF_TYPE = 3; // バフ系スキル

    const TARGET_RANGE_SELF = 0; // 自身を対象

    const TARGET_RANGE_SINGLE = 1; // 単体を対象

    const TARGET_RANGE_ALL = 2; // 全体を対象

    /**
     * 多対多のリレーション
     *
     * スキルレベル skill_level を取得したい場合は、下記のような形で取得が可能。
     * $party->skills->where('id', $skill->id)->first()->pivot->skill_level
     *
     * @return BelongsToMany<Party, $this>
     */
    public function parties(): BelongsToMany
    {
        return $this->belongsToMany(Party::class, 'rpg_party_learned_skills', 'skill_id', 'party_id')
            ->withPivot('skill_level')
            ->withTimestamps();
    }

    // 渡したパーティ(単体)に合ったスキルツリーを作成し、それを返す
    public static function aquireSkillTreeCollection(Party $party)
    {
        $role_skills = self::where('available_role_id', $party->role_id)->get();
        $skills_collection = collect();
        $stored_skill_ids = []; // 処理したスキルIDを格納していく用の配列

        foreach ($role_skills as $parent_skill) {
            // すでに処理済みのスキルだったら次のスキルの処理へ
            if (in_array($parent_skill->id, $stored_skill_ids, true)) {
                continue;
            }

            // 親スキルの取得条件を取得し、それぞれ満たしているのかを確認
            $parent_skill_requirement = SkillRequirement::where('acquired_skill_id', $parent_skill->id)->first();
            // 取得条件が特にない場合、各フラグをtrueにして子スキルの処理へ
            if (is_null($parent_skill_requirement)) {
                $parent_skill_requirement_skill_level = null;
                $parent_skill_is_checked_skill_level = true;
                $parent_skill_requirement_party_level = null;
                $parent_skill_is_checked_party_level = true;
                $parent_skill_is_learned = true;
            } else {
                // パーティが覚えているスキルのレベルと習得対象スキルの必要なスキルレベルを比較する
                // ただし親スキルは必要となるスキルが存在しないため、考慮する必要がない。
                $parent_skill_requirement_skill_level = $parent_skill_requirement->requirement_skill_level;

                $parent_skill_is_checked_skill_level = true;

                // パーティレベルが習得対象スキルの必要なパーティレベルより高ければ、trueとする
                $parent_skill_requirement_party_level = $parent_skill_requirement->requirement_party_level;
                if ($party->level >= $parent_skill_requirement_party_level) {
                    $parent_skill_is_checked_party_level = true;
                } else {
                    $parent_skill_is_checked_party_level = false;
                }

                // スキルレベル・パーティレベルどちらの条件も満たしていればtrueとする
                // @phpstan-ignore-next-line
                if ($parent_skill_is_checked_skill_level && $parent_skill_is_checked_party_level) {
                    $parent_skill_is_learned = true;
                } else {
                    $parent_skill_is_learned = false;
                }
            }

            // 親スキルの情報をまずコレクションとして格納
            $parent_skill_collection = collect([
                'skill_id' => $parent_skill->id,
                'skill_name' => $parent_skill->name,
                'skill_level' => $party->skills->where('id', $parent_skill->id)->first()->pivot->skill_level ?? 0,
                'attack_type' => $parent_skill->attack_type,
                'effect_type' => $parent_skill->effect_type,
                'target_range' => $parent_skill->target_range,
                'lv1_percent' => $parent_skill->lv1_percent,
                'lv1_ap_cost' => $parent_skill->lv1_ap_cost,
                'lv1_buff_turn' => $parent_skill->lv1_buff_turn,
                'lv2_percent' => $parent_skill->lv2_percent,
                'lv2_ap_cost' => $parent_skill->lv2_ap_cost,
                'lv2_buff_turn' => $parent_skill->lv2_buff_turn,
                'lv3_percent' => $parent_skill->lv3_percent,
                'lv3_ap_cost' => $parent_skill->lv3_ap_cost,
                'lv3_buff_turn' => $parent_skill->lv3_buff_turn,
                'description' => $parent_skill->description,
                'requirement_skill_level' => $parent_skill_requirement_skill_level,
                'is_checked_skill_level' => $parent_skill_is_checked_skill_level,
                'requirement_party_level' => $parent_skill_requirement_party_level,
                'is_checked_party_level' => $parent_skill_is_checked_party_level,
                'is_learned' => $parent_skill_is_learned,
                'child_skills' => collect(),
            ]);

            // 子スキルの処理
            // 子スキルを持つ場合、そのスキルについても同様に条件や詳細情報を格納していく
            $skill_requirements = SkillRequirement::where('requirement_skill_id', $parent_skill->id)->get();
            foreach ($skill_requirements as $skill_requirement) {
                $child_skill = $role_skills->firstWhere('id', $skill_requirement->acquired_skill_id);

                // パーティメンバーが親スキルを習得済みかどうか
                // 次に、覚えているなら現在のスキルのレベルが習得対象スキルの必要なスキルレベルより高ければ、trueとする
                $is_checked_skill_level = false;
                $learned_parent_skill = $party->skills->where('id', $parent_skill->id)->first();
                if (! is_null($learned_parent_skill)) {
                    if ($learned_parent_skill->pivot->skill_level >= $skill_requirement->requirement_skill_level) {
                        $is_checked_skill_level = true;
                    }
                }

                // パーティレベルが習得対象スキルの必要なパーティレベルより高ければ、trueとする
                if ($party->level >= $skill_requirement->requirement_party_level) {
                    $is_checked_party_level = true;
                } else {
                    $is_checked_party_level = false;
                }

                // スキルレベル・パーティレベルどちらの条件も満たしていればtrueとする
                if ($is_checked_skill_level && $is_checked_party_level) {
                    $is_learned = true;
                } else {
                    $is_learned = false;
                }

                // 子スキルの情報を格納し、親スキルのchild_skillsに格納する
                $child_skill_collection = collect([
                    'skill_id' => $child_skill->id,
                    'skill_name' => $child_skill->name,
                    'parent_skill_name' => $parent_skill->name,
                    'skill_level' => $party->skills->where('id', $child_skill->id)->first()->pivot->skill_level ?? 0,
                    'attack_type' => $child_skill->attack_type,
                    'effect_type' => $child_skill->effect_type,
                    'target_range' => $child_skill->target_range,
                    'lv1_percent' => $child_skill->lv1_percent,
                    'lv1_ap_cost' => $child_skill->lv1_ap_cost,
                    'lv1_buff_turn' => $child_skill->lv1_buff_turn,
                    'lv2_percent' => $child_skill->lv2_percent,
                    'lv2_ap_cost' => $child_skill->lv2_ap_cost,
                    'lv2_buff_turn' => $child_skill->lv2_buff_turn,
                    'lv3_percent' => $child_skill->lv3_percent,
                    'lv3_ap_cost' => $child_skill->lv3_ap_cost,
                    'lv3_buff_turn' => $child_skill->lv3_buff_turn,
                    'description' => $child_skill->description,
                    'requirement_skill_level' => $skill_requirement->requirement_skill_level,
                    'is_checked_skill_level' => $is_checked_skill_level,
                    'requirement_party_level' => $skill_requirement->requirement_party_level,
                    'is_checked_party_level' => $is_checked_party_level,
                    'is_learned' => $is_learned,
                ]);
                $parent_skill_collection['child_skills']->push($child_skill_collection);

                // 子スキルのIDを格納し、処理済みとする
                array_push($stored_skill_ids, $child_skill->id);
            }

            // スキルツリーに親子スキルの情報を格納し、処理済みとする
            $skills_collection->push($parent_skill_collection);
            array_push($stored_skill_ids, $parent_skill->id);
        }

        return $skills_collection;
    }

    // スキルの習得及びスキルLvの上昇処理。
    // 既に例外処理で括られていることを前提とする。
    public static function learnPartySkill(Party $learned_party, Skill $learned_skill)
    {
        Debugbar::debug("Skill::learnPartySkill: {$learned_party->nickname} {$learned_skill->name} ------------------------");

        // パーティがそのスキルを習得済かどうかの確認
        $party_learned_skill = $learned_party->skills->where('id', $learned_skill->id)->first();
        if ($party_learned_skill == null) {
            Debugbar::debug('未修得のため、習得処理を行います。');
            $create_party_learned_skill = PartyLearnedSkill::create([
                'party_id' => $learned_party->id,
                'skill_id' => $learned_skill->id,
                'skill_level' => 1,
            ]);
        } else {
            Debugbar::debug("修得済。レベル: {$party_learned_skill->pivot->skill_level} スキルレベル上昇処理を行います。");

            // クリック連打やバグなどでLv3->Lv4にはさせない
            if ($party_learned_skill->pivot->skill_level > 2) {
                throw new \Exception('スキルレベルが既にMAXです。画面のリロードをお試しください。');
            }
            // スキルポイントがない場合も、その後の処理を行わせない
            if ($learned_party->freely_skill_point < 1) {
                throw new \Exception('スキルポイントが足りません。画面のリロードをお試しください。');
            }

            // 中間テーブルの値の更新
            $update_party_learned_skill = $learned_party->skills()->updateExistingPivot($learned_skill->id, [
                'skill_level' => $party_learned_skill->pivot->skill_level + 1,
                'updated_at' => now(), // withTimestamps()で動作するはずだが、動作しないので一旦直接書き換える
            ]);
        }

        // 振分けたので、自由なスキルポイントを減らす
        $learned_party->update([
            'freely_skill_point' => $learned_party->freely_skill_point - 1,
        ]);

    }

    // 現在会得しているスキル情報を取得
    public static function getLearnedSkill($party)
    {
        $learned_skills = $party->skills->map(function ($skill) {

            // レベルに応じた消費APのコスト計算 スキルの数だけ回すので、これはループの生成する必要がある
            $ap_cost_property = 'lv'.$skill->pivot->skill_level.'_ap_cost';
            $skill_percent_property = 'lv'.$skill->pivot->skill_level.'_percent';
            $buff_turn_property = 'lv'.$skill->pivot->skill_level.'_buff_turn';
            $ap_cost = 99;        // デフォルト値。エラーの場合は99にしてとりあえずわかるようにしとく
            $skill_percent = 999; // デフォルト値。
            $buff_turn = 9;       // デフォルト値。

            $skill_attributes = $skill->getAttributes(); // DBの情報を全て配列として扱えるようにする
            // lv1ならlv1_ap_cost, lv2ならlv2_ap_costを取得
            if (array_key_exists($ap_cost_property, $skill_attributes)) {
                $ap_cost = $skill_attributes[$ap_cost_property];
            }
            if (array_key_exists($skill_percent_property, $skill_attributes)) {
                $skill_percent = $skill_attributes[$skill_percent_property];
            }
            if (array_key_exists($buff_turn_property, $skill_attributes)) {
                $buff_turn = $skill_attributes[$buff_turn_property];
            }

            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'description' => $skill->description,
                'attack_type' => $skill->attack_type,
                'effect_type' => $skill->effect_type,
                'target_range' => $skill->target_range,
                'skill_level' => $skill->pivot->skill_level,  // pivotのskill_levelを取得
                'ap_cost' => $ap_cost,
                'buff_turn' => $buff_turn,
                'elemental_id' => $skill->elemental_id,
                'skill_percent' => $skill_percent,
            ];
        });

        return $learned_skills;
    }

    // 今からどのメソッドでこのスキルを処理するのか決める
    // 全体効果スキルなら$opponents_indexはnullになりうるので、?intとする
    public static function decideExecSkill(
        int $role_id, object $selected_skill, object $self_data, Collection $opponents_data,
        bool $is_enemy, ?int $opponents_index, Collection $logs
    ) {
        Debugbar::debug('decideExecSkill(): --------------------');
        // 攻撃系スキル && 単体対象スキル($opponents_indexがnullでない)
        if ($selected_skill->effect_type == self::EFFECT_DAMAGE_TYPE && ! is_null($opponents_index)) {
            // スキル発動前に敵が討伐済みの場合、敵の選択を変更
            if ($opponents_data[$opponents_index]->is_defeated_flag == true) {
                $new_target_index = $opponents_data->search(function ($enemy) {
                    return $enemy->is_defeated_flag == false;
                });
                if ($new_target_index !== false) {
                    $opponents_index = $new_target_index;
                    Debugbar::debug("(スキル)攻撃対象が討伐済みのため対象を変更。改めて攻撃対象: {$opponents_data[$opponents_index]->name}");
                } else {
                    Debugbar::debug("すべての敵が討伐済みになったので、SKILLを使わず終了します。敵数: {$opponents_data->count()}");

                    return;
                }
            }
        }

        // 使用スキルの設定
        $damage = null;
        $heal_point = null;
        $buffs = null;

        switch ($selected_skill->id) {
            /**
             * 格闘家(Striker)
             */
            case 10:
                Debugbar::debug('ミドルブロウ');
                $logs->push("{$self_data->name}の{$selected_skill->name}！");
                $damage = ceil(BattleState::calculateActualStatusValue($self_data, 'str') * $selected_skill->skill_percent + 10);
                break;
            case 11:
                Debugbar::debug('スピンキック');
                $logs->push("{$self_data->name}の{$selected_skill->name}！鋭い蹴りで鉾の如く周囲を薙ぎ払う！");
                $damage = ceil(BattleState::calculateActualStatusValue($self_data, 'str') * $selected_skill->skill_percent + 5);
                break;
                /**
                 * 重騎士(Paradin)
                 */
            case 30:
                Debugbar::debug('ワイドスラスト');
                $logs->push("{$self_data->name}の{$selected_skill->name}！");
                $damage = ceil(BattleState::calculateActualStatusValue($self_data, 'str') * $selected_skill->skill_percent);
                break;
            case 31:
                Debugbar::debug('ワイドガード');
                $logs->push("{$self_data->name}の{$selected_skill->name}！パーティは守りの壁に包まれた！");
                $buffs = [
                    'buffed_skill_id' => $selected_skill->id,
                    'buffed_item_id' => null,
                    'buffed_skill_name' => $selected_skill->name,
                    'buffed_item_name' => null,
                    'buffed_def' => ceil($self_data->value_def * $selected_skill->skill_percent),
                    'remaining_turn' => $selected_skill->buff_turn,
                    'buffed_from' => 'SKILL',
                ];
                break;
            case 32:
                Debugbar::debug('ブレイヴスラッシュ');
                $logs->push("{$self_data->name}の{$selected_skill->name}！天地を揺らす一撃！");
                $damage = ceil(BattleState::calculateActualStatusValue($self_data, 'str') * $selected_skill->skill_percent) + BattleState::calculateActualStatusValue($self_data, 'def');
                break;
            case 33:
                Debugbar::debug('ガードアップ');
                $logs->push("{$self_data->name}は{$selected_skill->name}を発動！");
                $buffs = [
                    'buffed_skill_id' => $selected_skill->id,
                    'buffed_skill_name' => $selected_skill->name,
                    'buffed_def' => ceil($opponents_data[$opponents_index]->value_def * $selected_skill->skill_percent),
                    'remaining_turn' => $selected_skill->buff_turn,
                ];
                break;
                /**
                 * 魔導士(Mage)
                 */
            case 40:
                // 回復量 = (INT * ダメージ%)
                Debugbar::debug('ミニヒール');
                $logs->push("{$self_data->name}は{$selected_skill->name}を唱えた！");
                $heal_point = ceil(BattleState::calculateActualStatusValue($self_data, 'int') * $selected_skill->skill_percent);
                break;
            case 41:
                // 回復量 = (INT * ダメージ%)
                Debugbar::debug('ポップヒール');
                $logs->push("{$self_data->name}の{$selected_skill->name}！癒しの霧が味方を包む！");
                $heal_point = ceil(BattleState::calculateActualStatusValue($self_data, 'int') * $selected_skill->skill_percent);
                break;
            case 42:
                // 威力 = (INT * ダメージ%)
                Debugbar::debug('プチブラスト');
                $logs->push("{$self_data->name}は{$selected_skill->name}を唱えた！魔力の粒が相手を襲う！");
                $damage = ceil(BattleState::calculateActualStatusValue($self_data, 'int') * $selected_skill->skill_percent);
                break;
            case 43:
                // 威力 = (INT * ダメージ%) + 基礎ダメージ50
                Debugbar::debug('クラッシュボルト');
                // レベルごとに文章を変えられたら熱い
                $logs->push("{$self_data->name}は{$selected_skill->name}を唱えた！");
                $damage = ceil(BattleState::calculateActualStatusValue($self_data, 'int') * $selected_skill->skill_percent) + 50;
                break;
            case 44:
                // 威力 = (INT * ダメージ%) + 基礎ダメージ30
                Debugbar::debug('マナエクスプロージョン');
                // レベルごとに文章を変えられたら熱い
                $logs->push("{$self_data->name}の{$selected_skill->name}！解き放ったマナの塊が大爆発を起こす！");
                $damage = ceil(BattleState::calculateActualStatusValue($self_data, 'int') * $selected_skill->skill_percent) + 30;
                break;
            case 45:
                // STR = (INT * ダメージ%)とする
                Debugbar::debug('バトルメイジ');
                $logs->push("{$self_data->name}の{$selected_skill->name}！冒険中に修めてきた全ての智力が{$self_data->name}の力と代わる！");
                $buffs = [
                    'buffed_skill_id' => $selected_skill->id,
                    'buffed_item_id' => null,
                    'buffed_skill_name' => $selected_skill->name,
                    'buffed_item_name' => null,
                    'buffed_str' => ceil(($self_data->value_int * $selected_skill->skill_percent)),
                    'buffed_int' => ceil(-$self_data->value_int), // intを0にする
                    'remaining_turn' => $selected_skill->buff_turn,
                    'buffed_from' => 'SKILL',
                ];
                break;
            default:
                Debugbar::debug('存在しないスキルが選択されました。');
                break;
        }

        self::separateStoreProcessAccrodingToEffectType(
            $selected_skill, $self_data, $opponents_data, $opponents_index, $logs, (int) $damage, (int) $heal_point, $buffs
        );

    }

    public static function separateStoreProcessAccrodingToEffectType(
        object $selected_skill, object $self_data, Collection $opponents_data,
        ?int $opponents_index, Collection $logs, ?int $damage, ?int $heal_point, ?array $buffs
    ) {
        Debugbar::debug('separateStoreProcessAccrodingToEffectType(): ----------------');
        Debugbar::debug($selected_skill);

        // 指定したスキルのAPを消費
        $self_data->value_ap -= $selected_skill->ap_cost;
        if ($self_data->value_ap < 0) {
            $self_data->value_ap = 0;
        }

        // skills.effect_typeに応じて処理を分ける
        switch ($selected_skill->effect_type) {
            case self::EFFECT_SPECIAL_TYPE:
                BattleState::storePartySpecialSkill($self_data, $opponents_data, $opponents_index, $logs, $buffs, $selected_skill);
                break;
            case self::EFFECT_DAMAGE_TYPE:
                $damage = (int) ceil($damage);
                BattleState::storePartyDamage(
                    'SKILL', $self_data, $opponents_data, null, $opponents_index, $logs, $damage, $selected_skill->target_range, $selected_skill->attack_type
                );
                break;
            case self::EFFECT_HEAL_TYPE:
                $heal_point = (int) ceil($heal_point);
                BattleState::storePartyHeal(
                    'SKILL', $self_data, $opponents_data,
                    $opponents_index, $logs, $heal_point, $selected_skill->target_range, null, null
                );
                break;
            case self::EFFECT_BUFF_TYPE:
                BattleState::storePartyBuff(
                    'SKILL', $self_data, $opponents_data, $opponents_index, $logs, $buffs, $selected_skill->target_range
                );
                break;
        }

    }
}

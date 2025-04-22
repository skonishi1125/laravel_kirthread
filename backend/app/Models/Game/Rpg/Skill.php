<?php

namespace App\Models\Game\Rpg;

use App\Constants\Rpg\BattleData;
use App\Enums\Rpg\EffectType;
use App\Enums\Rpg\SkillDefinition;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'rpg_skills';

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
    public static function acquireSkillTreeCollection(Party $party)
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

    /**
     * players_json_dataのskillsに格納するスキル情報を取得する
     *
     * @return Collection
     */
    public static function getLearnedSkill($party)
    {
        Debugbar::debug('getLearnedSkill(): --------------------');
        $learned_skills = $party->skills->map(function ($skill) {

            $skill_level = $skill->pivot->skill_level;
            // レベルに応じた消費APのコスト計算 スキルの数だけ回すので、これはループの生成する必要がある
            $ap_cost_property = 'lv'.$skill_level.'_ap_cost';
            $skill_percent_property = 'lv'.$skill_level.'_percent';
            $buff_turn_property = 'lv'.$skill_level.'_buff_turn';
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

            $players_json_skills_data = BattleData::SKILL_TEMPLATE;

            $players_json_skills_data['id'] = $skill['id'];
            $players_json_skills_data['name'] = $skill['name'];
            $players_json_skills_data['description'] = $skill['description'];
            $players_json_skills_data['attack_type'] = $skill['attack_type'];
            $players_json_skills_data['effect_type'] = $skill['effect_type'];
            $players_json_skills_data['target_range'] = $skill['target_range'];
            $players_json_skills_data['is_target_enemy'] = $skill['is_target_enemy'];
            $players_json_skills_data['is_first'] = $skill['is_first'];
            $players_json_skills_data['skill_level'] = $skill_level;
            $players_json_skills_data['ap_cost'] = $ap_cost;
            $players_json_skills_data['buff_turn'] = $buff_turn;
            $players_json_skills_data['elemental_id'] = $skill['elemental_id'];
            $players_json_skills_data['skill_percent'] = $skill_percent;

            return $players_json_skills_data;
        })
        // スキルID順にし、キーが連番でなくなっているのでvaluesで0からの連番キー配列に整形しておく
            ->sortBy('id')
            ->values();

        Debugbar::debug($learned_skills);

        return $learned_skills;
    }

    /**
     * 引数の情報から、どのスキルを処理するのかを決定するメソッド
     *
     * $opponents_indexは 並び中央の味方に向けた場合は[1]が入るが、
     * 全体攻撃スキルを使った場合, $opponents_indexはnullであるため?で許容しておく。
     *
     * @param  object  $selected_skill_data  BattleData::SKILL_TEMPLATE に、選択されたスキルの情報が格納されたもの
     */
    public static function decideExecSkill(
        int $role_id,
        object $selected_skill_data,
        object $actor_data,
        Collection $battle_state_opponents_collection,
        bool $is_enemy, // 将来的に敵もこのdecideExecSkillを使うことになる可能性があるので、その時用の引数
        ?int $opponents_index,
        Collection $battle_logs_collection
    ) {
        Debugbar::debug('decideExecSkill(): --------------------');
        // 攻撃系スキル && 単体対象スキル($opponents_indexがnullでない)
        if ($selected_skill_data->effect_type === EffectType::Damage->value && ! is_null($opponents_index)) {
            // スキル発動前に敵が討伐済みの場合、敵の選択を変更
            if ($battle_state_opponents_collection[$opponents_index]->is_defeated_flag == true) {
                $new_target_index = $battle_state_opponents_collection->search(function ($enemy) {
                    return $enemy->is_defeated_flag == false;
                });
                if ($new_target_index !== false) {
                    $opponents_index = $new_target_index;
                    Debugbar::debug("(スキル)攻撃対象が討伐済みのため対象を変更。改めて攻撃対象: {$battle_state_opponents_collection[$opponents_index]->name}");
                } else {
                    Debugbar::debug("すべての敵が討伐済みになったので、SKILLを使わず終了します。敵数: {$battle_state_opponents_collection->count()}");

                    return;
                }
            }
        }

        // 指定したスキルのAPを消費
        $actor_data->value_ap -= $selected_skill_data->ap_cost;
        if ($actor_data->value_ap < 0) {
            $actor_data->value_ap = 0;
        }

        // 使用するスキルに応じて、ダメージ・回復量・付与されるバフを設定。
        $damage = null;
        $heal_point = null;

        $new_buff = BattleData::BUFF_TEMPLATE;
        $new_buff['buffed_from'] = 'SKILL';
        $new_buff['buffed_skill_id'] = $selected_skill_data->id;
        $new_buff['buffed_skill_name'] = $selected_skill_data->name;
        $new_buff['remaining_turn'] = $selected_skill_data->buff_turn;

        switch (SkillDefinition::from($selected_skill_data->id)) {
            // -------------------- 格闘家 --------------------
            case SkillDefinition::MiddleBlow :
                Debugbar::debug(SkillDefinition::MiddleBlow->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！強力な正拳突きが敵を撃ち抜く！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'str') * $selected_skill_data->skill_percent + 10);
                break;
            case SkillDefinition::HeavyKnuckle :
                Debugbar::debug(SkillDefinition::HeavyKnuckle->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！重い拳が敵の装甲を貫通する！");
                $damage = (int) ceil(100 * $selected_skill_data->skill_percent); // 固定値 * スキル%
                break;
            case SkillDefinition::SpinKick :
                Debugbar::debug(SkillDefinition::SpinKick->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！鋭い蹴りで周囲を薙ぎ払う！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'str') * $selected_skill_data->skill_percent + 5);
                break;
            case SkillDefinition::Transform :
                Debugbar::debug(SkillDefinition::Transform->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！守りを捨て、全ての力を解放する！");
                // buffed_str = (value_def * ダメージ%) |  buffed_spd = (value_int * ダメージ%)
                // また、DEFとINTを0にする
                $new_buff['buffed_str'] = (int) ceil($actor_data->value_def * $selected_skill_data->skill_percent);
                $new_buff['buffed_spd'] = (int) ceil($actor_data->value_int * $selected_skill_data->skill_percent);
                $new_buff['buffed_def'] = (int) ceil(-$actor_data->value_def);
                $new_buff['buffed_int'] = (int) ceil(-$actor_data->value_int);
                break;
                // -------------------- 治療師 --------------------
            case SkillDefinition::Healing :
                Debugbar::debug(SkillDefinition::Healing->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を唱えた！");
                $heal_point = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent) + 10;
                break;
            case SkillDefinition::AllHealing :
                Debugbar::debug(SkillDefinition::AllHealing->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を唱えた！");
                $heal_point = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent);
                break;
            case SkillDefinition::MiniVolt :
                Debugbar::debug(SkillDefinition::MiniVolt->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を唱えた！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent);
                break;
            case SkillDefinition::HolyAllow :
                Debugbar::debug(SkillDefinition::HolyAllow->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！光の弓矢が相手を貫く！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent) + 30;
                break;
            case SkillDefinition::HeavenRay :
                Debugbar::debug(SkillDefinition::HeavenRay->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！光の柱が敵全体に降り注ぐ！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent) + 30;
                break;
                // -------------------- 重騎士 --------------------
            case SkillDefinition::WideThrust :
                Debugbar::debug(SkillDefinition::WideThrust->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'str') * $selected_skill_data->skill_percent);
                break;
            case SkillDefinition::WideGuard :
                Debugbar::debug(SkillDefinition::WideGuard->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！パーティは守りの壁に包まれた！");
                $new_buff['buffed_def'] = (int) ceil($actor_data->value_def * $selected_skill_data->skill_percent);
                break;
            case SkillDefinition::BraveSlash :
                Debugbar::debug(SkillDefinition::BraveSlash->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！天地を揺らす一撃！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'str') * $selected_skill_data->skill_percent) + BattleState::calculateActualStatusValue($actor_data, 'def');
                break;
            case SkillDefinition::GuardUp :
                Debugbar::debug(SkillDefinition::GuardUp->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を発動！");
                $new_buff['buffed_def'] = (int) ceil($actor_data->value_def * $selected_skill_data->skill_percent);
                break;
                // -------------------- 魔導師 --------------------
            case SkillDefinition::MiniHeal :
                // 回復量 = (INT * ダメージ%)
                Debugbar::debug(SkillDefinition::MiniHeal->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を唱えた！");
                $heal_point = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent) + 5;
                break;
            case SkillDefinition::PopHeal :
                // 回復量 = (INT * ダメージ%)
                Debugbar::debug(SkillDefinition::PopHeal->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！癒しの霧が味方を包む！");
                $heal_point = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent) + 5;
                break;
            case SkillDefinition::PetitBlast :
                // 威力 = (INT * ダメージ%)
                Debugbar::debug(SkillDefinition::PetitBlast->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を唱えた！魔力の粒が相手を襲う！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent) + 5;
                break;
            case SkillDefinition::CrashBolt :
                // 威力 = (INT * ダメージ%) + 基礎ダメージ50
                Debugbar::debug(SkillDefinition::CrashBolt->label());
                // レベルごとに文章を変えられたら熱い
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！収束したマナが爆発する！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent) + 50;
                break;
            case SkillDefinition::ManaExplosion :
                // 威力 = (INT * ダメージ%) + 基礎ダメージ30
                Debugbar::debug(SkillDefinition::ManaExplosion->label());
                // (独り言)レベルごとに文章を変えられたら熱いけど
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！解き放ったマナの塊が大爆発を起こす！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent) + 30;
                break;
            case SkillDefinition::BattleMage :
                Debugbar::debug(SkillDefinition::BattleMage->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を発動！数々の冒険で修めてきた全ての智力が{$actor_data->name}の力と代わる...");
                // buffed_str = (value_int * ダメージ%) とし、 intを0とする
                $new_buff['buffed_str'] = (int) ceil($actor_data->value_int * $selected_skill_data->skill_percent);
                $new_buff['buffed_int'] = (int) ceil(-$actor_data->value_int);
                break;
                // -------------------- 弓馭者 --------------------
            case SkillDefinition::FirstAid :
                Debugbar::debug(SkillDefinition::FirstAid->label());
                $battle_logs_collection->push("{$actor_data->name}は応急処置に取り掛かった！");
                $heal_point = (int) ceil(30 * $selected_skill_data->skill_percent); // 固定値 * スキル%
                break;
            case SkillDefinition::FairyFog :
                Debugbar::debug(SkillDefinition::FairyFog->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！妖精たちの力を借りて味方の傷を包み込む...");
                $heal_point = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent) + 10;
                break;
            case SkillDefinition::BreakBowGun :
                Debugbar::debug(SkillDefinition::BreakBowGun->label());
                $battle_logs_collection->push("{$actor_data->name}の{$selected_skill_data->name}！相手の甲殻を打ち砕く、鋭い一撃！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'str') * $selected_skill_data->skill_percent) + 5;
                $new_buff['buffed_def'] = (int) (-($actor_data->value_def) * ($selected_skill_data->skill_percent * 0.5)); // マイナスの値にして、相手にデバフとして付与する
                break;
            case SkillDefinition::WindAccel :
                Debugbar::debug(SkillDefinition::WindAccel->label());
                $battle_logs_collection->push("{$actor_data->name}は風の力を使役し、攻撃した！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'str') * $selected_skill_data->skill_percent) + 5;
                $new_buff['buffed_spd'] = (int) ceil($actor_data->value_spd * $selected_skill_data->skill_percent);
                break;
                // -------------------- 理術師 --------------------
            case SkillDefinition::BookSmash :
                Debugbar::debug(SkillDefinition::BookSmash->label());
                $battle_logs_collection->push("{$actor_data->name}は手持ちの魔導書で思い切り殴りかかった！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'str') * $selected_skill_data->skill_percent);
                break;
            case SkillDefinition::MagicMissile :
                Debugbar::debug(SkillDefinition::MagicMissile->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を唱えた！");
                $damage = (int) ceil(BattleState::calculateActualStatusValue($actor_data, 'int') * $selected_skill_data->skill_percent);
                break;
            case SkillDefinition::GuardSpell :
                Debugbar::debug(SkillDefinition::GuardSpell->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を唱えた！");
                $new_buff['buffed_def'] = (int) ceil($actor_data->value_def * $selected_skill_data->skill_percent);
                break;
            case SkillDefinition::AttackSpell :
                Debugbar::debug(SkillDefinition::AttackSpell->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を唱えた！");
                $new_buff['buffed_str'] = (int) ceil($actor_data->value_def * $selected_skill_data->skill_percent);
                break;
            case SkillDefinition::MagicSpell :
                Debugbar::debug(SkillDefinition::MagicSpell->label());
                $battle_logs_collection->push("{$actor_data->name}は{$selected_skill_data->name}を唱えた！");
                $new_buff['buffed_int'] = (int) ceil($actor_data->value_def * $selected_skill_data->skill_percent);
                break;
            default:
                Debugbar::debug('存在しないスキルが選択されました。');
                break;
        }

        // 特殊・攻撃・回復・バフに応じて処理を分岐する
        switch ($selected_skill_data->effect_type) {
            case EffectType::Special->value:
                BattleState::storePartySpecialSkill($actor_data, $battle_state_opponents_collection, $opponents_index, $battle_logs_collection, $damage, $heal_point, $new_buff, $selected_skill_data);
                break;
            case EffectType::Damage->value:
                BattleState::storePartyDamage(
                    'SKILL', $actor_data, $battle_state_opponents_collection, null, $opponents_index, $battle_logs_collection, $damage, $selected_skill_data->target_range, $selected_skill_data->attack_type
                );
                break;
            case EffectType::Heal->value:
                BattleState::storePartyHeal(
                    'SKILL', $actor_data, $battle_state_opponents_collection,
                    $opponents_index, $battle_logs_collection, $heal_point, $selected_skill_data->target_range, null, null
                );
                break;
            case EffectType::Buff->value:
                BattleState::storePartyBuff(
                    'SKILL', $actor_data, $battle_state_opponents_collection, $opponents_index, $battle_logs_collection, $new_buff, $selected_skill_data->target_range
                );
                break;
        }

    }
}

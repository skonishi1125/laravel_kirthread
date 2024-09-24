<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Game\Rpg\BattleState;
use App\Models\Game\Rpg\Role;

use Barryvdh\Debugbar\Facades\Debugbar;

class Skill extends Model
{
    use HasFactory;
    protected $table = 'rpg_skills';

    const ATTACK_NO_TYPE        = 0; // 分類なし(ワイドガードなどのスキル)
    const ATTACK_PHYSICAL_TYPE  = 1; // 物理
    const ATTACK_MAGIC_TYPE     = 2; // 魔法

    const EFFECT_DAMAGE_TYPE = 1; // 攻撃系スキル
    const EFFECT_HEAL_TYPE   = 2; // 治療系スキル
    const EFFECT_BUFF_TYPE   = 3; // バフ系スキル

    const TARGET_RANGE_SINGLE = 1; // 単体を対象
    const TARGET_RANGE_ALL    = 2; // 全体を対象

    public function parties() {
      return $this->belongsToMany(Party::class, 'rpg_party_learned_skills', 'rpg_skill_id', 'rpg_party_id');
    }

    // 現在会得しているスキル情報を取得
    public static function getLearnedSkill($party) {
      $learned_skills = $party->skills->map(function($skill) {

        // レベルに応じた消費APのコスト計算 スキルの数だけ回すので、これはループの生成する必要がある
        $ap_cost_property = 'lv' . $skill->pivot->skill_level . '_ap_cost';
        $damage_percent_property = 'lv' . $skill->pivot->skill_level . '_percent';
        $ap_cost = 99; // デフォルト値。エラーの場合は99にしてとりあえずわかるようにしとく
        $damage_percent = 999; //デフォルト値。

        $skill_attributes = $skill->getAttributes(); // DBの情報を全て配列として扱えるようにする
        // lv1ならlv1_ap_cost, lv2ならlv2_ap_costを取得
        if (array_key_exists($ap_cost_property, $skill_attributes)) {
          $ap_cost = $skill_attributes[$ap_cost_property];
        }
        if (array_key_exists($damage_percent_property, $skill_attributes)) {
          $damage_percent = $skill_attributes[$damage_percent_property];
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
            'elemental_id' => $skill->elemental_id,
            'damage_percent' => $damage_percent,
        ];
      });
      return $learned_skills;
    }

    // 今からどのメソッドでこのスキルを処理するのか決める
    public static function decideExecSkill(
      $role_id, $selected_skill, $self_data, $opponents_data, $is_enemy, $opponents_index, $logs
    ) {
      Debugbar::debug("decideExecSkill(): --------------------");
      // 攻撃系スキル && 単体対象スキル($opponents_indexがnullでない)
      if ($selected_skill->effect_type == self::EFFECT_DAMAGE_TYPE && !is_null($opponents_index)) {
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

      // todo: 回復系や全体攻撃スキルの場合、敵全てが討伐済みになっても発動しちゃうかも

      switch ($role_id) {
        case Role::ROLE_STRIKER :
          Debugbar::debug('decideExecSkill(): 格闘家');
          $logs->push("{$self_data->name}は格闘家スキルを選択。");
          break;
        case Role::ROLE_MEDIC :
          Debugbar::debug('decideExecSkill(): 治療師');
          break;
        case Role::ROLE_PARADIN :
          Debugbar::debug('decideExecSkill(): 重騎士');
          self::decideExecParadinSkill($selected_skill, $self_data, $opponents_data, $opponents_index, $logs);
          break;
        case Role::ROLE_MAGE :
          Debugbar::debug('decideExecSkill(): 魔導士');
          self::decideExecMageSkill($selected_skill, $self_data, $opponents_data, $opponents_index, $logs);
          break;
        case Role::ROLE_RANGER :
          break;
        case Role::ROLE_BUFFER :
          break;
        default:
          break;
      }
    }

    /**
     * 重騎士
     */
    public static function decideExecParadinSkill($selected_skill, $self_data, $opponents_data, $opponents_index, $logs) {
      $damage     = null;
      $heal_point = null;

      // スキル処理
      switch ($selected_skill->id) {
        case 30 :
          Debugbar::debug('ワイドスラスト');
          $logs->push("{$self_data->name}の{$selected_skill->name}！");
          $damage = ($self_data->value_str * $selected_skill->damage_percent);
          break;
        case 31 :
          Debugbar::debug('ワイドガード');
          $logs->push("{$self_data->name}の{$selected_skill->name}！");
          $heal_point = 10;
          break;
        case 32 :
          Debugbar::debug('ブレイヴスラッシュ');
          $logs->push("{$self_data->name}の{$selected_skill->name}！大地を揺がす一撃が炸裂する！");
          $damage = ($self_data->value_str * $selected_skill->damage_percent) + $self_data->value_def;
          break;
        default:
          break;
      }

      self::separateStoreProcessAccrodingToEffectType(
        $selected_skill, $self_data, $opponents_data, $opponents_index, $logs, $damage, $heal_point
      );

    }


    /**
     * 魔導士
     */

    public static function decideExecMageSkill($selected_skill, $self_data, $opponents_data, $opponents_index, $logs) {
      $damage     = null;
      $heal_point = null;

      // スキル処理
      switch ($selected_skill->id) {
        case 40 :
          // 回復量 = (INT * ダメージ%)
          Debugbar::debug('ミニヒール');
          $logs->push("{$self_data->name}は{$selected_skill->name}を唱えた！");
          $heal_point = $self_data->value_int * $selected_skill->damage_percent;
          break;
        case 41 :
          // 回復量 = (INT * ダメージ%)
          Debugbar::debug('ポップヒール');
          $logs->push("{$self_data->name}の{$selected_skill->name}！癒しの霧が味方を包む！");
          $heal_point = $self_data->value_int * $selected_skill->damage_percent;
          break;
        case 42 :
          // 威力 = (INT * ダメージ%)
          Debugbar::debug('プチブラスト');
          $logs->push("{$self_data->name}は{$selected_skill->name}を唱えた！魔力の粒が相手を襲う！");
          $damage = $self_data->value_int * $selected_skill->damage_percent ;
          break;
        case 43 :
          // 威力 = (INT * ダメージ%) + 基礎ダメージ50
          Debugbar::debug('クラッシュボルト');
          // レベルごとに文章を変えられたら熱い
          $logs->push("{$self_data->name}は{$selected_skill->name}を唱えた！");
          $damage = ($self_data->value_int * $selected_skill->damage_percent) + 50;
          break;
        case 44 :
          // 威力 = (INT * ダメージ%) + 基礎ダメージ30
          Debugbar::debug('マナエクスプロージョン');
          // レベルごとに文章を変えられたら熱い
          $logs->push("{$self_data->name}の{$selected_skill->name}！解き放ったマナの塊が大爆発を起こす！");
          $damage = ($self_data->value_int * $selected_skill->damage_percent) + 30;
          break;
        default:
          break;
      }

      self::separateStoreProcessAccrodingToEffectType(
        $selected_skill, $self_data, $opponents_data, $opponents_index, $logs, $damage, $heal_point
      );

    }

    public static function separateStoreProcessAccrodingToEffectType(
      $selected_skill, $self_data, $opponents_data, $opponents_index, $logs, $damage, $heal_point 
    ) {
      Debugbar::debug("separateStoreProcessAccrodingToEffectType(): ----------------");
      Debugbar::debug($selected_skill);

      // 指定したスキルのAPを消費
      $self_data->value_ap -= $selected_skill->ap_cost;
      if ($self_data->value_ap < 0) $self_data->value_ap = 0;

      // skills.effect_typeに応じて処理を分ける
      switch($selected_skill->effect_type) {
        case self::EFFECT_DAMAGE_TYPE :
          $damage = ceil($damage);
          BattleState::storePartyDamage(
            'SKILL', $self_data, $opponents_data, $opponents_index, $logs, $damage, $selected_skill->target_range, $selected_skill->attack_type
          );
          break;
        case self::EFFECT_HEAL_TYPE :
          $heal_point = ceil($heal_point);
          BattleState::storePartyHeal(
            'SKILL', $self_data, $opponents_data, $opponents_index, $logs, $heal_point, $selected_skill->target_range, $selected_skill->attack_type
          );
          break;
        case self::EFFECT_BUFF_TYPE :
          $logs->push('バフ系のスキルが選択された。');
          break;
      }

    }


}

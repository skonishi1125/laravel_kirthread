<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Game\Rpg\BattleState;

use Barryvdh\Debugbar\Facades\Debugbar;

class Skill extends Model
{
    use HasFactory;
    protected $table = 'rpg_skills';

    const SKILL_CATEGORY_ATTACK = 1; // 攻撃系スキル
    const SKILL_CATEGORY_HEAL   = 2; // 治療系スキル
    const SKILL_CATEGORY_BUFF   = 3; // バフ系スキル

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
            'skill_category' => $skill->skill_category,
            'target_range' => $skill->target_range,
            'skill_level' => $skill->pivot->skill_level,  // pivotのskill_levelを取得
            'ap_cost' => $ap_cost,
            'damage_percent' => $damage_percent,
        ];
      });
      return $learned_skills;
    }

    // 今からどのメソッドでこのスキルを処理するのか決める
    public static function decideExecSkill($role_id, $selected_skill, $self_data, $opponents_data, $is_enemy, $opponents_index, $logs) {

      // 攻撃系スキルの場合
      if ($selected_skill->skill_category == self::SKILL_CATEGORY_ATTACK) {
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

      // todo: 回復系スキルの場合、敵全てが討伐済みになっても発動しちゃうかも

      switch ($role_id) {
        case "1":
          Debugbar::debug('decideExecSkill(): 格闘家');
          $logs->push("{$self_data->name}は格闘家スキルを選択。");
          break;
        case "2":
          Debugbar::debug('decideExecSkill(): 治療師');
          break;
        case "3":
          Debugbar::debug('decideExecSkill(): 重騎士');
          $logs->push("{$self_data->name}は重騎士スキルを選択。");
          break;
        case "4":
          Debugbar::debug('decideExecSkill(): 魔導士');
          self::decideExecMageSkill($selected_skill, $self_data, $opponents_data, $opponents_index, $logs);
          break;
        case "5":
          break;
        case "6":
          break;
        default:
          break;
      }
    }

    /**
     * 魔導士
     */

    public static function decideExecMageSkill($selected_skill, $self_data, $opponents_data, $opponents_index, $logs) {
      $skill_id       = $selected_skill->id;
      $self_int       = $self_data->value_int;
      // 範囲技の場合は後ほど個別に計算する。
      // 個別技でも後でバトルログ格納の時に考えたほうがいいかも。
      if ($opponents_index !== null) {
        $opponent_def   = $opponents_data[$opponents_index]->value_def;
        $opponent_int   = $opponents_data[$opponents_index]->value_int;
        $opponent_mdef = ($opponent_def * 0.25) + ($opponent_int * 0.75);
      }
      $damage_percent = $selected_skill->damage_percent;

      $damage     = null;
      $heal_point = null;

      // スキル処理
      switch ($skill_id) {
        case "2":
          // 回復量 = (INT * ダメージ%)
          Debugbar::debug('ヒールメイド');
          $logs->push("{$self_data->name}は{$selected_skill->name}を唱えた！");
          $heal_point = ($self_int * $damage_percent);
          break;
        case "4":
          // 回復量 = (INT * ダメージ%)
          Debugbar::debug('ヒールエクステンド');
          $logs->push("{$self_data->name}の{$selected_skill->name}！癒しの霧が味方を包む！");
          $heal_point = ($self_int * $damage_percent);
          break;
        case "7":
          // 威力 = (INT * ダメージ%)
          Debugbar::debug('プチブラスト');
          $logs->push("{$self_data->name}は{$selected_skill->name}を唱えた！魔力の粒が相手を襲う！");
          $damage = ($self_int * $damage_percent) - $opponent_mdef;
          break;
        case "8":
          // 威力 = (INT * ダメージ%) + 基礎ダメージ50
          Debugbar::debug('クラッシュボルト');
          // レベルごとに文章を変えられたら熱い
          $logs->push("{$self_data->name}の{$selected_skill->name}！マナの塊が大爆発を起こす！");
          $damage = (($self_int * $damage_percent) + 50 ) - $opponent_mdef;
          break;
        default:
          break;
      }

      Debugbar::debug("実数値計算。 ダメージ: '{$damage}' | 回復量: '{$heal_point}'");
      // AP消費処理
      $self_data->value_ap -= $selected_skill->ap_cost;

      if (!is_null($damage)) {
        $damage = ceil($damage);
        BattleState::storePartyDamage('SKILL', $self_data, $opponents_data, $logs, $damage);
      } else if (!is_null($heal_point)) {
        $heal_point = ceil($heal_point);
        BattleState::storePartyHeal('SKILL', $self_data, $opponents_data, $opponents_index, $logs, $heal_point, $selected_skill->target_range);
      }

    }


}

<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Barryvdh\Debugbar\Facades\Debugbar;

class Skill extends Model
{
    use HasFactory;
    protected $table = 'rpg_skills';

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
            'skill_level' => $skill->pivot->skill_level,  // pivotのskill_levelを取得
            'ap_cost' => $ap_cost,
            'damage_percent' => $damage_percent,
        ];
      });
      return $learned_skills;
    }

    // 今からどのメソッドでこのスキルを処理するのか決める
    public static function decideExecSkill($role_id, $selected_skill, $self_data, $opponents_data, $is_enemy, $index, $logs) {

      // スキル発動前に敵が討伐済みの場合、敵の選択を変更
      if ($opponents_data[$self_data->target_enemy_index]->is_defeated_flag == true) {
        $new_target_index = $opponents_data->search(function ($enemy) {
          return $enemy->is_defeated_flag == false;
        });
        if ($new_target_index !== false) {
          $self_data->target_enemy_index = $new_target_index;
          Debugbar::debug("(スキル)攻撃対象が討伐済みのため対象を変更。改めて攻撃対象: {$opponents_data[$self_data->target_enemy_index]->name}");
        } else {
          Debugbar::debug("すべての敵が討伐済みになったので、SKILLを使わず終了します。敵数: {$opponents_data->count()}");
          return;
        }
      }

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
          self::decideExecMageSkill($selected_skill, $self_data, $opponents_data, $index, $logs);
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

    public static function decideExecMageSkill($selected_skill, $self_data, $opponents_data, $index, $logs) {
      $skill_id = $selected_skill->id;
      $self_int = $self_data->value_int;
      $opponent_def = $opponents_data[$self_data->target_enemy_index]->value_def;
      $opponent_int = $opponents_data[$self_data->target_enemy_index]->value_int;
      $damage_percent = $selected_skill->damage_percent;

      $damage = 0;

      // 魔法防御力: (DEF * 0.25) + (INT * 0.5)
      $opponent_mdef = ($opponent_def * 0.25) + ($opponent_int * 0.50);

      switch ($skill_id) {
        case "7":
          // 威力 = (INT * ダメージ%)
          Debugbar::debug('プチブラスト');
          $logs->push("{$self_data->name}は{$selected_skill->name}を唱えた！小さく鋭い魔力が相手を襲う！");
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

      // 四捨五入しておく
      $damage = ceil($damage);
      Debugbar::debug("ダメージ実数値計算。 ダメージ: {$damage}");

      // AP消費処理
      $self_data->value_ap -= $selected_skill->ap_cost;

      if ($damage > 0) {
        Debugbar::debug("【SKILL】ダメージが1以上。敵の現在体力: {$opponents_data[$self_data->target_enemy_index]->value_hp}");
        $opponents_data[$self_data->target_enemy_index]->value_hp -= $damage;
        Debugbar::debug("攻撃した。敵の残り体力: {$opponents_data[$self_data->target_enemy_index]->value_hp}");
        // 敵を倒した場合
        if ($opponents_data[$self_data->target_enemy_index]->value_hp <= 0 ) {
          $opponents_data[$self_data->target_enemy_index]->value_hp = 0; // マイナスになるのを防ぐ。
          $opponents_data[$self_data->target_enemy_index]->is_defeated_flag = true;
          $logs->push("{$opponents_data[$self_data->target_enemy_index]->name}に{$damage}のダメージ！");
          $logs->push("{$opponents_data[$self_data->target_enemy_index]->name}を倒した！");
          Debugbar::debug("{$opponents_data[$self_data->target_enemy_index]->name}を倒した。敵の残り体力: {$opponents_data[$self_data->target_enemy_index]->value_hp} 敵討伐フラグ: {$opponents_data[$self_data->target_enemy_index]->is_defeated_flag} ");
        } else {
          $logs->push("{$opponents_data[$self_data->target_enemy_index]->name}に{$damage}のダメージ！");
          Debugbar::debug("{$opponents_data[$self_data->target_enemy_index]->name}はまだ生存している。敵の残り体力: {$opponents_data[$self_data->target_enemy_index]->value_hp} 敵討伐フラグ: {$opponents_data[$self_data->target_enemy_index]->is_defeated_flag} ");
        }
      // ダメージを与えられなかった場合
      } else {
        Debugbar::debug("ダメージを与えられない。");
        $logs->push("しかし{$opponents_data[$self_data->target_enemy_index]->name}にダメージは与えられなかった！");
        Debugbar::debug("攻撃が通らなかった。{$opponents_data[$self_data->target_enemy_index]->name}は当然生存している。敵の残り体力: {$opponents_data[$self_data->target_enemy_index]->value_hp} 敵討伐フラグ: {$opponents_data[$self_data->target_enemy_index]->is_defeated_flag} ");
      }

    }


}

<?php

namespace App\Models\Game\Rpg;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Party extends Model
{
    use HasFactory;

    protected $table = 'rpg_parties';

    protected $guarded = [
        'id',
    ];

    public static function boot()
    {
        parent::boot();

        // 削除した時、パーティの習得スキル情報だけ削除する
        static::deleting(function ($party) {
            $party->skills()->detach();
        });
    }

    public function savedata()
    {
        return $this->belongsTo(Savedata::class, 'savedata_id');
    }

    // $p->party_learned_skills
    public function party_learned_skills()
    {
        return $this->hasMany(PartyLearnedSkill::class, 'party_id');
    }

    /**
     * 多対多のリレーション
     *
     * @return BelongsToMany<Skill, $this>
     */
    public function skills()
    {
        // pivotの定義により、$party->skills[0]->pivot->skill_level というような形で中間tableの値を取得できる
        return $this
            ->belongsToMany(Skill::class, 'rpg_party_learned_skills', 'party_id', 'skill_id')
            ->withPivot('skill_level');
    }

    /**
     * @return belongsTo<Role, $this>
     */
    public function role(): BelongsTo
    {
        // 子側は自分の持つカラムを指定して、相手の主キーと紐づける
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function fetchNextLevelUpExp()
    {
        // 次に必要なEXPを取得
        $exp_model = Exp::where('level', $this->level + 1)->first();

        // Level MAXの場合は'-'を返す
        if (is_null($exp_model)) {
            return '-';
        }

        $next_level_up_exp = ($exp_model->total_exp - $this->total_exp);
        // マイナスになるようなら、'-'を返す
        if ($next_level_up_exp < 0) {
            return '-';
        } else {
            return $next_level_up_exp;
        }
    }

    /**
     * パーティメンバーに対して、引数に応じたステータスポイントの振り分けを行う
     *
     * @param  int  $input_point  振り分けるステータスポイント
     * @param  string  $status_type  'HP','STR'など振り分けるステータスの種類
     */
    public function allocateStatusPoint(int $input_point, string $status_type)
    {
        Debugbar::debug("Party::incrementeStatus({$input_point}, {$status_type}) ------------------------");

        $freely_status_point = $this->freely_status_point;
        // クリック連打やバグなどで予期せず振り分けられる形を防ぐ
        if ($freely_status_point < $input_point) {
            throw new \Exception('ステータスポイントが足りません。画面のリロードをお試しください。');
        }

        // $status_typeに応じて値を引き上げる
        switch ($status_type) {
            case 'HP':
                // HPだけ伸び幅を倍にする
                $this->update(['allocated_hp' => $this->allocated_hp + ($input_point * 2)]);
                break;
            case 'AP':
                $this->update(['allocated_ap' => $this->allocated_ap + $input_point]);
                break;
            case 'STR':
                $this->update(['allocated_str' => $this->allocated_str + $input_point]);
                break;
            case 'DEF':
                $this->update(['allocated_def' => $this->allocated_def + $input_point]);
                break;
            case 'INT':
                $this->update(['allocated_int' => $this->allocated_int + $input_point]);
                break;
            case 'SPD':
                $this->update(['allocated_spd' => $this->allocated_spd + $input_point]);
                break;
            case 'LUC':
                $this->update(['allocated_luc' => $this->allocated_luc + $input_point]);
                break;
        }

        // 振分けた分の自由ステータスポイントを減らす
        $this->update(['freely_status_point' => $this->freely_status_point - $input_point]);

    }

    /**
     * パーティメンバーに振り分けたステータス・スキルポイントを全てリセットする
     */
    public function resetStautsAndSkillPoint()
    {
        Debugbar::debug('resetStautsAndSkillPoint() ------------------------');
        // ステータスポイント
        // HPは倍上がる仕様のため、振り分けた分を2で割っておく
        $all_status_allocated_points =
            ($this->allocated_hp / 2) + $this->allocated_ap
            + $this->allocated_str + $this->allocated_def + $this->allocated_int
            + $this->allocated_spd + $this->allocated_luc;

        // スキルポイント処理
        // 習得しているスキルのレベル数を合計する
        $all_skill_allocated_points = $this->party_learned_skills()->sum('skill_level');

        // ステータス・スキルポイントの反映
        self::update([
            'allocated_hp' => 0,
            'allocated_ap' => 0,
            'allocated_str' => 0,
            'allocated_def' => 0,
            'allocated_int' => 0,
            'allocated_spd' => 0,
            'allocated_luc' => 0,
            'freely_status_point' => $this->freely_status_point + $all_status_allocated_points,
            'freely_skill_point' => $this->freely_skill_point + $all_skill_allocated_points,
        ]);

        // 修得スキルのリセット
        $this->party_learned_skills()->delete();
        Debugbar::debug('resetStautsAndSkillPoint() ------------------------正常終了。');
    }

    // $party: 戦闘終了後のrpg_battle_state.players_json_dataの一人分のデータ
    // Partyに配置せず、BattleStateの方に置き直してもいい。(というか、そうするべき)
    public static function calculateGaussianGrowth(&$party)
    {
        // Box-Muller法でガウス分布に従う乱数を生成
        $u1 = mt_rand() / mt_getrandmax();
        $u2 = mt_rand() / mt_getrandmax();
        $z = sqrt(-2 * log($u1)) * cos(2 * pi() * $u2);

        // 平均が mean のガウス分布の値に変換（分散を調整）
        // 例えばvarianceと, 成長率5で+9が出る確率
        // 1.0の場合 0.00003%, 2.0は0.6%, 3.0なら2.1%, 4.0なら4.7%
        $variance = 1.0; // 分散の調整

        // 上昇するステータスの分だけ回す(hp, ap, str, def, int, spd, luc)
        $role = Role::find($party->role_id);
        $growth_array = $role->exportGrowthArray();
        $increase_values = collect();

        foreach ($growth_array as $stat => $growth_value) {
            // ガウス分布計算 最低上昇値が1以上になるようにしておく
            $increase_value = max(1, ceil($growth_value + $z * $variance));
            // HPのみ、上昇幅は倍
            if ($stat === 'growth_hp') {
                $increase_values[$stat] = (int) ($increase_value * 2);
            } else {
                $increase_values[$stat] = (int) $increase_value;
            }
        }

        Debugbar::debug($increase_values);

        foreach ($increase_values as $stat => $increase) {
            switch ($stat) {
                case 'growth_hp':
                    $party->max_value_hp += $increase;
                    break;
                case 'growth_ap':
                    $party->max_value_ap += $increase;
                    break;
                case 'growth_str':
                    $party->value_str += $increase;
                    break;
                case 'growth_def':
                    $party->value_def += $increase;
                    break;
                case 'growth_int':
                    $party->value_int += $increase;
                    break;
                case 'growth_spd':
                    $party->value_spd += $increase;
                    break;
                case 'growth_luc':
                    $party->value_luc += $increase;
                    break;
            }
        }

        Debugbar::debug('calculateGaussianGrowth()---------------正常終了。');

        // レベルアップ上昇値をログに記載するため、上昇値の配列を返す
        return $increase_values;
    }

    /**
     * beginning画面で指定した情報から、パーティメンバーを作成する
     *
     * @return self $created_party
     */
    public static function generateRpgPartyMember($savedata_id, $role_id, $nickname)
    {
        $role_default_status = Role::getDefaultStatusById($role_id);

        $created_party = self::create([
            'savedata_id' => $savedata_id,
            'role_id' => $role_id,
            'nickname' => $nickname,
            'level' => 1,
            'value_hp' => $role_default_status['value_hp'],
            'value_ap' => $role_default_status['value_ap'],
            'value_str' => $role_default_status['value_str'],
            'value_def' => $role_default_status['value_def'],
            'value_int' => $role_default_status['value_int'],
            'value_spd' => $role_default_status['value_spd'],
            'value_luc' => $role_default_status['value_luc'],
            // EXP, statuspoint, skillpointはデータベース側の初期値として設定している。
        ]);

        return $created_party;
    }

    //
    /**
     * デバッグ用 ステータス初期値設定 \App\Models\Game\Rpg\Party::debugSetDefaultStatus();
     */
    public static function debugSetDefaultStatus()
    {
        $all_parties = self::get();

        foreach ($all_parties as $party) {
            $role_class = $party->role->class;
            switch ($role_class) {
                case Role::ROLE_STRIKER_CLASS_NAME:
                    $party->update([
                        'level' => 1,
                        'value_hp' => '40',
                        'value_ap' => '10',
                        'value_str' => '25',
                        'value_def' => '5',
                        'value_int' => '5',
                        'value_spd' => '25',
                        'value_luc' => '10',
                        'total_exp' => '0',
                    ]);
                    break;
                case Role::ROLE_MEDIC_CLASS_NAME:
                    $party->update([
                        'level' => 1,
                        'value_hp' => '30',
                        'value_ap' => '20',
                        'value_str' => '10',
                        'value_def' => '15',
                        'value_int' => '20',
                        'value_spd' => '10',
                        'value_luc' => '10',
                        'total_exp' => '0',
                    ]);
                    break;
                case Role::ROLE_PALADIN_CLASS_NAME:
                    $party->update([
                        'level' => 1,
                        'value_hp' => '50',
                        'value_ap' => '15',
                        'value_str' => '15',
                        'value_def' => '25',
                        'value_int' => '5',
                        'value_spd' => '5',
                        'value_luc' => '10',
                        'total_exp' => '0',
                    ]);
                    break;
                case Role::ROLE_MAGE_CLASS_NAME:
                    $party->update([
                        'level' => 1,
                        'value_hp' => '20',
                        'value_ap' => '25',
                        'value_str' => '5',
                        'value_def' => '10',
                        'value_int' => '25',
                        'value_spd' => '15',
                        'value_luc' => '10',
                        'total_exp' => '0',
                    ]);
                    break;
                case Role::ROLE_RANGER_CLASS_NAME:
                    $party->update([
                        'level' => 1,
                        'value_hp' => '40',
                        'value_ap' => '15',
                        'value_str' => '15',
                        'value_def' => '10',
                        'value_int' => '10',
                        'value_spd' => '20',
                        'value_luc' => '10',
                        'total_exp' => '0',
                    ]);
                    break;
                case Role::ROLE_BUFFER_CLASS_NAME:
                    $party->update([
                        'level' => 1,
                        'value_hp' => '30',
                        'value_ap' => '20',
                        'value_str' => '5',
                        'value_def' => '15',
                        'value_int' => '15',
                        'value_spd' => '20',
                        'value_luc' => '10',
                        'total_exp' => '0',
                    ]);
                    break;
            }
        }
        echo '初期値設定完了'.PHP_EOL;
    }

    // ガウス分布を使って成長を計算する関数
    // public static function calculateGaussianGrowth($mean){
    //   // Box-Muller法でガウス分布に従う乱数を生成
    //   $u1 = mt_rand() / mt_getrandmax();
    //   $u2 = mt_rand() / mt_getrandmax();
    //   $z = sqrt(-2 * log($u1)) * cos(2 * pi() * $u2);

    //   // 平均が mean のガウス分布の値に変換（分散を調整）
    //   // 1.0で+9が出る確率 = 約0.00003% 2.0は0.6%, 3.0なら2.1%, 4.0なら4.7%
    //   $variance = 1.0; // 分散の調整
    //   $growth = $mean + $z * $variance;

    //   // 成長値が0以下にならないように制限
    //   return max(1, round($growth));
    // }

}

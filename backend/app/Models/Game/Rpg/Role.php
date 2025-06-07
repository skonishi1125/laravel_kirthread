<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'rpg_roles';

    // 職業id
    const ROLE_STRIKER = 1;

    const ROLE_MEDIC = 2;

    const ROLE_PALADIN = 3;

    const ROLE_MAGE = 4;

    const ROLE_RANGER = 5;

    const ROLE_BUFFER = 6;

    const ROLE_NONE = 99; // 敵

    // 職業名
    const ROLE_STRIKER_CLASS_NAME = 'Striker';

    const ROLE_MEDIC_CLASS_NAME = 'Medic';

    const ROLE_PALADIN_CLASS_NAME = 'Paladin';

    const ROLE_MAGE_CLASS_NAME = 'Mage';

    const ROLE_RANGER_CLASS_NAME = 'Ranger';

    const ROLE_BUFFER_CLASS_NAME = 'Buffer';

    const ROLE_NONE_CLASS_NAME = 'Enemy';

    // 職業別 Lv.1時点でのステータス
    // TODO: Enumでgrowth * 10 とか、 growth * 5とかにすれば自動化できそう。
    public const STRIKER_DEFAULT_STATUS = [
        'value_hp' => 40,
        'value_ap' => 10,
        'value_str' => 25,
        'value_def' => 5,
        'value_int' => 5,
        'value_spd' => 25,
        'value_luc' => 10,
    ];

    public const MEDIC_DEFAULT_STATUS = [
        'value_hp' => 30,
        'value_ap' => 20,
        'value_str' => 10,
        'value_def' => 15,
        'value_int' => 20,
        'value_spd' => 10,
        'value_luc' => 10,
    ];

    public const PALADIN_DEFAULT_STATUS = [
        'value_hp' => 50,
        'value_ap' => 10,
        'value_str' => 15,
        'value_def' => 25,
        'value_int' => 10,
        'value_spd' => 5,
        'value_luc' => 10,
    ];

    public const MAGE_DEFAULT_STATUS = [
        'value_hp' => 20,
        'value_ap' => 25,
        'value_str' => 5,
        'value_def' => 10,
        'value_int' => 25,
        'value_spd' => 15,
        'value_luc' => 10,
    ];

    public const RANGER_DEFAULT_STATUS = [
        'value_hp' => 30,
        'value_ap' => 15,
        'value_str' => 15,
        'value_def' => 10,
        'value_int' => 15,
        'value_spd' => 20,
        'value_luc' => 10,
    ];

    public const BUFFER_DEFAULT_STATUS = [
        'value_hp' => 30,
        'value_ap' => 20,
        'value_str' => 15,
        'value_def' => 10,
        'value_int' => 15,
        'value_spd' => 15,
        'value_luc' => 10,
    ];

    /**
     * 渡したrole_idに対応するステータス情報を返す。
     *
     * @return array<string,int>
     */
    public static function getDefaultStatusById(int $role_id): array
    {
        return match ($role_id) {
            self::ROLE_STRIKER => self::STRIKER_DEFAULT_STATUS,
            self::ROLE_MEDIC => self::MEDIC_DEFAULT_STATUS,
            self::ROLE_PALADIN => self::PALADIN_DEFAULT_STATUS,
            self::ROLE_MAGE => self::MAGE_DEFAULT_STATUS,
            self::ROLE_RANGER => self::RANGER_DEFAULT_STATUS,
            self::ROLE_BUFFER => self::BUFFER_DEFAULT_STATUS,
            default => throw new \InvalidArgumentException('Invalid role_id: '.$role_id),
        };
    }

    public function Parties()
    {
        // 親側は相手のクラスを指定し、自分の主キーと紐づけようと思っている相手のカラムを指定する
        return $this->hasMany(Party::class, 'role_id');
    }

    // レベルアップ時に使うステータス値を配列として出力する
    public function exportGrowthArray()
    {
        return collect([
            'growth_hp' => $this->growth_hp,
            'growth_ap' => $this->growth_ap,
            'growth_str' => $this->growth_str,
            'growth_def' => $this->growth_def,
            'growth_int' => $this->growth_int,
            'growth_spd' => $this->growth_spd,
            'growth_luc' => $this->growth_luc,
        ]);

    }
}

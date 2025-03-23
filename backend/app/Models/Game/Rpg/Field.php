<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $table = 'rpg_fields';

    // 難易度の幅。最大5
    private const DIFFICULTY_RANGE = 5;

    public function savedata_cleared_field()
    {
        /**
         * tinkerでテストする場合の例
         *   $s = Savedata::find(2);
         *   $f->savedata_cleared_field; // 2 をクリアしているセーブデータの一覧がCollectonで返る
         *   $f->savedata_cleared_field->first()->savedata
         */
        return $this->hasMany(SavedataClearedField::class, 'field_id');
    }

    // 難易度を★☆☆☆☆ の形で返す。
    public function convertDifficultyStars()
    {
        $filled_stars = str_repeat('★', $this->difficulty);
        $empty_stars = str_repeat('☆', self::DIFFICULTY_RANGE - $this->difficulty);

        $difficulty_stars = $filled_stars.$empty_stars;

        return $difficulty_stars;
    }
}

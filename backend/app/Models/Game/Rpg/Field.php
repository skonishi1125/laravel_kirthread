<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $table = 'rpg_fields';

    // 難易度の幅。最大5
    const DIFFICULTY_RANGE = 5;

    // 難易度を★☆☆☆☆ の形で返す。
    public function convertDifficultyStars()
    {
        $filled_stars = str_repeat('★', $this->difficulty);
        $empty_stars = str_repeat('☆', self::DIFFICULTY_RANGE - $this->difficulty);

        $difficulty_stars = $filled_stars.$empty_stars;

        return $difficulty_stars;
    }
}

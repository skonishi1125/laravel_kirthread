<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Enemy extends Model
{
    use HasFactory;

    protected $table = 'rpg_enemies';

    protected $casts = [
        'is_boss' => 'boolean',
    ];

    // $e->enemy_learned_skills
    public function enemy_learned_skills()
    {
        return $this->hasMany(EnemyLearnedSkill::class, 'enemy_id');
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
            ->belongsToMany(Skill::class, 'rpg_enemy_learned_skills', 'enemy_id', 'skill_id')
            ->withPivot('skill_level');
    }
}

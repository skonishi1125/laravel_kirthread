<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnemyLearnedSkill extends Model
{
    use HasFactory;

    protected $table = 'rpg_enemy_learned_skills';

    protected $guarded = [
        'id',
    ];

    public function party()
    {
        return $this->belongsTo(Enemy::class);
    }
}

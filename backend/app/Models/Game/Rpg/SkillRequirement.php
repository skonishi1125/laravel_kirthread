<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillRequirement extends Model
{
    use HasFactory;

    protected $table = 'rpg_skill_requirements';

    protected $guarded = [
        'id',
    ];
}

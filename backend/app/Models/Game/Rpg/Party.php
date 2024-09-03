<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;
    protected $table = 'rpg_parties';

    public function skills() {
      return $this->belongsToMany(Skill::class, 'rpg_party_learned_skills', 'rpg_party_id', 'rpg_skill_id');
    }



}

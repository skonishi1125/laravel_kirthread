<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $table = 'rpg_skills';

    public function parties() {
      return $this->belongsToMany(Party::class, 'rpg_party_learned_skills', 'rpg_skill_id', 'rpg_party_id');
    }
    
}

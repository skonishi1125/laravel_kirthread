<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Game\Rpg\Party;

class PartyLearnedSkill extends Model
{
    use HasFactory;
    protected $table = 'rpg_party_learned_skills';

    // $s->party
    public function party() {
      return $this->belongsTo(Party::class);
    }

}

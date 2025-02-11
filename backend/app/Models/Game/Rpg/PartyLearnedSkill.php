<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyLearnedSkill extends Model
{
    use HasFactory;

    protected $table = 'rpg_party_learned_skills';

    protected $guarded = [
        'id',
    ];

    // $s->party
    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}

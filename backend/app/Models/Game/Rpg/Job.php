<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'rpg_jobs';

    protected $guarded = [
        'id',
    ];

    public function savedata()
    {
        return $this->belongsTo(Savedata::class);
    }
}

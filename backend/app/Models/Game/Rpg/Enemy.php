<?php

namespace App\Models\Game\Rpg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enemy extends Model
{
    use HasFactory;

    protected $table = 'rpg_enemies';

    protected $casts = [
        'is_boss' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReactionIcon extends Model
{
    protected $guarded = [
        'id',
    ];

    public function posts()
    {
        return $this->belongsToMany('App\Models\Post', 'reactions', 'reaction_icon_id', 'post_id');
    }
}

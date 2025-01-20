<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'icon',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function post()
    {
        return $this->hasMany('App\Models\Post', 'user_id');
    }

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }

    public function rpg_savedata()
    {
        return $this->hasOne('App\Models\Game\Rpg\Savedata');
    }
}

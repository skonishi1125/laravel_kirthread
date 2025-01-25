<?php

namespace App;

use App\Models\Game\Rpg\Savedata;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'icon',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
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

    /**
     * @return HasOne<Savedata, $this>
     */
    public function rpg_savedata(): HasOne
    {
        return $this->hasOne(Savedata::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'message', 'birth_year', 'birth_month', 'birth_day', 'sns_url',
    ];

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function makeLink($value)
    {
        return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>', $value);
    }

    public static function isCurrentUrlProfileShow($display_user_id)
    {
        $current_url = url()->current();
        $profile_show_url = url(
            route('profile_show', ['user_id' => $display_user_id])
        );

        return $current_url == $profile_show_url;
    }

    public static function isCurrentUrlProfileShowReacted($display_user_id)
    {
        $current_url = url()->current();
        $profile_show_reacted_url = url(
            route('profile_show_reacted', ['user_id' => $display_user_id])
        );

        return $current_url == $profile_show_reacted_url;
    }
}

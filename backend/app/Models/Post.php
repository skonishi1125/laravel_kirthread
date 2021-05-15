<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $guarded = [
      'id',
    ];

    public function makeLink($value) {
        return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)" , '<a href="\1\2">\1\2</a>' , $value);
    }

}

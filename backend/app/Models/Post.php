<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Support\Carbon;

class Post extends Model
{
    // クラスプロパティには、動的な値は入れられない。
    // https://qiita.com/H40831/items/15ebfbf7d9c05001b6df
    // private $now = Carbon::now();

    protected $guarded = [
      'id',
    ];

    public function makeLink($value) {
        return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)" , '<a href="\1\2">\1\2</a>' , $value);
    }

    public function isSetReaction($user_id, $post_id, $reaction_number = '') {
      $is_set_reaction = Reaction::where('user_id', $user_id)
          ->where('post_id', $post_id)
          ->where('reaction_number', $reaction_number)
          ->exists();
      // ログイン中のユーザが、その投稿に、同じリアクションをつけているかどうか。
      // true = つけている。 false = つけていない。
      return $is_set_reaction;
    }

    // クエリスコープ
    public function scopeRecently($query) {
        $last_month = new Carbon('last month');
        return $query->where('created_at', '>=', $last_month);
    }

    

}

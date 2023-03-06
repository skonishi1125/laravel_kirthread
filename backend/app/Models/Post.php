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

    // リレーション
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }


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

    public function getIsSetKaidddReactionAttribute() {
        $reactions = array_unique(explode(',', $this->reaction));
        $kaiddd_reactions = [5, 7];
        foreach ($kaiddd_reactions as $k_r) {
            if (in_array( $k_r, $reactions)) {
                return true;
            } else {
                continue;
            }
        }
        return false;
    }

    // ミューテタは他の部分の処理に影響が出るので、コメントアウトしておく。
    // public function setMessageAttribute($value) {
    //     $this->attributes['message'] = strtoupper($value);
    // }


}

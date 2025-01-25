<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function reaction_icons()
    {
        return $this->belongsToMany('App\Models\ReactionIcon', 'reactions', 'post_id', 'reaction_icon_id');
    }

    public function makeLink($value)
    {
        return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>', $value);
    }

    public function isSetReaction($user_id, $post_id, $reaction_icon_id = '')
    {
        $is_set_reaction = Reaction::where('user_id', $user_id)
            ->where('post_id', $post_id)
            ->where('reaction_icon_id', $reaction_icon_id)
            ->exists();

        // ログイン中のユーザが、その投稿に、同じリアクションをつけているかどうか。
        // true = つけている。 false = つけていない。
        return $is_set_reaction;
    }

    // クエリスコープ
    public function scopeRecently($query)
    {
        $last_month = new Carbon('last month');

        return $query->where('created_at', '>=', $last_month);
    }

    public function getIsSetKaidddReactionAttribute()
    {
        $reactions = array_unique(explode(',', $this->reaction));
        $kaiddd_reactions = [5, 7];
        foreach ($kaiddd_reactions as $k_r) {
            if (in_array($k_r, $reactions)) {
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

    public function acquireReactionType()
    {
        $reaction = Reaction::where('post_id', $this->id)->get();
        // $reaction = Reaction::get();
        // dd($reaction, $this->id);
    }

    public static function extractYoutubeVideoId($youtube_url)
    {
        // postにアップされる可能性のあるYouTubeのURL
        // https://www.youtube.com (PCブラウザで開いたURL)
        // https://m.youtube.com (モバイルで開いた際のURL)
        // https://youtu.be (共有ボタンなどから発行されるURL)
        // 動画IDは 11桁 が基本。

        if (! $youtube_url) {
            return null;
        }

        $youtube_video_id = '';

        if (substr($youtube_url, 0, 16) == 'https://youtu.be') {
            $youtube_video_id = substr($youtube_url, 17, 11);
        } else {
            preg_match('/v=((.){11})/', $youtube_url, $match);
            if (! empty($match[1])) {
                $youtube_video_id = $match[1];
            }
        }

        return $youtube_video_id;

    }
}

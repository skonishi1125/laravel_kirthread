<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{

    public function getPost(Request $request)
    {
        // 指定なしは20件の取得、最大1000件までの取得とする
        $item = isset($request['item'])? (int)$request['item'] : 20;
        if ($item > 1000) $item = 1000;

        $posts = Post::select('posts.id AS post_id', 'users.id AS user_id', 'users.name',
                'posts.message', 'posts.picture', 'posts.youtube_url', 'posts.created_at')
            ->join('users', 'posts.user_id', 'users.id')
            ->limit($item)
            ->orderBy('posts.id', 'desc')
            ->get();

        $rtn = [];
        $log_array = collect();

        foreach ($posts as $p) {
            $rtn[] = [
                'post_id'       =>  $p->post_id,
                'user_id'       =>  $p->user_id,
                'user_name'     =>  $p->name,
                'message'       =>  $p->message,
                'picture'       =>  $p->picture,
                'youtube_url'   =>  $p->youtube_url
            ];
            $log_array->push($p->post_id);
        }

        Log::channel('api')
            ->info(sprintf('"%s"件のpostを出力するAPIが使用されました。', $item), [$log_array]);

        return response()->json($rtn);
    }

}

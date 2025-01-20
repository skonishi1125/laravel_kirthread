<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function getPost(Request $request)
    {
        // 指定なしは20件の取得、最大1000件までの取得とする
        $item = isset($request['item']) ? (int) $request['item'] : 20;
        if ($item > 1000) {
            $item = 1000;
        }

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
                'post_id' => $p->post_id,
                'user_id' => $p->user_id,
                'user_name' => $p->name,
                'message' => $p->message,
                'picture' => $p->picture,
                'youtube_url' => $p->youtube_url,
            ];
            $log_array->push($p->post_id);
        }

        Log::channel('apilog')
            ->info(sprintf('"%s"件のpostを出力するAPIが使用されました。', $item), [$log_array]);

        return response()->json($rtn);
    }

    public function getJson()
    {
        // localhost / 127.0.0.1 環境だと正常に動作しないので、条件分岐しておく
        if (config('app.env') != 'production') {
            $url = 'https://kir-thread.site/api/post?item=10';
        } else {
            $url_path = route('api_post');
            $param = '?item=';
            $item_num = 10;
            $url = $url_path.$param.$item_num;
        }

        $json_raw_data = file_get_contents($url);
        $json_convert_data = mb_convert_encoding($json_raw_data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $array = json_decode($json_convert_data, false);

        $name_arr = [];
        foreach ($array as $a) {
            $name_arr[] = $a->user_name;
        }

        // 10人の名前をjsonでまた返す
        return response()->json($name_arr);
    }
}

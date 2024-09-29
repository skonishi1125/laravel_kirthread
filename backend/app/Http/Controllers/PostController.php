<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
Use App\User;
Use App\Models\Post;
Use App\Models\Reaction;
use App\Models\ReactionIcon;
use App\ReactionIcon as AppReactionIcon;
Use Auth;
use Illuminate\Support\Facades\Log;

// 画像リサイズに使用する
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class PostController extends Controller
{
    public function index()
    {
        // 投稿と、投稿したユーザーの取得
        $posts = Post::orderBy('id','desc')->paginate(50);
        $reaction_icons = ReactionIcon::all();

        $posts->map(function ($value) use ($reaction_icons) {
            $reaction_count_collection = collect();
            // 投稿1件に、どのリアクションが何件付いているのかを調べる。
            foreach ($reaction_icons as $r) {
                $attached_r_count = Reaction::where('post_id', $value->id)
                            ->where('reaction_icon_id', $r->id)
                            ->count();
                $reaction_count_collection->push([
                    'reaction_icon_id' => $r->id,
                    'is_picture_icon'  => $r->is_picture_icon,
                    'value'            => $r->value,
                    'name'             => $r->name,
                    'name_plural'      => $r->name_plural,
                    'url'              => $r->url,
                    'count'            => $attached_r_count
                ]);
            }
            $value['attached_reactions'] = $reaction_count_collection;
        });

        return view('index')
            ->with('posts', $posts)
            ->with('reaction_icons', $reaction_icons)
        ;
    }

    public function store(StorePostRequest $request)
    {
        // ファイルのアップロード
        if ($request->hasFile('picture')) {
          $picture = $request->file('picture');

          // 画像リサイズ
          $manager = new ImageManager(new Driver());
          $resized_picture = $manager
            ->read($picture->getPathname())
            ->scaleDown(width: 600); // これ以下のものをアップした場合は、拡張せずそのまま
          $picture_name = uniqid('pic_') . '.' . $request->file('picture')->guessExtension();

          $resized_picture->save(storage_path('app/public/uploads/' . $picture_name));
        } else {
            $picture_name = null;
        }

        if (isset($request->reply_post_id)) {
            $reply_post_id = null;
        } else {
            $reply_post_id = $request->reply_post_id;
        }

        // YouTube処理
        $youtube_video_id = null;
        if (isset($request->youtube_url)) {
          $youtube_video_id = Post::extractYoutubeVideoId($request->youtube_url);
        }

        $create = Post::create([
            'message' => $request->message,
            'picture' => $picture_name,
            'youtube_url' => $youtube_video_id,
            'good' => 0,
            'user_id' => Auth::id(),
            'reply_post_id' => $reply_post_id,
        ]);
        $create->save();

        Log::channel('postlog')
            ->debug('投稿が行われました。', [
                'id' => $create->id,
                'message' => $create->message,
                'picture' => $create->picture,
                'youtube_url' => $create->youtube_url,
                'user_id' => $create->user_id
            ]);

        return redirect('/');
    }

    public function show($id)
    {
        $post = Post::find($id);

        $reaction_icons = ReactionIcon::all();
        $reaction_count_collection = collect();
        // 投稿1件に、どのリアクションが何件付いているのかを調べる。
        foreach ($reaction_icons as $r) {
            $attached_r_count = Reaction::where('post_id', $post->id)
                        ->where('reaction_icon_id', $r->id)
                        ->count();
            $reaction_count_collection->push([
                'reaction_icon_id' => $r->id,
                'is_picture_icon'  => $r->is_picture_icon,
                'value'            => $r->value,
                'name'             => $r->name,
                'name_plural'      => $r->name_plural,
                'url'              => $r->url,
                'count'            => $attached_r_count
            ]);
        }
        $post['attached_reactions'] = $reaction_count_collection;

        $reactioned_user_ids = Reaction::where('post_id', $id)
            ->groupBy('user_id')
            ->get(['user_id']);
        $users = [];
        foreach($reactioned_user_ids as $u_id) {
            $id = $u_id['user_id'];
            $users[] = User::where('id', $id)->first();
        }
        return view('show')
            ->with('post', $post)
            ->with('users', $users)
            ->with('reaction_icons', $reaction_icons)
            ;
    }

    public function destroy(Request $request)
    {
        $delete = Post::find($request->post_id);
        $delete->delete();
        return redirect('/');
    }

    // public function addReaction($user_id, $post_id, $reaction_icon_id)
    // {
    //     $reaction = Reaction::create([
    //         'user_id' => $user_id,
    //         'post_id' => $post_id,
    //         'reaction_icon_id' => $reaction_icon_id,
    //     ]);

    //     $post = Post::find($post_id);
    //     if (is_null($post->reaction)) {
    //         $post->update([
    //             'reaction' => $reaction_icon_id,
    //         ]);
    //     } else {
    //       $post->update([
    //           'reaction' => $post->reaction . ',' . $reaction_icon_id,
    //       ]);
    //     }
    //     return redirect()->route('/');
    // }

    // public function removeReaction($user_id, $post_id, $reaction_icon_id)
    // {
    //     // もしボタンを押したユーザがその投稿に同じリアクションをしていたら、ボタンで解除する
    //     $post = Post::find($post_id);
    //     $user = User::find($user_id);
    //     $post_reactions = explode(",", $post->reaction);
        
    //     // postのリアクションから削除する
    //     if (count($post_reactions) > 0) {
    //         for ($i = count($post_reactions) - 1; $i >= 0; $i-- ) {
    //             if ( $post_reactions[$i] == $reaction_icon_id) {
    //                 array_splice($post_reactions, $i, 1);
    //                 break;
    //             }
    //         }
    //         // 判定のためバラバラにしたものをまたカンマで繋げ直す 1,2,3...
    //         $post->reaction = implode(",", $post_reactions);
    //         $post->save();
    //     } else {
    //         // post.reactionsを空にする
    //         $post->reaction = null;
    //     }
      
    //     // reactionsテーブルからも削除
    //     $remove_reaction_icon_id = Reaction::where('user_id', $user_id)
    //         ->where('post_id', $post_id)
    //         ->where('user_id', $user_id)
    //         ->where('reaction_icon_id', $reaction_icon_id)
    //         ->delete();

    //     return redirect()->route('/');
    // }

    // public function selectReaction(Request $request) {
    //     $post = Post::find($request->post_id);
    //     $bool = $post->isSetReaction($request->user_id, $request->post_id, $request->reaction_icon_id);

    //     if ($bool == true) {
    //         return redirect()
    //         ->route('remove_reaction', [
    //         'user_id' => $request->user_id,
    //         'post_id' => $request->post_id,
    //         'reaction_icon_id' => $request->reaction_icon_id,
    //         ]);
    //     } else {
    //         return redirect()
    //         ->route('add_reaction', [
    //         'user_id' => $request->user_id,
    //         'post_id' => $request->post_id,
    //         'reaction_icon_id' => $request->reaction_icon_id,
    //         ]);
    //     }
    // }

    public function ajaxReaction(Request $request) {
      $data = $request->all();

      // js側でpostIdなどの配列名を変えた場合もスムーズにコード修正ができるよう代入しておく。
      $post_id          = $data['postId'];
      $user_id          = $data['userId'];
      $reaction_icon_id = $data['reactionIconId'];
      $status           = $data['status']; // 0: 他人が押下済みのリアクション 1: 自分が押下済みのリアクション

      if ($status == 0) {
        \Debugbar::info('追加 | user_id: ' . $user_id . ' post_id: ' . $post_id . 'reaction_icon_id: ' . $reaction_icon_id);
        $create_reaction = Reaction::create([
          'user_id' => $user_id,
          'post_id' => $post_id,
          'reaction_icon_id' => $reaction_icon_id,
        ]);
      } else {
        \Debugbar::info('削除 | user_id: ' . $user_id . ' post_id: ' . $post_id . 'reaction_icon_id: ' . $reaction_icon_id);
        // バグや多重サブミットで同じユーザーからリアクションが複数付与されている場合があるのでついでに消す
        $remove_reactions = Reaction::where('user_id', $user_id)
          ->where('post_id', $post_id)
          ->where('reaction_icon_id', $reaction_icon_id)
          ->delete();
        // \DebugBar::info($remove_reactions);
      }

      // 24.6.10 現状使用していないが、操作したデータを改めて配列としてまとめておく
      $adjusted_data = [
        'post_id'           => $post_id,
        'user_id'           => $user_id,
        'reaction_icon_id'  => $reaction_icon_id,
        'status'            => $status
      ];

      // returnしているがjs側では使用していない。
      return $adjusted_data;
    }

}

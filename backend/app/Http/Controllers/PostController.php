<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\User;
Use App\Models\Post;
Use App\Models\Reaction;
use App\Models\ReactionIcon;
use App\ReactionIcon as AppReactionIcon;
Use Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 投稿と、投稿したユーザーの取得
        $posts = Post::orderBy('id','desc')->paginate(50);
        return view('index')
            ->with('posts', $posts)
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $validate = $request->validate([
          'message' => 'required_without_all:picture,youtube_url|max:255', // picture, youtube_urlと空白を入れてはいけない
          'picture' => 'nullable|image',
          'youtube_url' => 'nullable|starts_with:https://www.youtube.com,https://m.youtube.com,https://youtu.be',
        ],
        [
          'message.required_without_all' => '投稿が未記入です。',
          'message.max' => '投稿は最大255文字までです。',
          'picture.image' => 'こちらの写真の拡張子は非対応です。',
          'youtube_url.starts_with' => '動画URLの形式が誤っています。',
        ]);

        // ファイルのアップロード
        if ($request->hasFile('picture')) {
          $picture = $request->file('picture');
          $picture_name = uniqid('pic_') . '.' . $request->file('picture')->guessExtension();
          // ストレージフォルダに保存する。
          $path = storage_path('app/public/uploads');
          $picture->move($path, $picture_name);
        } else {
          $picture_name = null;
        }

        if (isset($request->reply_post_id)) {
          $reply_post_id = null;
        } else {
          $reply_post_id = $request->reply_post_id;
        }

        // YouTube処理
        $youtube_id = null;
        if (isset($request->youtube_url)) {
          if (substr($request->youtube_url, 0, 16) == 'https://youtu.be') {
            $youtube_id = substr($request->youtube_url, -11);
          } else {
            preg_match('/v=((.){11})/', $request->youtube_url, $match);
            $youtube_id = $match[1];
          }
        }

        $create = Post::create([
          'message' => $request->message,
          'picture' => $picture_name,
          'youtube_url' => $youtube_id,
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id);

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
            ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request);
        $delete = Post::find($request->post_id);
        $delete->delete();
        return redirect('/');

    }

    public function addReaction($user_id, $post_id, $reaction_icon_id)
    {
        // dd($user_id, $post_id, $reaction_icon_id);
        $reaction = Reaction::create([
            'user_id' => $user_id,
            'post_id' => $post_id,
            'reaction_icon_id' => $reaction_icon_id,
        ]);
        // create, updateはsave()不要

        // postのreactionに値を入れる
        $post = Post::find($post_id);
        // nullなら
        if (is_null($post->reaction)) {
            $post->update([
                'reaction' => $reaction_icon_id,
            ]);
        } else {
          $post->update([
              'reaction' => $post->reaction . ',' . $reaction_icon_id,
          ]);
        }
        return redirect()->route('/');
    }

    public function removeReaction($user_id, $post_id, $reaction_icon_id)
    {
      // もしボタンを押したユーザがその投稿に同じリアクションをしていたら、ボタンで解除する
      $post = Post::find($post_id);
      $user = User::find($user_id);
      $post_reactions = explode(",", $post->reaction);
      
      // postのリアクションから削除する
      if (count($post_reactions) > 0) {
        for ($i = count($post_reactions) - 1; $i >= 0; $i-- ) {
          if ( $post_reactions[$i] == $reaction_icon_id) {
            // dd($post_reactions);
            array_splice($post_reactions, $i, 1);
            break;
          }
        }
        // 判定のためバラバラにしたものをまたカンマで繋げ直す 1,2,3...
        $post->reaction = implode(",", $post_reactions);
        $post->save();
      } else {
        // post.reactionsを空にする
        $post->reaction = null;
      }
      
      // reactionsテーブルからも削除
      $remove_reaction_icon_id = Reaction::where('user_id', $user_id)
          ->where('post_id', $post_id)
          ->where('user_id', $user_id)
          ->where('reaction_icon_id', $reaction_icon_id)
          ->delete();

      return redirect()->route('/');
    }

    public function selectReaction(Request $request) {
      $post = Post::find($request->post_id);
      $bool = $post->isSetReaction($request->user_id, $request->post_id, $request->reaction_icon_id);

      if ($bool == true) {
        return redirect()
        ->route('remove_reaction', [
          'user_id' => $request->user_id,
          'post_id' => $request->post_id,
          'reaction_icon_id' => $request->reaction_icon_id,
        ]);
      } else {
        return redirect()
        ->route('add_reaction', [
          'user_id' => $request->user_id,
          'post_id' => $request->post_id,
          'reaction_icon_id' => $request->reaction_icon_id,
        ]);
      }

    }

    public function ajaxReaction(Request $request) {
      $data = $request->all();
      $post = Post::find($data['post_id']);
      $user = User::find($data['user_id']);

      $reactions = explode(",", $post->reaction);
      $counts = array_count_values($reactions); // 1(👀)のリアクションが何件か、などのデータ
      $reactions = array_unique($reactions); // 1のリアクションがあるのかどうかだけを確認する

      \Debugbar::info('テスト');

      // そのpostにそのuserがそのreaction済みなのかどうか
      $is_react = Reaction::where('user_id', $user->id)
        ->where('post_id', $post->id)
        ->where('reaction_icon_id', $data['reaction_icon_id'])
        ->first();
      \Debugbar::info('テスト', [$is_react]);
      if ($is_react !== null) {
        $is_react = true;
      } else {
        $is_react = false;
      }

      // リアクションの追加
      if ($data['status'] == 0) {
        \Debugbar::info('追加します。対象は↓', $data['reaction_icon_id']);
        $reaction = Reaction::create([
          'user_id' => $user->id,
          'post_id' => $post->id,
          'reaction_icon_id' => $data['reaction_icon_id'],
        ]);
        if (is_null($post->reaction)) {
          $post->update([
              'reaction' => $data['reaction_icon_id'],
          ]);
        } else {
          $post->update([
              'reaction' => $post->reaction . ',' . $data['reaction_icon_id'],
          ]);
        }
      // リアクションの削除
      } else {
        \Debugbar::info('削除です。対象は↓', $data['reaction_icon_id']);
        $post_reactions = explode(",", $post->reaction);
      
        if (count($post_reactions) > 0) {
          for ($i = count($post_reactions) - 1; $i >= 0; $i-- ) {
            if ( $post_reactions[$i] == $data['reaction_icon_id']) {
              array_splice($post_reactions, $i, 1);
              break;
            }
          }

          $post->reaction = implode(",", $post_reactions);
          $post->save();
        } else {
          $post->reaction = null;
        }
        
        $remove_reaction_icon_id = Reaction::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->where('reaction_icon_id', $data['reaction_icon_id'])
            ->delete();
      }

      \Debugbar::info($data, $post, $counts, $reactions, $is_react);
      

      $data = [
        'post' => $post,
        'is_react' => $is_react,
      ];

      return $data;
    }


}

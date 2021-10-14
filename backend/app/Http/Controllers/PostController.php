<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\User;
Use App\Models\Post;
Use App\Models\Reaction;
Use Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $posts = Post::orderBy('id','desc')->paginate(50);
      $users = User::get();
      $reactions = Reaction::get();
      // dd($posts);
      return view('index', compact('posts','users','reactions'));
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
          'message' => 'required|max:128',
          'picture' => 'nullable|image',
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

        $create = Post::create([
          'message' => $request->message,
          'picture' => $picture_name,
          'good' => 0,
          'user_id' => Auth::id(),
          'reply_post_id' => $reply_post_id,
        ]);
        $create->save();

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
        return view('show',compact('post'));
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

    public function addReaction($user_id, $post_id, $reaction_number)
    {
        // dd($user_id, $post_id, $reaction_number);
        $reaction = Reaction::create([
            'user_id' => $user_id,
            'post_id' => $post_id,
            'reaction_number' => $reaction_number,
        ]);
        // create, updateはsave()不要

        // postのreactionに値を入れる
        $post = Post::find($post_id);
        // nullなら
        if (is_null($post->reaction)) {
            $post->update([
                'reaction' => $reaction_number,
            ]);
        } else {
          $post->update([
              'reaction' => $post->reaction . ',' . $reaction_number,
          ]);
        }
        return redirect()->route('/');
    }

    public function removeReaction($user_id, $post_id, $reaction_number)
    {
      // もしボタンを押したユーザがその投稿に同じリアクションをしていたら、ボタンで解除する
      $post = Post::find($post_id);
      $user = User::find($user_id);
      $post_reactions = explode(",", $post->reaction);
      
      // postのリアクションから削除する
      if (count($post_reactions) > 0) {
        for ($i = count($post_reactions) - 1; $i >= 0; $i-- ) {
          if ( $post_reactions[$i] == $reaction_number) {
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
      $remove_reaction_number = Reaction::where('user_id', $user_id)
          ->where('post_id', $post_id)
          ->where('user_id', $user_id)
          ->where('reaction_number', $reaction_number)
          ->delete();

      return redirect()->route('/');
    }

    public function selectReaction(Request $request) {
      $post = Post::find($request->post_id);
      $bool = $post->isSetReaction($request->user_id, $request->post_id, $request->reaction_number);

      if ($bool == true) {
        return redirect()
        ->route('remove_reaction', [
          'user_id' => $request->user_id,
          'post_id' => $request->post_id,
          'reaction_number' => $request->reaction_number,
        ]);
      } else {
        return redirect()
        ->route('add_reaction', [
          'user_id' => $request->user_id,
          'post_id' => $request->post_id,
          'reaction_number' => $request->reaction_number,
        ]);
      }

    }


}

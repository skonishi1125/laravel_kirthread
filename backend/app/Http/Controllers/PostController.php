<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\User;
Use App\Models\Post;
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
      $posts = Post::orderBy('id','desc')->paginate(10);
      $users = User::get();
      // dd($posts);
      return view('index', compact('posts','users'));
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
          'picture' => 'nullable|mimes:jpeg,JPEG,jpg,JPG,png,PNG,gif,GIF,heic,HEIC',
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
    public function destroy($id)
    {
        //
        $delete = Post::find($id);
        $delete->delete();

        return redirect('/');

    }
}

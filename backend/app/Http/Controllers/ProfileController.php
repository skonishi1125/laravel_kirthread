<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Profile;
use App\Models\Reaction;
use App\Models\ReactionIcon;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $display_user = User::find($id);
        // profileがなければ新規作成する
        if (! isset($display_user->profile)) {
            $profile = Profile::create([
                'user_id' => $display_user->id,
                'message' => 'よろしくお願いします。',
            ]);
        }

        $posts = Post::where('user_id', $display_user->id)
            ->orderby('created_at', 'desc')
            ->paginate(50);
        $reaction_icons = ReactionIcon::all();

        $posts->map(function ($value) use ($reaction_icons) {
            $reaction_count_collection = collect();
            // 投稿1件に、どのリアクションが何件付いているのかを調べる。
            foreach ($reaction_icons as $r) {
                $attached_r_count = Reaction::where('post_id', $value->id)
                    ->where('reaction_icon_id', $r->id)
                    ->count();
                // dd($value, $value->user->id, $r->id, $attached_r_count);
                $reaction_count_collection->push([
                    'reaction_icon_id' => $r->id,
                    'is_picture_icon' => $r->is_picture_icon,
                    'value' => $r->value,
                    'name' => $r->name,
                    'name_plural' => $r->name_plural,
                    'url' => $r->url,
                    'count' => $attached_r_count,
                ]);
            }
            $value['attached_reactions'] = $reaction_count_collection;
        });

        return view('profile.show')
            ->with('display_user', $display_user)
            ->with('posts', $posts)
            ->with('reaction_icons', $reaction_icons);
    }

    public function show_reacted($id)
    {
        $display_user = User::find($id);

        // プロフィールを表示する対象ユーザーがリアクションした投稿を取得する
        $display_user_reaction_post_ids = Reaction::where('user_id', $display_user->id)->pluck('post_id');
        $posts = Post::whereIn('id', $display_user_reaction_post_ids)
            ->orderby('created_at', 'desc')
            ->paginate(50);

        $reaction_icons = ReactionIcon::all();

        $posts->map(function ($value) use ($reaction_icons) {
            $reaction_count_collection = collect();
            // 投稿1件に、どのリアクションが何件付いているのかを調べる。
            foreach ($reaction_icons as $r) {
                $attached_r_count = Reaction::where('post_id', $value->id)
                    ->where('reaction_icon_id', $r->id)
                    ->count();
                // dd($value, $value->user->id, $r->id, $attached_r_count);
                $reaction_count_collection->push([
                    'reaction_icon_id' => $r->id,
                    'is_picture_icon' => $r->is_picture_icon,
                    'value' => $r->value,
                    'name' => $r->name,
                    'name_plural' => $r->name_plural,
                    'url' => $r->url,
                    'count' => $attached_r_count,
                ]);
            }
            $value['attached_reactions'] = $reaction_count_collection;
        });

        // profileがなければ新規作成する
        if (! isset($display_user->profile)) {
            $profile = Profile::create([
                'user_id' => $display_user->id,
                'message' => 'よろしくお願いします。',
            ]);
        }

        return view('profile.show_reacted')
            ->with('display_user', $display_user)
            ->with('posts', $posts)
            ->with('reaction_icons', $reaction_icons);
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
    }
}

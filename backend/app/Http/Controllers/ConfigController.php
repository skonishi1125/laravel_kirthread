<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Profile;
Use Auth;

class ConfigController extends Controller
{

    public function index() {
        $user = Auth::user();
        return view('config.index')
            ->with('user', $user)
            ;
    }

    public function store(Request $request) {

        $user = Auth::user();
        $validate = $request->validate([
            'message' =>  'nullable|max:10',
            'icon'    =>  'nullable|image',
        ]);

        $icon_name = null;
        $message   = null;

        if (isset($request->icon)) {
            $icon = $request['icon'];
            $icon_name = uniqid('icon_') . '.' . $icon->guessExtension();
            $path = storage_path('app/public/icons');
            $icon->move($path, $icon_name);

            $add_icon = User::find(Auth::id());
            $add_icon->update(['icon' => $icon_name]);
        }

        if (isset($request->message)) {
            $message = $request->message;
        }

        // profileが存在しなければ作成しておく
        if (!isset($user->profile)) {
            $profile = Profile::create(['user_id'  =>  $user->id]);
        } else {
            $profile = $user->profile;
        }

        $profile->update(['message' => $message]);

        return redirect()->route('profile_show', ['user_id' => $user->id]);
    }
}

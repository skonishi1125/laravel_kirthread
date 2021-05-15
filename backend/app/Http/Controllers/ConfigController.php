<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
Use Auth;

class ConfigController extends Controller
{
    //
    public function index() {
      return view('config.index');
    }

    public function store(Request $request) {
      // バリデーション
      // dd($request);
      $validate = $request->validate([
        'icon' => 'nullable|image',
      ]);

      $icon = $request['icon'];
      $icon_name = uniqid('icon_') . '.' . $icon->guessExtension();
      $path = storage_path('app/public/icons');
      $icon->move($path, $icon_name);

      $add_icon = User::find(Auth::id());
      $add_icon->update([
                    'icon' => $icon_name,
                  ]);
      $add_icon->save();

      return redirect('/');
    }
}

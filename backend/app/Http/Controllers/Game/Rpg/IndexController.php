<?php

namespace App\Http\Controllers\Game\Rpg;

use App\Http\Controllers\Controller;
use Auth;

class IndexController extends Controller
{
    public function index()
    {
        // 未ログインユーザーがメニュー画面などにURL直打ちでアクセスした場合は、トップにリダイレクトさせる
        if (! Auth::check()) {
            if (request()->path() !== 'game/rpg') {
                return redirect('/game/rpg');
            }
        }

        return view('game/rpg/index');
    }
}

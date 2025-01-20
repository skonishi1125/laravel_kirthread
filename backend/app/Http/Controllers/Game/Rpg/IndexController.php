<?php

namespace App\Http\Controllers\Game\Rpg;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('game/rpg/index');
    }
}

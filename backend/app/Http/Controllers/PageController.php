<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    public function about() {
      return view('about/about');
    }

    public function game() {
      return view('game/index');
    }

    public function panel() {
      return view('game/panel');
    }
}

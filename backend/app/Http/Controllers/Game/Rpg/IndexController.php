<?php

namespace App\Http\Controllers\Game\Rpg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
  public function index() {
    return view('game/rpg/index');
  }
}

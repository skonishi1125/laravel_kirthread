<?php

namespace App\Http\Controllers\Study\TechBook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VueController extends Controller
{
  public function chapter4() {
    return view('study/techbook/vue/chapter4');
  }

  public function chapter8() {
    return view('study/techbook/vue/chapter8');
  }
}

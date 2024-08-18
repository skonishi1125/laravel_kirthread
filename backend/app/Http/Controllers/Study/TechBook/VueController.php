<?php

namespace App\Http\Controllers\Study\TechBook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use Auth;

class VueController extends Controller
{
  public function chapter4() {
    return view('study/techbook/vue/chapter4');
  }

  public function chapter8_purchases() {
    // $purchases = [
    //   [
    //     'date'        => '2017-11-21',
    //     'price'       => '25',
    //     'description' => 'Dog Food'
    //   ],
    //   [
    //     'date'        => '2017-11-21',
    //     'price'       => '50',
    //     'description' => 'Reastaurant Bill'
    //   ],
    //   [
    //     'date'        => '2017-11-21',
    //     'price'       => '37',
    //     'description' => 'Gasoline'
    //   ]
    // ];
    // return $purchases; // jsonで。

    $purchases = Auth::user()->purchases()->orderBy('date', 'desc')->get();
    return $purchases;

  }

  public function chapter8() {

    return view('study/techbook/vue/chapter8');
  }
}

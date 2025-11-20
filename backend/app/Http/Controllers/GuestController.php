<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class GuestController extends Controller
{

    public function showUpgradeForm()
    {
        if (!auth()->user()->is_guest) {
            return redirect('/');
        }
        dd('aa');
    }

    public function postForm(Request $request) {
        dd('post');
    }
}

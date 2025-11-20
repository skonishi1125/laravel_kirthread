<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * ゲーム画面で作成された簡易登録作成ユーザーについて管理する
 */
class GuestController extends Controller
{
    public function showUpgradeForm()
    {
        if (! auth()->user()->is_guest) {
            return redirect('/');
        }

        $name = auth()->user()->name;

        return view('guest.input')
            ->with('name', $name);
    }

    public function postForm(Request $request)
    {
        $user = Auth::user();
        if (! $user || ! $user->is_guest) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:10'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Profileはゲスト登録時に作っている
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_guest' => false,
        ]);

        return redirect()->route('home');
    }
}

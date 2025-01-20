<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        // dd($data);
        /*
         $dataは単なる文字列(array)で定義されているため、hasFileやguessExtensionなどは使えない。
         上記が使えるのはRequestとして呼び出した時だけ。
         ひとまずアイコン画像の設定は別のコントローラで行うことにする。
        */
        // if ($data['icon'] !== null) {
        //   $icon = $data['icon'];
        //   $icon_name = uniqid('icon_') . '.' . $icon->guessExtension();
        //   $path = storage_path('app/public/icons');
        //   $icon->move($path, $icon_name);
        // } else {
        //   $icon_name = null;
        // }

        $create_user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'icon' => null,
        ]);

        // プロフィールも一緒に作る
        $create_profile = Profile::create([
            'user_id' => $create_user->id,
            'message' => 'よろしくお願いします。',
        ]);

        return $create_user;
    }
}

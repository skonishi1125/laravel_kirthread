<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    private const REDIRECT_GAME_INDEX_SESSION_KEY = 'redirectGameIndex';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * /laravel_kirthread/vendor/laravel/ui/auth-backend/AuthenticatesUsers.phpのオーバーライド。
     *
     * ゲームからログインに遷移してきた場合のケースを考慮して書き換えている。
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $previous_url = url()->previous(); // ex: http://localhost:8000/game/rpg
        $previous_path = parse_url($previous_url, PHP_URL_PATH); // ex: /game/rpg

        // game/rpgから遷移してきた場合、そのフラグをセッションとして格納
        // ログイン部分でリダイレクトの繊維に使う。
        if ($previous_path === route('game_rpg_index', absolute: false)) {
            session()->put(self::REDIRECT_GAME_INDEX_SESSION_KEY, true);
        }

        return view('auth.login');
    }

    /**
     * /laravel_kirthread/vendor/laravel/ui/auth-backend/AuthenticatesUsers.phpのオーバーライド。
     */
    public function login(Request $request)
    {
        // game画面のログインから遷移してきたかどうかのフラグ
        // session()->pull(): sessionを取得すると勝手に消してくれるメソッド
        $is_redirect_game_page = session()->pull(self::REDIRECT_GAME_INDEX_SESSION_KEY, false);

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        // NOTE: PHPStanで警告を受けたので、以下の条件分岐だけ消した。
        // "method_exists($this, 'hasTooManyLoginAttempts') && " 
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            // gameのログインボタンから遷移してきた場合は、リダイレクト先をゲーム画面に変更。
            if ($is_redirect_game_page === true) {
                $this->redirectTo = route('game_rpg_index');
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}

@php
  // 最新のcss,jsファイルを読ませるようにする
  $date = config('app.last_modify_js_file_date');
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  {{-- ゲームページ用metaタグ --}}
  @if(Request::Is('game/rpg*'))
    <meta property="og:title" content="{{ config('app.game_name') }} - {{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('image/ogp/game.png') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="ブラウザですぐ遊べるRPG風ゲームです。自由な育成と進行が楽しめます。">

    {{-- 結構大きく乗るので、本格的な画像を用意するまではコメントアウトでもいいかも。
      <meta name="twitter:card" content="summary_large_image">
      <meta name="twitter:title" content="{{ config('app.game_name') }} - {{ config('app.name') }}">
      <meta name="twitter:description" content="ブラウザですぐ遊べるRPG風ゲームです。自由な育成と進行が楽しめます。">
      <meta name="twitter:image" content="{{ asset('image/ogp/game.png') }}"> 
    --}}
  @else
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('image/ogp/default.png') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="かあスレッドはシンプルな掲示板サイトです。">
  @endif
  <title>
    @if (config('app.env') !== 'production') {{ config('app.env') }} @endif {{ config('app.name') }}
  </title>

  {{-- css/jsをviteで読み込む --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>

<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
          {{ config('app.name') }}
          <small style="background: linear-gradient(to right,#e60000,#f39800,#fff100,#009944,#0068b7,#1d2088,#920783); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block;">v2.1</small>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <span id="now-time"></span>
            </li>
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
              <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
              </li>
              @if (Route::has('register'))
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
              @endif
              <li class="nav-item">
                <a href="{{ route('about') }}" class="nav-link">かあスレッドとは</a>
              </li>

              <li class="nav-item">
                <a href="{{ route('game') }}" class="nav-link">ゲーム</a>
              </li>
            @else
              @if (isset(Auth::user()->icon))
                <img class="profile-icon" src="{{ asset('storage/icons/' . Auth::user()->icon) }}">
              @else
                <img class="profile-icon" src="{{ asset('storage/icons/default.png' . Auth::user()->icon) }}">
              @endif
              <li class="nav-item dropdown">

                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="{{ route('config_index') }}">
                    {{ __('Config') }}
                  </a>
                  <a class="dropdown-item" href="{{ route('profile_show', ['user_id' => Auth::id()]) }}">
                    プロフィール
                  </a>
                  <a class="dropdown-item" href="{{ route('game') }}">ゲーム</a>
                  <a class="dropdown-item" href="{{ route('about') }}">かあスレッドとは</a>
                  <a id="csv-download" class="dropdown-item"
                    href="{{ route('study_download_post_csv', ['user_id' => Auth::id()]) }}">
                    CSVでDL
                  </a>
                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </div>
              </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    <main class="py-4">
      @yield('content')
    </main>
  </div>
</body>

</html>

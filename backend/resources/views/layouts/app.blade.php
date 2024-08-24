<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>
    @if (config('app.env') !== 'production')
      {{ config('app.env') }}
    @endif
    {{ config('app.name', 'Laravel') }}
  </title>

  @php
    // 最新のcss,jsファイルを読ませるようにする
    $date = Date('Ymd');
  @endphp

  <!-- Styles -->
  <link href="{{ asset('css/style.css?' . $date) }}" rel="stylesheet">
  {{-- vue.js --}}
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

</head>

<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
          {{ config('app.name', 'Laravel') }}
          <small>v2.0</small>
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
                  <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>

                  <a class="dropdown-item" href="{{ route('about') }}">かあスレッドとは</a>
                  <a class="dropdown-item" href="{{ route('game') }}">ゲーム</a>
                  <a id="csv-download" class="dropdown-item"
                    href="{{ route('study_download_post_csv', ['user_id' => Auth::id()]) }}">
                    CSVでDL
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
  {{-- Scripts head内に書くと動作しないことがあるため、分割する --}}
  <script type="text/javascript" src="{{ asset('js/stopwatch.js?' . $date) }}"></script>
  <script type="text/javascript" src="{{ asset('js/script.js?' . $date) }}"></script>
  <script type="text/javascript" src="{{ asset('js/reaction.js?' . $date) }}"></script>
  @if (Auth::check())
    <script type="text/javascript" src="{{ asset('js/csv_download.js?' . $date) }}"></script>
  @endif
  {{-- 画像までのローカルパス変数 --}}
  <script type="text/javascript">
    var path_to_image = '{{ asset('') }}';
  </script>
  {{-- vue.js --}}
  <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>

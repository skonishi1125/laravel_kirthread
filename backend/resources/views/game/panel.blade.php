<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>

<style>
  #table {
    margin: 0 auto;
  }

  .panel {
      width: 50px;
      height: 50px;
      display: inline-block;
      margin: 2px;
  }

  .red {
    background-color: red !important;
  }

  .red:hover {
    background-color: rgb(255, 86, 86) !important;
  }

  .green {
    background-color: green !important;
  }

  .green:hover {
    background-color: rgb(59, 146, 59) !important;
  }
</style>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                    <small>v1.1</small>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
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
                        @else
                            @if (isset(Auth::user()->icon))
                                <img class="profile-icon" src="{{ asset('storage/icons/' . Auth::user()->icon) }}">
                            @else
                                <img class="profile-icon" src="{{ asset('storage/icons/default.png' . Auth::user()->icon) }}">
                            @endif
                            <li class="nav-item dropdown">

                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h5>2色パネル</h5>
                  </div>
          
                  <div class="card-body">
          
                    <div class="col-12">
                      <p>
                        クリックして色を揃えよう<br>
                        クリックした四角とその周りの四角の色が反転する 色がどちらかに揃ったら完成
                      </p>
          
                      <table id="table"></table>
                      
                    </div> <!-- col-12 -->
                  </div><!-- card-body -->
                </div><!-- card -->
          
              </div><!-- col-xs-12 -->
          
            </div>
          </div>
        </main>
    </div>
    <script type="text/javascript" src="{{ asset('js/stopwatch.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/script.js') }}"></script>
    
    {{-- テンプレートにこれだけ入れるいい方法はないものか --}}
    <script type="text/javascript" src="{{ asset('js/game/panel.js') }}"></script>

</body>
</html>








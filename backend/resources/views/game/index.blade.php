@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">つくったゲームたち</h5>
        </div>

        <div class="card-body">

          <p class="text-muted mb-3">5分は暇をつぶすことができるかも</p>

          <ul class="list-unstyled mb-4">

              {{-- TD --}}
              <li class="mb-3">
                <div class="game-list-item d-block p-3 rounded border position-relative">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-column pr-3">
                      <a href="https://unityroom.com/games/260303_td_gunshi" class="game-title text-dark stretched-link" style="text-decoration: none;">
                        節約！軍師ちゃん
                      </a>
                      <span class="game-desc mt-1">
                        タワーディフェンスゲーム。ケチりつつクリアも目指してお金を稼ごう！<br>
                        ※unityroomのページを開きます。
                      </span>
                    </div>
                    <a href="https://github.com/skonishi1125/unity_3d_tower_defense" class="position-relative" style="z-index: 2;" target="_blank" rel="noopener noreferrer">
                      <img src="{{ asset('image/GitHub_Invertocat_Black_Clearspace.png') }}" alt="GitHub Repository" width="32" height="32">
                    </a>
                  </div>
                </div>
              </li>

              {{-- 2D アクション --}}
              <li class="mb-3">
                <div class="game-list-item d-block p-3 rounded border position-relative">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-column pr-3">
                      <a href="https://unityroom.com/games/relic_guardian" class="game-title text-dark stretched-link" style="text-decoration: none;">
                        Relic Guardian
                      </a>
                      <span class="game-desc mt-1">
                        2Dアクション。育成してクリスタルを時間いっぱい護り切れ！<br>
                        ※unityroomのページを開きます。
                      </span>
                    </div>
                    <a href="https://github.com/skonishi1125/2d_arena_action_unity" class="position-relative" style="z-index: 2;" target="_blank" rel="noopener noreferrer">
                      <img src="{{ asset('image/GitHub_Invertocat_Black_Clearspace.png') }}" alt="GitHub Repository" width="32" height="32">
                    </a>
                  </div>
                </div>
              </li>

              {{-- 2D STG --}}
              <li class="mb-3">
                <div class="game-list-item d-block p-3 rounded border position-relative">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-column pr-3">
                      <a href="https://unityroom.com/games/251130_cosmo_phoot" class="game-title text-dark stretched-link" style="text-decoration: none;">
                        Cosmo Phoot
                      </a>
                      <span class="game-desc mt-1">
                        全3面のシューティングゲーム。アイテムを取って強化を進めよう。<br>
                        ※unityroomのページを開きます。
                      </span>
                    </div>
                    <a href="https://github.com/skonishi1125/2d_shooting_unity" class="position-relative" style="z-index: 2;" target="_blank" rel="noopener noreferrer">
                      <img src="{{ asset('image/GitHub_Invertocat_Black_Clearspace.png') }}" alt="GitHub Repository" width="32" height="32">
                    </a>
                  </div>
                </div>
              </li>

              {{-- RPG --}}
              <li class="mb-3">
                <div class="game-list-item d-block p-3 rounded border position-relative">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-column pr-3">
                      <a href="{{ route('game_rpg_index') }}" class="game-title text-dark stretched-link" style="text-decoration: none;">
                        {{ config('app.game_name') }}
                      </a>
                      <span class="game-desc mt-1">
                        かあスレッドで遊べるコマンド選択式のブラウザRPG。
                      </span>
                    </div>
                    <a href="https://github.com/skonishi1125/laravel_kirthread?tab=readme-ov-file#%E3%82%B2%E3%83%BC%E3%83%A0%E3%81%AB%E3%81%A4%E3%81%84%E3%81%A6" class="position-relative" style="z-index: 2;" target="_blank" rel="noopener noreferrer">
                      <img src="{{ asset('image/GitHub_Invertocat_Black_Clearspace.png') }}" alt="GitHub Repository" width="32" height="32">
                    </a>
                  </div>
                </div>
              </li>

              {{-- 2色パネル --}}
              <li class="mb-3">
                <div class="game-list-item d-block p-3 rounded border position-relative">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex flex-column pr-3">
                      <a href="{{ route('game_panel') }}" class="game-title text-dark stretched-link" style="text-decoration: none;">
                        2色パネル
                      </a>
                      <span class="game-desc mt-1">色をそろえるミニゲーム。クリアを目にしたものはいない...</span>
                    </div>
                    <a href="https://github.com/skonishi1125/laravel_kirthread/blob/main/backend/resources/js/selfmade/game/panel.js" class="position-relative" style="z-index: 2;" target="_blank" rel="noopener noreferrer">
                      <img src="{{ asset('image/GitHub_Invertocat_Black_Clearspace.png') }}" alt="GitHub Repository" width="32" height="32">
                    </a>
                  </div>
                </div>
              </li>





            </ul>

            <div style="text-align: center; margin-top: 10px;">
              <a style="text-align:center" href="{{ route('/') }}">かあスレッドトップページへ</a>
            </div>

        </div><!-- card-body -->
      </div><!-- card -->

    </div><!-- col-md-10 -->

  </div>
</div>

@endsection

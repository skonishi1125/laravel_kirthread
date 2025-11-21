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

            {{-- RPG --}}
            <li class="mb-3">
              <a href="{{ route('game_rpg_index') }}" class="game-list-item d-block p-3 rounded border">
                <div class="d-flex flex-column">
                  <span class="game-title">{{ config('app.game_name') }}</span>
                  <span class="game-desc">
                    コマンド選択式のブラウザ短編RPG。
                  </span>
                </div>
              </a>
            </li>

            {{-- 2色パネル --}}
            <li class="mb-3">
              <a href="{{ route('game_panel') }}" class="game-list-item d-block p-3 rounded border">
                <div class="d-flex flex-column">
                  <span class="game-title">2色パネル</span>
                  <span class="game-desc">
                    色をそろえるミニゲーム。クリアを目にしたものはいない...
                  </span>
                </div>
              </a>
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

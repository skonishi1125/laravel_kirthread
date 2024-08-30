@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>つくったゲームたち</h5>
        </div>

        <div class="card-body">

          <div class="col-12">
            <p>5分は暇をつぶすことができるかも</p>

            <ul style="list-style-type: none">
              <li><a href="{{ route('game_panel') }}">2色パネル</a></li>
              {{-- <li><a href="{{ route('game_rpg_index') }}">RPG(鋭意製作中)</a></li> --}}
              <li><p>RPG(工事中)</p></li>
            </ul>


            <a href="{{ route('/') }}">もどる</a>
            
          </div> <!-- col-12 -->
        </div><!-- card-body -->
      </div><!-- card -->

    </div><!-- col-xs-12 -->

  </div>
</div>

@endsection

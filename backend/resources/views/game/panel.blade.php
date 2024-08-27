@extends('layouts.app')
@section('content')

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

@vite('resources/js/selfmade/game/panel.js')

@endsection









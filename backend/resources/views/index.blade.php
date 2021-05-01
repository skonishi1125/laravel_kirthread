@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>{{ Auth::user()->name }}さん、こんにちは。</h5>
        </div>

        <div class="card-body">
          @foreach ($posts as $post)
            <p>message: {{ $post->message }}</p>
          @endforeach
        </div>

      </div>
    </div>
  </div>
</div>
<!-- jsを読み込むときは、backend/publicのパス記述を省略させる -->
<script type="text/javascript" src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection

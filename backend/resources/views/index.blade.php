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

          <div class="col-xs-12">
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <form action="{{ route('store') }}" method="post" enctype="multipart/form-data">
              @CSRF
              <div class="form-group">
                <label for="post-message">投稿する内容を記述しよう</label>
                <textarea class="form-control" id="post-message" name="message"></textarea>
                <label for="post-picture">画像を添付する</label>
                <input type="file" name="picture" class="form-control-file" id="post-picture">
              </div>
              <button type="submit" class="btn btn-primary btn-sm">投稿する</button>
            </form>

            @foreach ($posts as $post)
              <p>message: {{ $post->message }}</p>
              @if (isset($post->picture))
                <img src="{{asset('storage/uploads/' . $post->picture)}}" alt="画像">
              @endif
            @endforeach

          </div> <!-- col-xs-12 -->
      </div><!-- card-body -->
    </div>
  </div>
</div>
<!-- jsを読み込むときは、backend/publicのパス記述を省略させる -->
<script type="text/javascript" src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection

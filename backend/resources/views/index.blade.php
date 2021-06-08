@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>みんなの投稿</h5>
        </div>

        <div class="card-body">

          <div class="col-12">
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
              <div class="form-group" style="position: relative;">
                @if (Auth::check() )
                <label for="post-message">{{ Auth::user()->name }} さん、こんにちは。</label>
                <textarea class="form-control" id="post-message" name="message"></textarea>
                <small>
                  <label for="post-picture">画像を添付する</label>
                  <input type="file" name="picture" class="form-control-file" id="post-picture">
                </small>

                <button type="submit" class="btn btn-primary btn-sm post-button">投稿</button>
              </div>

              @else
              <label for="post-message">投稿するには<a href="{{ route('login') }}">ログイン</a>が必要です。</label>
              @endif

            </form>

            <hr class="my-3">

            @foreach ($posts as $post)
                @include('parts.post',['post => $post'])
                <div class="my-2" style="border-bottom:1px dotted #333;"></div>
            @endforeach
          </div> <!-- col-12 -->

          <div class="mt-3 d-flex justify-content-center">
            {{ $posts->links() }}
          </div>




        </div><!-- card-body -->
      </div><!-- card -->

    </div><!-- col-xs-12 -->

  </div>
</div>
<!-- jsを読み込むときは、backend/publicのパス記述を省略させる -->
{{--
<script type="text/javascript" src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>
--}}
{{-- 
  <script type="text/javascript">
    var url = '{{ asset('') }}';
  </script> 
--}}

@endsection

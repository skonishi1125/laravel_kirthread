@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">
      {{-- 未ログインのユーザーにだけ宣伝する --}}
      {{-- 
      @if (! Auth::check())
        <div class="text-center mb-3">
          <small class="text-muted">
            <a href="{{ route('game_rpg_index') }}">🧙 RPGゲームの体験版を公開しています！</a>
          </small>
        </div>
      @endif 
      --}}
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
            @if (Auth::check() && !Auth::user()->is_guest )
              <p>{{ Auth::user()->name }} さん、こんにちは。</p>
              <form action="{{ route('store') }}" method="post" enctype="multipart/form-data">
                @CSRF
                <div class="form-group">
                  <textarea class="form-control" id="post-message" name="message" placeholder="投稿したい内容を記入">{{ old('message') }}</textarea>
                </div>
                
                <div class="form-group" style="font-size: small">
                  <label for="youtube_url">YouTubeの動画を載せる</label>
                  <input type="text" class="form-control form-control-sm" id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=QLXbggM1GXk" value="{{ old('youtube_url') }}">
                </div>

                <div class="form-group" style="font-size: small; width: 250px">
                  <label for="post-picture">画像を添付する</label>
                  <input type="file" name="picture" id="post-picture" class="form-control-file">
                </div>


                  <button type="submit" class="btn btn-primary btn-sm post-button">投稿</button>

              </form>

              @else
                @if (Auth::check() && Auth::user()->is_guest)
                  <label for="post-message">投稿するにはゲストアカウントを<a href="{{ route('guest_upgrade_get') }}">本登録</a>する必要があります。</label>
                @else
                  <label for="post-message">投稿するには<a href="{{ route('login') }}">ログイン</a>が必要です。</label>
                @endif
              @endif
            </div>


            <hr class="my-3">

            {{-- 投稿1つ１つのブロック --}}
            @foreach ($posts as $post)
                @include('parts.post',['post => $post, reaction_icons => $reaction_icons'])
                <div class="my-2" style="border-bottom:1px dotted #333;"></div>
            @endforeach
          </div> <!-- col-12 -->

          <div class="mt-3 d-flex justify-content-center">
            {{ $posts->links('vendor.pagination.original-pagination') }}
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

<div class="container post-container">
  <div class="row">

      {{-- アイコンの部分。未設定の場合、おばけのデフォルトアイコンを付与する --}}
      <div class="col-auto">
        @if (is_null($post->user->icon)) 
          <img class="profile-icon" src="{{ asset('storage/icons/' . 'default.png') }}" alt="p_icon">
        @else
          <img class="profile-icon" src="{{ asset('storage/icons/' . $post->user->icon) }}" alt="p_icon">
        @endif
      </div>
      
      <div class="col-auto name-wrapper">
        <span><b>{{ $post->user->name }}</b></span>
        <br>
        <small><a href="{{ route('show', ['id' => $post->id]) }}">id:[{{$post->id}}]</a></small>
        <small>{{$post->created_at}}</small>

        {{-- ゴミ箱 | リアクションアイコン --}}
        <div class="reaction-wrapper">
          @if (Auth::id() === $post->user_id)
          <form action="{{ route('destroy') }}" method="post" enctype="multipart/form-data">
            @CSRF
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <button type="submit" class="trash clearButton">
              <span class="material-icons">delete</span>
            </button>
          </form>
          @else
            @if (Auth::check())
              <span class="material-icons reaction" style="z-index: 10; user-select:none;">add_reaction</span>
            @endif
          @endif
        </div>

        {{-- 青い吹き出しのリアクションをつけるパーツ --}}
        {{-- includeは[]で引数などを指定しなくても、この先のbladeで同じ引数を使える --}}
        @include('parts.reaction.hukidashi')

      </div>

    <div class="col-12">
      <p>{!! nl2br($post->makeLink(e($post->message))) !!}</p>

      @if (isset($post->youtube_url))
        <div class="embed-responsive embed-responsive-16by9">
          <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $post->youtube_url }}" allowfullscreen></iframe>
        </div>
        <hr>
      @endif

      @if (isset($post->picture))
        <div class="text-center mb-3">
          <img class="img-fluid post-image" src="{{asset('storage/uploads/' . $post->picture)}}" alt="画像">
        </div>
      @endif

      <ul class="reaction-icons reaction-buttons">
        @if (isset($post->reaction))
          @php
            // リアクションされた数を数えたのち、重複している数値を削除する
            $reactions = explode(",", $post->reaction); 
            $counts = array_count_values($reactions);
            $reactions = array_unique($reactions);
          @endphp

          {{--  リアクションボタン部分。ログインユーザーしか押せなくする --}}
          @include('parts.reaction.button')
          {{-- @include('parts.reaction.button_old') --}}
        @endif
      </ul>

    </div>

  </div>
</div>

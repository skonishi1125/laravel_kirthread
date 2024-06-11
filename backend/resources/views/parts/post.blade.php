<div class="container post-container">
  <div class="row">

      {{-- アイコンの部分。未設定の場合、おばけのデフォルトアイコンを付与する --}}
      <div class="col-auto">
        @if (is_null($post->user->icon)) 
          <a href="{{route('profile_show', ['user_id' => $post->user->id])}}" class="not-like-link">
            <img class="profile-icon" src="{{ asset('storage/icons/' . 'default.png') }}" alt="p_icon">
          </a>
        @else
        <a href="{{route('profile_show', ['user_id' => $post->user->id])}}" class="not-like-link">
            <img class="profile-icon" src="{{ asset('storage/icons/' . $post->user->icon) }}" alt="p_icon">
          </a>
        @endif
      </div>
      
      <div class="col-auto name-wrapper">
        <a href="{{route('profile_show', ['user_id' => $post->user->id])}}">
          <span class="not-like-link"><b>{{ $post->user->name }}</b></span>
        </a>
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
              <span class="material-icons reaction reaction-display-icon" style="z-index: 10; user-select:none;">add_reaction</span>
            @endif
          @endif
        </div>

        {{--青い吹き出し関連のパーツ --}}
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

      <ul class="reaction-icons reaction-buttons below-post-reaction-buttons-container">
        {{--  青ボタン関連のパーツ --}}
        @include('parts.reaction.button')
      </ul>

    </div>

  </div>
</div>

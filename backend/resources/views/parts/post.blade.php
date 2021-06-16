<div class="container post-container">
  <div class="row">    

      <div class="col-auto">
        @if ( is_null(App\User::where('id',$post->user_id)->value('icon')) )
          <img class="profile-icon" src="{{asset('storage/icons/' . 'default.png')}}" alt="profileIcon">
        @else
          <img class="profile-icon" src="{{asset('storage/icons/' . App\User::where('id',$post->user_id)->value('icon'))}}" alt="profileIcon">
        @endif
      </div>
      
      <div class="col-auto name-wrapper">
        <span><b>{{App\User::where('id',$post->user_id)->value('name')}}</b></span>
        <br>
        <small><a href="{{ route('show', ['id' => $post->id]) }}">id:[{{$post->id}}]</a></small>
        <small>{{$post->created_at}}</small>

        <div class="reaction-wrapper">
          @if (Auth::id() === $post->user_id)
            <a href="{{ route('destroy', ['id' => $post->id]) }}" class="trash">
              <span class="material-icons">delete</span>
            </a>
          @else
            @if (Auth::check())
              <span class="material-icons reaction" style="z-index: 10; user-select:none;">add_reaction</span>
            @endif
          @endif
        </div>

        {{-- リアクションモーダル --}}
        @if (Auth::check())
          <div class="reaction-modal d-none">
            <ul class="reaction-icons">
              <li><a href="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 1]) }}">👀 </a></li>
              <li><a href="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 2]) }}">😢 </a></li>
              <li><a href="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 3]) }}">❤️ </a></li>
              <li><a href="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 4]) }}">❓ </a></li>

              <li><a href="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 5]) }}">
                  <img src="{{ asset('storage/reaction_icons/pic_60c59198dc55e.png') }}" alt="reaction_5">
              </a></li>

              <li><a href="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 6]) }}">
                  <img src="{{ asset('storage/reaction_icons/pic_haha56faeignae.png') }}" alt="reaction_6">
              </a></li>

            </ul>
          </div>
        @endif

      </div>

    <div class="col-12">
      <p>{!! nl2br($post->makeLink(e($post->message))) !!}</p>
      @if (isset($post->picture))
        <div class="text-center mb-3">
          <img class="img-thumbnail post-image" src="{{asset('storage/uploads/' . $post->picture)}}" alt="画像">
        </div>
      @endif
      @if (isset($post->reaction))
        <ul class="reaction-icons">
        @php
            $reactions = explode(",", $post->reaction);
            // リアクションされた数を数えたのち、重複している数値を削除する
            $counts = array_count_values($reactions);
            $reactions = array_unique($reactions);
        @endphp

        {{--  ログインチェック & ログインユーザが投稿したユーザと違う場合非表示？ --}}
        @if (Auth::check())
          @foreach ($reactions as $reaction)
              @switch($reaction)
                  @case(1)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li><a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 1]) }}" class="btn btn-info btn-sm add-reaction">👀 × {{ $counts[1] }}</a></li>
                    @else
                      <li><a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 1]) }}" class="btn btn-outline-info btn-sm">👀 × {{ $counts[1] }}</a></li>
                    @endif
                    @break
                  @case(2)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li><a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 2]) }}" class="btn btn-info btn-sm add-reaction">😢 × {{ $counts[2] }}</a></li>
                    @else
                      <li><a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 2]) }}" class="btn btn-outline-info btn-sm">😢 × {{ $counts[2] }}</a></li>
                    @endif
                    @break
                  @case(3)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li><a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 3]) }}" class="btn btn-info btn-sm add-reaction">❤️ × {{ $counts[3] }}</a></li>
                    @else
                      <li><a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 3]) }}" class="btn btn-outline-info btn-sm">❤️ × {{ $counts[3] }}</a></li>
                    @endif
                    @break
                  @case(4)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li><a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 4]) }}" class="btn btn-info btn-sm add-reaction">❓ × {{ $counts[4] }}</a></li>
                    @else
                      <li><a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 4]) }}" class="btn btn-outline-info btn-sm">❓ × {{ $counts[4] }}</a></li>
                    @endif
                    @break
                  @case(5)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li>
                        <a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 5]) }}" class="btn btn-info btn-sm add-reaction">
                          <img src="{{ asset('storage/reaction_icons/pic_60c59198dc55e.png') }}" alt="reaction_5"> × {{ $counts[5] }}
                        </a>
                      </li>
                      @else
                      <li>
                        <a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 5]) }}" class="btn btn-outline-info btn-sm">
                          <img src="{{ asset('storage/reaction_icons/pic_60c59198dc55e.png') }}" alt="reaction_5"> × {{ $counts[5] }}
                        </a>
                      </li>
                    @endif
                    @break
                  @case(6)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li>
                        <a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 6]) }}" class="btn btn-info btn-sm add-reaction">
                          <img src="{{ asset('storage/reaction_icons/pic_haha56faeignae.png') }}" alt="reaction_6"> × {{ $counts[6] }}
                        </a>
                      </li>
                      @else
                      <li>
                        <a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_number' => 6]) }}" class="btn btn-outline-info btn-sm">
                          <img src="{{ asset('storage/reaction_icons/pic_haha56faeignae.png') }}" alt="reaction_6"> × {{ $counts[6] }}
                        </a>
                      </li>
                    @endif
                    @break
                  @endswitch
          @endforeach
        @else
          {{-- 非ログインユーザから見えるリアクション？ --}}
          @foreach ($reactions as $reaction)
            @switch($reaction)
              @case(1)
                <li><a href="#" class="btn btn-outline-info btn-sm disabled">👀 x {{ $counts[1] }}</a></li>
              @break
              @case(2)
                <li><a href="#" class="btn btn-outline-info btn-sm disabled">😢 x {{ $counts[2] }}</a></li>
              @break
              @case(3)
                <li><a href="#" class="btn btn-outline-info btn-sm disabled">❤️ x {{ $counts[3] }}</a></li>
              @break
              @case(4)
                <li><a href="#" class="btn btn-outline-info btn-sm disabled">❓ x {{ $counts[4] }}</a></li>
              @break
              @case(5)
                <li><a href="#" class="btn btn-outline-info btn-sm disabled">
                  <img src="{{ asset('storage/reaction_icons/pic_60c59198dc55e.png') }}" alt="reaction_5"> × {{ $counts[5] }}
                </a></li>
              @break
              @case(6)
                <li><a href="#" class="btn btn-outline-info btn-sm disabled">
                  <img src="{{ asset('storage/reaction_icons/pic_haha56faeignae.png') }}" alt="reaction_6"> × {{ $counts[6] }}
                </a></li>
              @break
            @endswitch
          @endforeach
        @endif

        </ul>
      @endif {{-- isset($post->reaction) --}}
    </div>

  </div>
</div>

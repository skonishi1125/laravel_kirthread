<div class="container post-container">
  <div class="row">

      <div class="col-auto">
        @if ( is_null(App\User::where('id',$post->user_id)->value('icon')) )
          <img class="profile-icon" src="{{asset('storage/icons/' . 'default.png')}}" alt="p_icon">
        @else
          <img class="profile-icon" src="{{asset('storage/icons/' . App\User::where('id',$post->user_id)->value('icon'))}}" alt="p_icon">
        @endif
      </div>
      
      <div class="col-auto name-wrapper">
        <span><b>{{App\User::where('id',$post->user_id)->value('name')}}</b></span>
        <br>
        <small><a href="{{ route('show', ['id' => $post->id]) }}">id:[{{$post->id}}]</a></small>
        <small>{{$post->created_at}}</small>

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
        @include('parts.reaction.hukidashi',['post => $post', 'user_id => Auth::id()'])

      </div>

    <div class="col-12">
      <p>{!! nl2br($post->makeLink(e($post->message))) !!}</p>

      @if (isset($post->youtube_url))
        <div class="embed-responsive embed-responsive-16by9">
          <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $post->youtube_url }}" allowfullscreen></iframe>
        </div>
        <hr>

        {{-- <p>{{ $post->youtube_url}}</p> --}}

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

        {{--  ログインチェック & ログインユーザが投稿したユーザと違う場合非表示？ --}}
        @if (Auth::check())
          @foreach ($reactions as $reaction)
              @switch($reaction)
                  @case(1)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      {{-- add-reactionの方 --}}
                      <li><a class="reactions-button reactions btn btn-outline-info btn-sm reactions-button eyes add-reaction" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[1] }}" data-reaction="1" >👀 × {{ $counts[1] }}</a></li>
                    @else
                      <li><a class="reactions btn btn-outline-info btn-sm reactions-button eyes" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[1] }}" data-reaction="1" >👀 × {{ $counts[1] }}</a></li>
                    @endif
                    @break
                  @case(2)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li><a class="reactions btn btn-outline-info btn-sm reactions-button sads add-reaction" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[2] }}" data-reaction="2" >😭 × {{ $counts[2] }}</a></li>
                    @else
                      <li><a class="reactions btn btn-outline-info btn-sm reactions-button sads" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[2] }}" data-reaction="2">😭 × {{ $counts[2] }}</a></li>
                    @endif
                    @break
                  @case(3)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li><a class="reactions btn btn-outline-info btn-sm reactions-button hearts add-reaction" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[3] }}" data-reaction="3" >💕 × {{ $counts[3] }}</a></li>
                    @else
                      <li>
                        <a class="reactions btn btn-outline-info btn-sm reactions-button hearts" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[3] }}" data-reaction="3">💕 × {{ $counts[3] }}</a>
                      </li>
                    @endif
                    @break
                  @case(4)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li><a class="reactions btn btn-outline-info btn-sm reactions-button questions add-reaction" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[4] }}" data-reaction="4" >❓ × {{ $counts[4] }}</a></li>
                    @else
                      <li><a class="reactions btn btn-outline-info btn-sm reactions-button questions" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[4] }}" data-reaction="4" >❓ × {{ $counts[4] }}</a></li>
                    @endif
                    @break
                  @case(5)
                    {{--
                     @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li>
                        <a class="reactions btn btn-outline-info btn-sm reactions-button kaiddds add-reaction" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[5] }}" data-reaction="5" >
                          🕶 × {{ $counts[5] }}
                        </a>
                      </li>
                    @else
                      <li>
                        <a class="reactions btn btn-outline-info btn-sm reactions-button kaiddds" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $counts[5] }}" data-reaction="5" >
                          🕶 × {{ $counts[5] }}
                        </a>
                      </li>
                    @endif
                    @break 
                    --}}

                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li>
                        <a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 5]) }}" class="btn btn-info btn-sm add-reaction">
                          <img src="{{ asset('storage/reaction_icons/pic_60c59198dc55e.png') }}" alt="reaction_5"> × {{ $counts[5] }}
                        </a>
                      </li>
                      @else
                      <li>
                        <a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 5]) }}" class="btn btn-outline-info btn-sm">
                          <img src="{{ asset('storage/reaction_icons/pic_60c59198dc55e.png') }}" alt="reaction_5"> × {{ $counts[5] }}
                        </a>
                      </li>
                    @endif
                    @break
                  @case(6)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li>
                        <a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 6]) }}" class="btn btn-info btn-sm add-reaction">
                          <img src="{{ asset('storage/reaction_icons/pic_haha56faeignae.png') }}" alt="reaction_6"> × {{ $counts[6] }}
                        </a>
                      </li>
                      @else
                      <li>
                        <a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 6]) }}" class="btn btn-outline-info btn-sm">
                          <img src="{{ asset('storage/reaction_icons/pic_haha56faeignae.png') }}" alt="reaction_6"> × {{ $counts[6] }}
                        </a>
                      </li>
                    @endif
                    @break

                  @case(7)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li>
                        <a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 7]) }}" class="btn btn-info btn-sm add-reaction">
                          <img src="{{ asset('storage/reaction_icons/pic_10h382hj02f83f.png') }}" alt="reaction_7"> × {{ $counts[7] }}
                        </a>
                      </li>
                      @else
                      <li>
                        <a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 7]) }}" class="btn btn-outline-info btn-sm">
                          <img src="{{ asset('storage/reaction_icons/pic_10h382hj02f83f.png') }}" alt="reaction_7"> × {{ $counts[7] }}
                        </a>
                      </li>
                    @endif
                    @break

                  @case(8)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li>
                        <a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 8]) }}" class="btn btn-info btn-sm add-reaction">
                          <img src="{{ asset('storage/reaction_icons/pic_iidaro.png') }}" alt="reaction_8"> × {{ $counts[8] }}
                        </a>
                      </li>
                      @else
                      <li>
                        <a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 8]) }}" class="btn btn-outline-info btn-sm">
                          <img src="{{ asset('storage/reaction_icons/pic_iidaro.png') }}" alt="reaction_8"> × {{ $counts[8] }}
                        </a>
                      </li>
                    @endif
                    @break

                  @case(9)
                    @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
                      <li>
                        <a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 9]) }}" class="btn btn-info btn-sm add-reaction">
                          <img src="{{ asset('storage/reaction_icons/pic_iina.png') }}" alt="reaction_9"> × {{ $counts[9] }}
                        </a>
                      </li>
                      @else
                      <li>
                        <a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 9]) }}" class="btn btn-outline-info btn-sm">
                          <img src="{{ asset('storage/reaction_icons/pic_iina.png') }}" alt="reaction_9"> × {{ $counts[9] }}
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
              @case(7)
                <li><a href="#" class="btn btn-outline-info btn-sm disabled">
                  <img src="{{ asset('storage/reaction_icons/pic_10h382hj02f83f.png') }}" alt="reaction_7"> × {{ $counts[7] }}
                </a></li>
              @break
              @case(8)
                <li><a href="#" class="btn btn-outline-info btn-sm disabled">
                  <img src="{{ asset('storage/reaction_icons/pic_iidaro.png') }}" alt="reaction_8"> × {{ $counts[8] }}
                </a></li>
              @break
              @case(9)
                <li><a href="#" class="btn btn-outline-info btn-sm disabled">
                  <img src="{{ asset('storage/reaction_icons/pic_iina.png') }}" alt="reaction_9"> × {{ $counts[9] }}
                </a></li>
              @break
            @endswitch
          @endforeach
        @endif

        @endif {{-- isset($post->reaction) --}}
      </ul>
    </div>

  </div>
</div>

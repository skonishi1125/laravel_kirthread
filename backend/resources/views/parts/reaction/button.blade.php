{{--
@if (Auth::check())
  @foreach ($post->attached_reactions as $r)

    @if ($r['count'] !== 0)
      @if ($r['is_picture_icon'])
        @if ($post->isSetReaction(Auth::id(), $post->id, $r['reaction_icon_id']))
          <li>
            <a href="{{ route('remove_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => $r['reaction_icon_id']]) }}" class="btn btn-info btn-sm add-reaction">
              <img src="{{ asset('storage/reaction_icons/' . $r['url']) }}" alt="reaction_{{$r['reaction_icon_id']}}"> × {{ $r['count'] }}
            </a>
          </li>
        @else
          <li>
            <a href="{{ route('add_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => $r['reaction_icon_id']]) }}" class="btn btn-outline-info btn-sm">
              <img src="{{ asset('storage/reaction_icons/' . $r['url']) }}" alt="reaction_{{$r['reaction_icon_id']}}"> × {{ $r['count'] }}
            </a>
          </li>
        @endif

      @else
        @if ($post->isSetReaction(Auth::id(), $post->id, $r['reaction_icon_id']))
        <li>
          <a class="reactions-button reactions btn btn-outline-info btn-sm reactions-button add-reaction {{$r['name_plural']}}" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $r['count'] }}" data-reaction="{{$r['reaction_icon_id']}}" >{{$r['value']}} × {{ $r['count'] }}</a>
        </li>
        @else
        <li>
          <a class="reactions btn btn-outline-info btn-sm reactions-button {{$r['name_plural']}}" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $r['count'] }}" data-reaction="{{$r['reaction_icon_id']}}" >{{$r['value']}} × {{ $r['count'] }}</a>
        </li>
        @endif
      @endif
    @endif
  
  @endforeach



@else
  @foreach ($post->attached_reactions as $r)
    @if ($r['count'] !== 0)
      @if($r['is_picture_icon'])
        <li>
          <a href="#" class="btn btn-outline-info btn-sm disabled">
            <img src="{{ asset('storage/reaction_icons/' . $r['url']) }}" alt="reaction_{{$r['reaction_icon_id']}}"> × {{ $r['count'] }}
          </a>
        </li>
      @else
        <li><a href="#" class="btn btn-outline-info btn-sm disabled">{{ $r['value'] }} x {{ $r['count'] }}</a></li>
      @endif
    @endif
  @endforeach
@endif

--}}

@if (Auth::check())
@foreach ($reactions as $reaction)
    @switch($reaction)
        @case(1)
          @if ($post->isSetReaction(Auth::id(), $post->id, $reaction))
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
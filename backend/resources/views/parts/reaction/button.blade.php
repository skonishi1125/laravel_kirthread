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
        {{-- リアクションを外す(青い表示になっているボタン) --}}
        <li>
          <a class="reactions btn btn-outline-info btn-sm reactions-button {{$r['name_plural']}} add-reaction" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $r['count'] }}" data-reaction="{{$r['reaction_icon_id']}}" >h{{$r['value']}} × {{ $r['count'] }}</a>
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
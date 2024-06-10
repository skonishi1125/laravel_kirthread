@if (Auth::check())
  @foreach ($post->attached_reactions as $r)
    @if ($r['count'] !== 0)
      @if ($post->isSetReaction(Auth::id(), $post->id, $r['reaction_icon_id']))
        {{-- リアクションを外す(青い背景色となっている)ボタン --}}
        <li>
          <a class="reactions btn btn-outline-info btn-sm reactions-button {{$r['name_plural']}} add-reaction" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $r['count'] }}" data-reaction="{{$r['reaction_icon_id']}}" data-reactionname="{{ $r['name'] }}" data-reactionnameplural="{{ $r['name_plural'] }}" data-ispictureicon="{{ $r['is_picture_icon'] }}" data-value="{{ $r['value'] }}">
            @if ($r['value'])
              {{$r['value']}} × {{ $r['count'] }}
            @else
              <img src="{{ asset('storage/reaction_icons/' . $r['url']) }}" alt="reaction_{{$r['reaction_icon_id']}}"> × {{ $r['count'] }}
            @endif
          </a>
        </li>
      @else
        {{-- リアクションを付与するボタン --}}
        <li>
          <a class="reactions btn btn-outline-info btn-sm reactions-button {{$r['name_plural']}}" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-count="{{ $r['count'] }}" data-reaction="{{$r['reaction_icon_id']}}" data-reactionname="{{ $r['name'] }}" data-reactionnameplural="{{ $r['name_plural'] }}" data-ispictureicon="{{ $r['is_picture_icon'] }}" data-value="{{ $r['value'] }}" >{{$r['value']}} × {{ $r['count'] }}</a>
        </li>
        @endif
      @endif
  @endforeach
{{-- ログインしていないユーザーが閲覧する場合、青ボタンは押せないようにする --}}
@else
  @foreach ($post->attached_reactions as $r)
    @if ($r['count'] !== 0)
      <li>
        <a href="#" class="btn btn-outline-info btn-sm disabled">
          @if ($r['value'])
            {{$r['value']}} × {{ $r['count'] }}
          @else
            <img src="{{ asset('storage/reaction_icons/' . $r['url']) }}" alt="reaction_{{$r['reaction_icon_id']}}"> × {{ $r['count'] }}
          @endif
        </a>
      </li>
    @endif
  @endforeach
@endif
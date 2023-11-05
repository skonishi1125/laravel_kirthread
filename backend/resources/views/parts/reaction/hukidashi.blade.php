@if (Auth::check())
<div class="reaction-modal d-none">
  <ul class="reaction-icons">

    @foreach ($reaction_icons as $r)
      @if ($r->is_picture_icon)
        <form action="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => $r->id])  }}" method="post" enctype="multipart/form-data">
            @CSRF
            <li>
            <button type="submit" class="clearButton">
                <img src="{{ asset('storage/reaction_icons/' . $r->url) }}" alt="reaction_{{$r->id}}">
            </button>
            </li>
        </form>
      @else
        <li>
          <a role="button" class="reactions {{$r->name_plural}}" data-reaction="{{$r->id}}" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}">
            {{$r->value}}
          </a>
        </li>
      @endif
    @endforeach

  </ul>
</div>
@endif
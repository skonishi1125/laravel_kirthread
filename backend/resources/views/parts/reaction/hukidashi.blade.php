@if (Auth::check())
  <div class="reaction-modal d-none">
    <ul class="reaction-icons">
      @foreach ($reaction_icons as $r)
        <li>
          <a role="button" class="reactions {{$r->name_plural}}" data-reaction="{{$r->id}}" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}" data-reactionname="{{ $r->name }}" data-reactionnameplural="{{ $r->name_plural }}" data-ispictureicon="{{ $r->is_picture_icon }}" data-value="{{ $r->value }}">
            @if ($r->value)
              {{$r->value}}
            @else
              <img src="{{ asset('storage/reaction_icons/' . $r->url) }}" alt="reaction_{{$r->id}}">
            @endif
          </a>
        </li>
      @endforeach
    </ul>
  </div>
@endif
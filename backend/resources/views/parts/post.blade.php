<div class="container post-container">
  <div class="row">

      <div class="col-auto">
        @if ( is_null(App\User::where('id',$post->user_id)->value('icon')) )
          <img class="profile-icon" src="{{asset('storage/icons/' . 'default.png')}}" alt="profileIcon">
        @else
          <img class="profile-icon" src="{{asset('storage/icons/' . App\User::where('id',$post->user_id)->value('icon'))}}" alt="profileIcon">
        @endif
      </div>
      <div class="col-auto">
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
            <span class="material-icons">add_reaction</span>
          @endif
        </div>
      </div>

    <div class="col-12">
      <p>{!! nl2br($post->makeLink(e($post->message))) !!}</p>
      @if (isset($post->picture))
        <img class="img-thumbnail post-image" src="{{asset('storage/uploads/' . $post->picture)}}" alt="画像">
      @endif
    </div>
  </div>
</div>

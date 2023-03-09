@if (Auth::check())
<div class="reaction-modal d-none">
  <ul class="reaction-icons">
    <li><a role="button" class="reactions eyes" data-reaction="1" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}">👀</a></li>
    <li><a role="button" class="reactions sads" data-reaction="2" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}">😭</a></li>
    <li><a role="button" class="reactions hearts" data-reaction="3" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}">💕</a></li>
    <li><a role="button" class="reactions questions" data-reaction="4" data-postid="{{ $post->id }}" data-userid="{{ Auth::user()->id }}">❓</a></li>

    <form action="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 5])  }}" method="post" enctype="multipart/form-data">
      @CSRF
      <li>
        <button type="submit" class="clearButton">
          <img src="{{ asset('storage/reaction_icons/pic_60c59198dc55e.png') }}" alt="reaction_5">
        </button>
      </li>
    </form>
    
    <form action="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 6])  }}" method="post" enctype="multipart/form-data">
      @CSRF
      <li>
        <button type="submit" class="clearButton">
          <img src="{{ asset('storage/reaction_icons/pic_haha56faeignae.png') }}" alt="reaction_6">
        </button>
      </li>
    </form>

    <form action="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 7])  }}" method="post" enctype="multipart/form-data">
      @CSRF
      <li>
        <button type="submit" class="clearButton">
          <img src="{{ asset('storage/reaction_icons/pic_10h382hj02f83f.png') }}" alt="reaction_7">
        </button>
      </li>
    </form>

    <form action="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 8])  }}" method="post" enctype="multipart/form-data">
      @CSRF
      <li>
        <button type="submit" class="clearButton">
          <img src="{{ asset('storage/reaction_icons/pic_iidaro.png') }}" alt="reaction_8">
        </button>
      </li>
    </form>

    <form action="{{ route('select_reaction', ['user_id' => Auth::id(), 'post_id' => $post->id, 'reaction_icon_id' => 9])  }}" method="post" enctype="multipart/form-data">
      @CSRF
      <li>
        <button type="submit" class="clearButton">
          <img src="{{ asset('storage/reaction_icons/pic_iina.png') }}" alt="reaction_9">
        </button>
      </li>
    </form>

  </ul>
</div>
@endif
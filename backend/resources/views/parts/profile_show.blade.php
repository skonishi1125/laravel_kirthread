<div class="container my-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>{{ $display_user->name }}さんのページ</h5>
        </div>

        <div class="card-body">
          <div class="col-12">

            <div class="container post-container">
              <div class="row">
                  <div class="col-auto">
                    @if (is_null($display_user->icon)) 
                      <img class="profile-icon" src="{{ asset('storage/icons/' . 'default.png') }}" alt="p_icon">
                    @else
                      <img class="profile-icon" src="{{ asset('storage/icons/' . $display_user->icon) }}" alt="p_icon">
                    @endif
                  </div>
                  <div class="col-auto name-wrapper">
                    <span>
                      <b>{{ $display_user->name }}</b>
                      <br>
                      <small>
                        @if (isset($display_user->profile->birth_year) || isset($display_user->profile->birth_month) || isset($display_user->profile->birth_day))
                          🎂 {{$display_user->profile->birth_year}}.{{$display_user->profile->birth_month}}.{{$display_user->profile->birth_day}}
                        @endif
                        @if (isset($display_user->profile->sns_url))
                          <a href="{{$display_user->profile->sns_url}}"><span class="material-symbols-outlined">home</span></a>
                        @endif
                      </small>
                    </span>
                    <br>
                    <small>登録日: {{$display_user->created_at}}</small>
                    <br>

                  </div>
                <div class="col-12">
                  <p>{!! nl2br($display_user->profile->makeLink(e($display_user->profile->message))) !!}</p>
                </div>
              </div>
            </div>

            @if (Auth::id() === $display_user->id)
              <a class="btn btn-success edit-button" href="{{ route('config_index')}}" role="button">編集</a>
            @endif

          </div> <!-- col-12 -->
        </div><!-- card-body -->
      </div><!-- card -->

      <div class="mt-3"></div>

      <div class="card">
        <ul class="nav nav-tabs">
          @if ( \App\Models\Profile::isCurrentUrlProfileShow($display_user->id) )
            <li class="nav-item">
              <a class="nav-link active" href="{{ route('profile_show', ['user_id' => $display_user->id])}}">投稿</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('profile_show_reacted', ['user_id' => $display_user->id])}}">リアクションした投稿</a>
            </li>
          @elseif ( \App\Models\Profile::isCurrentUrlProfileShowReacted($display_user->id) )
            <li class="nav-item">
              <a class="nav-link" href="{{ route('profile_show', ['user_id' => $display_user->id])}}">投稿</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="{{ route('profile_show_reacted', ['user_id' => $display_user->id])}}">リアクションした投稿</a>
            </li>
          @endif
        </ul>
      
        <div class="mt-3"></div>
        @foreach ($posts as $post)
          @include('parts.post',['post => $post, reaction_icons => $reaction_icons'])
          <div class="my-2" style="border-bottom:1px dotted #333;"></div>
        @endforeach

        <div class="mt-3 d-flex justify-content-center">
          {{ $posts->links('vendor.pagination.original-pagination') }}
        </div>
      </div>



    </div><!-- col-xs-12 -->
  </div>
</div>

@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>{{App\User::where('id',$post->user_id)->value('name')}}さんの投稿</h5>
        </div>

        <div class="card-body">

          <div class="col-12">
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <hr class="my-3">

            @include('parts.post',['post => $post'])

            <div class="col-12">
              @foreach ($users as $user)
                @if ($user['icon'] !== null)
                  <img class="profile-icon" style="border: 1px solid gray" src="{{asset('storage/icons/' . $user['icon'])}}" alt="">
                  <span>{{ $user['name'] }}</span>
                @else
                  <img class="profile-icon" style="border: 1px solid gray" src="{{asset('storage/icons/default.png')}}" alt="">
                  <span>{{ $user['name'] }}</span>
                @endif
              @endforeach
              <span>さんがリアクションをつけています</span>
            </div>

          </div> <!-- col-12 -->

        </div><!-- card-body -->
      </div><!-- card -->

    </div><!-- col-xs-12 -->

  </div>
</div>
<!-- jsを読み込むときは、backend/publicのパス記述を省略させる -->
{{--
<script type="text/javascript" src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script> 
--}}

@endsection

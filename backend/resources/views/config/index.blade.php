@extends('layouts.app')

@section('content')
<div class="container my-3">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>設定画面</h5>
        </div>

        <div class="card-body">

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li><small>{{ $error }}</small></li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="col-xs-12">
            <form action="{{ route('config_store') }}" method="post" enctype="multipart/form-data">
              @csrf

              <div class="form-group">
                <label for="config-message">
                  <h5 class="card-title mb-2">メッセージ</h5>
                  <h6 class="card-subtitle mb-2 text-muted">プロフィールページに表示される文言です。</h6>
                </label>
                <textarea class="form-control" id="config-message" name="message" placeholder="よろしくお願いします。">{{  $user->profile->message ?? old('message') }}</textarea>
              </div>

              <div class="form-group">
                <label for="config-icon">
                  <h5 class="card-title mb-2">プロフィール画像</h5>
                  <h6 class="card-subtitle mb-2 text-muted">未指定の場合は既存のアイコンのまま更新されます。</h6>
                </label>
                <div>
                  <input style="font-size: 75%;" type="file" name="icon" class="form-control-file" id="config-icon">
                </div>
              </div>

              <button type="submit" class="btn btn-primary btn-sm">変更</button>

              {{-- 
                <div class="form-select-wrap">
                  <select class="birthday-year">
                  </select>
                  / 
                  <select class="birthday-month">
                  </select>
                  /
                  <select class="birthday-day">
                  </select>
                </div> 
              --}}

            </form>

          </div> <!-- col-xs-12 -->
      </div><!-- card-body -->
    </div>
  </div>
</div>
<!-- jsを読み込むときは、backend/publicのパス記述を省略させる -->
<script type="text/javascript" src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

{{--
<script>
  let userBirthdayYear = document.querySelector('.birthday-year');
  let userBirthdayMonth = document.querySelector('.birthday-month');
  let userBirthdayDay = document.querySelector('.birthday-day');

  /**
   * selectのoptionタグを生成するための関数
   * @param {Element} elem 変更したいselectの要素
   * @param {Number} val 表示される文字と値の数値
   */
  function createOptionForElements(elem, val) {
    let option = document.createElement('option');
    option.text = val;
    option.value = val;
    elem.appendChild(option);
  }

  //年の生成
  for(let i = 1920; i <= 2020; i++) {
    createOptionForElements(userBirthdayYear, i);
  }
  //月の生成
  for(let i = 1; i <= 12; i++) {
    createOptionForElements(userBirthdayMonth, i);
  }
  //日の生成
  for(let i = 1; i <= 31; i++) {
    createOptionForElements(userBirthdayDay, i);
  }

  /**
   * 日付を変更する関数
   */
  function changeTheDay() {
    //日付の要素を削除
    userBirthdayDay.innerHTML = '';

    //選択された年月の最終日を計算
    let lastDayOfTheMonth = new Date(userBirthdayYear.value, userBirthdayMonth.value, 0).getDate();

    //選択された年月の日付を生成
    for(let i = 1; i <= lastDayOfTheMonth; i++) {
      createOptionForElements(userBirthdayDay, i);
    }
  }

  userBirthdayYear.addEventListener('change', function() {
    changeTheDay();
  });

  userBirthdayMonth.addEventListener('change', function() {
    changeTheDay();
  });
  </script>
--}}

@endsection

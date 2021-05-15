@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>設定画面</h5>
        </div>

        <div class="card-body">

          <div class="col-xs-12">
            <form action="{{ route('config.store') }}" method="post" enctype="multipart/form-data">
              @CSRF
              <div class="form-group">
                <label for="post-picture">アイコンを設定する</label>
                <input type="file" name="icon" class="form-control-file">
              </div>
              <button type="submit" class="btn btn-primary btn-sm">変更</button>
            </form>

          </div> <!-- col-xs-12 -->
      </div><!-- card-body -->
    </div>
  </div>
</div>
<!-- jsを読み込むときは、backend/publicのパス記述を省略させる -->
<script type="text/javascript" src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection

@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>CSVインポート</h5>
        </div>
        <div class="card-body">
          <div class="col-12">

            {{-- 指定ファイルがCSVでなかった場合のバリデーションエラー --}}
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('study_import_csv_store') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="importCsvFile">投稿としてインポートするファイルを選択</label>
                <input type="file" class="form-control-file" id="importCsvFile" name="importCsvFile">
              </div>
              <button type="submit" class="btn btn-primary mb-2">取り込む</button>
            </form>

          </div> <!-- col-12 -->
        </div><!-- card-body -->
      </div><!-- card -->
    </div><!-- col-xs-12 -->

  </div>
</div>

@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    ログインに成功しました。数秒後にページ移動します...
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script type="text/javascript">
  // 自動遷移
  setTimeout(function(){
    window.location.href = '{{ route('/') }}';
  }, 1*1000);
</script>

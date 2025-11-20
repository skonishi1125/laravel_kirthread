@extends('layouts.app')
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">本登録</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger small">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <p class="text-muted small">ゲーム等のゲストデータを引き継ぎ、本登録ができます。</p>

                    <form method="POST" action="{{ route('guest_upgrade_post') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">おなまえ</label>
                            <input id="name" class="form-control" name="name" required value="{{ old('name', $name) }}">
                        </div>

                        <div class="form-group">
                            <label for="email">メールアドレス</label>
                            <input type="email" id="email" class="form-control" name="email" required value="{{ old('email') }}">
                        </div>

                        <div class="form-group">
                            <label for="password">パスワード</label>
                            <input type="password" id="password" class="form-control" name="password" required>
                            <small id="passwordError" class="text-danger" style="display:none;"></small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">パスワード（確認）</label>
                            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary">この内容で登録</button>
                        </div>
                    </form>

                </div><!-- /card-body -->
                <p style="text-align: center; margin-top: 10px; font-size: 12px;">
                    ※情報の取り扱いや本サイトの詳細は<a target="_blank" href="{{ route('about') }}">こちらのページ</a>をご参照ください。
                </p>
            </div><!-- /card -->


        </div>
    </div>
</div>

@vite('resources/js/selfmade/guest/password_validate.js')

@endsection


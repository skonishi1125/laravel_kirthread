@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>かあスレッドとは</h5>
        </div>

        <div class="card-body">

          <div class="col-12">
            <h5><b>このサイトって？</b></h5>
            <p>
              ぼく (<a href="https://twitter.com/skirplus">@skirplus</a>) が勉強用に作っている掲示板サイトです。覚えた内容が機能として不定期にアップデートされていきます。<br>
              機能としては書き込み、画像のアップロードが主ですが自分の学習内容によってコンテンツが増えるかも。twitterで呟くことでもないことを良かったら書き込んでください。大抵僕が反応します。知り合いじゃなくても当たりに行きます。
            </p>
            <div class="py-2"></div>
            <h5><b>登録時の注意点</b></h5>
            <p>
              かあスレッドは登録しないと書き込めません。<br>
              登録する際はemailとパスワードが必要なのですが、パスワードはこちら側から確認できないような形で格納されますが、他で使ってるパスワードとかは流用しないほうが良いと思います。emailもaaa@tekitou.comみたいな適当な形で登録が可能です。
            </p>
            <div class="py-2"></div>

            <h5><b>Q&A, 要望について</b></h5>
            <p>
              書き込んでくれたら僕が見ます。要望とかは無理じゃなかったら時間空いたとき頑張ってみます。
            </p>
            <div class="py-2"></div>

            <h5><b>環境</b></h5>
            <p>
              インフラ: conoHa VPS <br>
              使ってるもの: PHP(Laravel), MySQL <br>
              かあスレ稼働料金: 500円 <br>
            </p>

            <a href="{{ route('/') }}">もどる</a>
            
          </div> <!-- col-12 -->
        </div><!-- card-body -->
      </div><!-- card -->

    </div><!-- col-xs-12 -->

  </div>
</div>

@endsection

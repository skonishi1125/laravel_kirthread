@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">
          <h5>かあスレッドとは</h5>
        </div>

        <div class="card-body">

          <div class="col-12">
            <h5><b>GitHubリポジトリ</b></h5>
            <a href="https://github.com/skonishi1125/laravel_kirthread">https://github.com/skonishi1125/laravel_kirthread</a>
            <div class="py-2"></div>

            <h5><b>このサイトについて</b></h5>
            <p>
              自分 (<a href="https://twitter.com/skirplus">@skirplus</a>) が勉強用に運用している掲示板サイトです。<br>
              学習した内容や作ってみたいと思った内容が、不定期に機能としてアップデートされていきます。<br>
              機能としては書き込み、画像のアップロードが主ですが自分の学習内容によってコンテンツが増えるかも。Twitterで呟くことでもないことを良かったら書き込んでください。
            </p>
            <div class="py-2"></div>

            <h5><b>登録時の注意点</b></h5>
            <p>
              かあスレッドへの投稿はユーザー登録が必要になります。<br>
              emailとパスワードの記入が求められますが、パスワードは管理者側から確認できない形で格納されます。<br>
              email, パスワードはどちらも普段使っているものを流用しないことをお勧めします。<br>
              aaa@tekitou.comのように、架空のメールアドレスやパスワードを使えるので、まずはそういった形での登録をお試しください。
            </p>
            <div class="py-2"></div>

            <h5><b>Q&A, 要望について</b></h5>
            <p>
              掲示板に書き込みください。<br>
              要望とかは労力がそこまでかからないものであれば、時間が空いたときに頑張ってみます。
            </p>
            <div class="py-2"></div>

            <h5><b>稼働環境など</b></h5>
            <p>
              <a target="_blank" href="https://github.com/skonishi1125/laravel_kirthread#%E7%92%B0%E5%A2%83">こちら</a>をどうぞ。<br>
              稼働料金は月1,500円くらい(VPS / ドメイン料金等こみこみで)
            </p>

            <h5><b>そのほか</b></h5>
                <b>X(Twitter)</b>
                <br>
                イラストの投稿がメインで、プログラムの話は控えめです。
                <div class="py-2"></div>

                <div class="iframely-embed"><div class="iframely-responsive" style="height: 140px; padding-bottom: 0;"><a href="https://x.com/skirplus" data-iframely-url="https://iframely.net/Hr35Vbcc?_timeline=false&_theme=dark&theme=light"></a></div></div><script async src="https://iframely.net/embed.js"></script>

                <div class="py-2"></div>
                <b>ブログ</b>
                <br>
                <a href="https://plusmash.blog">かあブログ</a>をやってます。チラシの裏に書くような内容をまとめています。<br>
                <a href="https://plusmash.blog/about/">https://plusmash.blog/about/</a>
                {{-- {!! $twitter_iframely_data->html !!} --}}

            <div style="text-align: center; margin-top: 10px;">
                <a style="text-align:center" href="{{ route('/') }}">かあスレッドトップページへ</a>
            </div>
            
          </div> <!-- col-12 -->
        </div><!-- card-body -->
      </div><!-- card -->

    </div><!-- col-xs-12 -->

  </div>
</div>

@endsection

// Laravelの場合、addEventListener構築時はDOMファイルの読み込みを待つ
{
  document.addEventListener( 'DOMContentLoaded' , function( e ) {
    'use strict';

    /**
     * 投稿削除時の警告処理
     */
    let trash = document.getElementsByClassName('trash');
    for ( let i = 0; i < trash.length; i++ ) {
      trash[ i ].addEventListener( 'click', function( e ) {
        if ( confirm( '本当に削除しますか？' ) ) {
        } else {
          // デフォルトの動作をキャンセルする(submitイベント(e)の本来の動作を止める)
          // これを記述しないとfalseの場合でもaタグのイベントが動作する
          e.preventDefault();
        }
      }, false );
    }

  }, false ); // DOMContentLoaded
}
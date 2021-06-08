// Laravelの場合、addEventListener構築時はDOMファイルの読み込みを待つ
{
  document.addEventListener( 'DOMContentLoaded' , function( e ) {
    'use strict';
    var trash = document.getElementsByClassName('trash');
    // console.log(trash[0]);
    // console.log(trash[0].href);

    var i;
    for ( i = 0; i < trash.length; i++ ) {
        trash[ i ].addEventListener( 'click', function( e ) {
            // e.preventDefault();
            // console.log(trash[i]); は動作しない
            // console.log(this.href);
            if ( confirm( '本当に削除しますか？' ) ) {
              e.preventDefault();
              let a = this.href;
              location.href = a;
            } else {
              // デフォルトの動作をキャンセルする(submitイベント(e)の本来の動作を止める)
              // これを記述しないとfalseの場合でもaタグのイベントが動作する
              e.preventDefault();
            }
        }, false );
    }
  }, false );

}
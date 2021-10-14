// Laravelの場合、addEventListener構築時はDOMファイルの読み込みを待つ
{
  document.addEventListener( 'DOMContentLoaded' , function( e ) {
    'use strict';
    var trash = document.getElementsByClassName('trash');
    // console.log(trash[0]);
    // console.log(trash[0].href);
    var i;
    for ( let i = 0; i < trash.length; i++ ) {
        trash[ i ].addEventListener( 'click', function( e ) {
            // e.preventDefault();
            // console.log(trash[i]); は動作しない
            // console.log(this.href);
            if ( confirm( '本当に削除しますか？' ) ) {
              // e.preventDefault();
              // let a = this.href;
              // location.href = a;
            } else {
              // デフォルトの動作をキャンセルする(submitイベント(e)の本来の動作を止める)
              // これを記述しないとfalseの場合でもaタグのイベントが動作する
              e.preventDefault();
            }
        }, false );
    }

    // リアクションを押したときの反応
    let reaction = document.getElementsByClassName('reaction');
    console.log(reaction);
    for ( let i = 0; i < reaction.length; i++) {
      reaction[ i ].addEventListener('click', (e) => {
        // クリックしたreactionの初めの要素を取得している。これをクリックした要素に直す
        // console.log(document.querySelectorAll('.reaction'));
        
        // console.log(this.querySelectorAll('.reaction'));
        // console.log(this);
        // 上の場合、thisが#documentになっている

        // クリックした要素の選択
        console.log(reaction[i]);

        // クリックした親を取得して、その兄弟要素のreaction_modalを表示する
        let oya = reaction[i].parentNode;
        console.log(oya);

        let eventDOM = e.target;
        let eventParent = e.target.parentNode;
        let eventParentNextSib = eventParent.nextElementSibling;
        console.dir(eventDOM);
        console.dir(eventParent);
        console.log(eventParentNextSib);
        eventParentNextSib.classList.toggle('d-none');

        // 最初に出てきた(querySelector)では取得できているので、これをクリックした要素にしたい・・・
        console.log(document.querySelector('.reaction').parentNode.nextElementSibling);
      })
    };

  }, false ); // DOMContentLoaded
}
// Laravelの場合、addEventListener構築時はDOMファイルの読み込みを待つ
{
  document.addEventListener( 'DOMContentLoaded' , function( e ) {
    'use strict';
    console.log('csvだよ');
    let csv_download_element = document.getElementById('csv-download');
    csv_download_element.addEventListener('click', function( e ) {
        if ( confirm( '今までのあなたの投稿をcsv形式でダウンロードしますか？' ) ) {
        } else {
          e.preventDefault();
        }
    }, false );

  }, false ); // DOMContentLoaded
}
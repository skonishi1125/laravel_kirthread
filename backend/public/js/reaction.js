{
  document.addEventListener( 'DOMContentLoaded' , function( e ) {
    'use strict';

    /**
     * リアクション吹き出し表示処理
     */
    let reactionDisplayIcon = document.getElementsByClassName('reaction-display-icon');
    for ( let i = 0; i < reactionDisplayIcon.length; i++) {
      reactionDisplayIcon[ i ].addEventListener('click', (e) => {
        // let oya = reactionDisplayIcon[i].parentNode; // クリックしたアイコンのspan要素を包むdiv要素全て
        // let eventDOM = e.target;// クリックしたアイコンのspan要素単体
        // console.log(oya, eventDOM);
        let eventParent = e.target.parentNode;
        let eventParentNextSib = eventParent.nextElementSibling; // クリックしたspan要素を含むdiv要素の、次に並ぶ要素(reaction-modal)
        // console.log(eventParentNextSib);
        eventParentNextSib.classList.toggle('d-none'); // rection-modalクラスをd-noneクラスで操作する
      })
    };

    /**
     * リアクション計算処理
     */
    // reactionsクラスを持つ全HTMLCollenction要素(=吹き出しボタンの要素)を、js配列として取得する
    // この作業をするとforEachやmapで回せるようになる
    let reactions = Array.from(document.getElementsByClassName('reactions')); 
    reactions.forEach (function (reaction) {
      // 吹き出しボタンクリック時の挙動
      $(reaction).on('click', function(e) { 

        // e.target: クリックした正確な要素を取得する(<a><img></a>でimgをクリックしたなら、img)
        // e.currentTarget: イベントリスナーを設定した要素を取得する(上記でimgをクリックしたら、aタグを取得してくれる)
        // console.log(e.target, e.currentTarget);
        // 今回はimgをクリックしてもaタグとして処理を進めたいので、currentTargetを採用する
        
        // 多重サブミット対策 
        // reactionAjaxExec()を開始した時、disabledクラスを付与している
        // ajax処理中はdisabledを付与しておき、ajaxが正常終了する時にこのクラスを外す
        if (!$(e.currentTarget).hasClass('disabled')) {
          // 投稿下部の青ボタンの親となるdiv要素を吹き出しボタンのクリックに合わせて操作するため取得しておく
          let belowPostReactionButtonsContainer = $(e.currentTarget).closest('.post-container').find('.below-post-reaction-buttons-container');

          // 投稿下部の青ボタン部分の表示に必要な要素の取得
          let belowPostReactionData = {
            'postId'            : this.dataset.postid,
            'userId'            : this.dataset.userid,
            'reactionIconId'    : this.dataset.reaction,
            'reactionName'      : this.dataset.reactionname,
            'reactionNamePlural': this.dataset.reactionnameplural,
            'isPictureIcon'     : this.dataset.ispictureicon,
            'value'             : this.dataset.value,
            'status'            : 0
          };

          // statusの設定調整
          // 0: 投稿に対して自分以外がリアクションをしている場合
          // 1: 投稿に対して自分も含めてリアクションをしている場合
          // 青ボタンが以下のクラスの並び(自分によってリアクションが付与されている)なら、statusを1にするイメージ
          // 👍の例: class="reactions-button thumbs_ups add-reaction"
          // hasClass()は文字列検索なので、クラス付与の順番が間違っていると動作しない
          let grantedClass = 'reactions-button ' + belowPostReactionData['reactionNamePlural'] +  ' add-reaction'
          let is_add = $(e.currentTarget).closest('.post-container').find('.reactions-button').hasClass(grantedClass);
          if (is_add) belowPostReactionData['status'] = 1;

          reactionAjaxExec(belowPostReactionData, e, belowPostReactionButtonsContainer);

        }
      });
    });

    function reactionAjaxExec(belowPostReactionData,  e, belowPostReactionButtonsContainer) {
      // console.log('関数', belowPostReactionData, e, belowPostReactionButtonsContainer);

      // ajax処理が終わるまでクリックした対象要素をdisabledとしておく(何度もクリックされないようにする)
      e.currentTarget.classList.add('disabled');

      // csrfトークンを自動的にヘッダーに追加しておくことで、正当なリクエストかどうかを判断できる
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "POST",
        url: '/ajax', // PostController@ajaxReaction
        data: belowPostReactionData,
        dataType: 'json',
        success: function (result) {

          let t       = belowPostReactionButtonsContainer;

          let a       = document.createElement('a');
          let li      = document.createElement('li');
          let form    = document.createElement('form');
          let img     = document.createElement('img');

          let status  = belowPostReactionData['status'];
          let isPictureIcon = belowPostReactionData['isPictureIcon'];
          let value   = belowPostReactionData['value']; // 元々reactという変数名
          // クラス名はreactions.name_pluralを使う
          let className = belowPostReactionData['reactionNamePlural']; 
          let t_find_className = t.find('.' + className);

          console.log('-----------------------------------------');
          // console.log(
          //   '文字のリアクション', 
          //   className,
          //   t.find('.add-reaction ' + className).length && t_find_className.length,
          //   t.find('.add-reaction ' + className),
          //   t.find('.add-reaction ' + className).length,
          //   t_find_className,
          //   t_find_className.length
          // );

          // t_find_className.length の解説(例: 👍)
          // t(投稿下部の青ボタンの親div)の中から、class="thumbs_ups"と付与された青ボタンがあるかどうかを確認しているチェック
          // lengthはfindで見つかった要素の数を返すことができる。見つかればその数, 見つからなければ0を返す。 
          // lengthで存在チェックをすることは結構メジャーな使い方っぽい。

          // 押したリアクションの青ボタンが存在している場合の処理
          if( t_find_className.length == 1 ) {
            // 誰かが押している青ボタンが存在していた場合、カウントを1増やす
            if (status == 0) {
              var cnt = t_find_className.data()["count"] + 1;
              t_find_className.data()["count"] = cnt;
              console.log('pushed someone reacted');
            // 自分が押した青ボタンが存在していた場合、カウントを1減らす
            } else {
              var cnt = t_find_className.data()["count"] - 1;
              t_find_className.data()["count"] = cnt;
              console.log('pushed myself reacted');
            }
            t_find_className.text(value + ' x ' + cnt);
            t_find_className.toggleClass('add-reaction');
            // cntが0になったなら、青ボタン自体をHTML要素から削除する
            if (cnt == 0) {
              console.log('Delete because cnt has reached 0.');
              t_find_className.remove();
            }
            e.currentTarget.classList.remove('disabled');

          // 青ボタンのdiv要素にリアクションがない状態で、吹き出し部分からリアクションをつけた時の処理
          } else {
            // console.log(
            //   'リアクション無し。', t_find_className, t_find_className.length, className,
            //   a, li, form, t
            // );

            // 青ボタン(押下済み)aタグの作成
            a.className = 'btn btn-info btn-sm reactions add-btn reactions-button ' + className + ' add-reaction';
            a.dataset.postid              = belowPostReactionData['postId'];
            a.dataset.userid              = belowPostReactionData['userId'];
            a.dataset.reactionname        = belowPostReactionData['reactionName'];
            a.dataset.reactionnameplural  = belowPostReactionData['reactionNamePlural'];
            a.dataset.ispictureicon       = belowPostReactionData['isPictureIcon'];
            a.dataset.value               = belowPostReactionData['value'];
            a.dataset.reaction = 1;
            a.dataset.count = 1;

            // 画像リアクションだった場合、aタグの内部にimgタグを仕込む
            if (isPictureIcon == 1) {
              console.log('pushed picture reaction');
              img.src = e.target.getAttribute('src');
              img.alt = e.target.getAttribute('alt');
              a.appendChild(img);
              let textNode = document.createTextNode(' × 1');
              a.appendChild(textNode);
            } else {
              console.log('pushed text reaction');
              a.textContent = value + ' × 1';
            }

            li.append(a);
            form.append(li);
            form.method = 'get';
            form.name = "form_test";
            form.action = '/ajax';
            t.append(form);
            e.currentTarget.classList.remove('disabled');

            // 増やした青ボタンを押した場合でも、ajax処理に遷移するようにする
            let added_btns = Array.from(document.getElementsByClassName('add-btn'));
            added_btns.forEach(function(added_btn) {
              $(added_btn).on('click', function(e) {
                // 増えた = 押されている前提なので、ステータスを1に変更して渡す
                belowPostReactionData['status'] = 1;
                $.ajax({
                  type: "POST",
                  url: '/ajax', // PostController@ajaxReaction
                  data: belowPostReactionData,
                  dataType: 'json',
                  success: function (result) {
                    console.log('pushed added_btn');
                     // 増えたボタンを再度押す = 自分でつけたリアクションを消すことなので、クリック対象の要素を削除する
                    e.currentTarget.remove();
                  },
                  error: function (result) {
                    console.log('errored by added_btn error:', result);
                  }
                });
              });
            });
          } // if( t_find_className.length == 1 ) 〆
        },
        error: function (result) {
          console.log('ajax error');
        }
      });
    } // reactionAjaxExec() 〆

    function sample() {
      console.log('function test');
    }

  }, false ); // DOMContentLoaded
}
// Laravelの場合、addEventListener構築時はDOMファイルの読み込みを待つ
{
  document.addEventListener( 'DOMContentLoaded' , function( e ) {
    'use strict';
    var trash = document.getElementsByClassName('trash');
    var i;
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

    // 😁+を押したときの反応
    let reaction = document.getElementsByClassName('reaction');
    for ( let i = 0; i < reaction.length; i++) {
      reaction[ i ].addEventListener('click', (e) => {
        let oya = reaction[i].parentNode;
        let eventDOM = e.target;
        let eventParent = e.target.parentNode;
        let eventParentNextSib = eventParent.nextElementSibling;
        eventParentNextSib.classList.toggle('d-none');
      })
    };

    // 非同期リアクション関係
    var reactions = Array.from(document.getElementsByClassName('reactions'));
    reactions.forEach (function (reaction) {
      $(reaction).on('click', function(e) {

        // 多重サブミット対策
        if (!$(e.target).hasClass('disabled')) {
          // リアクションがつく範囲を取得
          var table = $(e.target).closest('.post-container').find('.reaction-buttons');
          var post_id = this.dataset.postid;
          var user_id = this.dataset.userid;
          var reaction_number = $(e.target).data('reaction');
          var data = {
            'post_id'           : post_id,
            'user_id'           : user_id,
            'reaction_number'   : reaction_number,
            'status'            : 0
          };
          switch ($(e.target).data('reaction')) {
            case 1 :
              // hasClass()は文字列検索なので、クラス付与の順番が間違っていると動作しない
              var is_add = $(e.target).closest('.post-container').find('.reactions-button').hasClass('reactions-button eyes add-reaction');
              if (is_add) {
                data['status'] = 1;
              }
              var parts = {
                'reaction'       : "👀",
                'className' : "eyes",
              }
              reactionAjaxExec(data, e, table, parts);
              break;
            case 2 : 
              var is_add = $(e.target).closest('.post-container').find('.reactions-button').hasClass('reactions-button sads add-reaction');
              if (is_add) {
                data['status'] = 1;
              }
              var parts = {
                'reaction'       : "😭",
                'className' : "sads",
              }
              reactionAjaxExec(data, e, table, parts);
              break;
            case 3:
              var is_add = $(e.target).closest('.post-container').find('.reactions-button').hasClass('reactions-button hearts add-reaction');
              if (is_add) {
                data['status'] = 1;
              }
              var parts = {
                'reaction'       : "💕",
                'className' : "hearts",
              }
              reactionAjaxExec(data, e, table, parts);
              break;
            case 4:
              var is_add = $(e.target).closest('.post-container').find('.reactions-button').hasClass('reactions-button questions add-reaction');
              if (is_add) {
                data['status'] = 1;
              }
              var parts = {
                'reaction'       : "❓",
                'className' : "questions",
              }
              reactionAjaxExec(data, e, table, parts);
              break;
            case 5:
              // var is_add = $(e.target).closest('.post-container').find('.reactions-button').hasClass('reactions-button kaiddds add-reaction');
              // if (is_add) {
              //   data['status'] = 1;
              // }
              // var parts = {
              //   'reaction'  : "🕶",
              //   'path'      : path_to_image + 'storage/reaction_icons/pic_60c59198dc55e.png',
              //   'className' : "kaiddds",
              // }
              // reactionAjaxExec(data, e, table, parts, true);
              break;
          }; // switch
        }; // if (!$(e.target).hasClass('disabled'))
      }); // $(reaction).on('click', function(e)
    });// forEach


    function reactionAjaxExec(data, e, table, parts, img_flag = false) {
      // console.log(data, e, table, parts);
      e.target.classList.add('disabled');

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      
      $.ajax({
        type: "POST",
        url: '/ajax',
        data: data,
        dataType: 'json',
        success: function (result) {

          var t = table;
          var status = data['status'];
          var form = document.createElement('form');
          var li = document.createElement('li');
          var a = document.createElement('a');

          var react = parts['reaction'];
          var t_find_className = t.find('.' + parts['className']);

          // すでに押されている
          if (t.find('.add-reaction ' + parts['className']).length && t_find_className.length) {
            if (status !== 0) {
            //   var cnt = t.find('.add-reaction').data()["count"] + 1;
            //   t.find('.eyes').data()["count"] = cnt;
            //   console.log('A');
            // } else {
              var cnt = t.find('.add-reaction ' + parts['className']).data()['count'] - 1;
              t_find_className.data()["count"] = cnt;
              console.log('B');
              e.target.classList.remove('disabled');
            }
            t.find('.add-reaction ' + parts['className']).text(react + ' x ' + cnt);
            t.find('.add-reaction ' + parts['className']).removeClass('add-reaction');
            console.log('C');
            e.target.classList.remove('disabled');


          } else if( t_find_className.length == 1 ) {
            if (status == 0) {
              // 誰かが押しているリアクションボタンを押した時
              var cnt = t_find_className.data()["count"] + 1;
              t_find_className.data()["count"] = cnt;
              console.log('D');
              e.target.classList.remove('disabled');
            } else {
              var cnt = t_find_className.data()["count"] - 1;
              t_find_className.data()["count"] = cnt;
              console.log('E');
              e.target.classList.remove('disabled');
            }
            t_find_className.text(react + ' x ' + cnt);
            t_find_className.toggleClass('add-reaction');
            console.log('F');
            if (cnt == 0) {
              console.log('cntが0なのでremoveします。');
              t_find_className.remove();
            }
            e.target.classList.remove('disabled');

          // リアクションがない状態で、😀+からリアクションをつけた時の処理
          } else {
            console.log('リアクション無し。');
            console.log(a, li, form, t);
            a.textContent = react + ' × 1';
            a.className = 'btn btn-info btn-sm reactions add-btn reactions-button ' + parts['className'] + ' add-reaction';
            a.dataset.postid = data['post_id'];
            a.dataset.userid = data['user_id'];
            a.dataset.reaction = 1;
            a.dataset.count = 1;
            li.append(a);
            form.append(li);
            form.method = 'get';
            form.name = "form_test";
            form.action = '/ajax';
            t.append(form);
            e.target.classList.remove('disabled');

            // 増えたボタン用の処理（よくわからんがここ必要）
            var added_btn = document.getElementsByClassName('add-btn');
            var triggers = Array.from(added_btn);
            triggers.forEach(function(trigger) {
              trigger.addEventListener('click', function(e) {
                var data2 = {
                  'post_id'           : data['post_id'],
                  'user_id'           : data['user_id'],
                  'reaction_number'   : data['reaction_number'],
                  'status'            : 1,
                }
                $.ajax({
                  type: "POST",
                  url: '/ajax', //controllerまでのroute
                  data: data2,
                  dataType: 'json',
                  success: function (result) {
                    console.log('増えたボタンを押した', e.target);
                    e.target.remove();
                  },
                  error: function (result) {
                    console.log('増えたボタンでのError:', result);
                  }
                }); // 増えたajax〆
              }); // addeventlistener
            });// triggers.foreach
          } // else(リアクションボタンがなかった時(x0からx1になった時))
        },
        error: function (result) {
          console.log('errorだよ');
        }
      }); // ajax
    }// reactionAjaxExec()


  }, false ); // DOMContentLoaded
}
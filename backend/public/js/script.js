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

    // 既存の👀ボタンをajax化する
    var eyes = document.getElementsByClassName('eyes');
    var sads = document.getElementsByClassName('sads');

    // 全てのリアクションの要素から、押したリアクションについての情報をクラスを参考にして要素を取得
    // function getReaction() {
    //   var reaction_types = null;
    //   var reactions = document.getElementsByClassName('reactions');
    //   console.log('reactions : ' + Array.from(reactions));
    //   reactions = Array.from(reactions);
    //   reactions.forEach( function (reaction) {
    //     reaction.addEventListener('click', function(e) {
    //       switch ($(e.target).data('reaction')) {
    //         case 1 : 
    //           reaction_types = document.getElementsByClassName('eyes'); 
    //           console.log('1', reaction_types); 
    //           break;
    //         case 2 : 
    //           reaction_types = document.getElementsByClassName('sads'); 
    //           console.log('2', reaction_types); 
    //           break;
    //         default : break;
    //       };
    //     });
    //   });
    //   return reaction_types;
    // }

    // function test() {
    //   // functionでまとめて、ボタンを押した時に呼び出す？
    //   reaction_types = getReaction();
    //   reaction_types = Array.from(reaction_types);
    //   // reaction_types.forEach( function (r) {
    //   //   r.addEventListener('click', function(e) {
    //   //     console.log(e.target);
    //   //   });
    //   // });
    // }

    // var tsts = Array.from(document.getElementsByClassName('reactions'));
    // tsts.forEach (function (tst) {
    //   tst.addEventListener('click',function(e) {
        
    //     // 1. リアクションボタンを押した時、そのリアクションを判別する
    //     // 2. 判別したリアクションをreaction_typesに入れる
    //     // 3. reaction_types.addEventListenerで下記を実装する


    //   });
    // });


    var reactions = Array.from(document.getElementsByClassName('reactions'));
    var e = Array.from(document.getElementsByClassName('eyes'));
    var s = Array.from(document.getElementsByClassName('sads'));
    reactions.forEach (function (reaction) {
      $(reaction).on('click', function(e) {
        // リアクションがつく範囲を取得
        var table = $(e.target).closest('.post-container').find('.reaction-buttons');
        var post_id = this.dataset.postid;
        var user_id = this.dataset.userid;
        var status = 0;
        if ($(e.target).closest('.post-container').find('.reactions').hasClass('add-reaction')) {
          status = 1;
        }
        var data = {
          'post_id'           : post_id,
          'user_id'           : user_id,
          'reaction_number'   : 1,
          'status'            : status 
        };

        switch ($(e.target).data('reaction')) {
          case 1 : 
            var parts = {
              'reaction'       : "👀",
              'className' : "eyes",
            }
            reactionAjaxExec(data, e, table, parts);
            break;
          case 2 : 
            console.log('2です');
            var parts = {
              'reaction'       : "😢",
              'className' : "sads",
            }
            reactionAjaxExec(data, e, table, parts);
            break;
          default : 
            console.log('defaultでした');
            break;
        }; // switch
      }); // addEve
    });// forEach


    function reactionAjaxExec(data, e, table, parts) {
      // console.log(data, e, table, parts);

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

          // すでに押されてされている
          if (t.find('.add-reaction').length ) {
            if (status !== 0) {
            //   var cnt = t.find('.add-reaction').data()["count"] + 1;
            //   t.find('.eyes').data()["count"] = cnt;
            //   console.log('A');
            // } else {
              var cnt = t.find('.add-reaction').data()["count"] - 1;
              t_find_className.data()["count"] = cnt;
              console.log('B');
            }
            t.find('.add-reaction').text(react + ' x ' + cnt);
            t.find('.add-reaction').removeClass('add-reaction');
            console.log('C');


          } else if( t_find_className.length == 1 ) {
            if (status == 0) {
              // 誰かが押しているリアクションボタンを押した時
              var cnt = t_find_className.data()["count"] + 1;
              t_find_className.data()["count"] = cnt;
              console.log('D');
            // } else {
            //   var cnt = t_find_className.data()["count"] - 1;
            //   t_find_className.data()["count"] = cnt;
            //   console.log('E');
            }
            t_find_className.text('👀 x ' + cnt);
            t_find_className.addClass('add-reaction');
            console.log('F');

          // リアクションがない状態で、😀+からリアクションをつけた時の処理
          } else {
            a.textContent = react + ' × 1';
            a.className = 'btn btn-info btn-sm reactions add-reaction add-btn ' + parts['className'];
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
    }



    // クリック1回目で動かない？ -> クリックの回数分回ってしまう
    function ajaxToggleReaction(reactions) {
      reactions = Array.from(reactions);
      console.log('ajaxToggleReaction', reactions);

      reactions.forEach(function(tst) {
        // console.log('reactions.forEachの中'); // 17回ってんだけど、これは正常だと思う
        console.log(tst);

        // 😅ここがクリックの回数分回ってしまう
        tst.addEventListener('click', function(e) {
          var t = $(e.target).closest('.post-container').find('.reaction-buttons');
          var post_id = this.dataset.postid;
          var user_id = this.dataset.userid;

          console.log(t, post_id, user_id, e); // 取れてそうだ
    
          //  0 追加 , 1 削除 add-reaction(リアクション済みの場合、削除に変更)
          var status = 0;
          if ($(e.target).closest('.post-container').find('.reactions').hasClass('add-reaction')) {
            status = 1;
          }
  
          var data = {
            'post_id'           : post_id,
            'user_id'           : user_id,
            'reaction_number'   : 1,
            'status'            : status 
          };
  
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
              var form = document.createElement('form');
              var li = document.createElement('li');
              var a = document.createElement('a');

              if (t.find('.add-reaction').length ) {
                if (status == 0) {
                  var cnt = t.find('.add-reaction').data()["count"] + 1;
                  t.find('.eyes').data()["count"] = cnt;
                } else {
                  var cnt = t.find('.add-reaction').data()["count"] - 1;
                  t.find('.eyes').data()["count"] = cnt;
                }
                t.find('.add-reaction').text('👀 x ' + cnt);
                t.find('.add-reaction').removeClass('add-reaction');

              } else if( t.find('.eyes').length == 1 ) {
                if (status == 0) {
                  var cnt = t.find('.eyes').data()["count"] + 1;
                  t.find('.eyes').data()["count"] = cnt;
                } else {
                  var cnt = t.find('.eyes').data()["count"] - 1;
                  t.find('.eyes').data()["count"] = cnt;
                }
                t.find('.eyes').text('👀 x ' + cnt);
                t.find('.eyes').addClass('add-reaction');

              } else {
                a.textContent = "👀 × 1";
                a.className = 'btn btn-info btn-sm add-reaction add-btn eyes';
                li.append(a);
                form.append(li);
                form.method = 'get';
                form.name = "form_test";
                form.action = '/ajax';
                t.append(form);
          
                // getelementsbyclassnameで取得したものは配列に直して、foreachで処理を作る
                added_btn = document.getElementsByClassName('add-btn');
                var triggers = Array.from(added_btn);
                triggers.forEach(function(trigger) {
                  trigger.addEventListener('click', function(e) {
                    var data = {
                      'post_id'           : post_id,
                      'user_id'           : user_id,
                      'reaction_number'   : 1,
                      'status'            : 1,
                    }
                    $.ajax({
                      type: "POST",
                      url: '/ajax', //controllerまでのroute
                      data: data,
                      dataType: 'json',
                      success: function (result) {
                        console.log('増えた', e.target);
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
            error: function(result) {
              console.log('errorだよ');
            }
          }); // ajax

        });// tsts.addEventListener

      });// tst.foreach
    }; // ajaxToggleReaction


    /*
    eyes = Array.from(eyes);
    eyes.forEach(function(eye) {
      

      // テスト用
      // eye.addEventListener('mouseover', function(e) {
      //   var t = $(e.target).closest('div').find('.reaction-buttons');
      //   var me = t.find('.eyes').length;
      //   // console.log(t, 'addreaction no length : ' + t.find('.add-reaction').length, 'eyes: ' + me);
      // });

      eye.addEventListener('click', function(e) {
        var t = $(e.target).closest('.post-container').find('.reaction-buttons');
        
        console.log(t);

        var post_id = this.dataset.postid;
        var user_id = this.dataset.userid;
  
        //  0 追加 , 1 削除 add-reaction(リアクション済みの場合、削除に変更)
        var status = 0;
        if ($(e.target).closest('.post-container').find('.reactions').hasClass('add-reaction')) {
          status = 1;
        }
        console.log('post, user, ステータス: ', post_id, user_id, status);

        var data = {
          'post_id'           : post_id,
          'user_id'           : user_id,
          'reaction_number'   : 1,
          'status'            : status 
        };

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: "POST",
          url: '/ajax', //controllerまでのroute
          data: data,
          dataType: 'json',
          success: function (result) {
            // console.log('success!');
            // 登録済みのリアクション表示を追加する
            // var t = $(e.target).closest('div').find('.reaction-buttons');
            // var t = $(e.target).find('.reaction-buttons');
            console.log(t);
            var form = document.createElement('form');
            var li = document.createElement('li');
            var a = document.createElement('a');
        
            // あるならリアクションの数字を今+1増やす。
            if (t.find('.add-reaction').length ) {
              console.log('リアクション済みだった');
              // statusが 0 なら +1 , 1 なら -1
              if (status == 0) {
                console.log('is_react: ' + result['is_react']);
                var cnt = t.find('.add-reaction').data()["count"] + 1;
                t.find('.eyes').data()["count"] = cnt;
              } else {
                console.log(t.find('.add-reaction').data()["count"] );
                var cnt = t.find('.add-reaction').data()["count"] - 1;
                t.find('.eyes').data()["count"] = cnt;
                console.log('こっちだ cnt: ' + cnt);
              }
              t.find('.add-reaction').text('👀 x ' + cnt);
              t.find('.add-reaction').removeClass('add-reaction');
        
            // リアクションボタンがなければ追加する
            } else if( t.find('.eyes').length == 1 ) {
              console.log('自分はつけてないけどリアクションはある');
              // statusが 0 なら +1 , 1 なら -1

              if (status == 0) {
                console.log('画がが / ' + t.find('.eyes').data()["count"]);
                var cnt = t.find('.eyes').data()["count"] + 1;
                t.find('.eyes').data()["count"] = cnt;
                console.log(cnt, t.find('.eyes').data()["count"] );
              } else {
                var cnt = t.find('.eyes').data()["count"] - 1;
                t.find('.eyes').data()["count"] = cnt;
              }
              t.find('.eyes').text('👀 x ' + cnt);
              t.find('.eyes').addClass('add-reaction');
            } else {
              console.log('nai');
              a.textContent = "👀 × 1";
              a.className = 'btn btn-info btn-sm add-reaction add-btn eyes';
              li.append(a);
              form.append(li);
              form.method = 'get';
              form.name = "form_test";
              form.action = '/ajax';
              t.append(form);
        
              // getelementsbyclassnameで取得したものは配列に直して、foreachで処理を作る
              added_btn = document.getElementsByClassName('add-btn');
              var triggers = Array.from(added_btn);
              // ajaxによって追加したボタンに関するクリックイベント
        
              triggers.forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                  console.log(trigger, 'tst');
                  console.log('増えたボタンです');
                  // 増えたボタンにもajax処理を登録する
                  var data = {
                    'post_id'           : post_id,
                    'user_id'           : user_id,
                    'reaction_number'   : 1,
                    // すでに押されているならstatusを削除にする 増えたボタンは必ず押されているので削除でOK
                    'status'            : 1 // 0 追加 / 1 削除
                  }
                  $.ajax({
                    type: "POST",
                    url: '/ajax', //controllerまでのroute
                    data: data,
                    dataType: 'json',
                    success: function (result) {
                      console.log('増えた', e.target);
        
                      // リアクションが自分のものだけ( x 1)なら消す
                      e.target.remove();
        
                      // 他の人もリアクションしていたら数を減らす
                      // statusが1で、リアクションが0になったならボタン表示を消す。

                    },
                    error: function (result) {
                      console.log('増えたボタンでのError:', result);
                    }
                  }); // 増えたajax〆
                }); // addeventlistener
              });// triggers.foreach
            } // else(リアクションボタンがなかった時(x0からx1になった時))

          },
          error: function(result) {
            console.log('errorだよ');
          }
        }); // ajax
      });// eye.addEventListener
    });// eye.foreach
    
    */


    // // ajaxテスト
    // var added_btn; // ajaxで増えたボタンたち
    // var aj_tst = document.getElementById("ajax_test");
    // aj_tst.addEventListener('click',function(e){
    //   var post_id = this.dataset.postid;
    //   var user_id = this.dataset.userid;

    //   //  0 追加 , 1 削除 add-reaction(リアクション済みの場合、削除に変更)
    //   var status = 0;
    //   if ($(e.target).closest('div').find('.reactions').hasClass('add-reaction')) {
    //     status = 1;
    //   }

    //   // console.log( $(e.target).closest('div').find('.reactions'), );

    //   var data = {
    //     'post_id'           : post_id,
    //     'user_id'           : user_id,
    //     'reaction_number'   : 1,
    //     'status'            : status 
    //   }
    //   // console.log(aj_tst, data);
    //   // $.ajaxSetup({
    //   //   headers: {
    //   //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //   //   }
    //   // });

    //   $.ajax({
    //     type: "POST",
    //     url: '/ajax', //controllerまでのroute
    //     data: data,
    //     dataType: 'json',
    //     success: function (result) {
    //       // 登録済みのリアクション表示を追加する
    //       var t = $(e.target).closest('div').find('.reaction-buttons');
    //       var form = document.createElement('form');
    //       var li = document.createElement('li');
    //       var a = document.createElement('a');

    //       // あるならリアクションの数字を今+1増やす。
    //       if (t.find('.add-reaction').length) {
    //         console.log('ある');
    //         console.log(t.find('.add-reaction').data()["count"]);
    //         // statusが 0 なら +1 , 1 なら -1
    //         if (status == 0) {
    //           var cnt = t.find('.add-reaction').data()["count"] + 1;
    //         } else {
    //           var cnt = t.find('.add-reaction').data()["count"] - 1;
    //         }
    //         t.find('.add-reaction').text('👀 x ' + cnt);

    //       // リアクションボタンがなければ追加する
    //       } else {
    //         console.log('nai');
    //         a.textContent = "👀 × 1";
    //         a.className = 'btn btn-info btn-sm add-reaction add-btn';
    //         li.append(a);
    //         form.append(li);
    //         form.method = 'get';
    //         form.name = "form_test";
    //         form.action = '/ajax';
    //         t.append(form);

    //         // getelementsbyclassnameで取得したものは配列に直して、foreachで処理を作る
    //         added_btn = document.getElementsByClassName('add-btn');
    //         var triggers = Array.from(added_btn);
    //         // ajaxによって追加したボタンに関するクリックイベント

    //         triggers.forEach(function(trigger) {
    //           trigger.addEventListener('click', function(e) {
    //             console.log(trigger, 'tst');
    //             // 増えたボタンにもajax処理を登録する
    //             var data = {
    //               'post_id'           : post_id,
    //               'user_id'           : user_id,
    //               'reaction_number'   : 1,
    //               // すでに押されているならstatusを削除にする 増えたボタンは必ず押されているので削除でOK
    //               'status'            : 1 // 0 追加 / 1 削除
    //             }
    //             $.ajax({
    //               type: "POST",
    //               url: '/ajax', //controllerまでのroute
    //               data: data,
    //               dataType: 'json',
    //               success: function (result) {
    //                 console.log('増えた', e.target);
  
    //                 // リアクションが自分のものだけ( x 1)なら消す
    //                 e.target.remove();
  
    //                 // 他の人もリアクションしていたら数を減らす
  
    //                 /*
    //                   statusが1で、リアクションが0になったならボタン表示を消す。
    //                 */



    //               },
    //               error: function (result) {
    //                 console.log('増えたボタンでのError:', result);
    //               }
    //             }); // 増えたajax〆
    //           }); // addeventlistener
    //         });// triggers.foreach
    //       } // else(リアクションボタンがなかった時(x0からx1になった時))
    //     }, // ajax success
    //     error: function (result) {
    //       console.log('Errorでした:', result);
    //     }
    //   }); // 蒼テストボタンのajax

    // });// 青いテストボタンのeventlistener




  }, false ); // DOMContentLoaded
}
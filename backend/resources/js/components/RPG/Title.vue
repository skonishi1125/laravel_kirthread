<template>
  <div class="row" style="margin-top: 100px; text-align: center;">
    <div class="col-sm-12">
      <p class="mb-5">Title.vue(タイトル)</p>
      <span v-if="status == 'ready'">
        <div>
          <button class="btn btn-success" @click="switchMenuScreen">街に戻る</button>
        </div>
        <div class="mt-5">
          <button class="btn btn-danger" @click="displayDeleteModal">セーブデータの削除</button>
        </div>
      </span>
      <span v-if="status == 'signed'">
        <button class="btn btn-primary" @click="switchBeginningScreen">最初からはじめる</button>
      </span>
    </div>
  </div>

  <!-- セーブデータ削除モーダル -->
  <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">セーブデータの削除</h4>
          <button type="button" class="close" data-dismiss="modal" aria-rabel="Close"><span aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">
          <p>
            以下のファイルを削除しますが本当によろしいですか？ <br> 
          </p>
          <ul>
            <span v-for="party in this.dataParties">
              <li>{{ party['nickname'] }}【Lv.{{ party['level'] }}: {{party['class_japanese']}}】</li>
            </span>
          </ul>
          <div v-if="errorMessage !== null">
            <small style="color:red">{{ this.errorMessage }}</small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal" @click="resetData">やめる</button>
          <button type="button" class="btn btn-danger" @click="deleteSavedata">削除</button>
        </div>

      </div>
    </div>
  </div>


  <!-- すぐつく機能 -->
  <div class="modal fade" id="modal-login" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">遊ぶ前に</h5>
        </div>

        <div class="modal-body">
          <p>
            ゲームを遊ぶ前に<a target="_blank" href="/register">新規登録</a>または<a target="_blank" href="/login">ログイン</a>が必要です。<br>
            各手続きが面倒な方は「すぐ作る」ボタンから、登録兼ログインも可能です。
          </p>
          <div style="text-align: center;" >
            <button class="btn btn-sm btn-success" @click="generateCredential">
              <span v-if="generateCredentialEmailString !== null && generateCredentialPassword !== null">
                再生成する
              </span>
              <span v-else>
                すぐ作る
              </span>
            </button>
          </div>
          <div v-if="generateCredentialEmailString !== null && generateCredentialPassword !== null">
            <hr>
            <div>
              <p>
                以下の情報で作成します。<small>(一部編集可能)</small><br>
                <small>ユーザーネーム: </small><input type="text" maxlength="10" v-model="generateCredentialName"><br>
                <small>email: <b>sugutuku_<input  type="text" maxlength="10" v-model="this.generateCredentialEmailString">@sample.com</b></small> <br>
                <small>パスワード: </small><input  type="text" maxlength="16" v-model="generateCredentialPassword"><br>
                <br>
                <div v-if="errorMessage !== null">
                  <small style="color:red">このメールアドレスはすでに使われています。 再生成または別のアドレスの記入をお試しください。</small>
                </div>
              </p>
            </div>
            <div style="text-align: center;">
              <button class="btn btn-sm btn-primary" @click="createUser">こちらの情報でログイン</button>
            </div>
          </div>
          <hr>
          <p>
            <small>
              ※"すぐ作る"について<br>
              登録に必要なemailとパスワードを自動生成し、ユーザー情報を作成します。<br>
              作られたemailとパスワードは画面に表示されますが、メモを忘れると以降ログインができなくなります。<br>
              「とりあえずサービスを触ってみたい！」と言う目的の方はこちらをぜひお試しください。<br>
              <br>
              ※かあスレッドについて<br>
              基本的に掲示板ベースのwebサービスです。詳しくは<a target="_blank" href="/about">こちら</a>を参照ください。<br>
            </small>
          </p>
        </div>

      </div>
    </div>
  </div>

</template>

<script>
  import $ from 'jquery';
  import axios from 'axios';
  export default {
    data() { // script内で使用する変数を定義する。
      return {
        status: "",
        userId: null,
        passwordCharacters: 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%',
        generateCredentialName: '登録くん',
        generateCredentialEmail: null,
        generateCredentialEmailString: null,
        generateCredentialPassword: null,
        errorMessage: null,
        dataMoney: null,
        dataParties: [],
      }
    },
    created() {
      console.log('title.vue');
      // ユーザーの状況確認
      // まずはログインしてもらう必要がある。ログイン済みの場合セーブデータがあるかどうかの判定をする
      this.checkUserSituation();
    },
    mounted() {
    },
    methods: {
      checkUserSituation() {
        axios.get('/api/game/rpg/title/check_situation')
          .then(response => {
            console.log(response.data);
            this.status = response.data['status'];
            this.userId = response.data['user_id'];
            if (this.status == 'unsigned') {
              $('#modal-login').modal('show');
            }
        });
      },
      generateCredential() {
        // ランダムなID用の文字列生成
        this.generateCredentialEmailString = Math.random().toString(36).substring(2, 12); // 10桁のランダム文字

         // ランダムな8桁のパスワードを生成
         this.generateCredentialPassword = '';
          for (let i = 0; i < 8; i++) {
            const randomIndex = Math.floor(Math.random() * this.passwordCharacters.length);
            this.generateCredentialPassword += this.passwordCharacters[randomIndex];
          }

        // todo: ランダムで作った文字列が重複している可能性が0ではないのでチェックした方がいいかもしれない
      },
      createUser(){
        this.generateCredentialEmail = `sugutuku_${this.generateCredentialEmailString}@sample.com`;
        // axios.postで登録
        axios.post(`/api/game/rpg/title/create_rpg_user`, {
          name: this.generateCredentialName,
          email: this.generateCredentialEmail,
          password: this.generateCredentialPassword,
        })
        .then(response => {
          console.log(`通信OK`);
          // ワンタッチ挟んだ方がいいかも。 メモする時間を与える感じで。
          alert(`
            作成完了しました。\n 
            あなたのemail: ${this.generateCredentialEmail}\n
            あなたのパスワード: ${this.generateCredentialPassword}\n
            メッセージを閉じるとページが遷移し、上記の情報は二度と確認することができません。準備出来次第お進みください。
          `);
          location.reload(); // リロードする
        })
        .catch(error => {
          console.log(`通信失敗。`);
          if (error.response && error.response.data) {
            this.errorMessage = error.response.data.message;
          } else {
            this.errorMessage = "予期しないエラーが発生しました。もう一度お試しください。"
          }
        });
      },
      switchMenuScreen() {
        this.$store.dispatch('setScreen', 'menu');
        this.$router.push('/game/rpg/menu');
      },
      switchBeginningScreen() {
        this.$store.dispatch('setScreen', 'beginning');
        this.$router.push('/game/rpg/beginning');
      },
      displayDeleteModal() {
        axios.get(`/api/game/rpg/title/check_savedata_info`)
        .then(response => {
          console.log(response.data);
          this.dataMoney = response.data['money'];
          this.dataParties = response.data['parties'];
          $('#modal-delete').modal('show');
        })
        .catch(error => {
          console.log(`通信失敗。`);
          if (error.response && error.response.data) {
            this.errorMessage = error.response.data.message;
          } else {
            this.errorMessage = "予期しないエラーが発生しました。画面リロードなどをお試しください。"
          }
        });
      },
      deleteSavedata() {
        axios.post(`/api/game/rpg/title/delete_savedata`)
        .then(response => {
          console.log(response.data.message);
          $('#modal-delete').modal('hide');
          location.reload(); // リロードする
        })
        .catch(error => {
          console.log(`通信失敗。`);
          if (error.response && error.response.data) {
            this.errorMessage = error.response.data.message;
          } else {
            this.errorMessage = "予期しないエラーが発生しました。画面リロードなどをお試しください。"
          }
        });
      }
    },
  }
</script>
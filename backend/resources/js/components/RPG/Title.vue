<style>
.title-select-btn {
  margin-top: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
}

.title-button {
  text-align: center;
  width: 300px;
}
</style>

<template>
  <div class="container">
    <div class="row" style="margin-top: 100px; text-align: center;">
      <div class="col-sm-12">
        <p class="mb-5">
          <b>Epic Reckoning</b>
        </p>
        <span v-if="status == 'ready'">
          <div class="title-select-btn">
            <button class="btn btn-info title-button" @click="switchMenuScreen">街に戻る</button>
            <button class="btn btn-danger title-button" @click="displayDeleteModal">セーブデータの削除</button>
          </div>
        </span>
        <span v-if="status == 'signed'">
          <div class="title-select-btn">
            <button class="btn btn-primary title-button" @click="switchBeginningScreen">最初からはじめる</button>
          </div>
        </span>
      </div>
    </div>
  </div>

  <!-- セーブデータ削除モーダル -->
  <teleport to="body">
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h6 class="modal-title"><b>セーブデータの削除</b></h6>
            <button type="button" class="close" data-dismiss="modal" aria-rabel="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
            <p>
                以下のファイルを削除しますが本当によろしいですか？ <br> 
            </p>
            <ul>
                <span v-for="party in dataParties">
                <li>{{ party['nickname'] }}【Lv.{{ party['level'] }}: {{party['class_japanese']}}】</li>
                </span>
            </ul>
            <div v-if="errorMessage !== null">
                <small style="color:red">{{ errorMessage }}</small>
            </div>
            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-outline-info" data-dismiss="modal" @click="resetData">やめる</button>
            <button type="button" class="btn btn-danger" @click="deleteSavedata">削除</button>
            </div>

        </div>
        </div>
    </div>
  </teleport>

  <!-- すぐつく機能 -->
  <teleport to="body">
    <div class="modal fade" id="modal-login" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-sm">

          <!-- ヘッダー -->
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold">ゲームを遊ぶ前に</h5>
          </div>

          <!-- 本文 -->
          <div class="modal-body">

            <div style="text-align: center;">
              <p class="mb-2">
                おなまえを入力してください。
              </p>
  
              <p class="text-muted small mb-4">
                ※ゲーム本編とは関連はなく、プレイデータを管理するために使用します。
              </p>
            </div>

            <input 
              type="text" 
              maxlength="10"
              v-model="userName"
              class="form-control w-75 mx-auto mb-4 text-center"
              placeholder="おなまえ（10文字以内）"
            >

            <div class="text-center" style="height: 20px;">
              <small v-if="errorMessage" class="text-danger">{{ errorMessage }}</small>
            </div>

            <!-- ロード表示（スピナー + メッセージ） -->
            <div v-if="isLoading" class="text-center mt-3">
              <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                <span class="sr-only">Loading...</span>
              </div>
              <div class="mt-2">
                <small class="text-secondary">データ保存中です。画面を更新します…</small>
              </div>
            </div>

            <!-- ボタン（処理中はdisabled＆文言変更） -->
            <div class="text-center mt-3">
              <button 
                class="btn btn-primary px-4"
                @click="createUser"
                :disabled="isLoading"
              >
                <span v-if="!isLoading">ゲームを始める</span>
                <span v-else>処理中...</span>
              </button>
            </div>

          </div>

          <div class="px-4 py-3 border-top bg-light">

            <p class="small mb-2">
              <strong>※本ページの登録について</strong><br>
              ゲームの進行管理に必要となるユーザー情報をゲストとして作成します。<br>
              <span class="text-danger">
                「とりあえずゲームを触りたい！」という方は本機能を是非ご利用ください！
              </span>
            </p>

            <p class="small mb-2">
              <strong>※本サイトについて</strong><br>
              自分の学習用に運用している掲示板サイトです。詳しくは
              <a target="_blank" href="/about">こちら</a> をご覧ください。
            </p>

            <p class="small mb-0">
              <strong>※従来の会員登録を利用する場合</strong><br>
              <a target="_blank" href="/register">新規登録</a> ページまたは 
              <a target="_blank" href="/login">ログイン</a> ページからどうぞ。
            </p>

          </div>

        </div>
      </div>
    </div>
  </teleport> 
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
        userName: 'かあスレくん',
        isLoading: false,
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
      createUser(){
        this.isLoading = true;
        // 空文字 or null or undefined の判定
        if (!this.userName || this.userName.trim() === '') {
          this.userName = 'かあスレくん';
        }
        axios.post(`/api/game/rpg/title/create_rpg_user`, {
          name: this.userName,
        })
        .then(response => {
          console.log(`通信OK`);
          setTimeout(() => {
            location.reload();
          }, 800); // スピナー表示の演出用
        })
        .catch(error => {
          console.log(`通信失敗。`);
          this.isLoading = false; // エラー時は元に戻す
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

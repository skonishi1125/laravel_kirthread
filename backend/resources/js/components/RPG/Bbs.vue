<style scoped>
.bbs-post-message {
  font-size: 0.9em;
  margin-bottom: 0px;
}

.spoiler-hidden {
  color: gray;
}


</style>

<template>
  <div v-if="status.status == 'start'">
    <div class="sub-screen-wrapper">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>読み込み中...</small></p>
          </div>
          <hr>
        </div>
      </div>
  
      <div class="row mt-3 sub-sucreen-main-space sub-screen-back-img">
        <div class="col-12"></div>
      </div>
    </div>
  </div>

  <div v-if="status.status == 'loaded'">
    <div class="sub-screen-wrapper">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>気になる書き込みはあるかな？ (現在文字数:{{ newPostMessage.length }})</small></p>
          </div>
          <hr>
          <div>
            <p style="font-size: 0.8em; color:red">{{ noticeMessage }}</p>
          </div>
        </div>
      </div>
  
      <div class="row mt-3 sub-sucreen-main-space sub-screen-back-img">
        <div class="col-12">
          <div class="row">
            <!-- 左バー -->
            <div class="col-2 my-2">
              <div style="min-height: 480px;  display: flex; flex-flow: column; justify-content: space-evenly; border-right: 1px dotted black; text-align: center;">
                <div><button class="btn btn-sm btn-outline-success" @click="$router.push({ name: 'menu_plaza'})">広場に戻る</button></div>
              </div>
            </div>

            <!-- 右バー -->
            <div class="col-10 mt-5">
              <div class="row">
                <div class="col-12" style="max-height: 330px; overflow-y: scroll;">
                  <table class="table table-borderless table-hoverable">
                    <thead>
                      <tr style="border-bottom: 1px dotted;">
                        <th>
                          書き込み一覧
                        </th>
                        <th></th>
                        <!-- 連打されると負荷の原因になるので、用意しない -->
                        <!-- 
                        <th>
                          <button class="btn btn-outline-info btn-sm rounded-pill px-3" @click="fetchBbsPost">
                            更新
                          </button>
                        </th> 
                        -->

                      </tr>
                    </thead>
                    <tbody v-for="bbsPost in bbsPosts">
                      <tr>
                        <td>
                          <small style="color: gray">[{{ bbsPost.id }}] 書込日:{{ bbsPost.created_at }} </small>
                          <p class="bbs-post-message"
                            :class="{ 'spoiler-hidden': bbsPost.is_spoiled && !spoilerStates[bbsPost.id] }">
                            {{ spoilerStates[bbsPost.id] || !bbsPost.is_spoiled ? bbsPost.message : '***ネタバレを含みます***' }}
                          </p>
                        </td>
                        <td>
                          <button class="btn btn-secondary btn-sm" 
                            v-if="bbsPost.is_spoiled"
                            @click="showSpoiler(bbsPost.id)">表示
                          </button>
                        </td>
                        <td>
                          <button class="btn btn-danger btn-sm"
                            :disabled="isDeletingStates[bbsPost.id]"
                            v-if="bbsPost.savedata_id == loginSavedataId"
                            @click="deletePost(bbsPost.id)">
                            削除
                          </button>
                        </td>
                      </tr>
                    </tbody>
  
                  </table>
                </div> <!-- max-heightを指定した、col-12 div -->

                <!-- 書き込み部分 -->
                <div class="col-12 mt-5">
                  <form @submit.prevent="submitPost" class="d-flex align-items-center">
                    <div class="form-inline">
                      <input type="text" class="form-control form-control-sm mr-2" placeholder="投稿する内容を記入してください(最大:20文字)" style="width: 570px;" v-model="newPostMessage" maxlength="20">
                      <label for="spoiler" class="mr-1">ネタバレ有</label>
                      <input type="checkbox" id="spoiler" name="spoiler" v-model="newPostIsSpoiled">
                    </div>
                    <button type="submit" class="btn btn-info btn-sm ml-5">
                      書き込む
                    </button>
                  </form>
                </div>
              </div> <!-- row -->
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>



</template>

<script>
  import $ from 'jquery';
  import { mapState } from 'vuex';
  import axios from 'axios';
  export default {
    data() { // script内で使用する変数を定義する。
      return {
        loginSavedataId: null,
        bbsPosts: [],
        spoilerStates: {}, // 投稿ごとのネタバレ表示|非表示の管理配列
        isDeletingStates: {}, // 投稿削除中かどうかを管理する配列
        newPostMessage: '',
        newPostIsSpoiled: false,
        noticeMessage: null,
      }
    },
    // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
    // vuexに存在するmapStateメソッドでstore.jsで定義したstate.screen.currentを取得。thisで参照できるようになる。
    computed: { 
      ...mapState({
          status: state => state.menu.plaza.bbs
      }),
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
      this.$store.dispatch('setMenuPlazaBbsStatus', 'start');
      this.fetchBbsPost();
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log(`Bbs.vue`);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
      fetchBbsPost() {
        console.log(`fetchBbsPost(): -----------`);
        axios.get('/api/game/rpg/menu/plaza/bbs/fetch_post')
          .then(response => {
            this.loginSavedataId = response.data[0];
            this.bbsPosts = response.data[1];
            // console.log(this.loginSavedataId);
            // console.log(this.bbsPosts[0]);
            this.$store.dispatch('setMenuPlazaBbsStatus', 'loaded');
          });
      },

      showSpoiler(postId) {
        // this.spoilerStates[postId] = true; とほぼ同じ
        this.spoilerStates[postId] = true;
      },

      submitPost() {
        console.log(`submitPost(): -----------`);
        // バリデーションチェック
        if (!this.newPostMessage || this.newPostMessage.length > 20) {
          this.noticeMessage = '※投稿する文章は20文字以内で入力してください。';
          return;
        }

        axios.post('/api/game/rpg/menu/plaza/bbs/store', {
          savedata_id: this.loginSavedataId,
          message: this.newPostMessage,
          is_spoiled: this.newPostIsSpoiled
        })
        .then(response => {
          console.log(`Success.`);
          this.noticeMessage = response.data.successMessage;
          this.newPostMessage = '';
          this.newPostSpoiler = false;
          this.errors = '';
          this.fetchBbsPost(); // 再取得
        }).catch(error => {
          this.noticeMessage = error.response.data.errorMessage;
        });
      },

      // 削除処理
      deletePost(postId) {
        // すでに削除中ならreturn
        if (this.isDeletingStates[postId]) {
          return;
        } 

        // 削除中であることを示すステータス配列に値を格納
        this.isDeletingStates[postId] = true;

        axios.post('/api/game/rpg/menu/plaza/bbs/delete', {
          id: postId,
        })
        .then(response => {
          console.log(`Success.`);
          this.noticeMessage = response.data.successMessage;
          this.fetchBbsPost(); // 再取得
        }).catch(error => {
          this.noticeMessage = '削除に失敗しました。すでに消えている可能性があります。';
          this.fetchBbsPost();
        })
        .finally(() => {
          this.isDeletingStates[postId] = false;
        });
      }

    }
  }
</script>

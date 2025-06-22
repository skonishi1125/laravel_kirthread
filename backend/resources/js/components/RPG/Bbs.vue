<style scoped>
.bbs-post-message {
  font-size: 0.9em;
  margin-bottom: 0px;
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
            <p><small>気になる書き込みはあるかな？</small></p>
          </div>
          <hr>
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
                        <th>書き込み一覧</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <small style="color: gray">id:[1] 2025-06-30 23:50:10 <<かあ>></small>
                          <p class="bbs-post-message">イッカクの雷やばいよ！</p>
                        </td>
                        <td><button class="btn btn-secondary btn-sm">表示</button></td>
                        <td><button class="btn btn-danger btn-sm">削除</button></td>
                      </tr>
                      <tr>
                        <td>
                          <small style="color: gray">id:[1] 2025-06-30 23:50:10 <<かあ>></small>
                          <p class="bbs-post-message">草原のボスの咆哮ってなんですか？</p>
                          <td><button class="btn btn-secondary btn-sm">表示</button></td>
                          <td><button class="btn btn-danger btn-sm">削除</button></td>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <small style="color: gray">id:[1] 2025-06-30 23:50:10 <<かあ>></small>
                          <p class="bbs-post-message">草原のボスの咆哮ってなんですか？</p>
                          <td><button class="btn btn-secondary btn-sm">表示</button></td>
                          <td><button class="btn btn-danger btn-sm">削除</button></td>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <small style="color: gray">id:[1] 2025-06-30 23:50:10 <<かあ>></small>
                          <p class="bbs-post-message">草原のボスの咆哮ってなんですか？</p>
                          <td><button class="btn btn-secondary btn-sm">表示</button></td>
                          <td><button class="btn btn-danger btn-sm">削除</button></td>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <small style="color: gray">id:[1] 2025-06-30 23:50:10 <<かあ>></small>
                          <p class="bbs-post-message">草原のボスの咆哮ってなんですか？</p>
                          <td><button class="btn btn-secondary btn-sm">表示</button></td>
                          <td><button class="btn btn-danger btn-sm">削除</button></td>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <small style="color: gray">id:[1] 2025-06-30 23:50:10 <<かあ>></small>
                          <p class="bbs-post-message">草原のボスの咆哮ってなんですか？</p>
                          <td><button class="btn btn-secondary btn-sm">表示</button></td>
                          <td><button class="btn btn-danger btn-sm">削除</button></td>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <small style="color: gray">id:[1] 2025-06-30 23:50:10 <<かあ>></small>
                          <p class="bbs-post-message">草原のボスの咆哮ってなんですか？</p>
                          <td><button class="btn btn-secondary btn-sm">表示</button></td>
                          <td><button class="btn btn-danger btn-sm">削除</button></td>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <small style="color: gray">id:[1] 2025-06-30 23:50:10 <<かあ>></small>
                          <p class="bbs-post-message">草原のボスの咆哮ってなんですか？</p>
                          <td><button class="btn btn-secondary btn-sm">表示</button></td>
                          <td><button class="btn btn-danger btn-sm">削除</button></td>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <small style="color: gray">id:[1] 2025-06-30 23:50:10 <<かあ>></small>
                          <p class="bbs-post-message">草原のボスの咆哮ってなんですか？</p>
                          <td><button class="btn btn-secondary btn-sm">表示</button></td>
                          <td><button class="btn btn-danger btn-sm">削除</button></td>
                        </td>
                      </tr>
                    </tbody>
  
                  </table>
                </div> <!-- max-heightを指定した、col-12 div -->

                <!-- 書き込み部分 -->
                <div class="col-12 mt-5">
                  <form class="d-flex align-items-center">
                    <div class="form-inline">
                      <input type="text"class="form-control form-control-sm mr-2" placeholder="投稿する内容を記入してください(最大:20文字)" style="width: 570px;">
                      <label for="spoiler" class="mr-1">ネタバレ有</label>
                      <input type="checkbox" id="spoiler" name="spoiler">
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
            console.log(`response`);
            this.$store.dispatch('setMenuPlazaBbsStatus', 'loaded');
          });
      }
    }
  }
</script>

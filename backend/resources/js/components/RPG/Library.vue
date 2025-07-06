<style scoped>
.weight-bold {
  font-weight: bold;
  font-size: 0.9rem;
  margin-bottom: 0.7rem;
}

.table-hoverable tbody tr:hover {
  cursor: pointer;
  background-color: #fdf6e3;
  transition: background-color 0.2s ease;
}

.cleared-row {
  opacity: 0.6;
  font-style: italic;
}

.book-modal-content {
  background: #fdf6e3; /* クリームがかった背景 */
  /* font-family: "Georgia", "游明朝", serif; */
  max-height: 700px;
  border-radius: 6px;
  padding: 24px;
  line-height: 1.8;
  box-shadow: 0 0 20px rgba(0,0,0,0.15);
}

.book-modal-title {
  font-weight: bold;
  font-size: 1.2rem;
  border-bottom: 1px solid #ccc;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
}

.book-modal-body {
  max-height: 60vh;
  overflow-y: auto;
  padding: 1rem;
  white-space: pre-line;
}

.book-modal-footer {
  text-align: right;
  border-top: 1px solid #ccc;
  padding-top: 1rem;
}


</style>

<template>
  <div v-if="status.status == 'start'">
    <div class="sub-screen-wrapper">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>lib 読み込み中...</small></p>
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
            <p><small>何を読もうかな？</small></p>
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
                <div><button class="btn btn-sm btn-outline-info active">戦術学論</button></div> <!-- デフォルト -->
                <div><button class="btn btn-sm btn-outline-info">魔物図譜</button></div>
                <div><button class="btn btn-sm btn-outline-info">歴史神話学</button></div>
                <div><button class="btn btn-sm btn-outline-success my-5" @click="$router.push({ name: 'menu_plaza'})">広場に戻る</button></div>
              </div>
            </div>

            <!-- 右 書籍一覧 -->
            <div class="col-10 my-5" style="max-height: 480px; overflow-y: scroll;">
              <div class="col-12">
                <table class="table table-borderless table-hoverable">
                  <thead>
                    <tr style="border-bottom: 1px dotted;">
                      <th>書籍一覧</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="weight-bold" @click="openBookModal"><td>戦術論I: 治療師の観点から捉える補助職業の優位性</td></tr>
                    <tr class="weight-bold" @click="openBookModal"><td>魔導工学I: 魔導師使用学術の推論</td></tr>
                    <tr class="weight-bold" @click="openBookModal"><td>0からわかる！戦闘のススメ</td></tr>
                    <tr class="weight-bold" @click="openBookModal"><td>魔導工学I: 魔導師使用学術の推論</td></tr>
                    <tr class="weight-bold" @click="openBookModal"><td>Ranger's skill: Learn By Reading</td></tr>
                  </tbody>

                </table>
              </div>
            </div>

          </div>



        </div>
      </div>
    </div>
  </div>

  <teleport to="body">
    <div class="modal fade" id="book-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-backdrop-adjust" role="document">
        <div class="modal-content book-modal-content">
          
          <div class="modal-header">
            <h6 class="modal-title book-modal-title"><b>戦術論I: 治療師の観点から捉える補助職業の優位性</b></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body book-modal-body">
            <p>コンテンツ</p>
            <p>
              あああああああああああああああああああああああああああああああああああああああああああああああああああ<br>
              あああああああああああああああああああああああああああああああああああああああああああああああああああ<br>
              あああああああああああああああああああああああああああああああああああああああああああああああああああ<br>
              あああああああああああああああああああああああああああああああああああああああああああああああああああ<br>
              あああああああああああああああああああああああああああああああああああああああああああああああああああ<br>
              あああああああああああああああああああああああああああああああああああああああああああああああああああ<br>
              <br>
              <br>
              <br>
              テストです。テストです。テストです。テストです。テストです。テストです。テストです。テストです。
              テストです。テストです。テストです。テストです。テストです。テストです。テストです。テストです。
              テストです。テストです。テストです。テストです。テストです。テストです。テストです。テストです。
            </p>
            <p>【終】</p>
          </div>

          <div class="modal-footer book-modal-footer">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">読み終える</button>
          </div>
        </div>
      </div>
    </div>
  </teleport>

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
          status: state => state.menu.plaza.library
      }),
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
      this.$store.dispatch('setMenuPlazaLibraryStatus', 'start');
      this.fetchBook();
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log(`Library.vue  ${this.status.status}`);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
      fetchBook() {
        console.log(`fetchBook(): --------`);
        axios.get('/api/game/rpg/menu/plaza/library/fetch_book')
          .then(response => {
            console.log(`response`);
            this.$store.dispatch('setMenuPlazaLibraryStatus', 'loaded');
          });
      },
      openBookModal() {
        $('#book-modal').modal('show');
      }
    }
  }
</script>

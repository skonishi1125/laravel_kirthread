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
  /* background: #fdf6e3; */
  /* font-family: "Georgia", "游明朝", serif; */
  max-height: 700px;
  border-radius: 6px;
  padding: 24px;
  line-height: 1.8;
  box-shadow: 0 0 20px rgba(0,0,0,0.15);
}

.book-modal-color-adventure {
  background: #e3ebfd;
}

.book-modal-color-enemy {
  background: #dedede;
}

.book-modal-color-history {
  background: #fdf6e3;
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

  <div v-else>
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
                <div>
                  <button class="btn btn-sm btn-outline-info"
                  :class="{'active': status.status === 'adventure'}"
                  @click="changeCurrentBookCategory('adventure')">
                  戦術学論</button>
                </div> <!-- デフォルト -->
                <div>
                  <button class="btn btn-sm btn-outline-info"
                  :class="{'active': status.status === 'job'}"
                  @click="changeCurrentBookCategory('job')">
                  職能編纂</button> <!-- へんさん -->
                </div>
                <div>
                  <button class="btn btn-sm btn-outline-info"
                  :class="{'active': status.status === 'enemy'}"
                  @click="changeCurrentBookCategory('enemy')">
                  魔物図譜</button>
                </div>
                <div>
                  <button class="btn btn-sm btn-outline-info"
                  :class="{'active': status.status === 'history'}"
                  @click="changeCurrentBookCategory('history')">
                  歴史神話学</button>
                </div>

                <div>
                  <button class="btn btn-sm btn-outline-success my-5" @click="$router.push({ name: 'menu_plaza'})">広場に戻る</button>
                </div>

              </div>
            </div>

            <!-- 右 書籍一覧 -->
            <div class="col-10 my-5" style="max-height: 480px; overflow-y: scroll;">
              <div class="col-12">
                <table class="table table-borderless table-hoverable">
                  <thead>
                    <tr style="border-bottom: 1px dotted;">
                      <th v-if="status.status === 'adventure'">戦術学論 書籍一覧</th>
                      <th v-if="status.status === 'job'">職能編纂 書籍一覧</th>
                      <th v-if="status.status === 'enemy'">魔物図譜 書籍一覧</th>
                      <th v-if="status.status === 'history'">歴史神話学 書籍一覧</th>
                    </tr>
                  </thead>
                  <tbody v-for="currentBook in currentBooks">
                    <tr class="weight-bold" @click="openBookModal(currentBook)"><td>{{ currentBook.name }}</td></tr>
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
        <div class="modal-content book-modal-content"
          :class="{'book-modal-color-adventure': status.status === 'adventure'},
            {'book-modal-color-enemy': status.status === 'enemy'},
            {'book-modal-color-history': status.status === 'history'}"
          >
          <div class="modal-header">
            <h6 class="modal-title book-modal-title"><b>{{ modalBook.name }}</b></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body book-modal-body" ref="bookBody">
            <div v-html="modalBook.content"></div>
            <p style="text-align: right; font-weight: bold;">【終】</p>
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
        adventureBooks: {},
        jobBooks: {},
        enemyBooks: {},
        historyBooks: {},
        currentBooks: {}, // 現在の状態で表示する本の配列をそのまま入れる
        modalBook: {}
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
            console.log(`fetchBook: OK`);
            this.adventureBooks = response.data[0] || [];
            this.jobBooks = response.data[1] || [];
            this.enemyBooks = response.data[2] || [];
            this.historyBooks = response.data[3] || [];

            // 画面にそれぞれ表示させられるよう、項目を入れる
            this.currentBooks = this.adventureBooks;

            this.$store.dispatch('setMenuPlazaLibraryStatus', 'adventure');
            console.log(this.adventureBooks);
          });
      },

      // モーダル用の変数に現在の書籍情報を格納する
      openBookModal(book) {
        this.modalBook = book;

        // 表示する時、別の本でスクロールが下までいっていたら上に戻す。
        const $modal = $('#book-modal');
        $modal.one('shown.bs.modal', () => {
          const el = this.$refs.bookBody; // ref="bookBody"をつけた、modalのbodyを対象にする
          if (el) el.scrollTo({ top: 0, behavior: 'smooth' });
        });
        $modal.modal('show');
      },

      changeCurrentBookCategory(category) {
        switch (category) {
          case 'adventure':
            this.currentBooks = this.adventureBooks;
            this.$store.dispatch('setMenuPlazaLibraryStatus', 'adventure');
            break;
          case 'job':
            this.currentBooks = this.jobBooks;
            this.$store.dispatch('setMenuPlazaLibraryStatus', 'job');
            break;
          case 'enemy':
            this.currentBooks = this.enemyBooks;
            this.$store.dispatch('setMenuPlazaLibraryStatus', 'enemy');
            break;
          case 'history':
            this.currentBooks = this.historyBooks;
            this.$store.dispatch('setMenuPlazaLibraryStatus', 'history');
            break;
        }
      }
    }
  }
</script>

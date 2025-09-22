<style>
.sub-screen-wrapper {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

.sub-sucreen-text-space {
    padding: 10px 0px;
}

.menu-bar {
  display: flex;
  flex-flow: column;
  min-height: 500px;
  text-align: center;
  border-right: 1px dotted;
}

.btn-menu {
  width: 100%;
}

.btn-end {
  margin-top: auto;
}

.btn-ending-style {
  color: white;
  background-color: #e879e5;
  border-color: #be52bb;
}

.btn-ending-style:hover {
  color: white;
  background-color: #8e448b;
  border-color: #943991;
}

.btn-ending-style:active {
  background-color: #8e448b !important;
  border-color: #943991 !important;
}

/* モーダル関連。スマホ版で開いた時の挙動を調整する */
.modal-backdrop {
  /* スマホ版でモーダルを開いた時、黒背景が途中までしか生成されない挙動を防ぐ */
  width: 100% !important;
  height: 100% !important;
}

.modal-dialog {
  max-width: 720px;
  width: 100%;
  margin: 1.75rem auto;
}


</style>

<!-- 冒険、ショップ、スキル振りなどの一覧ページ。すべてこのページのレイアウトがベースになる -->
<template>

  <div v-if="menu_view == 'start'">
    <div class="row my-5 h-100">
      <div class="col-2 menu-bar">
      </div>
  
      <div class="col-10" v-if="isMenuRoute">
  
        <div class="sub-screen-wrapper">
          <div class="row sub-sucreen-text-space">
            <div class="col-12">
              <div>
                <p><small>読み込み中...</small></p>
              </div>
            </div>
          </div>
  
          <div class="row mt-3 sub-sucreen-main-space">
            <div class="col-12"></div>
          </div>
        </div>

      </div>
  
      <div v-else class="col-10">
        <router-view></router-view>
      </div>
    </div>
  </div>

  <div v-else-if="menu_view == 'loaded'">
    <div class="row my-5 h-100">
      <div class="col-2 menu-bar">
        <div><button class="btn btn-info btn-menu my-4" @click="$router.push({ name: 'menu_adventure'})">冒険に出る</button></div>
        <div><button class="btn btn-info btn-menu my-4" @click="$router.push({ name: 'menu_plaza'})">中心広場</button></div>
        <div><button class="btn btn-info btn-menu my-4" @click="$router.push({ name: 'menu_shop'})">ショップ</button></div>
        <div><button class="btn btn-info btn-menu my-4" @click="$router.push({ name: 'menu_status'})">ステータス</button></div>
        <div><button class="btn btn-secondary btn-menu my-4" @click="$router.push({ name: 'menu_other'})">マニュアル</button></div>

        <div v-if="this.is_cleared == true">
          <div>
            <button class="btn btn-menu my-4 btn-ending-style" @click="openConfirmEndingModal">財宝の確認</button>
          </div>
        </div>


        <div class="btn-end">
          <button class="btn btn-outline-success btn-menu" @click="endGame">タイトルに戻る</button>
        </div>
      </div>
  
      <div class="col-10" v-if="isMenuRoute">
  
        <div class="sub-screen-wrapper">
          <div class="row sub-sucreen-text-space">
            <div class="col-12">
              <div>
                <p><small>
                  街に辿り着いた。さて、何をすべきだろう？<br>
                </small></p>
                <hr>
                <small style="color: gray">
                  (左メニューから行動を選択しましょう。)
                </small>
              </div>
            </div>
          </div>
  
          <div class="row mt-3 sub-sucreen-main-space">
            <div class="col-12">
              <!-- contents -->
            </div>
          </div>
        </div>
  
      </div>
  
      <div v-else class="col-10">
        <!-- メニューはそのまま、children固有の要素を出す -->
        <router-view></router-view>
      </div>
    </div>
  </div>

  <!-- 確認モーダル -->
  <teleport to="body">
    <div class="modal fade" id="modal-confirm-ending" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title"><b>確認画面</b></h6>
            <button type="button" class="close" data-dismiss="modal" aria-rabel="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" role="form">
                <div class="form-group">
                    <p>
                      あなたたちは古城を制圧し、伝承にまつわる金銀財宝を手にしました。<br>
                      このことを街中に知らせますか？
                    </p>
                    <small>
                      <b style="color: blue">
                        ※エンディングに移動します。<br>
                        （このデータで遊べなくなる等の要素は特に存在しないため、気軽に選択してください。）
                      </b>
                    </small>
                </div>
            </form>
          </div>
          <!-- Modal Actions -->
          <div class="modal-footer">
            <button type="button" class="btn btn-ending-style" @click="transitionEnding">すすめる</button>
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
        is_cleared: false,
      }
    },
    computed: {
      ...mapState({
          // store.jsに定義したステータスがthis.menu_viewで使えるようになる。
          menu_view: state => state.menu.view,
      }),
      // methodsに書かずcomputedに書く。
      // リアクティブに監視されるため、URLが変更された時に自動的に再計算される
      isMenuRoute() {
        return this.$route.path == '/game/rpg/menu';
      },
    },
    created() {
      this.$store.dispatch('setScreen', 'menu');
      this.$store.dispatch('setBattleStatus', 'start');
      this.loadCanBeClear();

    },
    mounted() {
      console.log('Menu.vue', this.menu_view, this.isMenuRoute) ;
    },
    methods: {
      /**
       * ログイン中のユーザーのセーブデータがクリア条件を満たしているのかを確認する
       */
      loadCanBeClear() {
        console.log('loadCanBeClear');
        axios.get('/api/game/rpg/menu/can_be_clear')
          .then(response => {
            console.log(`response.data: ${response.data['is_cleared']}`);
            this.is_cleared = response.data['is_cleared'];
            this.$store.dispatch('setMenuView', 'loaded');
        });
      },

      openConfirmEndingModal() {
        $('#modal-confirm-ending').modal('show');
      },

      /**
       * エンディングに遷移する
       */
      transitionEnding() {
        // 画面遷移前に、モーダルを閉じておく
        $('#modal-confirm-ending').modal('hide');
        this.$router.push(`/game/rpg/ending`);
      },

      endGame() {
        this.$store.dispatch('setScreen', 'title');
        this.$router.push('/game/rpg');
      }
    }
  }

</script>

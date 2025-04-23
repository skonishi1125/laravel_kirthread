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
  justify-content: space-evenly;
  min-height: 500px;
  text-align: center;
  border-right: 1px dotted;
}

.btn-menu {
  width: 100%;
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

  <div class="row my-5 h-100">
    <div class="col-2 menu-bar">
      <div><button class="btn btn-info btn-menu" @click="$router.push('/game/rpg/menu/adventure')">冒険に出る</button></div>
      <!-- <div><button class="btn btn-info btn-menu" @click="$router.push('/game/rpg/menu/skill')">中心広場</button></div> -->
      <div><button class="btn btn-info btn-menu" disabled>中心広場</button></div>
      <div><button class="btn btn-info btn-menu" @click="$router.push('/game/rpg/menu/shop')">ショップ</button></div>
      <div><button class="btn btn-info btn-menu" @click="$router.push('/game/rpg/menu/status')">ステータス</button></div>
      <div><button class="btn btn-secondary btn-menu" @click="$router.push('/game/rpg/menu/other')">マニュアル</button></div>
      <div><button class="btn btn-success btn-menu" @click="endGame">タイトルに戻る</button></div>
    </div>

    <div class="col-10" v-if="isMenuRoute">

      <div class="container">
        <div class="row sub-sucreen-text-space">
          <div class="col-12">
            <div>
            <p>
              街に辿り着いた。どうしようか？<br>
              <span style="color: gray">(左メニューから行動を選択してください。)</span style="color: gray">
            </p>
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

</template>

<script>
export default {
  computed: {
    // methodsに書かずcomputedに書く。
    // リアクティブに監視されるため、URLが変更された時に自動的に再計算される
    isMenuRoute() {
      return this.$route.path == '/game/rpg/menu';
    },
  },
  created() {
    this.$store.dispatch('setScreen', 'menu');
    this.$store.dispatch('setBattleStatus', 'start');
  },
  mounted() {
    console.log('Menu.vue');
    console.log(this.isMenuRoute);
  },
  methods: {
    endGame() {
      this.$store.dispatch('setScreen', 'title');
      this.$router.push('/game/rpg');
    }
  }
}

</script>

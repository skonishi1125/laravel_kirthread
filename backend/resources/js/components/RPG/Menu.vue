<style>
.menu-bar {
  display: flex;
  flex-flow: column;
  justify-content: space-between;
  height: 400px;
  border-right: 1px dotted;
}
</style>

<!-- 冒険、ショップ、スキル振りなどの一覧ページ -->
<template>

  <div class="row">
    <div class="col-3 menu-bar">
      <div><button @click="$router.push('/game/rpg/menu/adventure')">冒険へ行く</button></div>
      <div><button @click="$router.push('/game/rpg/menu/shop')">ショップ</button></div>
      <div><button @click="$router.push('/game/rpg/menu/skill')">スキル振り</button></div>
      <div><button @click="endGame">タイトルに戻る</button></div>
    </div>
    <div class="col-9">
      <!-- メニューはそのまま、children固有の要素を出す -->
      <router-view></router-view>
    </div>
  </div>

</template>

<script>
export default {
  created() {
    this.$store.dispatch('setScreen', 'menu');
  },
  mounted() {
    console.log('Menu.vue');
  },
  methods: {
    endGame() {
      this.$store.dispatch('setScreen', 'title');
      this.$router.push('/game/rpg');
    }
  }
}

</script>
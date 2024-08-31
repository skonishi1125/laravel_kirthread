<template>
  <div class="row">
    <div class="col-sm-12">
      <p>Battle.vueというコンポーネントのtemplateです</p>
      <button @click="endBattle">バトル終了</button>
    </div>
  </div>
</template>

<script>
export default {
  created() {
    this.$store.dispatch('startBattle');
  },
  methods: {
    endBattle() {
      this.$store.dispatch('endBattle');  // バトル終了時に状態を更新
      this.$router.push('/game/rpg/shop'); // 任意の画面に遷移
    }
  }
}

</script>
<template>
  <div class="row">
    <div class="col-sm-12">
      <p>Battle.vue</p>
      <button @click="endBattle">バトル終了</button>
    </div>
  </div>
</template>

<script>
export default {
  created() {
    this.$store.dispatch('setScreen', 'battle');
  },
  mounted() {
    console.log('Battle.vue');
  },
  methods: {
    endBattle() {
      this.$store.dispatch('setScreen', 'menu');
      this.$router.push('/game/rpg/menu'); // 任意の画面に遷移
    }
  }
}
</script>
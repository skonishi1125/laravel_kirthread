<template>
  <div class="row" style="margin-top: 100px; text-align: center;">
    <div class="col-sm-12">
      <span>Title.vue(タイトル)</span><br>
      <button class="btn btn-success" @click="switchMenuScreen">街に戻る</button>
    </div>
    <div class="col-sm-12 mt-5">
      <button class="btn btn-primary" @click="switchBeginningScreen">最初からはじめる</button>
    </div>
  </div>

</template>

<script>
  export default {
    created() {
      // デフォルト値がtitleなので不要とも言えるが。
      this.$store.dispatch('setScreen', 'title');
    },
    mounted() {
      console.log('title.vue');
    },
    methods: {
      switchMenuScreen() {
        this.$store.dispatch('setScreen', 'menu');
        this.$router.push('/game/rpg/menu');
      },
      switchBeginningScreen() {
        this.$store.dispatch('setScreen', 'beginning');
        this.$router.push('/game/rpg/beginning');
      }
    },
  }
</script>
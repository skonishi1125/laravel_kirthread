<template>
  <div class="row" style="margin-top: 100px; text-align: center;">
    <div class="col-sm-12">
      <span>Title.vue</span><br>
      <button @click="startGame">はじめる</button>
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
      startGame() {
        this.$store.dispatch('setScreen', 'menu');
        this.$router.push('/game/rpg/menu');
      }
    },
  }
</script>
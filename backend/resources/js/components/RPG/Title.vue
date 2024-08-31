<template>
  <div class="row" style="margin-top: 100px; text-align: center;">
    <div class="col-sm-12">
      <span>Title.vue</span><br>
      <button @click="startGame">はじめる</button>
    </div>
  </div>

</template>

<script>
  import { mapState } from 'vuex';
  export default {
    mounted() {
      console.log('title.vue', this.currentScreen);
      this.$store.dispatch('setScreen', 'title');
    },
    computed: {
    ...mapState(['currentScreen'])
    },
    methods: {
      startGame() {
        this.$store.dispatch('setScreen', 'menu');
        this.$router.push('/game/rpg/adventure');
      }
    },
  }
</script>
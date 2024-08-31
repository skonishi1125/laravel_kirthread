<template>
  <div class="container" style="background-color: rgb(228, 231, 231); border: 1px solid black; min-height: 600px;">
    <h4 style="text-align: center;">App.vue</h4>

    <div v-if="currentScreen == 'title'">
      <router-view></router-view>
    </div>

    <div v-else-if="currentScreen == 'menu'">
      <div class="row">
        <div class="col-sm-3"><button @click="$router.push('/game/rpg/adventure')">冒険へ行く</button></div>
        <div class="col-sm-3"><button @click="$router.push('/game/rpg/shop')">ショップ</button></div>
        <div class="col-sm-3"><button @click="$router.push('/game/rpg/skill')">スキル振り</button></div>
        <div class="col-sm-3"><button @click="endGame">タイトル</button></div>
      </div>
      <div class="mb-5"></div>
      <router-view></router-view>
    </div>

  </div>
</template>

<script>
  import $ from 'jquery';
  import axios from 'axios';
  import { mapState } from 'vuex';
  export default {
    computed: {
    ...mapState(['isInBattle']),
    ...mapState(['currentScreen'])
    },
    data() {
      return {
      }
    },
    mounted() { // DOMが呼ばれた際に実行するコード
      console.log('app.vue',this.isInBattle, this.currentScreen);
    },
    methods: {
      startGame() {
        this.$store.dispatch('setScreen', 'menu');
        this.$router.push('/game/rpg/adventure');
      },
      endGame() {
        this.$store.dispatch('setScreen', 'title');
        this.$router.push('/game/rpg');
      }
    }
  }
</script>
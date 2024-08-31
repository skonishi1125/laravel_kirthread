<template>
  <div class="container" style="background-color: rgb(228, 231, 231); border: 1px solid black; min-height: 600px;">
    <h4>App.vue</h4>

    <div v-if="!isInBattle">
      <div class="row">
        <div class="col-sm-4"><button @click="$router.push('/game/rpg/adventure')">冒険へ行く</button></div>
        <div class="col-sm-4"><button @click="$router.push('/game/rpg/shop')">ショップ</button></div>
        <div class="col-sm-4"><button @click="$router.push('/game/rpg/skill')">スキル振り</button></div>
      </div>
    </div>

    <div class="mb-5"></div>

    <router-view></router-view>
  </div>
</template>

<script>
  import $ from 'jquery';
  import axios from 'axios';
  import { mapState } from 'vuex';
  export default {
    computed: {
    // スプレッド構文
    ...mapState(['isInBattle'])// Vuex store.jsの状態を取得
    },
    data() {
      return {
      }
    },
    mounted() { // DOMが呼ばれた際に実行するコード
      console.log('app.vue');
      console.log(mapState);
    },
    methods: {
    }
  }
</script>
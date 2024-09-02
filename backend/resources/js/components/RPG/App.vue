<template>
  <div class="container" style="background-color: rgb(228, 231, 231); border: 1px solid black; min-height: 600px;">
    <h4 style="text-align: center;">App.vue 
      <small style="font-size: 12px;">
        {{ currentScreen }}.{{ battleStatus }}.{{ selectedCommands }}
      </small>
    </h4>

    <div v-if="currentScreen == 'title'">
      <router-view></router-view>
    </div>

    <div v-else-if="currentScreen == 'menu'">
      <router-view></router-view>
    </div>

    <div v-else-if="currentScreen == 'battle'">
      <router-view></router-view>
    </div>

  </div>
</template>

<script>
  import { mapState } from 'vuex';
  export default {
    data() { // script内で使用する変数を定義する。
      return {}
    },
    computed: { // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
    // vuexに存在するmapStateメソッドでstore.jsで定義したstate.currentScreenを取得。thisで参照できるようになる。
    ...mapState(['currentScreen']),
    ...mapState(['battleStatus']),
    ...mapState(['selectedCommands']),
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log('app.vue', this.currentScreen);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
    }
  }
</script>
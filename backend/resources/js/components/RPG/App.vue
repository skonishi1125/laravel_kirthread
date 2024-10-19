<template>
  <div class="container" style="background-color: rgb(228, 231, 231); border: 1px solid black; min-height: 600px;">
    <h4 style="text-align: center; height: 60px;">App.vue 
      <!-- 
      戦闘画面用デバッグ 
       <small style="font-size: 12px;">
        {{ currentScreen }}.{{ battleStatus }}.{{ selectedCommands }}<br>
        {{ battleSessionId }}
      </small> 
      -->
      <small style="font-size: 12px;">
        {{ currentScreen }}.{{ beginningStatus }}<br>
      </small>
    </h4>

    <router-view></router-view>

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
    ...mapState(['beginningStatus']),
    ...mapState(['selectedCommands']),
    ...mapState(['battleSessionId']),
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
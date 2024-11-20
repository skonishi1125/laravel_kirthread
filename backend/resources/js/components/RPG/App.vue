<style>
.main-screen {
  background-color: rgb(255, 255, 255); 
  border: 1px solid black; 
  /* 4:3 */
  min-width: 1280px;
  min-height: 720px; 
  position: relative;
}

.sub-sucreen-text-space {
  height: 120px;
  border: 1px solid rgba(0, 0, 0, .5);
}

.sub-sucreen-main-space {
  height: 500px;
  border: 1px solid rgba(0, 0, 0, .5);
  /* border: 1px solid rgb(60, 0, 255); */
}


</style>

<template>
  <!-- debug -->
  <div class="container" style="background-color: whitesmoke; border: 1px solid black; min-width: 1280px">
    <div class="row">
      <div class="col-12" style="text-align: center;">
        <h4>
          App.vue
          <!-- <small style="font-size: 12px;">{{ currentScreen }}.{{ beginningStatus }}.{{ selectedRoleInformations }}<br></small> -->
        </h4>
      </div>
    </div>
      <!-- 
      戦闘画面用デバッグ 
       <small style="font-size: 12px;">
        {{ currentScreen }}.{{ battleStatus }}.{{ selectedCommands }}<br>
        {{ battleSessionId }}
      </small> 
      -->

  </div>

  <!-- メイン画面 4:3 -->
  <div class="container main-screen">

    <router-view></router-view>

  </div>
  <!-- メニュー: ステータス画面デバッグ -->
  <small style="font-size: 12px;">{{ currentScreen }}.{{ menuStatusState }}<br></small>

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
    ...mapState(['menuStatusState']),
    ...mapState(['selectedCommands']),
    ...mapState(['selectedRoleInformations']),
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
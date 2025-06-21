<style scoped>

</style>

<template>
  <div class="sub-screen-wrapper">
    <div class="row sub-sucreen-text-space">
      <div class="col-12">
        <div>
          <p><small>Job 読み込み中...</small></p>
        </div>
        <hr>
      </div>
    </div>

    <div class="row mt-3 sub-sucreen-main-space sub-screen-back-img">
      <div class="col-12"></div>
    </div>
  </div>
</template>

<script>
  import $ from 'jquery';
  import { mapState } from 'vuex';
  import axios from 'axios';
  export default {
    data() { // script内で使用する変数を定義する。
      return {
        
      }
    },
    // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
    // vuexに存在するmapStateメソッドでstore.jsで定義したstate.screen.currentを取得。thisで参照できるようになる。
    computed: { 
      // ...mapState({
      //     status: state => state.menu.plaza
      // }),
    },
    created() { // DOMに依存しない処理を書く(state処理など。)

    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log(`Job.vue`);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。

    }
  }
</script>

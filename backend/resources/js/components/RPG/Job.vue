<style scoped>
.push-button-wrapper {
  text-align: center;
  margin-top: 30px;
}

.push-button {
  font-family: 'Arial Black', sans-serif;
  font-size: 24px;
  font-weight: bold;
  color: #fff;
  width: 220px;
  height: 100px;
    background: #7b99a8;
  border: none;
  border-radius: 20px;
  box-shadow: 0 8px #37474f, 0 4px 6px rgba(0,0,0,0.2);
  cursor: pointer;
  transition: all 0.1s ease-in-out;
  position: relative;
}

.push-button:active {
  transform: translateY(4px);
  box-shadow:
    0 4px #10344a,
    0 2px 4px rgba(0,0,0,0.2);
}

</style>

<template>
  <div class="sub-screen-wrapper">
    <div class="row sub-sucreen-text-space">
      <div class="col-12">
        <div>
          <p><small>ボタンを押した回数に応じて、ゴールドが獲得できます。</small></p>
        </div>
        <hr>
      </div>
    </div>

    <div class="row mt-3 sub-sucreen-main-space sub-screen-back-img">
      <div class="col-12">

        <div class="row">
          <!-- 左バー -->
          <div class="col-2 my-2">
            <div style="min-height: 480px;  display: flex; flex-flow: column; justify-content: space-evenly; border-right: 1px dotted black; text-align: center;">
              <div><button class="btn btn-sm btn-outline-success" @click="$router.push({ name: 'menu_plaza'})">広場に戻る</button></div>
            </div>
          </div>

          <!-- 右バー -->
          <div class="col-10 my-2">
            <div class="row">
              <div class="col-8 my-5">
                <div class="push-button-wrapper">
                  <button class="push-button">PUSH</button>
                </div>
              </div>
              <div class="col-4 my-5">
                <p>クリック数: 108 回</p>
                <button class="btn btn-info" style="width: 170px">精算する</button>
              </div>
            </div>
          </div>

        </div>

      </div>
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

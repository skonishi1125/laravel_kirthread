<template>
  <div class="row">
    <div class="col-sm-12">
      <p>購入品を選択してください</p>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <form class="form-horizontal" role="form">
        <ul>
          <li>
            <label>ヒールポーション(所持数:0)</label>
            <input type="number">
            <button>購入</button>
          </li>
          <li>
            <label>マナウォーター(所持数:0)</label>
            <input type="number">
            <button>購入</button>
          </li>
          <li>
            <label>キュアリーフ(所持数:0)</label>
            <input type="number">
            <button>購入</button>
          </li>
        </ul>
      </form>
    </div>
  </div>
</template>

<script>
  import $ from 'jquery';
  import axios from 'axios';
  export default {
    data() {
      return {
      }
    },
    mounted() { // DOMが呼ばれた際に実行するコード
      console.log('shop.vue');
    },
    methods: {
    }
  }
</script>
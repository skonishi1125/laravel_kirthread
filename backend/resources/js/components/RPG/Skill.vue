<template>
  <div class="row">
    <div class="col-sm-12">
      <p>スキル振り分け</p>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <ul>
        <li><button>ファイアボール</button></li>
        <li><button>アイスエッジ</button></li>
        <li><button>サンダーランス</button></li>
      </ul>
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
      console.log('skill.vue');
    },
    methods: {
    }
  }
</script>
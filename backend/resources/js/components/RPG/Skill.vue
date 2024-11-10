<style>
.skill-items {
  margin: 20px;
}

</style>

<template>
  <div class="container">
    <div class="row sub-sucreen-text-space">
      <div class="col-12">
        <div>
          <p>メンバーのステータス及びスキルの確認・ポイントの振り分けができます。</p>
        </div>
        <hr>
        <div>
          <p>
            <b>ポップヒール</b> 50%/魔/回復 消費AP: 10 <br>
            回復魔力を周囲に浮かべ、全体のHPを回復する。【条件】Lv10以上, ミニヒール Lv1以上
          </p>
        </div>
      </div>
    </div>
  
    <div class="row mt-3 sub-sucreen-main-space">
      <div class="col-12">
        <ul class="nav nav-tabs">
          <a class="nav-link active">メイ</a>
          <a class="nav-link ">パラ</a>
          <a class="nav-link ">カア</a>
        </ul>

        <div class="row">
          <div class="col-2 my-5">
            <div style="min-height: 300px;  display: flex; flex-flow: column;  justify-content: space-evenly; border-right: 1px dotted black; text-align: center;">
              <div><button class="btn btn-sm btn-outline-info">ステータス</button></div>
              <div><button class="btn btn-sm btn-outline-info active">スキル確認</button></div>
            </div>
          </div>
          <div class="col-10 my-5" style="max-height: 300px; overflow-y: scroll;">
            <ul>
              <li class="skill-items">
                <button class="btn btn-sm btn-outline-info">ミニヒール</button>
                <ul>
                  <li><button class="btn btn-sm btn-outline-info">ポップヒール</button></li>
                </ul>
              </li>
              <li class="skill-items">
                <button class="btn btn-sm btn-outline-info">プチブラスト</button>
                <ul>
                  <li><button class="btn btn-sm btn-outline-info">クラッシュボルト</button></li>
                  <li><button class="btn btn-sm btn-outline-info">マナエクスプロージョン</button></li>
                </ul>
              </li>
              <li class="skill-items">
                <button class="btn btn-sm btn-outline-info">プチブラスト</button>
                <ul>
                  <li><button class="btn btn-sm btn-outline-info">クラッシュボルト</button></li>
                  <li><button class="btn btn-sm btn-outline-info">マナエクスプロージョン</button></li>
                  <ul>
                    <li><button class="btn btn-sm btn-outline-info">クラッシュボルト</button></li>
                    <li><button class="btn btn-sm btn-outline-info">マナエクスプロージョン</button></li>
                  </ul>
                </ul>
              </li>
              <li class="skill-items"><button class="btn btn-sm btn-outline-info">バトルメイジ</button></li>
              <li class="skill-items"><button class="btn btn-sm btn-outline-info disabled">???</button></li>
              <li class="skill-items"><button class="btn btn-sm btn-outline-info disabled">???</button></li>

            </ul>
          </div>
        </div>




      </div>
    </div>

  </div>
</template>

<script>
  export default {
    created() {
    },
    mounted() {
      console.log('skill.vue');
    },
    methods: {
    }
  }
</script>
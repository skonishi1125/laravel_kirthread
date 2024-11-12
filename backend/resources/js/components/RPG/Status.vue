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

    <!-- axiosで受け取れてから表示させる -->
    <div v-if="menuStatusState == 'skill'">
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

              <ul v-for="parentSkill in this.skillTree">
                <li class="skill-items"><button class="btn btn-sm btn-outline-info">{{ parentSkill.name }}</button></li>
                <div v-if="parentSkill.childSkills">
                  <ul v-for="childSkill in parentSkill.childSkills">
                    <li><button class="btn btn-sm btn-outline-info">{{ childSkill.name }}</button></li>
                  </ul>
                </div>
              </ul>

              <!-- <ul>
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
              </ul> -->

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
        skillTree: [],
        hoveredDescription: null, // 現在マウスオーバーしている要素の説明
        errorMessage: null,
      }
    },
    computed: {
      ...mapState(['menuStatusState']),
    },
    created() {
      this.getSkill();
      console.log(this.menuStatusState);
    },
    mounted() {
      console.log('Status.vue');
    },
    methods: {
      getSkill() { 
        console.log(`getSkill(): -----------------`);
        axios.get('/api/game/rpg/status/skill_tree')
          .then(response => {
            console.log(`response.data: ${response.data}`);
            this.skillTree = response.data;
            console.log(this.skillTree);
            console.log('skillTree取得完了したのでステータスをskillに変更。');

            // todo: スキルを受け取って、色々格納する変数を分けられたらいいかも。
            // api側からスキル情報を一括で受け取って、それが誰のスキルなのかを変数で分けたり。

            this.$store.dispatch('setMenuStatusState', 'skill');
          }
        );
      },
      showDescription(description) {
        this.hoveredDescription = description;
      },
      clearAllDescription() {
        this.hoveredDescription = null;
      },
    }
  }
</script>
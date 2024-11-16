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
            <!-- クリックした時そのキャラの情報を取得するようにして、activeクラスを張り替えるような実装にするとよい -->
            <a class="nav-link active">{{ this.partiesInformation[0].nickname }}</a>
            <a class="nav-link">{{ this.partiesInformation[1].nickname }}</a>
            <a class="nav-link">{{ this.partiesInformation[2].nickname }}</a>
          </ul>
  
          <div class="row">
            <div class="col-2 my-5">
              <div style="min-height: 300px;  display: flex; flex-flow: column;  justify-content: space-evenly; border-right: 1px dotted black; text-align: center;">
                <div><button class="btn btn-sm btn-outline-info">ステータス</button></div>
                <div><button class="btn btn-sm btn-outline-info active">スキル確認</button></div>
              </div>
            </div>
            <div class="col-10 my-5" style="max-height: 300px; overflow-y: scroll;">

              <!-- 0:灰/特殊 1:青/攻撃 2:緑/回復 3:黄/バフ -->
              <ul v-for="parentSkill in this.skillTreeArray[this.currentDecidedMemberIndex]">
                <li class="skill-items">
                  <button class="btn btn-sm"
                    :class="{
                      'btn-outline-secondary': parentSkill.effect_type === 0,
                      'btn-outline-primary': parentSkill.effect_type === 1,
                      'btn-outline-success': parentSkill.effect_type === 2,
                      'btn-outline-warning': parentSkill.effect_type === 3,
                    }"
                  >
                    {{ parentSkill.skill_name }}
                  </button>
                </li>
                <div v-if="parentSkill.child_skills">
                  <ul v-for="childSkill in parentSkill.child_skills">
                    <li>
                      <button class="btn btn-sm"
                        :class="{
                          'btn-outline-secondary': parentSkill.effect_type === 0,
                          'btn-outline-primary': parentSkill.effect_type === 1,
                          'btn-outline-success': parentSkill.effect_type === 2,
                          'btn-outline-warning': parentSkill.effect_type === 3,
                        }"
                      >
                        {{ childSkill.skill_name }}
                      </button>
                    </li>
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
        partiesInformation: [],
        statusArray: [],
        skillTreeArray: [],
        hoveredDescription: null, // 現在マウスオーバーしている要素の説明
        errorMessage: null,
        displaySkillName: '',
        displaySkillDescription: '',
      }
    },
    computed: {
      ...mapState(['menuStatusState']),
      ...mapState(['currentDecidedMemberIndex']),
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
        axios.get('/api/game/rpg/parties/information')
          .then(response => {
            console.log(`response.data: ${response.data}`);
            this.partiesInformation = response.data;
            console.log(this.partiesInformation[0],this.partiesInformation[0]['status'], this.partiesInformation[1]);
            console.log('情報取得完了。各変数に情報振り分け。');
            this.partiesInformation.forEach(partyInformation => {
              this.statusArray.push(partyInformation['status']);
              this.skillTreeArray.push(partyInformation['skill_tree']);
            });

            // console.log(this.skillTreeArray[0], this.statusArray[0]);
            // 1人目のメンバーのスキル一覧の、1つ目のスキルの情報を参照したい場合
            console.log(this.skillTreeArray[0][0]['skill_name'], this.skillTreeArray[0][0].child_skills);

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
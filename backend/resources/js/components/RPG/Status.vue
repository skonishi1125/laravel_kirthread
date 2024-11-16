<style>
.character-nav-tab {
  cursor: pointer;
}
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
        <div v-if="Object.keys(this.skillInformation).length > 0">
          <div>
            <p>
              <b>{{ this.skillInformation.skill_name }}</b>
              <span v-if="this.skillInformation.skill_level == 0">【<small><b>未習得</b></small>】</span>
              <span v-else>【習得済<small>(SLv:<b>{{ this.skillInformation.skill_level }}</b>)</small>】</span>
              
              【{{ this.skillInformation.attack_type }}】
              【{{ this.skillInformation.effect_type }}】
              【{{ this.skillInformation.target_range }}】<br>
              {{ this.skillInformation.description }} <small style="color:red">{{ this.skillInformation.conditions }}</small>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- axiosで受け取れてから表示させる -->
    <div v-if="menuStatusState == 'skill'">
      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12">
          <ul class="nav nav-tabs">
            <!-- クリックした時そのキャラの情報を取得するようにして、activeクラスを張り替えるような実装にするとよい -->
            <a class="nav-link character-nav-tab"
              :class="{'active': this.currentSelectedPartyMemberIndex === 0}" 
              @click="$store.dispatch('setCurrentSelectedPartyMemberIndex', 0)"
            >
              {{ this.partiesInformation[0].nickname }}
            </a>
            <a class="nav-link character-nav-tab" 
              :class="{'active': this.currentSelectedPartyMemberIndex === 1}" 
              @click="$store.dispatch('setCurrentSelectedPartyMemberIndex', 1)"
            >
              {{ this.partiesInformation[1].nickname }}
            </a>
            <a class="nav-link character-nav-tab" 
              :class="{'active': this.currentSelectedPartyMemberIndex === 2}" 
              @click="$store.dispatch('setCurrentSelectedPartyMemberIndex', 2)"
            >
              {{ this.partiesInformation[2].nickname }}
            </a>
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
              <ul v-for="parentSkill in this.skillTreeArray[this.currentSelectedPartyMemberIndex]">
                <li class="skill-items">
                  <button class="btn btn-sm"
                    :class="{
                      'disabled': !parentSkill.is_learned,
                      'btn-outline-secondary': parentSkill.effect_type === 0,
                      'btn-outline-primary': parentSkill.effect_type === 1,
                      'btn-outline-success': parentSkill.effect_type === 2,
                      'btn-outline-warning': parentSkill.effect_type === 3,
                    }"
                    @mouseover="showSkillInformation(parentSkill)"
                  >
                    <span v-if="parentSkill.is_learned">{{ parentSkill.skill_name }} <small>(SLv:<b>{{ parentSkill.skill_level }}</b>)</small></span>
                    <span v-else="parentSkill.is_learned">???</span>
                  </button>
                </li>
                <div v-if="parentSkill.child_skills">
                  <ul v-for="childSkill in parentSkill.child_skills">
                    <li>
                      <button class="btn btn-sm"
                        :class="{
                          'disabled': !childSkill.is_learned,
                          'btn-outline-secondary': parentSkill.effect_type === 0,
                          'btn-outline-primary': parentSkill.effect_type === 1,
                          'btn-outline-success': parentSkill.effect_type === 2,
                          'btn-outline-warning': parentSkill.effect_type === 3,
                        }"
                        @mouseover="showSkillInformation(childSkill)"
                      >
                        <span v-if="childSkill.is_learned">{{ childSkill.skill_name }}<small>(SLv:<b>{{ childSkill.skill_level }}</b>)</small></span>
                        <span v-else="childSkill.is_learned">???</span>
                      </button>
                    </li>
                  </ul>
                </div>
              </ul>
            </div> <!-- class="col-10 my-5" -->
          </div>

          <!-- TODO: 現在選択中のパーティメンバーのデータを出す(index指定する) -->
          <div class="row">
            <div class="col-12">
              <small>
                未振り分けのステータスポイント:【{{ this.partiesInformation[this.currentSelectedPartyMemberIndex].freely_status_point }}】 | スキルポイント:【{{ this.partiesInformation[currentSelectedPartyMemberIndex].freely_skill_point }}】
                ※スキルツリーはスクロール可能。
              </small>
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
        skillInformation: {},
      }
    },
    computed: {
      ...mapState(['menuStatusState']),
      ...mapState(['currentSelectedPartyMemberIndex']),
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
            // console.log(this.partiesInformation[0],this.partiesInformation[0]['status'], this.partiesInformation[1]);

            console.log('スキルツリー変数格納開始。');
            this.partiesInformation.forEach(partyInformation => {
              this.statusArray.push(partyInformation['status']);
              this.skillTreeArray.push(partyInformation['skill_tree']);
            });

            // console.log(this.skillTreeArray[0], this.statusArray[0]);
            // 1人目のメンバーのスキル一覧の、1つ目のスキルの情報を参照したい場合
            // console.log(this.skillTreeArray[0][0]['skill_name'], this.skillTreeArray[0][0].child_skills);

            this.$store.dispatch('setMenuStatusState', 'skill');
          }
        );
      },
      showSkillInformation(skill) {
        // console.log(`showSkillInformation: ${skill.skill_name} -------`);

        this.skillInformation = {
          skill_name: '???',
          skill_level: skill.skill_level,
          description: '-',
          attack_type: '-',
          effect_type: '-',
          target_range: '-',
        };

        // 習得可能なスキルの場合、各要素を開示させる
        if (skill.is_learned) {
          this.skillInformation.skill_name = skill.skill_name;
          this.skillInformation.description = skill.description;
          switch (skill.attack_type) {
            case 0:
              this.skillInformation.attack_type = "-"
              break;
            case 1:
              this.skillInformation.attack_type = "物理"
              break;
            case 2:
              this.skillInformation.attack_type = "魔法"
              break;
          };
          switch (skill.effect_type) {
            case 0:
              this.skillInformation.effect_type = "特殊"
              break;
            case 1:
              this.skillInformation.effect_type = "攻撃"
              break;
            case 2:
              this.skillInformation.effect_type = "回復"
              break;
            case 3:
              this.skillInformation.effect_type = "バフ"
              break;
            case 9:
              this.skillInformation.effect_type = "その他"
              break;
          };
  
          switch (skill.target_range) {
            case 0:
              this.skillInformation.target_range = "自身"
              break;
            case 1:
              this.skillInformation.target_range = "単体"
              break;
            case 2:
              this.skillInformation.target_range = "全体"
              break;
          };
        }

        // 習得条件メッセージの作成
        // 複数条件
        if (skill.requirement_skill_level && skill.requirement_party_level) {
          // console.log('a');
          this.skillInformation.conditions = `【条件】Lv${skill.requirement_party_level}以上,${skill.parent_skill_name} SLv${skill.requirement_skill_level}以上`;
        // SLv条件
        } else if (skill.requirement_skill_level && skill.requirement_party_level == null) {
          // console.log('b');
          this.skillInformation.conditions = `【条件】${skill.parent_skill_name} SLv${skill.requirement_skill_level}以上`;
        // パーティLv条件
        } else if (skill.requirement_skill_level == null && skill.requirement_party_level) {
          // console.log('c');
          this.skillInformation.conditions = `【条件】Lv${skill.requirement_party_level}以上`;
        // 条件なし
        } else if (skill.requirement_skill_level == null && skill.requirement_party_level == null) {
          // console.log('d');
          this.skillInformation.conditions = '';
        }
      },
      clearSkillInformation() {
        console.log(`clearSkillInformation: -------`);
        this.skillInformation = {};
      },
    }
  }
</script>
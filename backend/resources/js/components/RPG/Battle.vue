<style>
.player-status {
  display: inline-block;
}

.command-list {
  display: flex; 
  flex-flow: column; 
  justify-content: space-between; 
  background-color: white;
  border: 1px solid black; 
  position: absolute;
  left: 5%;
  top: 25%;
  width: 300px;
  height: 300px;
  text-align: center;
}

.character-picture {
  position: absolute;
  background-image: url("/storage/app/public/RPG_sample.png");
  background-size: cover;
  right: 10%;
  top: 12%;
  width: 40%;
  height: 80%;
}

</style>

<template>
  <div class="row" @click="nextEncountAction">
    <div class="col-12" style="background-color: green; border: 1px solid red; position: relative;">
      <div style="display: flex; flex-flow: column; justify-content: space-between; background-color: pink;">

        <!-- command 普段は非表示で、battleStatusがcommandの場合のみ出す。 -->
         <div v-if="battleStatus == 'command'">
           <div class="command-list">
            <div style="margin: auto;"><button @click="handleCommandSelection('ATTACK')">ATTACK</button></div>
            <div style="margin: auto;"><button @click="handleCommandSelection('SPECIAL')">SPECIAL</button></div>
            <div style="margin: auto;"><button @click="handleCommandSelection('DEFENCE')">DEFENCE</button></div>
            <div style="margin: auto;"><button @click="handleCommandSelection('ITEM')">ITEM</button></div>
            <div style="margin: auto;"><button @click="handleCommandSelection('ESCAPE')">ESCAPE</button></div>
           </div>
           <!-- 味方の立ち絵を出す -->
           <div class="character-picture"></div>
         </div>

        <!-- messageフィールド -->
        <div style="background-color: beige; margin-bottom: 50px;">
          {{ this.currentPartyMemberIndex }} 人目
          <p v-if="battleStatus == 'encount'">敵が現れた！</p> <!-- todo:この時に敵をクリックすると発火してしまうので直す。 -->
          <p v-if="battleStatus == 'command'">どうする？</p>
          <p v-if="battleStatus == 'enemySelect'">対象を選択してください</p>
          <p v-if="battleStatus == 'exec'">戦闘開始します。</p>
        </div>

        <!-- enemy -->
        <div style="display: flex; justify-content: space-evenly; background-color: beige; height: 300px; margin-bottom: 50px;">
          <div @click="selectEnemy(index)" style="width:100px; height:100px; border: 1px solid black; margin: auto;" v-for="(enemy, index) in enemyData" :key="index">
            {{ enemy.name }}
          </div>
        </div>

        <div style="display: flex; justify-content: space-evenly; background-color: beige; height: 100px; z-index: 5;" >

          <div style="margin: auto;" v-for="(partyMember, index) in partyData" :key="index">
            <p>{{ partyMember.nickname }}</p>
            <p>HP: {{ partyMember.value_hp }} | AP: {{ partyMember.value_ap }}</p>
          </div>

        </div>

      </div>
    </div>
  </div>

  <button @click="endBattle">バトル終了</button>

</template>

<script>
import { mapState } from 'vuex';
import $ from 'jquery';
import axios from 'axios';
export default {
  data() {
    return {
      // 入力したコマンド情報を格納しておく。下記は例
      firstCommands :{
        selectCommand: 'attack',
        selectSkillId: null,
        selectEnemyNumber: '1', //左から1,2,3
      },
      secondCommands:{
        selectCommand: 'special',
        selectSkillId: 1,
        selectEnemyNumber: '1', //左から1,2,3
      },
      thirdCommands :{

      },

      // encount時に格納される。
      partyData: {},
      enemyData: {}

    }
  },
  computed: {
    ...mapState(['battleStatus']),
    ...mapState(['selectedCommands']),
    ...mapState(['currentPartyMemberIndex'])
  },
  created() {
    this.$store.dispatch('setScreen', 'battle');
    this.$store.dispatch('setBattleStatus', 'encount');
    this.getEncountData();
  },
  mounted() {
    console.log('Battle.vue', this.battleStatus);
  },
  methods: {
    getEncountData() {
      axios.get('/api/game/rpg/battle/encount')
        .then(response => {
          let data = response.data;
          this.partyData = data[0] || [];
          this.enemyData = data[1] || [];
        }
      );
    },
    // 画面範囲全体をクリックし、 encount状態から次の状態へ遷移する
    nextEncountAction() {
      if (this.battleStatus == 'encount') {
        this.$store.dispatch('setBattleStatus', 'command');
      }
    },

    handleCommandSelection(command) {
      let currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
      this.$store.dispatch('setSelectedCommand', { partyMember: currentMember.nickname, command });
      this.$store.dispatch('setBattleStatus', 'enemySelect');
    },

    // 選択した敵の順番を格納する(3人いたら, 0, 1, 2の順。右端は2)
    selectEnemy(enemyIndex) {
      if (this.battleStatus !== "enemySelect") return; // 敵選択中以外に敵をクリックした場合は何もさせない。
      const currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
      this.$store.dispatch('setSelectedEnemy', { partyMember: currentMember.nickname, enemyIndex: enemyIndex });
      if (this.$store.state.currentPartyMemberIndex < this.partyData.length - 1) {
        this.$store.dispatch('incrementPartyMemberIndex');
        this.$store.dispatch('setBattleStatus', 'command');
      } else {
        this.$store.dispatch('setBattleStatus', 'exec');
        this.execBattleCommand();
      }
    },
    nextEnemySelectAction() {
      if (this.battleStatus == 'command') {
        this.$store.dispatch('setBattleStatus', 'enemySelect');
      }
    },
    execBattleCommand() {
      console.log('APIに送ります');
      axios.post('/api/game/rpg/battle/exec', this.$store.state.selectedCommands)
        .then(response => {
          console.log('送りました');
        }
      );
    },
    // execCommand() {
    //   console.log('戦闘開始！');
    //   axios.post('/api/game/rpg/savedata', selectCommand)
    //     .then(response => {
    //       this.money = response.data.money;
    //     }
    //   );
    //   this.backSelectCommandStatus();
    // },
    // 戦闘が続く場合、再びコマンド選択画面にする。
    backSelectCommandStatus() {
      this.$store.dispatch('setBattleStatus', 'command');
    },

    endBattle() {
      this.$store.dispatch('setScreen', 'menu');
      this.$router.push('/game/rpg/menu'); // 任意の画面に遷移
    },


  }
}
</script>
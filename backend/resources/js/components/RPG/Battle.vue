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
  <div class="row" @click="nextAction">
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
          <div v-if="battleStatus == 'outputLog'" v-for="(log, index) in this.battleLog" :key="index">
            <p>{{ log }}</p>
          </div>
        </div>

        <!-- enemy -->
        <div style="display: flex; justify-content: space-evenly; background-color: beige; height: 300px; margin-bottom: 50px;">
          <div @click="selectEnemy(index)" style="width:100px; height:100px; border: 1px solid black; margin: auto;" v-for="(enemy, index) in enemyData" :key="index">
            {{ enemy.name }} / {{ enemy.value_hp }}
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
      // (例として1人目がATTACKを選択)流れ: 
      // encount時にpartyDataとenemyDataにjsonの値を格納
      // command時にhandleCommandSelectionでcurrentMemberにpartyData[0]を格納
      //   stateのselectedCommandsに、currentMember.nicknameとATTACKを格納
      // enemySelect時にselectEnemyでcurrentMemberにpartyData[0]を格納
      //   setSelectedEnemyを使って先ほど格納したstateのselectedCommandsに敵のインデックスを追加
      // 3人分格納できた時点でexecに移る。
      partyData: {},
      enemyData: {},
      battleLog: {},

    }
  },
  computed: {
    ...mapState(['battleStatus']),
    ...mapState(['selectedCommands']),
    ...mapState(['currentPartyMemberIndex']),
    ...mapState(['battleSessionId']),
  },
  created() {
    this.$store.dispatch('setScreen', 'battle');
    this.$store.dispatch('setBattleStatus', 'encount');
    this.getEncountData();
  },
  mounted() {
  },
  methods: {
    getEncountData() {
      axios.get('/api/game/rpg/battle/encount')
        .then(response => {
          let data = response.data;
          this.partyData = data[0] || [];
          this.enemyData = data[1] || [];
          this.$store.dispatch('setBattleSessionId', data[2] || []);

          // 実行タイミングによって正しく格納された値が表示されない場合があるが、一応入っている
          console.log('Battle.vue', this.battleStatus, this.battleSessionId); 

        }
      );
    },
    // 画面範囲全体をクリックし、 encount状態から次の状態へ遷移する
    nextAction() {
      if (this.battleStatus == 'encount') {
        this.$store.dispatch('setBattleStatus', 'command');
      } else if (this.battleStatus == 'outputLog') {
        console.log('outputLogから押しました');
        this.$store.dispatch('setBattleStatus', 'command');
      }
    },

    handleCommandSelection(command) {
      let currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
      this.$store.dispatch('setSelectedCommand', { partyId: currentMember.id, command });
      this.$store.dispatch('setBattleStatus', 'enemySelect');
    },

    // 選択した敵の順番を格納する(3人いたら, 0, 1, 2の順。右端は2)
    selectEnemy(enemyIndex) {
      if (this.battleStatus !== "enemySelect") return; // 敵選択中以外に敵をクリックした場合は何もさせない。
      const currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
      this.$store.dispatch('setSelectedEnemy', { partyId: currentMember.id, enemyIndex: enemyIndex });
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
      axios.post('/api/game/rpg/battle/exec', {
        session_id: this.$store.state.battleSessionId,
        selectedCommands: this.$store.state.selectedCommands,
      })
        .then(response => {
          let data = response.data;
          this.partyData = data[0] || [];
          this.enemyData = data[1] || [];
          this.battleLog = data[2] || []; //戦闘結果を取得する
          this.$store.dispatch('setBattleStatus', 'outputLog');
          // stateのリセット
          this.$store.dispatch('resetBattleStatus');
        }
      );
    },
    endBattle() {
      this.$store.dispatch('setScreen', 'menu');
      this.$router.push('/game/rpg/menu'); // 任意の画面に遷移
    },
  }
}
</script>
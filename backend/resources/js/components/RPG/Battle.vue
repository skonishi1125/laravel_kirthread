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
  background-size: cover;
  right: 10%;
  top: 12%;
  width: 30%;
  height: 80%;
}

.log-container {
  height: 120px; /* 高さを設定して、スクロール可能にする */
  overflow-y: scroll; /* スクロールバーを常に表示 */
  background-color: white;
  /* border: 1px solid #ccc; 境界線を追加して見やすくする（任意） */
  padding: 10px; /* 内側の余白（任意） */
}
.log-container::-webkit-scrollbar {
  width: 8px; /* スクロールバーの幅 */
}
.log-container::-webkit-scrollbar-thumb {
  background-color: #888; /* スクロールバーのつまみ部分の色 */
  border-radius: 10px; /* スクロールバーのつまみ部分の角を丸める */
}
.log-container::-webkit-scrollbar-track {
  background: #f1f1f1; /* スクロールバーのトラック部分の色 */
}

.log-item {
  margin-bottom: -15px; /* 各ログアイテムの下に余白を追加（任意） */
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
           <div class="character-picture" :style="backgroundImageStyle"></div>
         </div>

        <!-- messageフィールド -->
        <div style="background-color: beige; margin-bottom: 50px;">
          <<{{ this.currentPartyMemberIndex }} 人目選択中>>
          <p v-if="battleStatus == 'encount'">敵が現れた！</p>
          <p v-if="battleStatus == 'command'">
            {{ this.partyData[this.currentPartyMemberIndex].name }}はどうする？
          </p>
          <p v-if="battleStatus == 'enemySelect'">対象を選択してください</p>
          <p v-if="battleStatus == 'exec'">戦闘開始します。</p>
          <div v-if="battleStatus == 'outputLog'" class="log-container">
            <div v-for="(log, index) in this.battleLog" :key="index" class="log-item">
              <p>ターン{{ (index + 1) }} : {{ log }}</p>
            </div>
          </div>
          <p v-if="battleStatus == 'resultWin'">敵を倒した！</p>
          <p v-if="battleStatus == 'resultLose'" @click="endBattle">全滅した...</p>
        </div>

        <!-- enemy -->
        <div style="display: flex; justify-content: space-evenly; background-color: beige; min-height: 300px; margin-bottom: 50px;">
          <div v-if="Array.isArray(enemyData) && enemyData.length > 0" style="margin: 20px 0 20px 0;" v-for="(enemy, index) in enemyData.filter(enemy => !enemy.is_defeated_flag)" :key="index">
            <div class="progress" style="width: 150px">
              <div class="progress-bar bg-danger" role="progressbar" :style="{ width: calculatePercentage(enemy.value_hp, enemy.max_value_hp) + '%' }" aria-valuenow="enemy.value_hp" aria-valuemin="0" :aria-valuemax="enemy.max_value_hp">
                <!-- HP: {{ enemy.value_hp }} / {{ enemy.max_value_hp }} -->
              </div>
            </div>
            <div @click="selectEnemy(enemy.enemy_index)" style="width:100px; height:100px; border: 1px solid black; margin: auto; margin-top: 15px">
              {{ enemy.name }} / {{ enemy.value_hp }}
            </div>
          </div>
        </div>

        <!-- partyのステータス -->
        <div style="display: flex; justify-content: space-evenly; background-color: beige; height: 100px; z-index: 5;" >
          <div style="margin: auto; text-align:center;" v-for="(partyMember, index) in partyData" :key="index">
            <p style="font-size: 14px;">{{ partyMember.name }}</p>
            <div class="progress" style="width: 150px; margin-bottom: 5px">
              <div class="progress-bar bg-success" role="progressbar" :style="{ width: calculatePercentage(partyMember.value_hp, partyMember.max_value_hp) + '%' }" aria-valuenow="partyMember.value_hp" aria-valuemin="0" :aria-valuemax="partyMember.max_value_hp">
                HP: {{ partyMember.value_hp }} / {{ partyMember.max_value_hp }}
              </div>
            </div>
            <div class="progress" style="width: 150px">
              <div class="progress-bar" role="progressbar" :style="{ width: calculatePercentage(partyMember.value_ap, partyMember.max_value_ap) + '%' }" aria-valuenow="partyMember.value_ap" aria-valuemin="0" :aria-valuemax="partyMember.max_value_ap">
                AP: {{ partyMember.value_ap }} / {{ partyMember.max_value_ap }}
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <button @click="escapeBattle">逃げる</button>

</template>

<script>
import { mapState } from 'vuex';
import $ from 'jquery';
import axios from 'axios';
export default {
  data() {
    return {
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
    // コマンド選択時のキャラクターの立ち絵
    backgroundImageStyle() {
      return {
        backgroundImage: `url(/storage/rpg/character/portrait/${this.partyData[this.currentPartyMemberIndex].role_portrait})`
      }
    }
  },
  created() {
    this.$store.dispatch('setScreen', 'battle');
    if (this.battleStatus !== 'escape' && this.battleStatus !== 'resultLose' && this.battleStatus !== 'resultWin') {
      this.getEncountData();
    }
  },
  mounted() {
  },
  methods: {
    calculatePercentage(currentValue, maxValue) {
      return (currentValue / maxValue) * 100;
    },
    getEncountData() {
      console.log('getEncountData(): ----------------------------------');
      // 途中終了してメニューに戻った場合、このメソッドが走らないようにする
      axios.get('/api/game/rpg/battle/encount')
        .then(response => {
          let data = response.data;
          this.partyData = data[0] || [];
          this.enemyData = data[1] || [];
          this.$store.dispatch('setBattleSessionId', data[2] || []);
          // 実行タイミングによって正しく格納された値が表示されない場合があるが、一応入っている
          console.log('Battle.vue', this.battleStatus, this.battleSessionId); 
          // getで呼び出せた後にencountにすることで、呼び出す前に画面をクリックした時のエラーを防ぐ
          this.$store.dispatch('setBattleStatus', 'encount');
        }
      );
    },
    // 画面範囲全体をクリックし、 encount状態から次の状態へ遷移する
    nextAction() {
      switch (this.battleStatus) {
        case 'encount':
          console.log('nextAction.encount(): ----------------------------------');
          // 味方が戦闘不能の場合は、コマンド選択対象から外してcurrentPartyMemberIndexをインクリメントする
          this.battleCommandSetup(); // リロードして[0,1]が戦闘不能だった場合は、インクリメントする
          break;
        case 'outputLog': 
          console.log('nextAction.outputLog(): ----------------------------------');
          // ログ出力の後にコマンド画面に遷移するときに、現在のメンバーが戦闘不能かどうかをチェックする
          // これがないと、最初のキャラ（メイジちゃん）のコマンド選択画面が出てしまうから。
          this.battleCommandSetup(); 
          break;
        default: 
          console.log(`nextAction()で指定のない状態です。${this.battleStatus}`);
      }
    },

    // 敵選択後に、コマンド処理に戻る時に動かす処理
    battleCommandSetup() {
      console.log('battleCommandSetup(): ----------------------------------');

      // 敵を全て討伐していた場合は、勝利画面に。
      if (this.enemyData.every(enemy => enemy.is_defeated_flag === true)){
        console.log('敵を全て討伐したので、勝利画面に移行します。');
        this.$store.dispatch('setBattleStatus', 'resultWin');
        return;
      }

      // パーティメンバーの分だけ回す。(現状は3人なので、0,1,2)
      if (this.currentPartyMemberIndex <= 2) {
        const currentMember = this.partyData[this.currentPartyMemberIndex];
        console.log(`currentMember: ${currentMember} ${this.currentPartyMemberIndex}`);
        // 次に選択するコマンドメンバーが戦闘不能の場合、インクリメントをあげてスキップする
        if (currentMember.is_defeated_flag == true) {
          console.log(`${currentMember.name}は戦闘不能のため、インクリメントしてスキップ。`);
          this.$store.dispatch('incrementPartyMemberIndex');
          this.battleCommandSetup();
        }
        // 最後のメンバーが戦闘不能の時、コマンド選択画面に遷移させるとbackgroundセットでエラーになるので、0,1の場合だけコマンド画面に遷移させる。
        if (this.currentPartyMemberIndex <= 2) {
          console.log(`コマンド選択画面へ遷移します。`);
          this.$store.dispatch('setBattleStatus', 'command');
        }
      } else {
        // 全員選択が終わっているので、行動実行。
        console.log(`全員の選択が終了しています。`);

        // 自分たちがやられている場合は、敗北画面に。
        if (this.partyData.every(member => member.is_defeated_flag === true)){
          console.log('→全滅していることで選択が終了したため、敗北画面に遷移します。');
          this.$store.dispatch('setBattleStatus', 'resultLose');
          return;
        }

        this.$store.dispatch('setBattleStatus', 'exec');
        this.execBattleCommand();
      }
    },

    handleCommandSelection(command) {
      console.log('handleCommandSelection(): ----------------------------------');
      // 現在コマンド選択中のデータをcurrentPartyMemberIndexに格納する
      let currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
      this.$store.dispatch('setSelectedCommand', { partyId: currentMember.id, command });
      this.$store.dispatch('setBattleStatus', 'enemySelect');
    },

    // 選択した敵の順番を格納する(3人いたら, 左端0, 1, 右端2の順。)
    selectEnemy(enemyIndex) {
      if (this.battleStatus !== "enemySelect") return; // 敵選択中以外に敵をクリックした場合は何もさせない。
      console.log('selectEnemy(): ----------------------------------');
      const currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
      this.$store.dispatch('setSelectedEnemy', { partyId: currentMember.id, enemyIndex: enemyIndex });
      // インクリメントして次のメンバーのセットアップに移行する
      this.$store.dispatch('incrementPartyMemberIndex');
      console.log('selectEnemy終わり');
      this.battleCommandSetup(); // 0,1選択時に走らせる
    },

    execBattleCommand() {
      console.log('execBattleCommand(): ----------------------------------');
      axios.post('/api/game/rpg/battle/exec', {
        session_id: this.$store.state.battleSessionId,
        selectedCommands: this.$store.state.selectedCommands,
      })
        .then(response => {
          console.log('通信成功');
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
      console.log('endBattle(): ----------------------------------');
      if (this.$store.state.battleSessionId !== '') {
        console.log('セッションIDが設定されているケース');
        axios.post('/api/game/rpg/battle/escape', {
          session_id: this.$store.state.battleSessionId,
        })
          .then(response => { 
            this.$store.dispatch('setBattleStatus', 'escape');
            this.$store.dispatch('setScreen', 'menu');
            this.$router.push('/game/rpg/menu'); // 任意の画面に遷移
         });
      } else {
        //セッションIDが設定されていないケースは、DBで消す必要はなくそのままメニュー画面に飛ばす。
        console.log('セッションIDが設定されていないケース');
        this.$store.dispatch('setBattleStatus', 'escape');
        this.$store.dispatch('setScreen', 'menu');
        this.$router.push('/game/rpg/menu'); // 任意の画面に遷移
      }
    },
    escapeBattle() {
      console.log('escapeBattle(): ----------------------------------');
      if (this.$store.state.battleSessionId !== '') {
        console.log('escape:セッションIDが設定されているケース');
        axios.post('/api/game/rpg/battle/escape', {
          session_id: this.$store.state.battleSessionId,
        })
          .then(response => { 
            this.$store.dispatch('resetAllBattleStatus');
            this.$store.dispatch('setScreen', 'menu');
            this.$router.push('/game/rpg/menu'); // 任意の画面に遷移
         });
      } else {
        //セッションIDが設定されていないケースは、DBで消す必要はなくそのままメニュー画面に飛ばす。
        console.log('escape:セッションIDが設定されていないケース');
        this.$store.dispatch('resetAllBattleStatus');
        this.$store.dispatch('setScreen', 'menu');
        this.$router.push('/game/rpg/menu'); // 任意の画面に遷移
      }
    },


  }
}
</script>
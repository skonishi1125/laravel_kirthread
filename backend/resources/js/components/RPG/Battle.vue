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
            {{ this.partyData[this.currentPartyMemberIndex].nickname }}はどうする？
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
        <div style="display: flex; justify-content: space-evenly; background-color: beige; height: 300px; margin-bottom: 50px;">
          <div style="margin: 20px 0 20px 0" v-for="(enemy, index) in enemyData" :key="index">
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
            <p style="font-size: 14px;">{{ partyMember.nickname }}</p>
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
    // todo:敵の読み込みが終わっていないのにクリックするとcommand状態で停止するので調整が必要。
    nextAction() {
      if (this.battleStatus == 'start') {
        // this.getEncountData();
      } else if (this.battleStatus == 'encount') {
        // 敗北後に画面をリロードした場合などは、該当画面に遷移させる
        if (this.partyData.length === 0) {
          console.log('やられてしまった...');
          this.$store.dispatch('setBattleStatus', 'resultLose');
          return;
        }
        // ログ出力後、敵を全て倒していたら勝利とする
        if (this.enemyData.length === 0) {
          console.log('敵を全員倒しました');
          this.$store.dispatch('setBattleStatus', 'resultWin');
          return;
        }

        // 味方が戦闘不能の場合は、コマンド選択対象から外してcurrentPartyMemberIndexをインクリメントする
        while (true) {
          let currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
          if (currentMember.is_defeated_flag == false) {
            this.$store.dispatch('setBattleStatus', 'command');
            break;
          } else {
            this.$store.dispatch('incrementPartyMemberIndex');
            console.log('nextAction:このパーティメンバーはすでに戦闘不能です：', currentMember.nickname);
          }
        }
        this.$store.dispatch('setBattleStatus', 'command');
      } else if (this.battleStatus == 'outputLog') {
        console.log('outputLogから押しました');
        // ログ出力後、パーティが全滅していたら敗北とする
        if (this.partyData.length === 0) {
          console.log('やられてしまった...');
          this.$store.dispatch('setBattleStatus', 'resultLose');
          return;
        }
        // ログ出力後、敵を全て倒していたら勝利とする
        if (this.enemyData.length === 0) {
          console.log('敵を全員倒しました');
          this.$store.dispatch('setBattleStatus', 'resultWin');
          return;
        }
        // パーティ/敵が残っている場合はコマンド選択に戻す
        this.$store.dispatch('setBattleStatus', 'command');
      } else {
        console.log('encount/outoutLog以外の時に画面全体をクリックしました');
      }
    },

    handleCommandSelection(command) {
      while (true) {
        // 現在コマンド選択中のデータをcurrentPartyMemberIndexに格納する
        let currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
        if (currentMember.is_defeated_flag == false) {
          this.$store.dispatch('setSelectedCommand', { partyId: currentMember.id, command });
          this.$store.dispatch('setBattleStatus', 'enemySelect');
          break;
        } else {
          this.$store.dispatch('incrementPartyMemberIndex');
          console.log('handleCommandSelection:このパーティメンバーはすでに戦闘不能です：', currentMember.nickname);
        }
      }
    },

    // 選択した敵の順番を格納する(3人いたら, 左端0, 1, 右端2の順。)
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

          // 味方が倒れた場合、行動できなくする(一旦取り除こうか)
          this.partyData = this.partyData.filter(party => {
            console.log(party.nickname, party.is_defeated_flag);
            return !party.is_defeated_flag;
          });
          // if (this.partyData.length === 0) {
          //   console.log('やられてしまった...');
          //   this.$store.dispatch('setBattleStatus', 'resultLose');
          // }

          // 敵を倒した場合、画面から取り除く
          this.enemyData = this.enemyData.filter(enemy => {
            console.log(enemy.name, enemy.is_defeated_flag);
            return !enemy.is_defeated_flag;
          });
          // if (this.enemyData.length === 0) {
          //   console.log('敵を全員倒しました');
          //   this.$store.dispatch('setBattleStatus', 'resultWin');
          // }
        }
      );
    },
    endBattle() {
      console.log('戦闘終わり');
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
      console.log('戦闘から逃げ出した。');
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
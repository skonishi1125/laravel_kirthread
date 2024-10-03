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

.command-list-row {
  padding: 6% 0%;
  cursor: pointer;
}
.command-list-row:hover {
  background-color: beige;
}

.command-list-row-skills {
  width: 300px;
  text-align: left;
  padding: 10px 30px;
  cursor: pointer;
}
.command-list-row-skills:hover {
  background-color: beige;
}
.command-list-row-skills_not_enough_ap {
  width: 300px;
  text-align: left;
  padding: 10px 30px;
  cursor: default;
  background-color: #888;
}

.party-status-wrapper {
  display: flex; 
  justify-content: space-evenly; 
  background-color: none; 
  height: 100px; 
  z-index: 5; 
  margin-bottom: 20px;
}

.character-picture {
  position: absolute;
  background-size: cover;
  right: 0%;
  top: -10%;
  width: 60%;
  min-height: 110%;
  z-index: 4;
}

.enemy-picture {
  background-size: cover;
  width: 180px;
  height: 180px;
  margin-top: 20px;
}

/* battleStatus === 'enemySelect'の場合のみ割り当てる */
.enemy-hover-active {
  cursor: pointer;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

.enemy-hover-active:hover {
  border: 2px solid #ffcc00;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

/* battleStatus === 'partySelect'の場合のみ割り当てる */
.party-hover-active {
  cursor: pointer;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

.party-hover-active:hover {
  border: thick double #ffcc00 !important;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.log-container {
  height: 86px; /* 高さを設定して、スクロール可能にする */
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

.battlelog_result_wrapper {
  background-color: black;
  color: rgb(58, 250, 58);
  padding: 10px 10px;
  height: 150px;
  overflow-y: scroll !important;
  font-size: 12px;
}

.battlelog_result_wrapper ul {
  padding-left: 0;
}

.battlelog_result_wrapper li {
  list-style: none;
  border-bottom: 1px dotted white;
}


</style>

<template>
  <div class="row" @click="nextAction">
    <!-- todo: ステージごとに背景を変える -->
    <div class="col-12" style="background-image: url('/image/rpg/field/grassland.png'); background-size: cover;  position: relative;">

      <div v-if="battleStatus == 'error'">
        <div style="cursor: pointer; background-color: white;">
          <p @click="escapeBattle" >エラーが発生しました。このメッセージをクリックして一度戻ってください</p>
        </div>
      </div>

      <div style="display: flex; flex-flow: column; justify-content: space-between;">

        <!-- command 普段は非表示で、battleStatusがcommandの場合のみ出す。 -->
         <div v-if="battleStatus == 'command'">
           <div class="command-list">
            <div class="command-list-row" @click="handleCommandSelection('ATTACK')" @mouseover="showCommandDescription('ATTACK')" @mouseleave="clearAllDescription">ATTACK</div>

            <div class="btn-group dropright">
              <div class="command-list-row dropdown-toggle" style="width: 100%;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  @mouseover="showCommandDescription('SKILL')" @mouseleave="clearAllDescription">
                <a>SKILL</a>
              </div>
              <div class="dropdown-menu">
                <div 
                  v-for="skill in this.partyData[this.currentPartyMemberIndex].skills" class=""
                   @mouseover="showSkillDescription(skill.description)" @mouseleave="clearAllDescription"
                >
                  <div v-if="this.partyData[this.currentPartyMemberIndex].value_ap < skill.ap_cost">
                    <div class="command-list-row-skills_not_enough_ap">
                      <div style="display: flex; justify-content: space-between;">
                        <span>{{ skill.name }}</span>
                        <span>{{ skill.ap_cost }}</span>
                      </div>
                      <div>
                      </div>
                    </div>
                  </div>

                  <div v-else>
                    <div @click="handleCommandSelection('SKILL', skill.id, skill.attack_type, skill.effect_type, skill.target_range)" class="command-list-row-skills">
                      <div style="display: flex; justify-content: space-between;">
                        <span>{{ skill.name }}</span>
                        <span>{{ skill.ap_cost }}</span>
                      </div>
                      <div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <div class="command-list-row" @click="handleCommandSelection('DEFENCE')" @mouseover="showCommandDescription('DEFENCE')" @mouseleave="clearAllDescription">DEFENCE</div>
            <div class="command-list-row" @click="handleCommandSelection('ITEM')" @mouseover="showCommandDescription('ITEM')" @mouseleave="clearAllDescription">ITEM</div>
            <div class="command-list-row" @click="handleCommandSelection('RETURN')" @mouseover="showCommandDescription('RETURN')" @mouseleave="clearAllDescription">RETURN</div>
           </div>
           <!-- 味方の立ち絵を出す -->
           <div class="character-picture" :style="backgroundImageStyle"></div>
         </div>

        <!-- messageフィールド -->
        <div style="background-color: white; margin: 30px; border: thick double rgb(50, 161, 206); min-height: 120px; padding: 5px 10px; font-size: 14px; position: relative;">
          <!-- <<{{ this.currentPartyMemberIndex }} 人目選択中>> -->
           <!-- <div style="position: absolute; right: 0%; bottom: 0%;">
            【バトルログ】
           </div> -->
          <p v-if="battleStatus == 'encount'">敵が現れた！</p>
          <p v-if="battleStatus == 'command'">
            {{ this.partyData[this.currentPartyMemberIndex].name }}はどうする？<br>
            <div>
              <span v-if="hoveredDescription != null">
                <hr>
              </span>
              <span v-html="hoveredDescription"></span>
            </div>
          </p>
          <p v-if="battleStatus == 'enemySelect'">対象の敵を選択してください</p>
          <p v-if="battleStatus == 'partySelect'">対象の味方を選択してください</p>
          <p v-if="battleStatus == 'exec'">戦闘開始します。</p>
          <div v-if="battleStatus == 'outputLog'" class="log-container">
            <div v-for="(log, index) in this.battleLog" :key="index" class="log-item">
              <p>{{ (index + 1) }}: {{ log }}</p>
            </div>
          </div>
          <div v-if="battleStatus == 'resultWin'" class="log-container">
            <div v-for="(log, index) in this.resultLog" :key="index" class="log-item">
              <p>{{ log }}</p>
            </div>
          </div>
          <p v-if="battleStatus == 'resultLose'">全滅した...</p>
        </div>

        <!-- enemy -->
        <div style="display: flex; justify-content: space-evenly; min-height: 300px; margin-bottom: 50px;">
          <div v-if="Array.isArray(enemyData) && enemyData.length > 0" style="margin: 20px 0 20px 0;" v-for="(enemy, index) in enemyData.filter(enemy => !enemy.is_defeated_flag)" :key="index">
            <div class="progress">
              <div class="progress-bar bg-danger" role="progressbar" :style="{ width: calculatePercentage(enemy.value_hp, enemy.max_value_hp) + '%' }" aria-valuenow="enemy.value_hp" aria-valuemin="0" :aria-valuemax="enemy.max_value_hp">
                <!-- HP: {{ enemy.value_hp }} / {{ enemy.max_value_hp }} -->
              </div>
            </div>
            <div 
            @click="selectEnemy(enemy.enemy_index)" 
            :style="{ backgroundImage: 'url(/image/rpg/enemy/' + enemy.portrait + ')'}" 
            :class="{ 'enemy-picture': true, 'enemy-hover-active': battleStatus === 'enemySelect'}"
            >
              <!-- {{ enemy.name }} / {{ enemy.value_hp }} -->
            </div>
          </div>
        </div>

          <!-- 勝利時、次の戦闘に遷移 -->
          <div v-if="battleStatus == 'resultWin'"  style="position: relative;">
            <div style="position: absolute; right: 8%; bottom: 0%; background-color: white; padding: 5px 10px; cursor: pointer;">
              <a @click="nextBattle">次の戦闘へ進む</a>
            </div>
          </div>

        <!-- partyのステータス -->
        <div class="party-status-wrapper">
          <div v-for="(partyMember, index) in partyData" :key="index">

            <div 
            @click="selectParty(partyMember.player_index)"
            :class="{'party-hover-active': battleStatus === 'partySelect'}"
            style="margin: auto; text-align:center;  padding: 10px 20px; background-color: white; border: thick double rgb(50, 161, 206);" 
            >
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
  </div>

  <div v-if="battleStatus !== 'resultWin'">
    <button @click="escapeBattle">逃げる</button>
  </div>

  <div class="battlelog_result_wrapper overflow-auto">
    <ul>
      <li>【戦闘履歴】</li>
      <li v-for="log in this.battleLogHistory" >{{ log }}</li>
    </ul>
  </div>

</template>

<script>
import { mapState } from 'vuex';
import axios from 'axios';
export default {
  data() {
    return {
      fieldId: this.$route.params.fieldId,
      stageId: this.$route.params.stageId,
      partyData: {},
      hoveredDescription: null, // 現在マウスオーバーしているスキルの説明
      enemyData: {},
      battleLog: {}, // リアルタイムの戦闘結果
      battleLogHistory: [], // これまでの戦闘結果を配列として履歴に残す。最大100件を考えている
      resultLog: {}, // 戦闘勝利時のゴールド、経験値情報などを格納
    }
  },
  computed: {
    ...mapState(['battleStatus']),
    ...mapState(['clearStage']),
    ...mapState(['selectedCommands']),
    ...mapState(['currentPartyMemberIndex']),
    ...mapState(['battleSessionId']),
    // コマンド選択時のキャラクターの立ち絵
    backgroundImageStyle() {
      return {
        backgroundImage: `url(/image/rpg/character/portrait/${this.partyData[this.currentPartyMemberIndex].role_portrait})`
      }
    }
  },
  created() {
    this.$store.dispatch('setScreen', 'battle');
    // if (this.battleStatus !== 'escape' && this.battleStatus !== 'resultLose' && this.battleStatus !== 'resultWin') {
  },
  mounted() {
    if (this.battleStatus == 'start') {
      console.log('mounted() ------------------------');
      this.getEncountData(this.fieldId, this.stageId, this.clearStage);
    }
  },
  methods: {
    // HP, APの表示
    calculatePercentage(currentValue, maxValue) {
      return (currentValue / maxValue) * 100;
    },
    // スキルにmouseoverした時、ちなんだ説明文を表示
    showCommandDescription(command) {
      let description = '';
      switch (command) {
        case 'ATTACK':
          description = '敵単体に通常攻撃を行います。'
          break;
        case 'SKILL': 
          description = 'APを消費した技を使うことで戦闘を優位に進めることができます。'
          break;
        case 'DEFENCE': 
          description = '選択したターンの間、相手の攻撃を軽減します。'
          break;
        case 'ITEM': 
          description = '所持中のアイテムを使用します。'
          break;
        case 'RETURN': 
          description = 'コマンドの選択を最初からやり直します。'
          break;
      }
      this.hoveredDescription = description;
    },
    showSkillDescription(description) {
      this.hoveredDescription = description;
    },
    clearAllDescription() {
      // コマンド、スキル、アイテムの説明文全ての表示を消す
      this.hoveredDescription = null;
    },

    getEncountData(fieldId, stageId, clearStage) {
      console.log(`getEncountData(): fieldId:${fieldId} stageId:${stageId} clearStage:${clearStage}----------------------------------`);
      // 途中終了してメニューに戻った場合、このメソッドが走らないようにする
      axios.post(`/api/game/rpg/battle/encount`,{
        field_id: fieldId,
        stage_id: stageId,
        clear_stage: clearStage,
      })
      .then(response => {
        let data = response.data;
        this.partyData = data[0] || [];
        this.enemyData = data[1] || [];
        this.$store.dispatch('setBattleSessionId', data[2] || []);
        // 実行タイミングによって正しく格納された値が表示されない場合があるが、一応入っている
        console.log('Battle.vue', this.battleStatus, this.battleSessionId); 
        // getで呼び出せた後にencountにすることで、呼び出す前に画面をクリックした時のエラーを防ぐ
        this.$store.dispatch('setBattleStatus', 'encount');
      })
      .catch(error => {
          if (error.response && error.response.status === 500) {
            console.log(`500エラー: ${error.response.data.message}`);
          } else {
            console.log(`その他エラー: ${error.response.data.message}`);
          }
          this.$store.dispatch('setBattleStatus', 'error');
      })
    },
    // 画面範囲全体をクリックし、 encount状態から次の状態へ遷移する
    nextAction() {
      switch (this.battleStatus) {
        case 'encount':
          console.log('nextAction() encount: ----------------------------------');
          // 味方が戦闘不能の場合は、コマンド選択対象から外してcurrentPartyMemberIndexをインクリメントする
          this.battleCommandSetup(); // リロードして[0,1]が戦闘不能だった場合は、インクリメントする
          break;
        case 'outputLog': 
          console.log('nextAction() outputLog: ----------------------------------');
          // ログ出力の後にコマンド画面に遷移するときに、現在のメンバーが戦闘不能かどうかをチェックする
          // これがないと、最初のキャラ（メイジちゃん）のコマンド選択画面が出てしまうから。
          this.battleCommandSetup(); 
          break;
        // case 'resultWin':
        //   console.log('nextAction() resultWin: ----------------------------------');
        //   this.resultWin();
        default: 
          console.log(`nextAction()で指定のない状態です。${this.battleStatus}`);
      }
    },

    // 敵選択後もしくは DEFENCEなど敵選択の必要がない処理を選択後、コマンド処理に戻る時に動かす処理
    battleCommandSetup() {
      console.log('battleCommandSetup(): ----------------------------------');
      this.hoveredDescription = null; // スキルの説明文を消しておく

      // 敵を全て討伐していた場合は、勝利画面に。
      if (this.enemyData.every(enemy => enemy.is_defeated_flag === true)){
        console.log('敵を全て討伐したので、勝利画面に移行します。');
        this.$store.dispatch('setBattleStatus', 'resultWin');
        this.resultWin();
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
          console.log(`次のパーティコマンド選択画面へ遷移します。`);
          // console.dir(this.partyData[this.currentPartyMemberIndex].skills);
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

    // on_the_selected_command_id は都度変化する
    // skillを選んだならそのスキルのID, アイテムならそのアイテムのIDを格納する
    handleCommandSelection(command, on_the_selected_command_id, attack_type, effect_type, target_range) {
      console.log('handleCommandSelection(): ----------------------------------');
      // 現在コマンド選択中のパーティデータをcurrentPartyMemberIndexに格納する
      let currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
      switch (command) {
        case ("ATTACK") :
          console.log('ATTACK選択。');
          this.$store.dispatch('setSelectedCommand', { partyId: currentMember.id, command });
          this.$store.dispatch('setBattleStatus', 'enemySelect');
          break;
        case ("SKILL") :
          // attack_type    0:無し 1:物理 2:魔法
          // effect_type    0 特殊 1:攻撃 2:回復 3:バフ
          // target_range   0:自身 1:単体 2:全体
          console.log(`SKILL選択。
            skill_id: ${on_the_selected_command_id} attack_type: ${attack_type} effect_type: ${effect_type} 対象: ${target_range}`
          );
          this.$store.dispatch(
            'setSelectedCommandSkill', 
            { partyId: currentMember.id, command, skillId: on_the_selected_command_id }
          );
          if (target_range == 0 || target_range == 2) {
            console.log(`自身を選択するスキル, もしくは範囲スキルを選択したので対象選択をスキップ。`);
            this.$store.dispatch('incrementPartyMemberIndex');
            this.battleCommandSetup();
            return;
          }

          // 攻撃スキルなら、enemySelectへ
          if (effect_type == 1) {
            console.log(`攻撃系単体スキル。enemySelectへ。`);
            this.$store.dispatch('setBattleStatus', 'enemySelect');
          // 回復, バフスキルなら、partySelectへ
          } else {
            console.log(`回復・バフ系単体スキル。partySelectへ。`);
            this.$store.dispatch('setBattleStatus', 'partySelect');
          }
          break;
        case ("DEFENCE") :
          console.log('DEFENCE選択。');
          this.$store.dispatch('setSelectedCommand', { partyId: currentMember.id, command });
          // 防御は敵を指定する必要がないので、enemySelectに遷移させない。
          this.$store.dispatch('incrementPartyMemberIndex');
          this.battleCommandSetup(); 
          break;
        case ("ITEM") :
          console.log('ITEM選択。');
          this.$store.dispatch('setSelectedCommand', { partyId: currentMember.id, command });
          this.$store.dispatch('incrementPartyMemberIndex');
          this.battleCommandSetup(); 
          break;
        case ("RETURN") :
          console.log('RETURN選択。');
          this.$store.dispatch('resetSelectedCommands');
          this.$store.dispatch('resetPartyMemberIndex');
          this.battleCommandSetup();
          break;
      }

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
      this.battleCommandSetup();
    },

    // 選択した味方の順番を格納する
    selectParty(playerIndex) {
      if (this.battleStatus !== "partySelect") return; // 敵選択中以外に敵をクリックした場合は何もさせない。
      console.log('selectParty(): ----------------------------------');
      const currentMember = this.partyData[this.$store.state.currentPartyMemberIndex];
      this.$store.dispatch('setSelectedParty', { partyId: currentMember.id, playerIndex: playerIndex });
      // インクリメントして次のメンバーのセットアップに移行する
      this.$store.dispatch('incrementPartyMemberIndex');
      console.log('selectParty終わり');
      this.battleCommandSetup(); 
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
          this.pushBattleLogHistory(this.battleLog);
          this.$store.dispatch('setBattleStatus', 'outputLog');
          // stateのリセット
          this.$store.dispatch('resetBattleStatus');
        }
      );
    },

    resultWin() {
      // 経験値と獲得ゴールドを加算させ、レベルアップ処理を行う
      console.log('resultWin: ----------------------------------');
      this.resultLog = null;
      this.$store.dispatch('setClearStage', this.fieldId + '-' + this.stageId);
      axios.post('/api/game/rpg/battle/result-win', {
        session_id: this.$store.state.battleSessionId,
        is_win: true,
      })
        .then(response => {
          console.log('リザルト結果処理完了。');
          let data = response.data;
          this.resultLog = data; //戦闘結果を取得する
          console.dir(response.data);
        }
      );
    },

    nextBattle() {
      // 戦闘終了後、次のステージに進む。
      // 1-1 > 1-2 > 1-3という感じで。
      const fieldId = this.$route.params.fieldId;
      const stageId = this.$route.params.stageId;

      // どのステージをクリアしたのかの値を作る。
      this.$store.dispatch('setClearStage', fieldId + '-' + stageId);
      console.log(`clearStage: ${this.clearStage}`);

      console.log('nextBattle(): --------------------', fieldId, stageId, this.clearStage);
      // バトルログなど、色々リセット
      this.battleLogHistory = [];
      this.$store.dispatch('resetAllBattleStatus');
      this.$store.dispatch('setBattleStatus', 'start');
      const nextStageId = parseInt(stageId) + 1;
      this.$router.push(`/game/rpg/battle/${fieldId}/${nextStageId}`); // 任意の画面に遷移
    },

    escapeBattle() {
      console.log('escapeBattle(): ----------------------------------');
      axios.post('/api/game/rpg/battle/escape', {
        session_id: this.$store.state.battleSessionId,
      })
        .then(response => { 
          this.$store.dispatch('resetAllBattleStatus');
          this.$store.dispatch('setScreen', 'menu');
          this.$router.push('/game/rpg/menu'); // 任意の画面に遷移
        })
        .catch(error => {
        // エラーが返る = すでに消えている ということなのでメニュー画面に戻す。
        this.$store.dispatch('resetAllBattleStatus');
        this.$store.dispatch('setScreen', 'menu');
        this.$router.push('/game/rpg/menu');
        });
    },
    pushBattleLogHistory(logs) {
      console.log("pushBattleLogHistory(): -------------------");
      logs.forEach(log => {
        this.battleLogHistory.unshift( log );
      });
      this.battleLogHistory.unshift('------------------------------------------------【ターン終了】------------------------------------------------');
    }
  },
  beforeRouteUpdate(to, from, next) {
    console.log('beforeRouteUpdate(): URL変更を確認しました ----------------------------------');
    // 戦闘ステータスが変更されたときにデータを再取得
    // `to` 引数を使って新しいルートのパラメータを取得
    const newFieldId = to.params.fieldId;
    const newStageId = to.params.stageId;
    console.log(`新しいfieldId: ${newFieldId} stageId: ${newStageId} `);
    if (newFieldId && newStageId) {
      console.log('エンカウントデータを再取得します。');
      this.getEncountData(newFieldId, newStageId, this.clearStage);
    }
    next();
  }
}
</script>
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

        <!-- コマンド選択中ステータスの時、各要素よりも上にコマンド表を出す。 -->
         <div v-if="battleStatus == 'command'">
           <div class="command-list">
            <div style="margin: auto;"><button @click="nextEnemySelectAction">ATTACK</button></div>
            <div style="margin: auto;"><button @click="nextEnemySelectAction">SPECIAL</button></div>
            <div style="margin: auto;"><button @click="nextEnemySelectAction">DEFENCE</button></div>
            <div style="margin: auto;"><button @click="nextEnemySelectAction">ITEM</button></div>
            <div style="margin: auto;"><button @click="nextEnemySelectAction">ESCAPE</button></div>
           </div>
           <!-- 味方の立ち絵を出す -->
           <div class="character-picture"></div>
         </div>

        <div style="background-color: beige; margin-bottom: 50px;">
          <p v-if="battleStatus == 'encount'">敵が現れた！</p>
          <p v-if="battleStatus == 'command'">どうする？</p>
          <p v-if="battleStatus == 'enemySelect'">対象を選択してください</p>
          <p v-if="battleStatus == 'exec'">戦闘開始！</p>
        </div>

        <div style="display: flex; justify-content: space-evenly; background-color: beige; height: 300px; margin-bottom: 50px;">
          <div @click="nextExecAction" style="width:100px; height:100px; border: 1px solid black; margin: auto;">てき1</div>
          <div @click="nextExecAction" style="width:100px; height:100px; border: 1px solid black; margin: auto;">てき2</div>
          <div @click="nextExecAction" style="width:100px; height:100px; border: 1px solid black; margin: auto;">てき3</div>
        </div>

        <div style="display: flex; justify-content: space-evenly; background-color: beige; height: 100px; z-index: 5;" >
          <div style="margin: auto;">
            <p>格闘家</p>
            <p>HP: 100 | MP: 10</p>
          </div>
          <div style="margin: auto;">
            <p>魔導士</p>
            <p>HP: 100 | MP: 10</p>
          </div>
          <div style="margin: auto;">
            <p>狩人</p>
            <p>HP: 100 | MP: 10</p>
          </div>

        </div>

      </div>
    </div>
  </div>

  <button @click="endBattle">バトル終了</button>

</template>

<script>
import { mapState } from 'vuex';
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
      thirdCommands :{},
    }
  },
  computed: {
    ...mapState(['battleStatus'])
  },
  created() {
    this.$store.dispatch('setScreen', 'battle');
    this.$store.dispatch('setBattleStatus', 'encount');
  },
  mounted() {
    console.log('Battle.vue', this.battleStatus);
  },
  methods: {
    endBattle() {
      this.$store.dispatch('setScreen', 'menu');
      this.$router.push('/game/rpg/menu'); // 任意の画面に遷移
    },
    // 画面範囲全体をクリックし、 encount状態から次の状態へ遷移する
    nextEncountAction() {
      if (this.battleStatus == 'encount') {
        this.$store.dispatch('setBattleStatus', 'command');
      }
    },
    nextEnemySelectAction() {
      if (this.battleStatus == 'command') {
        this.$store.dispatch('setBattleStatus', 'enemySelect');
      }
    },
    nextExecAction() {
      if (this.battleStatus == 'enemySelect') {
        this.$store.dispatch('setBattleStatus', 'exec');
        this.execCommand(this.firstCommands, this.secondCommands, this.thirdCommands);
      }
    },
    // 戦闘が続く場合、再びコマンド選択画面にする。
    backSelectCommandStatus() {
      this.$store.dispatch('setBattleStatus', 'command');
    },
    execCommand(first, second, third) {
      console.log('戦闘開始！');
      setTimeout(function() {
        console.log('戦闘処理終わり。コマンド選択に戻ります');
      }, 1000);
      this.backSelectCommandStatus();
    },

  }
}
</script>
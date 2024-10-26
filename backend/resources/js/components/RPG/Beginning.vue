<style>
/* ベースの7角形 */
.parameter-base-wrapper {
  width: 200px;
  height: 200px;
  background-color: rgba(34, 129, 51, 0.5);
  clip-path: polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%);
}

.parameter-role {
  width: 200px;
  height: 200px;
}
.parameter-role.paint {
  background-color: rgba(255, 49, 49, 0.5);
}

.parameter-role.striker {
  clip-path: polygon(50% 10%, 66% 33%, 100% 60%, 60% 65%, 38% 65%, 0% 60%, 33% 33%);
}
.parameter-role.medic {
  clip-path: polygon(50% 20%, 84% 25%, 75% 56%, 65% 81%, 28% 92%, 30% 54%, 33% 36%);
}
.parameter-role.paradin {
  clip-path: polygon(50% 0%, 72% 28%, 85% 60%, 75% 100%, 43% 65%, 33% 55%, 29% 31%);
}
.parameter-role.mage {
  clip-path: polygon(50% 30%, 90% 20%, 60% 56%, 59% 77%, 25% 100%, 15% 58%, 27% 38%);
}
.parameter-role.ranger {
  clip-path: polygon(50% 10%, 78% 27%, 85% 59%, 65% 74%, 42% 74%, 10% 58%, 35% 38%);
}
.parameter-role.buffer {
  clip-path: polygon(50% 20%, 83% 24%, 66% 54%, 64% 81%, 31% 81%, 10% 59%, 33% 41%);
}

.role-picture {
  position: absolute;
  left: 0%;
  background-size: cover;
  min-width: 440px;
  min-height: 682px;
}

.role-description-wrapper {
  /* border: 1px dotted black; */
}

.role-description-message {
  padding: 5px 10px;
}

</style>


<template>
  <div v-if="beginningStatus == 'start'">
    <div class="row">
      <div class="col-12">
        <p>beginning.vue, start</p>
      </div>
    </div>
  </div>

  <div v-if="beginningStatus == 'prologue'">
    <div class="row">
      <div class="col-12" style="border: 1px solid black">
        <p>
          <hr>
          かつて栄華を誇った王国があった。<br>
          王族が住んでいた壮麗なる城は、いつからか魔物の巣窟と化し、凋落した古城と成り果てた。<br>
          時は流れ、人々は魔物の手の届かぬ地に集落を築きつつも、ひとつの伝承が語り継がれている。<br>
          <br>
          "王族がその全盛期に手にした財宝は、未だ古城の奥深くに眠っている――。"<br>
          <br>
          <!--
          くどいか？
          魔物が支配する荒れ果てた地を越え、古城に近づくほどに強力な敵が待ち受けている。
          しかし古城に限らず未だ開拓されていないその地には、まだ誰も手をつけぬ秘宝や資源が眠っているという。 
          その噂は冒険者たちの心に火を灯し、彼らは命を賭してその地に挑む。
          -->
          伝承に命を賭し、未だ開拓されていない地へ足を踏み入れる者たちを「冒険者」と呼ぶ。<br>
          <br>
          <hr>
          <br>
          ...あなたも今まさに冒険者として旅立つ意志を強く持っています。同じ志を持つ仲間を募り、冒険へ向かいましょう。<br>
        </p>
      </div>
      <div class="col-12" style="text-align:right;">
        <button class="btn btn-secondary" @click="switchSetCharacter">→進む</button>
      </div>
    </div>
  </div>

  <div v-if="beginningStatus == 'setCharacter'">
    <div class="row">
      <div class="col-12" style="margin-bottom: 10px; border-bottom: 1px solid gray;">
        <div>
          <p>共に冒険へと旅立つパーティのメンバーを決定しましょう。 (1/3)</p>
        </div>
      </div>

      <div class="col-6 role-description-wrapper">
        <div class="role-description-message">
          <p>
            【<span style="font-weight: bold;">{{ this.roleData[this.currentDisplayRoleIndex]['class_kana'] }} </span>】
            <span>({{ this.roleData[this.currentDisplayRoleIndex]['class'] }})</span>
          </p>
          <p>{{ this.roleData[this.currentDisplayRoleIndex]['description'] }}</p>
        </div>
      </div>
 
      <div class="col-6" style="min-width: 440px; min-height: 682px;">
        <div>
          <div class="role-picture" :style="backgroundImageStyle"></div>
        </div>
      </div>

      <!-- currentDisplayRoleIndexを調整するボタン -->
      <button class="btn btn-primary btn-lg" style="position: absolute; top: 50%; right: 3%; z-index: 10;" @click="incrementDisplayRoleIndex">→</button>
      <button class="btn btn-primary btn-lg" style="position: absolute; top: 50%; left : 3%; z-index: 10;" @click="decrementDisplayRoleIndex">←</button>
    </div>
    
    <!-- パラメータ -->
    <div style="position: absolute; bottom: 5%; right: 5%;">
      <div class="parameter-base-wrapper">
        <div class="parameter-role">
          <div v-if="currentDisplayRoleIndex === 0">
            <div class="parameter-role paint striker"></div>
          </div>
          <div v-else-if="currentDisplayRoleIndex === 1">
            <div class="parameter-role paint medic"></div>
          </div>
          <div v-else-if="currentDisplayRoleIndex === 2">
            <div class="parameter-role paint paradin"></div>
          </div>
          <div v-else-if="currentDisplayRoleIndex === 3">
            <div class="parameter-role paint mage"></div>
          </div>
          <div v-else-if="currentDisplayRoleIndex === 4">
            <div class="parameter-role paint ranger"></div>
          </div>
          <div v-else-if="currentDisplayRoleIndex === 5">
            <div class="parameter-role paint buffer"></div>
          </div>
        </div>
      </div>
      <div>
        <p style="position:absolute;top: -13%;right: 44%;">HP </p>
        <p style="position:absolute;top: 10%;right: -3%;">AP </p>
        <p style="position:absolute;top: 55%;left: 104%;">STR</p>
        <p style="position:absolute;top: 100%;left: 71%;">DEF</p>
        <p style="position:absolute;top: 100%;left: 13%;">INT</p>
        <p style="position:absolute;top: 54%;left: -17%;">SPD</p>
        <p style="position:absolute;top: 9%;left: -4%;">LUC</p>
      </div>

    </div>

  </div>

</template>

<script>
  import { mapState } from 'vuex';
  import axios from 'axios';
  export default {
    data() { // script内で使用する変数を定義する。
      return {
        roleData: {},
      }
    },
    computed: { // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
    // vuexに存在するmapStateメソッドでstore.jsで定義したstate.currentScreenを取得。thisで参照できるようになる。
    ...mapState(['currentScreen']),
    ...mapState(['beginningStatus']),
    ...mapState(['currentDisplayRoleIndex']),
    backgroundImageStyle() {
      return {
        backgroundImage: `url(/image/rpg/character/portrait/${this.roleData[this.currentDisplayRoleIndex]['portrait_image_path']})`
      }
    }
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
      this.$store.dispatch('setScreen', 'beginning');
      this.checkIsUserExistPartyData();
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log('Beginning.vue', this.currentScreen);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
      checkIsUserExistPartyData() {
        console.log('checkIsUserExistPartyData(): -----------------------');
        // axiosでログイン中のユーザーがデータを作っているかどうかをチェックして操作を決定する
        // ロールデータを取っておく
        axios.get('/api/game/rpg/beginning/role')
        .then(response => {
          this.roleData = response.data;
          // response.data.forEach(data => {
          //   console.log(data);
          //  });
          // console.log(this.roleData, this.roleData[0], this.roleData[0]['class']);
            console.log('roleデータ読み込み完了.');
        });
        axios.get('/api/game/rpg/beginning/check-is-exist-data')
          .then(response => {
            const isExistData = response.data;
            if (isExistData) {
              // すでに登録済みのユーザーがURL直打ちなどでアクセスした場合はリダイレクト
              console.log('savedata, party登録済みのためリダイレクト');
              this.$store.dispatch('setScreen', 'menu');
              this.$router.push('/game/rpg/menu');
            } else {
              console.log('party未設定のため、処理を続けます。');

              this.$store.dispatch('setBeginningStatus', 'prologue');
            }
        });
      },
      switchSetCharacter() {
        console.log('switchSetCharacter(): -----------------------');
        this.$store.dispatch('setBeginningStatus', 'setCharacter');
      },
      incrementDisplayRoleIndex() {
        console.log('incrementDisplayRoleIndex(): -----------------------');
        this.$store.dispatch('incrementCurrentDisplayRoleIndex');
        console.log(this.currentDisplayRoleIndex);
      },
      decrementDisplayRoleIndex() {
        console.log('decrementDisplayRoleIndex(): -----------------------');
        this.$store.dispatch('decrementCurrentDisplayRoleIndex');
        console.log(this.currentDisplayRoleIndex);

      },



    }
  }
</script>
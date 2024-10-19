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
          王族が住んでいた壮麗なる城は、いつからか魔物の巣窟と化し、荒れ果てた古城に成り果てた。<br>
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
        <button class="btn btn-secondary">→進む</button>
      </div>
    </div>
  </div>

  <div v-if="beginningStatus == 'setCharacter'">
    <div class="row">
      <div class="col-12">
        <p>パーティメンバーを決める (1/3)</p>
      </div>
    </div>
  </div>

</template>

<script>
  import { mapState } from 'vuex';
  import axios from 'axios';
  export default {
    data() { // script内で使用する変数を定義する。
      return {}
    },
    computed: { // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
    // vuexに存在するmapStateメソッドでstore.jsで定義したstate.currentScreenを取得。thisで参照できるようになる。
    ...mapState(['currentScreen']),
    ...mapState(['beginningStatus']),
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
    }
  }
</script>
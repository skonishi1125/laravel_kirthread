<style scoped>
.status-and-skills-title {
  font-weight: bold;
  border-bottom: 1px dotted black;
  padding-bottom: 10px;
}

.status-line {
  display: flex;
  justify-content: flex-start;
  margin-bottom: 10px;
}

.status-line .label {
  width: 40px; /* HP, STR などの表示幅を固定 */
  font-weight: bold;
}

.status-line .value {
  width: 80px; /* 数値の位置を統一するならここも固定 */
  text-align: left;
}

.skill-line {
  display: flex;
  justify-content: flex-start;
  margin-bottom: 10px;
}
.skill-line .label {
  width: 140px; /* スキル名の幅を揃える */
  font-weight: bold
}
.skill-line .value {
  width: 60px;
  text-align: right;
  font-weight: bold;
}


</style>

<template>
  <div v-if="status.status == 'start'">
    <div class="sub-screen-wrapper">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>Refresh 読み込み中...</small></p>
          </div>
          <hr>
        </div>
      </div>
  
      <div class="row mt-3 sub-sucreen-main-space sub-screen-back-img">
        <div class="col-12"></div>
      </div>
    </div>
  </div>

  <div v-if="status.status == 'loaded'">
    <div class="sub-screen-wrapper">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>パーティメンバーを休息させ、割り振ったステータス、スキルポイントをリセットすることができます。</small></p>
          </div>
          <hr>
        </div>
      </div>
  
      <div class="row mt-3 sub-sucreen-main-space sub-screen-back-img">
        <div class="col-12">
          <div class="row">

            <!-- 左バー -->
            <div class="col-2 my-2">
              <div style="min-height: 480px;  display: flex; flex-flow: column; justify-content: space-evenly; border-right: 1px dotted black; text-align: center;">
                <div><button class="btn btn-sm btn-outline-success my-5" @click="$router.push({ name: 'menu_plaza'})">広場に戻る</button></div>
              </div>
            </div>

            <!-- 右 キャラステータス一覧 -->
            <div class="col-10 my-5" style="max-height: 300px;">
              <div class="col-12">
                <!-- クリックした時そのキャラの情報を取得するようにして、activeクラスを張り替える -->
                <ul class="nav nav-tabs">
                  <a class="nav-link character-nav-tab active">カア</a>
                  <a class="nav-link character-nav-tab">パラ</a>
                  <a class="nav-link character-nav-tab">シュウ</a>
                </ul>

                <div class="row">
                  <div class="col-4 mt-3">
                    <h6 class="status-and-skills-title">現在のステータス</h6>
                    <div class="status-line">
                      <span class="label">HP</span>
                      <span class="value">100</span>
                      <span class="value">(<small><b>+0</b></small>)</span>
                    </div>
                    <div class="status-line">
                      <span class="label">AP</span>
                      <span class="value">10</span>
                      <span class="value">(<small><b>+15</b></small>)</span>
                    </div>
                    <div class="status-line">
                      <span class="label">STR</span>
                      <span class="value">112</span>
                      <span class="value">(<small><b>+100</b></small>)</span>
                    </div>
                    <div class="status-line">
                      <span class="label">DEF</span>
                      <span class="value">9</span>
                      <span class="value">(<small><b>+28</b></small>)</span>
                    </div>
                    <div class="status-line">
                      <span class="label">INT</span>
                      <span class="value">21</span>
                      <span class="value">(<small><b>+28</b></small>)</span>
                    </div>
                    <div class="status-line">
                      <span class="label">SPD</span>
                      <span class="value">91</span>
                      <span class="value">(<small><b>+28</b></small>)</span>
                    </div>
                    <div class="status-line">
                      <span class="label">LUC</span>
                      <span class="value">15</span>
                      <span class="value">(<small><b>+8</b></small>)</span>
                    </div>
                  </div>
                  <div class="col-4 mt-3">
                    <h6 class="status-and-skills-title">修得済みのスキル</h6>
                    <div class="skill-line">
                      <span class="label">プチブラスト</span>
                      <span class="value"><small>(SLv.<b>1</b>)</small></span>
                    </div>
                    <div class="skill-line">
                      <span class="label">クラッシュボルト</span>
                      <span class="value"><small>(SLv.<b>1</b>)</small></span>
                    </div>
                    <div class="skill-line">
                      <span class="label">プチヒール</span>
                      <span class="value"><small>(SLv.<b>1</b>)</small></span>
                    </div>
                    <div class="skill-line">
                      <span class="label">ポップヒール</span>
                      <span class="value"><small>(SLv.<b>1</b>)</small></span>
                    </div>

                  </div>
                </div>

                <div class="row">
                  <div class="col-12 text-right mt-5">
                    <button class="btn btn-info btn-sm">ポイントを振り直す</button>
                  </div>
                </div>

              </div>
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
        partiesInformation: []
      }
    },
    // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
    // vuexに存在するmapStateメソッドでstore.jsで定義したstate.screen.currentを取得。thisで参照できるようになる。
    computed: { 
      ...mapState({
          // statusという変数の中に、 refresh: { status: 'start'} を入れちゃってるイメージ
          // 余裕があれば直した方がいいが、他のVueもこんな感じになっているので合わせる
          status: state => state.menu.plaza.refresh
      }),
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
      this.getPartiesInformation();
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log(`Refresh.vue ${this.status.status}`);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
      // TODO: Status.vueで使用したgetPartiesInfo()がそのまま使えるかも。
      getPartiesInformation() {
        console.log(`fetchPart`);
        axios.get('/api/game/rpg/parties/information')
          .then(response => {
            console.log(`response.data: ${response.data}`);
            this.partiesInformation = response.data;
            console.log(this.partiesInformation[0],this.partiesInformation[0]['status'], this.partiesInformation[1]);
          }
        );

        this.$store.dispatch('setMenuPlazaRefreshStatus', 'loaded');

      }
    }
  }
</script>

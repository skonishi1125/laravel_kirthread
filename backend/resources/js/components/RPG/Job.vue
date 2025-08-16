<style scoped>
.push-button-wrapper {
  text-align: center;
  margin-top: 30px;
}

.push-button {
  font-family: 'Arial Black', sans-serif;
  font-size: 24px;
  font-weight: bold;
  color: #fff;
  width: 220px;
  height: 100px;
    background: #7b99a8;
  border: none;
  border-radius: 20px;
  box-shadow: 0 8px #37474f, 0 4px 6px rgba(0,0,0,0.2);
  cursor: pointer;
  transition: all 0.1s ease-in-out;
  position: relative;
}

.push-button:active {
  transform: translateY(4px);
  box-shadow:
    0 4px #10344a,
    0 2px 4px rgba(0,0,0,0.2);
}

.color-beginner { color: #00aaff; font-weight: bold; }
.color-general { color: #22cc88; font-weight: bold; }
.color-expert { color: #ffaa00; font-weight: bold; }
.color-professional { color: #ff5555; font-weight: bold; }

/* Heroだけ特別 */
.color-hero {
  background: linear-gradient(90deg, #ff0000, #ffa500, #ffff00, #00ff00, #0000ff, #8a2be2, #ff0000);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: bold;
  animation: rainbow 5s linear infinite;
}

@keyframes rainbow {
  0% { background-position: 0% }
  100% { background-position: 100% }
}

</style>

<template>
  <div class="sub-screen-wrapper">
    <!-- start -->
    <div v-if="status.status == 'start'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>読み込み中...</small></p>
            <hr>
          </div>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12"></div>
      </div>
    </div>

    <!-- loaded -->
    <div v-if="status.status == 'loaded'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p>
              <small>
                <span v-if="grade === 0">「おう！ここにボタンがあるだろ？ひたすら押してくれるだけでいいんだ。任せたぜ！」</span>
                <span v-if="grade === 1">「おいおい、金に困ってんのか？だったらいつものやつ頼むぜ！」</span>
                <span v-if="grade === 2">「おっす！あんたも立派になったもんだな。今日も一緒に頑張ろうぜ！」</span>
                <span v-if="grade === 3">「あんたにここまでやられると俺の立場も危ういぜ...。 負けてられねえ！」</span>
                <span v-if="grade === 4">「あ....！ お世話になります！本日も労働されるとのことで、何卒よろしくお願いいたします！」</span>
              </small>
            </p>
          </div>
          <hr>
          <p>
            <small>
              現在の階級: <span :class="gradeClass" >{{ gradeLabel }}</span>
            </small>
          </p>
        </div>
      </div>
  
      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12">
  
          <div class="row">
            <!-- 左バー -->
            <div class="col-2 my-2">
              <div style="min-height: 480px;  display: flex; flex-flow: column; justify-content: space-evenly; border-right: 1px dotted black; text-align: center;">
                <div><button class="btn btn-sm btn-outline-success" @click="$router.push({ name: 'menu_plaza'})">広場に戻る</button></div>
              </div>
            </div>

            <!-- 右バー -->
            <div class="col-10 my-2">
              <div class="row" style="margin-top: 100px">
                <div class="col-8 my-5">
                  <div class="push-button-wrapper">
                    <button class="push-button" @click="addPushCounter">PUSH</button>
                  </div>
                </div>
                <div class="col-4 my-5">
                  <p>クリック回数: {{ pushCounter }} 回</p>
                  <button class="btn btn-info" 
                    style="width: 170px" 
                    @click="openPaymentModal"
                    :disabled="pushCounter < 10"
                    >精算する</button>
                </div>
              </div>
            </div>
          </div>
  
        </div>
      </div><!-- row mt-3 sub-sucreen-main-space -->
    </div><!-- loaded -->

    <!-- result -->
    <div v-if="status.status == 'result'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>労働が終了しました。お疲れ様でした！</small></p>
            <hr>
            <div>
              <p><small>あなたの記録: {{ loginUserResult.total_count }}回</small></p>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12">
          <div class="row">
            <!-- 左バー -->
            <div class="col-2 my-2">
              <div style="min-height: 480px;  display: flex; flex-flow: column; justify-content: space-evenly; border-right: 1px dotted black; text-align: center;">
                <div><button class="btn btn-sm btn-outline-success" @click="$router.push({ name: 'menu_plaza'})">広場に戻る</button></div>
              </div>
            </div>

            <!-- 右バー -->
            <div class="col-10 mt-5">
              <div class="row">
                <div class="col-12" style="max-height: 400px; overflow-y: scroll;">
                  <table class="table table-borderless table-hoverable">
                    <thead>
                      <tr style="border-bottom: 1px dotted;">
                        <th>
                          ランキング
                        </th>
                      </tr>
                      <tr>
                        <th>順位</th>
                        <th>総クリック回数</th>
                      </tr>
                    </thead>
                    <tbody v-for="(ranking, index) in rankings">
                      <tr>
                        <td>
                          <small>{{ index + 1 }}位</small>
                        </td>
                        <td>
                          <small>{{ ranking.total_count }} 回</small>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div> <!-- max-heightを指定した、col-12 div -->

              </div> <!-- row -->
            </div>

          </div>
        </div>
      </div>
    </div><!-- result -->

  </div>

  <teleport to="body">
    <div class="modal fade" id="payment-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-backdrop-adjust" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title book-modal-title"><b>確認画面</b></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body" style="min-height: 200px;">
            <p>お疲れ様でした。働いた分だけ、お金を精算することができます。</p>
            <div class="d-flex justify-content-around" style="margin: 20px 0px">
              <span>クリック回数: {{ pushCounter }} 回</span>
              <span>→</span>
              <span>獲得ゴールド: {{ earnedMoney }} G</span>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-info" data-dismiss="modal" @click="calculateResult">確定</button>
          </div>
        </div>
      </div>
    </div>
  </teleport>

</template>

<script>
  import $ from 'jquery';
  import { mapState } from 'vuex';
  import axios from 'axios';
  export default {
    data() { // script内で使用する変数を定義する。
      return {
        pushCounter: 0,
        earnedMoney: 0,
        grade: null,
        gradeLabel: null,
        paymentRate: 0,
        loginUserResult: {},
        rankings: {}
      }
    },
    // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
    // vuexに存在するmapStateメソッドでstore.jsで定義したstate.screen.currentを取得。thisで参照できるようになる。
    computed: { 
      ...mapState({
          status: state => state.menu.plaza.job
      }),
      // 引数を渡す場合はcomputedには書けない
      gradeClass() {
        switch (this.grade) {
          case 0: return 'color-beginner';
          case 1: return 'color-general';
          case 2: return 'color-expert';
          case 3: return 'color-professional';
          case 4: return 'color-hero';
          default: return '';
        }
      },
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
      this.fetchJobStatus();
      this.$store.dispatch('setMenuPlazaJobStatus', 'start');
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log(`Job.vue ${this.status.status}`);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。

      fetchJobStatus() {
        console.log(`fetchJobStatus`);
        axios.get('/api/game/rpg/menu/plaza/job/fetch_status')
        .then(response => {
          this.grade = response.data.grade;
          this.gradeLabel = response.data.grade_label;
          this.paymentRate = response.data.payment_rate;
          this.$store.dispatch('setMenuPlazaJobStatus', 'loaded');
        });
      },

      openPaymentModal() {
        this.earnedMoney = Math.round(this.pushCounter * this.paymentRate);
        $('#payment-modal').modal('show');
      },

      addPushCounter() {
        this.pushCounter++;
      },

      // 精算処理, 及び全ユーザーのランキング取得
      calculateResult() {
        console.log(`calculatePaymentMoney`);
        axios.post('/api/game/rpg/menu/plaza/job/calculate', {
          earned_money: this.earnedMoney,
          push_count: this.pushCounter
        }).then(response => {
          this.loginUserResult = response.data[0];
          this.rankings = response.data[1];
          this.pushCounter = 0;
          this.earnedMoney = 0;

          this.$store.dispatch('setMenuPlazaJobStatus', 'result');

        });
      },

    }
  }
</script>

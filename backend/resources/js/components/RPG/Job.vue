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
            <p><small>「おう！ここにボタンがあるだろ？ひたすら押してくれるだけでいいんだ。任せたぜ！」</small></p>
          </div>
          <hr>
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
                    :class="{'disabled': pushCounter < 10}" 
                    >精算する</button>
                </div>
              </div>
            </div>
          </div>
  
        </div>
      </div><!-- row mt-3 sub-sucreen-main-space -->
    </div><!-- loaded -->

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
            <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">確定</button>
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
        earnedMoney: 0
      }
    },
    // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
    // vuexに存在するmapStateメソッドでstore.jsで定義したstate.screen.currentを取得。thisで参照できるようになる。
    computed: { 
      ...mapState({
          status: state => state.menu.plaza.job
      }),
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
      this.fetchJobStatus();
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log(`Job.vue ${this.status.status}`);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。

      fetchJobStatus() {
        console.log(`fetchJobStatus`);
        axios.get('/api/game/rpg/menu/plaza/job/fetch_status')
        .then(response => {
          console.log(`response`);
          this.$store.dispatch('setMenuPlazaJobStatus', 'loaded');
        });

      },

      openPaymentModal() {
        this.earnedMoney = Math.round(this.pushCounter / 10);
        $('#payment-modal').modal('show');
      },

      addPushCounter() {
        this.pushCounter++;

      }

    }
  }
</script>

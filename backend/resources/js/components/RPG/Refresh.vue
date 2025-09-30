<style scoped>
.character-nav-tab {
  cursor: pointer;
}

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
            <p><small>読み込み中...</small></p>
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
            <p><small>「こんにちは〜。ゆっくりしていってね。...あ、お代はちょうだいね。」</small></p>
          </div>
          <hr>
          <p><small style="color:gray">※パーティメンバーを休息させ、割り振ったステータス、スキルポイントをリセットすることができます。</small></p>
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
                  <a class="nav-link character-nav-tab"
                  :class="{'active': status.currentSelectedPartyMemberIndex === 0}"
                  @click="$store.dispatch('setMenuPlazaRefreshCurrentSelectedPartyMemberIndex', 0)"
                  >
                    {{ partiesInformation[0].nickname }}
                  </a>
                    <a class="nav-link character-nav-tab"
                    :class="{'active': status.currentSelectedPartyMemberIndex === 1}" 
                    @click="$store.dispatch('setMenuPlazaRefreshCurrentSelectedPartyMemberIndex', 1)"
                  >
                    {{ partiesInformation[1].nickname }}
                  </a>
                  <a class="nav-link character-nav-tab"
                    :class="{'active': status.currentSelectedPartyMemberIndex === 2}" 
                    @click="$store.dispatch('setMenuPlazaRefreshCurrentSelectedPartyMemberIndex', 2)"
                  >
                    {{ partiesInformation[2].nickname }}
                  </a>
                </ul>

                <div class="row">
                  <div class="col-6 mt-3">
                    <h6 class="status-and-skills-title">現在のステータス</h6>
                    <div class="status-line">
                      <span class="label">HP:</span>
                      <span class="value">
                        {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_hp + partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_hp }}</span>
                      <span class="value">
                        (
                          <small>
                            {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_hp }}
                            <b>+{{ partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_hp }}</b>
                          </small>
                        )
                      </span>
                    </div>
                    <div class="status-line">
                      <span class="label">AP:</span>
                      <span class="value">
                        {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_ap + partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_ap }}
                      </span>
                      <span class="value">
                        (
                          <small>
                            {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_ap }}
                            <b>+{{ partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_ap }}</b>
                          </small>
                        )
                      </span>
                    </div>
                    <div class="status-line">
                      <span class="label">STR:</span>
                      <span class="value">
                        {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_str + partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_str }}
                      </span>
                      <span class="value">
                        (
                          <small>
                            {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_str }}
                            <b>+{{ partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_str }}</b>
                          </small>
                        )
                      </span>
                    </div>
                    <div class="status-line">
                      <span class="label">DEF:</span>
                      <span class="value">
                        {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_def + partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_def }}
                      </span>
                      <span class="value">
                        (
                          <small>
                            {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_def }}
                            <b>+{{ partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_def }}</b>
                          </small>
                        )
                      </span>
                    </div>
                    <div class="status-line">
                      <span class="label">INT:</span>
                      <span class="value">
                        {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_int + partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_int }}
                      </span>
                      <span class="value">
                        (
                          <small>
                            {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_int }}
                            <b>+{{ partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_int }}</b>
                          </small>
                        )
                      </span>
                    </div>
                    <div class="status-line">
                      <span class="label">SPD:</span>
                      <span class="value">
                        {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_spd + partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_spd }}
                      </span>
                      <span class="value">
                        (
                          <small>
                            {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_spd }}
                            <b>+{{ partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_spd }}</b>
                          </small>
                        )
                      </span>
                    </div>
                    <div class="status-line">
                      <span class="label">LUC:</span>
                      <span class="value">
                        {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_luc + partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_luc }}
                      </span>
                      <span class="value">
                        (
                          <small>
                            {{ partiesInformation[status.currentSelectedPartyMemberIndex].status.value_luc }}
                            <b>+{{ partiesInformation[status.currentSelectedPartyMemberIndex].status.allocated_luc }}</b>
                          </small>
                        )
                      </span>
                    </div>
                  </div>
                  <div class="col-6 mt-3">
                    <h6 class="status-and-skills-title">修得済みのスキル</h6>
                    <div v-for="skill in partiesInformation[status.currentSelectedPartyMemberIndex].skills">
                      <div class="skill-line">
                        <span class="label">{{ skill.name }}</span>
                        <span class="value"><small>(SLv.<b>{{ skill.skill_level }}</b>)</small></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 text-right mt-5 d-flex justify-content-between">
                    <span style="color: red"><small>{{ refreshSuccessMessage }}</small></span>
                    <button class="btn btn-outline-info btn-sm" @click="openPaymentModal(partiesInformation[status.currentSelectedPartyMemberIndex])">休息する</button>
                  </div>
                </div>

              </div>
            </div>


          </div>

        </div>
      </div>
    </div>
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

          <div class="modal-body">
            <p>
              {{ modalPartyInformation.nickname }}に割り振ったポイントをリセットします。(所持金: <b>{{ currentMoney }}</b> G)<br>
            </p>
            <div style="text-align: right;">
              <span>必要代金: {{ modalPartyInformation.level * 100 }} G <small>(※ Lv x 100 G)</small></span>
            </div>
            <div style="min-height: 50px;">
              <span style="color:red" v-if="currentMoney < modalPartyInformation.level * 100">
                所持金が不足しています。
              </span>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-info btn-sm"
            :disabled="currentMoney < modalPartyInformation.level * 100"
            @click="paymentRefresh"
            >
              リセットする
            </button>
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
        partiesInformation: [],
        currentMoney: 0,
        modalPartyInformation: {},
        modalErrorMessage: null,
        refreshSuccessMessage: null,
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
      this.fetchPartiesInfo();
      this.$store.dispatch('setMenuPlazaRefreshStatus', 'start');
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log(`Refresh.vue ${this.status.status}`);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
      // モーダルに表示中パーティメンバーの情報を出す
      openPaymentModal(partyInformation) {
        this.modalPartyInformation = partyInformation;
        // this.earnedMoney = Math.round(this.pushCounter * this.paymentRate);
        $('#payment-modal').modal('show');
      },
      fetchPartiesInfo() {
        console.log(`fetchPartiesInfo`);
        axios.get('/api/game/rpg/menu/plaza/refresh/fetch_parties_info')
          .then(response => {
            console.log(`response.data: ${response.data}`);
            this.partiesInformation = response.data[0];
            this.currentMoney = response.data[1];
            console.log(this.partiesInformation[0],this.partiesInformation[0]['status'], this.partiesInformation[1]);
            this.$store.dispatch('setMenuPlazaRefreshCurrentSelectedPartyMemberIndex', 0);
            this.$store.dispatch('setMenuPlazaRefreshStatus', 'loaded');
          }
        ).catch(error => {
            console.log('ERROR');
            this.$router.push({ name: 'menu_plaza'});
        });
      },

      paymentRefresh(){
        console.log(`paymentRefresh`);
        if (this.currentMoney < this.modalPartyInformation.level * 100) {
          this.modalErrorMessage = '所持金が不足しています。'
          return;
        }

        axios.post('/api/game/rpg/menu/plaza/refresh/reset_status_and_skill_point', {
          party_id: this.modalPartyInformation.party_id,
          payment_money: this.modalPartyInformation.level * 100
        })
        .then(response => {
          this.fetchPartiesInfo();
          this.refreshSuccessMessage = 'ステータス、スキルポイントの振り直しが完了しました！';
          $('#payment-modal').modal('hide');
        });
      },


    }
  }
</script>

<style scoped>
.item-nav-tab {
  cursor: pointer;
}

.action-link {
    cursor: pointer;
}
.table-hoverable tbody tr:hover {
    cursor: pointer;
    background-color: #fdf6e3;
    transition: background-color 0.2s ease;
}

.weight-bold {
    font-weight: bold;
    font-size: 0.9rem;
    margin-bottom: 0.7rem;
}

</style>

<!-- v-if系はtemplateの内部に書く。 -->
<template>

  <div class="sub-screen-wrapper">
    <div v-if="status == 'start'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>読み込み中...</small></p>
          </div>
          <hr>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12"></div>
      </div>

    </div>

    <div v-if="status == 'buyable'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <p><small>何を買おうかな？(所持金: <b>{{ money }}</b> G)</small></p>
          <hr>
          <p>
            <span v-if="after_purchase_array.after_purchase_flag" style="color: red">
              <small>・{{ after_purchase_array.name }}を{{ after_purchase_array.number }}個購入しました！</small>
            </span>
          </p>
        </div>

      </div>

      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12">
          <ul class="nav nav-tabs">
            <a class="nav-link item-nav-tab"
              :class="{'active': status === 'buyable'}"
              @click="$store.dispatch('setMenuShopStatus', 'buyable')"
            >
              買う
            </a>
            <a class="nav-link item-nav-tab"
              :class="{'active': status === 'sellable'}"
              @click="$store.dispatch('setMenuShopStatus', 'sellable')"
            >
              売る
            </a>
          </ul>
        </div>
        <div class="col-12" style="height: 100%;">
          <table class="table table-borderless table-hoverable">
            <thead>
                <tr>
                  <th>名前</th>
                  <th>価格</th>
                  <th>説明</th>
                  <th>所持数</th>
                </tr>
            </thead>
            <tbody>
              <tr v-for="buyItem in buyItemList" @click="showPurchaseForm(buyItem)">
                <td class="weight-bold">{{ buyItem.name }}</td>
                <td class="weight-bold">{{ buyItem.price }} G</td>
                <td class="weight-bold">{{ buyItem.description }}</td>
                <td class="weight-bold">{{ buyItem.possession_number }}/<span color:red></span>{{ buyItem.max_possession_number }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-if="status == 'sellable'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>不要なアイテムはあったっけ。(所持金: <b>{{ money }}</b> G)</small></p>
          </div>
          <hr>
          <span v-if="after_sell.after_sell_flag" style="color: blue">
            <small>・{{ after_sell.name }}を{{ after_sell.number }}個売却しました。</small>
          </span>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12">
          <ul class="nav nav-tabs">
            <a class="nav-link item-nav-tab"
              :class="{'active': status === 'buyable'}"
              @click="$store.dispatch('setMenuShopStatus', 'buyable')"
            >
              買う
            </a>
            <a class="nav-link item-nav-tab"
              :class="{'active': status === 'sellable'}"
              @click="$store.dispatch('setMenuShopStatus', 'sellable')"
            >
              売る
            </a>
          </ul>
        </div>

        <div class="col-12" style="height: 100%;">
          <table class="table table-borderless table-hoverable">
            <thead>
                <tr>
                  <th>名前</th>
                  <th>売値</th>
                  <th>説明</th>
                  <th>所持数</th>
                </tr>
            </thead>
            <tbody>
              <tr v-for="sellItem in sellItemList" @click="showSellForm(sellItem)" class="sell-table">
                <td class="weight-bold">{{ sellItem.name }}</td>
                <td class="weight-bold">{{ sellItem.price }} G</td>
                <td class="weight-bold">{{ sellItem.description }}</td>
                <td class="weight-bold">{{ sellItem.possession_number }}/<span color:red></span>{{ sellItem.max_possession_number }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  
    <!-- 購入モーダル -->
    <teleport to="body">
      <div class="modal fade" id="modal-item-purchase" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
              <h6 class="modal-title"><b>購入品の確認</b></h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
              <!-- error message -->
              <div v-if="error_message">
                  <p style="color:red;"><small>{{ error_message }}</small></p>
              </div>
  
              <!-- Edit purchase form -->
              <form class="form-horizontal" role="form">
                  <!-- Date -->
                  <div class="form-group">
                  <label class="control-label">
                      {{ purchaseForm.name }}をいくつ購入しますか？<br>
                  </label>
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="inputGroup-sizing-sm">購入数: </span>
                    </div>
                    <input type="text" style="display: none;">
                    <input id="purchase-number" min="0" type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                        v-model.number="inputPurchaseItemNumber"
                        :max="maxPurchaseLimit"
                        @input="validatePurchaseItem"
                    >
                  </div>
                  <hr>
                  <div>
                    <small> 現在所持数: <b>{{ purchaseForm.possession_number }}</b> 個</small>
                    <br>
                    <small>
                      ※{{ purchaseForm.name }}の最大所持可能数: <b style="color:red">{{ purchaseForm.max_possession_number }}</b> 個
                    </small>
                  </div>
                  <div style="text-align: right;">
                    <p>合計: {{ purchaseForm.price * inputPurchaseItemNumber }} G</p>
                  </div>
                  <small style="color:red">
                    アイテムは合計10個まで所持することができます。現在の合計所持数: <b>{{ currentPossession }}</b> 個
                  </small>
                  <small v-if="modalErrorMessage != null">{{ modalErrorMessage }}</small>
                </div>
              </form>
              </div>
  
              <!-- Modal Actions -->
              <div class="modal-footer">
                <!-- 購入しないときは、押せなくする -->
                <button type="button" class="btn btn-info" 
                  @click="paymentItem"
                  :disabled="inputPurchaseItemNumber < 1 || purchaseForm.max_possession_number - purchaseForm.possession_number < 1 || currentPossession >= maxPossession"
                >
                  購入する
              </button>
              </div>
          </div>
          </div>
      </div>

      <!-- 売却モーダル -->
      <div class="modal fade" id="modal-item-sell" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
              <h6 class="modal-title"><b>売却品の確認</b></h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <!-- Date -->
                    <div class="form-group">
                    <label class="control-label">
                        {{ sellForm.name }}をいくつ売却しますか？<br>
                    </label>
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">売却数: </span>
                      </div>
                      <input type="text" style="display: none;">
                      <input id="purchase-number" min="1" type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                          v-model.number="inputSellItemNumber"
                          :max="maxSellLimit"
                          @input="validateSellItem"
                      >
                    </div>
                    <hr>
                    <div>
                      <small> 現在所持数: <b>{{ sellForm.possession_number }}</b> 個</small>
                    </div>
                  </div>
                </form>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-danger" @click="sellOffItem">売却する</button>
              </div>
          </div>
          </div>
      </div>
    </teleport>
  </div>



</template>

<script>
  import $ from 'jquery';
  import axios from 'axios';
  import { mapState } from 'vuex';
  export default {
    data() {
      return {
        shopListItems: [],
        buyItemList: [],
        sellItemList: [],
        error_message: '',
        modalErrorMessage: null,
        inputPurchaseItemNumber: 0,
        purchaseForm: {
          item_id: '',
          name: '',
          price: '',
          possession_number: '',
          max_possession_number: '',
        },
        inputSellItemNumber: 0,
        sellForm: {
          item_id: '',
          name: '',
          price: '',
          possession_number: '',
          max_possession_number: '',
        },
        currentPossession: 0,
        maxPossession: 10,
        money: 0,
        price: 0,
        after_purchase_array: {
          name: '',
          number: 0,
          after_purchase_flag: false,
        },
        after_sell: {
          name: '',
          number: 0,
          after_sell_flag: false,
        },
      }
    },
    created() {
      // 初期値をセット 
      // これで"ショップ" > "ステータス"  > "ショップ"と遷移しても、初めの表示からとなる
      this.$store.dispatch('setMenuShopStatus', 'start'); 
      this.getShopInfo();
    },
    computed: {
      // menu.shop.status == 'start' の値がcomponentで呼べるようになる
      ...mapState({
        status: state => state.menu.shop.status,
      }),

      // 使用している値に変化があるたびに、計算し直すようcomputedに書く
      maxPurchaseLimit() {
        console.log('maxPurchaseLimit');
          const remainingOverall = this.maxPossession - this.currentPossession; // 全体であと何個持てるか
          const remainingThisItem = this.purchaseForm.max_possession_number - this.purchaseForm.possession_number; // このアイテムであと何個持てるか
          return Math.max(Math.min(remainingOverall, remainingThisItem), 0);
      },
      maxSellLimit() {
        console.log('maxSellLimit');
        return this.sellForm.possession_number;
      },
    },
    mounted() {
      console.log(this.status); // state.menu.shop.status
      $('#modal-item-purchase').on('shown.bs.modal', () => {
        $('#purchase-number').focus();
      })
    },
    methods: {

      getShopInfo() {
        console.log("getShopInfo(): -----------------------------------------");
        axios.get('/api/game/rpg/shop/information')
          .then(response => {
              this.currentPossession = response.data.current_possession;
              this.money = response.data.money;
              this.buyItemList = response.data.buyItemList;
              this.sellItemList = response.data.sellItemList;

              // 画面の準備ができたら、statusを変更
              if (this.after_sell.after_sell_flag === true) {
                this.$store.dispatch('setMenuShopStatus', 'sellable');
              } else {
                this.$store.dispatch('setMenuShopStatus', 'buyable');
              }
          });
      },

      // 購入モーダル表示
      showPurchaseForm(buyItem) {
        console.log('showPurchaseForm(): -------------------');
        // アイテム情報をpurchaseForm配列に格納しておく。
        this.purchaseForm.item_id = buyItem.id;
        this.purchaseForm.name = buyItem.name;
        this.purchaseForm.price = buyItem.price;
        this.purchaseForm.possession_number = buyItem.possession_number;
        this.purchaseForm.max_possession_number = buyItem.max_possession_number;

        console.log(this.purchaseForm.max_possession_number);

        // 別の商品を選択した時に過去の商品のモーダルで設定した内容が出ないよう初期化しておく
        this.inputPurchaseItemNumber = 0;
        this.error_message = null;
        this.modalErrorMessage = null;

        $('#modal-item-purchase').modal('show');
      },

      // 売却モーダル表示
      showSellForm(sellItem) {
        console.log(`showSellForm():------------`);
        this.sellForm.item_id = sellItem.id;
        this.sellForm.name = sellItem.name;
        this.sellForm.price = sellItem.price;
        this.sellForm.possession_number = sellItem.possession_number;
        this.sellForm.max_possession_number = sellItem.max_possession_number;

        // 売却数の初期値を、所持数と同じにしておく
        this.inputSellItemNumber = sellItem.possession_number;
        $('#modal-item-sell').modal('show');
      },

      validatePurchaseItem(event) {
        const value = Number(event.target.value);
        // 最大購入可能数 = 最大所持制限数 - 現時点での所持数
        const maxNum = this.purchaseForm.max_possession_number - this.purchaseForm.possession_number

        if (maxNum === 0) {
          // this.inputPurchaseItemNumber = maxNum; // 最大値を超えた場合は最大値に
          // this.modalErrorMessage = `振り分け可能なステータスポイントがありません。`;
        } else if (value > maxNum) {
          this.inputPurchaseItemNumber = maxNum; // 最大値を超えた場合は最大値に
          this.modalErrorMessage = `所持制限数を超える購入はできません。`;
        } else if (value < 0) {
          this.inputPurchaseItemNumber = 0; // 最小値を下回った場合は0に
          this.modalErrorMessage = 'マイナスの値を指定することはできません。';
        }
      },

      // 支払確定処理
      paymentItem() {
        let form = {
          item_id: this.purchaseForm.item_id,
          number: this.inputPurchaseItemNumber,
          name: this.purchaseForm.name,
          price: this.purchaseForm.price,
        }

        // TODO: 所持金が足りないケースなどはバックエンドで対応しているが、フロントエンド側で制御してやってもいい
        axios['post']('/api/game/rpg/shop/payment', form)
          .then(response => {
            // 処理が終わるまで、読み込み中画面を出しておこうと思ったが動作しないので一旦コメントアウト
            // this.$store.dispatch('setMenuShopStatus', 'start'); 
            // リスト更新
            this.getShopInfo();

            // 購入した商品を画面に出すため、情報を保管しておく
            this.after_purchase_array.after_purchase_flag = true;
            this,this.after_sell.after_sell_flag = false;
            this.after_purchase_array.name = form.name;
            this.after_purchase_array.number = form.number;

            // モーダルを閉じる
            $('#modal-item-purchase').modal('hide');
            // this.$store.dispatch('setMenuShopStatus', 'buyable'); 
          })
          .catch(error => {
            console.log(error.response.data.error);
            this.error_message = error.response.data.error;
          });

      },

      // 売却処理
      sellOffItem() {
        let form = {
          item_id: this.sellForm.item_id,
          number: this.inputSellItemNumber,
          name: this.sellForm.name,
          price: this.sellForm.price,
        }

        axios.post(
          '/api/game/rpg/shop/sell_off', 
          form
        )
        .then(response => {
            // リスト更新
            this.getShopInfo();

            // 売却した商品を画面に出すため、情報を保管しておく
            this.after_sell.after_sell_flag = true;
            this.after_purchase_array.after_purchase_flag = false;
            this.after_sell.name = form.name;
            this.after_sell.number = form.number;

            // モーダルを閉じる
            $('#modal-item-sell').modal('hide');
        });



      }

    }
  }
</script>

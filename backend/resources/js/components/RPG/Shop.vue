<style scoped>
.sub-sucreen-text-space {
    padding: 10px 0px;
}
.action-link {
    cursor: pointer;
}

.table-hoverable tbody tr:hover {
    cursor: pointer;
    background-color: #fdf6e3;
    transition: background-color 0.2s ease;
}

</style>

<!-- v-if系はtemplateの内部に書く。 -->
<template>

  <div class="container">
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
          <p>何を買おうかな？(所持金: {{ money }} G)</p>
        </div>
          <div class="col-12" style="color: blue" v-if="after_purchase_array.after_purchase_flag">
            <hr>
            <p>{{ after_purchase_array.name }} x {{ after_purchase_array.number }} を購入しました!</p>
          </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space">
        <div class="col-12">
          <ul class="nav nav-tabs">
            <a class="nav-link active">買う</a>
            <a class="nav-link ">売る</a>
          </ul>
        </div>
        <div class="col-12">
          <table class="table table-borderless table-hoverable">
            <thead>
                <tr>
                  <th>名前</th>
                  <th>価格</th>
                  <th>説明</th>
                </tr>
            </thead>
            <tbody>
              <tr v-for="shopListItem in shopListItems" @click="showPurchaseForm(shopListItem)">
                <td>{{ shopListItem.name }}</td>
                <td>{{ shopListItem.price }} G</td>
                <td>{{ shopListItem.description }}</td>
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
              <h4 class="modal-title">購入フォーム</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
              <!-- error message -->
              <div v-if="error_message">
                  <p style="color:red;">{{ error_message }}</p>
              </div>
  
              <!-- Edit purchase form -->
              <form class="form-horizontal" role="form">
                  <!-- Date -->
                  <div class="form-group">
                  <label class="control-label">
                      {{ purchaseForm.name }}をいくつ購入しますか？<br>
                  </label>
                  <div style="max-width: 100px;">
                      <!-- 
                      inputが1つだけの場合、enterを押すと勝手にリロードされるので対策 
                          https://qiita.com/koara-local/items/0c8343bc34e46d3d6390
                          https://www.softel.co.jp/blogs/tech/archives/3614?
                      -->
                      <input type="text" style="display: none;">
                      <input id="purchase-number" min="1" max="100" type="number" class="form-control" v-model="number" @keyup.enter="false">
  
                  </div>
                  <hr>
                  <div style="text-align: right;">
                      合計: {{ purchaseForm.price * number }} G
                  </div>
                  </div>
              </form>
              </div>
  
              <!-- Modal Actions -->
              <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
              <button type="button" class="btn btn-primary" @click="paymentItem">購入する</button>
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
        error_message: '',
        purchaseForm: {
          item_id: '',
          name: '',
          price: '',
        },
        money: 0,
        price: 0,
        number: 1,
        after_purchase_array: {
          name: '',
          number: 0,
          after_purchase_flag: false,
        },
      }
    },
    created() {
      // 初期値をセット 
      // これで"ショップ" > "ステータス"  > "ショップ"と遷移しても、初めの表示からとなる
      this.$store.dispatch('setMenuShopStatus', 'start'); 
      this.getShopList();
      this.getCurrentMoney();
    },
    computed: {
      // menu.shop.status == 'start' の値がcomponentで呼べるようになる
      ...mapState({
        status: state => state.menu.shop.status,
      }),
    },
    mounted() {
      console.log(this.status); // state.menu.shop.status
      $('#modal-item-purchase').on('shown.bs.modal', () => {
        $('#purchase-number').focus();
      })
    },
    methods: {
      // ショップ販売物一覧をlaravelAPIから取得
      getShopList() {
        console.log("getShopList(): -----------------------------------------");
        axios.get('/api/game/rpg/shop/list')
          .then(response => {
            this.shopListItems = response.data;
          });
      },
      // 所持金をセーブデータから取得
      getCurrentMoney() {
        console.log("getCurrentMoney(): -----------------------------------------");
        axios.get('/api/game/rpg/savedata')
          .then(response => {
            this.money = response.data.money;
            this.$store.dispatch('setMenuShopStatus', 'buyable'); // 所持金が取得でき次第、statusを変更
          }
        );
      },

      // 購入モーダル表示
      showPurchaseForm(shopListItem) {
        // アイテム情報をpurchaseForm配列に格納しておく。
        this.purchaseForm.item_id = shopListItem.id;
        this.purchaseForm.name = shopListItem.name;
        this.purchaseForm.price = shopListItem.price;
        this.purchaseForm.max_possession_number = shopListItem.max_possession_number;

        console.log(this.purchaseForm.max_possession_number);

        // エラーメッセージを消しておく
        this.number = 1;
        this.error_message = null;

        $('#modal-item-purchase').modal('show');
      },

      // 支払確定処理
      paymentItem() {
        let form = {
          money: this.money,
          number: this.number,
          name: this.purchaseForm.name,
          price: this.purchaseForm.price,
        }

        axios['post']('/api/game/rpg/shop/payment', form)
          .then(response => {
            // 所持金更新
            this.getCurrentMoney();

            // 購入情報を配列に入れておく
            this.after_purchase_array.after_purchase_flag = true;
            this.after_purchase_array.name = form.name;
            this.after_purchase_array.number = form.number;

            // モーダルを閉じる
            $('#modal-item-purchase').modal('hide');
          })
          .catch(error => {
            console.log(error.response.data.error);
            this.error_message = error.response.data.error;
          });

      },

    }
  }
</script>

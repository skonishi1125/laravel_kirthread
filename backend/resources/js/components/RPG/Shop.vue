<style scoped>
  .action-link {
    cursor: pointer;
  }
</style>

<template>
  <div class="row">
    <div class="col-sm-12">
      <p>購入品を選択してください(所持金:{{ this.money }} G)</p>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <table class="table table-borderless">
        <thead>
            <tr>
              <th>名前</th>
              <th>価格</th>
              <th>説明</th>
              <th></th>
            </tr>
        </thead>
        <tbody>
          <tr v-for="shopListItem in shopListItems">
            <td>{{ shopListItem.name }}</td>
            <td>{{ shopListItem.price }} G</td>
            <td>{{ shopListItem.description }}</td>
            <td><a class="action-link" @click="showPurchaseForm(shopListItem)">買う</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 購入モーダル -->
  <div class="modal fade" id="modal-item-purchase" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Purchase</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <!-- Edit purchase form -->
          <form class="form-horizontal" role="form">
            <!-- Date -->
            <div class="form-group">
              <label class="control-label">購入数を記載してください</label>
              <div style="max-width: 100px;">
                <input id="purchase-number" type="number" class="form-control" v-model="number">
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

</template>

<script>
  import $ from 'jquery';
  import axios from 'axios';
  export default {
    data() {
      return {
        shopListItems: [],
        purchaseForm: {
          item_id: '',
          price: '',
          number: '',
        },
        money: 0,
        price: 0,
        number: 0,
      }
    },
    mounted() { // DOMが呼ばれた際に実行するコード
      console.log('shop.vue');
      this.getShopList();
      this.getCurrentMoney();

      $('#modal-item-purchase').on('shown.bs.modal', () => {
        $('#purchase-number').focus();
      })

    },
    methods: {
      // 販売物一覧をlaravel側APIから取得する。
      getShopList() {
        axios.get('/api/game/rpg/shop/list')
          .then(response => {
            this.shopListItems = response.data;
          });
      },
      // 所持金をセーブデータから取得
      getCurrentMoney() {
        axios.get('/api/game/rpg/savedata')
          .then(response => {
            this.money = response.data.money;
          });
      },

      // 購入モーダルの表示
      // モーダルを開いた時の情報をjs側で定義したpurchaseFormに格納する。
      showPurchaseForm(shopListItem) {
        this.purchaseForm.item_id = shopListItem.id;
        this.purchaseForm.name = shopListItem.name;
        this.purchaseForm.price = shopListItem.price;
        $('#modal-item-purchase').modal('show');
      },

      // 支払確定処理
      paymentItem() {
        let form = {
          money: this.money,
          price: this.purchaseForm.price,
          number: this.number
        }

        axios['post']('/api/game/rpg/shop/payment', form)
          .then(response => {
            // 所持金更新
            this.getCurrentMoney();
            form.money = 0;
            form.price = 0;
            form.number = 0;

            // モーダルを閉じる
            $('#modal-item-purchase').modal('hide');

            // todo: 購入しましたと画面に出す

          })
          .catch(error => {
            console.log(error.response.data.error);
            // todo: エラーメッセージをモーダルに出す

          });

      },

    }
  }
</script>
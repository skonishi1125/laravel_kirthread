<style scoped>
  .action-link {
    cursor: pointer;
  }
</style>

<!-- v-if系はtemplateの内部に書く。 -->
<template>

  <div class="row" v-if="this.after_purchase_array.after_purchase_flag">
    <div class="col-sm-12" style="color: blue">
      <p>{{ after_purchase_array.name }} x {{ this.after_purchase_array.number }} を購入しました!</p>
    </div>
  </div>

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

</template>

<script>
  import $ from 'jquery';
  import axios from 'axios';
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
    mounted() { // DOMが呼ばれた際に実行するコード
      this.$store.dispatch('setScreen', 'menu');
      this.getShopList();
      this.getCurrentMoney();
      $('#modal-item-purchase').on('shown.bs.modal', () => {
        $('#purchase-number').focus();
      })

    },
    methods: {
      // ショップ販売物一覧をlaravelAPIから取得
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

      // 購入モーダル表示
      showPurchaseForm(shopListItem) {
        // アイテム情報をpurchaseForm配列に格納しておく。
        this.purchaseForm.item_id = shopListItem.id;
        this.purchaseForm.name = shopListItem.name;
        this.purchaseForm.price = shopListItem.price;

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
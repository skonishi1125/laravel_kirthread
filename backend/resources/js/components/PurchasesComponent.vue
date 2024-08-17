<style scoped>
  .action-link {
    cursor: pointer;
  }

  .m-b-none {
    margin-bottom: 0;
  }
</style>

<template>
  <div class="panel-body">
    <p class="m-b-none" v-if="purchases.length === 0">
      You Have not created any purchases.
    </p>
    <table class="table table-borderless m-b-none" v-if="purchases.length > 0">
      <thead>
        <tr>
          <th>Date</th>
          <th>Price</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="purchase in purchases">
          <td style="vertical-align: middle;">
            {{ purchase.date }}
          </td>
          <td style="vertical-align: middle;">
            {{ purchase.price }}
          </td>
          <td style="vertical-align: middle;">
            {{ purchase.description }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        purchases: [
          // 下記のデータをjsonから取得する
          // {
          //   date        : '2017-11-21',
          //   price       : '25',
          //   description : 'Dog Food'
          // },
          // {
          //   date        : '2017-11-21',
          //   price       : '50',
          //   description : 'Restaurant Bill'
          // },
          // {
          //   date        : '2017-11-20',
          //   price       : '37',
          //   description : 'Gasoline'
          // }
        ]
      }
    },
    mounted() { // DOMが呼ばれた際に実行するコード
      this.getPurchases();
    },
    methods: {
      getPurchases() {
        axios.get('/study/techbook/vue/chapter8_purchases')
          .then(response => {
            // console.log(response.data);
            this.purchases = response.data;
          });
      }
    }


  }
</script>

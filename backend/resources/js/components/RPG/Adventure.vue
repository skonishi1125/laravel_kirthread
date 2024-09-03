<style scoped>
  .action-link {
    cursor: pointer;
  }
</style>

<template>
  <div class="row">
    <div class="col-sm-12">
      <p>いきたい場所を選択してください</p>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <table class="table table-borderless">
        <thead>
            <tr>
              <th>名前</th>
              <th>難易度</th>
              <th></th>
            </tr>
        </thead>
        <tbody>
          <tr v-for="field in fieldList">
            <td>{{ field.name }}</td>
            <td>{{ field.difficulty }}</td>
            <td><a class="action-link" @click="startBattle(field.id)">行く</a></td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  export default {
    data() {
      return {
        fieldList:[],
      }
    },
    created() {
      // this.$store.dispatch('setScreen', 'menu');
      // ↑本来はこちらが必要だが、親側のMenu.vueでstateをmenuにしているので不要。
      // (通常adventure画面でreloadすると、stateがデフォルトのtitleに戻るためメニュー画面が出なくなる)
      this.getFieldList();
    },
    mounted() {
      console.log('Adventure.vue');
    },
    methods: {
      // フィールド一覧をlaravelAPIから取得
      getFieldList() {
        axios.get('/api/game/rpg/field/list')
          .then(response => {
            this.fieldList = response.data;
          });
      },
      startBattle(stageId) {
        this.$store.dispatch('setScreen', 'battle');
        this.$router.push('/game/rpg/battle');
        // this.$router.push(`/game/rpg/battle/${stageId}`);
      }

    }
  }
</script>
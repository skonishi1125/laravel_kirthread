<style scoped>
.action-link {
    cursor: pointer;
}
.sub-sucreen-text-space {
    padding: 10px 0px;
}

.weight-bold {
    font-weight: bold;
    font-size: 0.9rem;
    margin-bottom: 0.7rem;
}

.table-hoverable tbody tr:hover {
    background-color: #fdf6e3;
    transition: background-color 0.2s ease;
}

</style>

<template>
  <div class="container">
    <div class="row sub-sucreen-text-space">
      <div class="col-12">
        <div>
        <p>どこに向かおうか？</p>
        </div>
      </div>
    </div>

    <div class="row mt-3 sub-sucreen-main-space">
      <div class="col-12">
        <table class="table table-borderless table-hoverable">
          <thead>
              <tr>
                <th>フィールド名</th>
                <th>難易度</th>
                <th></th>
              </tr>
          </thead>
          <tbody>
            <tr v-for="field in fieldList">
              <td  class="weight-bold">{{ field.name }}</td>
              <td>{{ field.difficulty }}</td>
              <td>
                <a class="action-link weight-bold" @click="startFirstStageBattle(field.id)">行く</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
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
      startBattle(fieldId, stageId) {
        this.$store.dispatch('setScreen', 'battle');
        this.$router.push(`/game/rpg/battle/${fieldId}/${stageId}`);
      },
      startFirstStageBattle(fieldId) {
        this.$store.dispatch('setScreen', 'battle');
        this.$router.push(`/game/rpg/battle/${fieldId}/1`);
      }

    }
  }
</script>

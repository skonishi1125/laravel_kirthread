<style scoped>
  .action-link {
    cursor: pointer;
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
        <table class="table table-borderless">
          <thead>
              <tr>
                <th>フィールド名</th>
                <th>難易度</th>
                <th>ステージ</th>
              </tr>
          </thead>
          <tbody>
            <tr v-for="field in fieldList">
              <td>{{ field.name }}</td>
              <td>{{ field.difficulty }}</td>
              <!-- <td>
                <a v-for="stage_id in 5" :key="stage_id" class="action-link" @click="startBattle(field.id, stage_id)" style="margin:0 10px; display: inline-block; min-width: 30px;">
                  {{ field.id }}-{{ stage_id }}
                </a>
              </td> -->
              <td>
                <a class="action-link" @click="startFirstStageBattle(field.id)">行く</a>
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
        // this.$router.push('/game/rpg/battle');
        this.$router.push(`/game/rpg/battle/${fieldId}/${stageId}`);
      },
      startFirstStageBattle(fieldId) {
        this.$store.dispatch('setScreen', 'battle');
        // this.$router.push('/game/rpg/battle');
        this.$router.push(`/game/rpg/battle/${fieldId}/1`);
      }

    }
  }
</script>
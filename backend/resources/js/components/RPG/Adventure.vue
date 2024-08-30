<template>
  <div class="row">
    <div class="col-sm-12">
      <p>いきたい場所を選択してください</p>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <ul v-for="field in fieldList">
        <li><button @click="startBattle(field.id)">{{ field.name }}</button></li>
      </ul>
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
    mounted() { // DOMが呼ばれた際に実行するコード
      console.log('Adventure.vue');
      this.getFieldList();
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
        this.$router.push(`/game/rpg/battle/${stageId}`);
      }

    }
  }
</script>
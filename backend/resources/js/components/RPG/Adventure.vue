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
    cursor: pointer;
    background-color: #fdf6e3;
    transition: background-color 0.2s ease;
}

.cleared-row {
    opacity: 0.6;
    font-style: italic;
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
              </tr>
          </thead>
          <tbody>
            <tr v-for="field in fieldList" :class="{ 'cleared-row': field.is_cleared }" @click="showConfirmModal(field)">
              <td  class="weight-bold">{{ field.name }}</td>
              <td>{{ field.difficulty }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <!-- 確認モーダル -->
  <teleport to="body">
    <div class="modal fade" id="modal-confirm-field" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title"><b>確認</b></h6>
            <button type="button" class="close" data-dismiss="modal" aria-rabel="Close"><span aria-hidden="true">&times;</span></button>
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
                      {{ confirmModalFIeld.name }}に向かいます。
                  </label>
                </div>
            </form>
          </div>
          <!-- Modal Actions -->
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
            <button type="button" class="btn btn-primary" @click="startFirstStageBattle(confirmModalFIeld.id)">出発する</button>
          </div>
        </div>
      </div>
    </div>
  </teleport>

</template>

<script>
  import $ from 'jquery';
  import axios from 'axios';
  export default {
    data() {
      return {
        fieldList:[],
        error_message: '',
        confirmModalFIeld:{
          id: null,
          name: '',
        }
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

      /**
       * セーブデータに応じたフィールドの一覧を取得する。
       */
      getFieldList() {
        axios.get('/api/game/rpg/field/list')
          .then(response => {
            this.fieldList = response.data;
          });
      },

      /**
       * 冒険の行き先を確認するモーダルを表示する。
       */
      showConfirmModal(selectedField) {
        this.confirmModalFIeld.id = selectedField.id;
        this.confirmModalFIeld.name = selectedField.name;
        $('#modal-confirm-field').modal('show');
      },

      /**
       * 選択したフィールドの、最初のステージに遷移する。
       */
      startFirstStageBattle(fieldId) {
        // 画面遷移前に、モーダルを閉じておく
        $('#modal-confirm-field').modal('hide');
        this.$store.dispatch('setScreen', 'battle');
        this.$router.push(`/game/rpg/battle/${fieldId}/1`);
      }

    }
  }
</script>

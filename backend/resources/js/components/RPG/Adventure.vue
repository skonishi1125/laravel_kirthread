<style scoped>
.action-link {
    cursor: pointer;
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

.color-red {
    color: red;
}

.color-blue {
    color: blue;
}
</style>

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

    <div v-if="status == 'selectable'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>どこに向かおうか。</small></p>
            <hr>
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
                <td class="weight-bold" :class="{ 'color-blue':field.id == 11, 'color-red':field.id == 10 }">{{ field.name }}</td>
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
  
              <form class="form-horizontal" role="form">
                  <div class="form-group">

                    <!-- ステージごとに個別の文章を出す -->
                    <div v-if="confirmModalFIeld.id == 11">
                      <label class="control-label">
                        <p style="color: gray;">
                          怪しい書物通りに耕作地の地下を探索したところ、青白く光るポータルを見つけた...
                        </p>
                        入ってみますか？
                        <br>
                        <br>
                        <small>
                          <b>※戦闘画面に遷移します。<br>準備ができている場合、「出発する」を選択してください。
                          </b>
                        </small>
                      </label>
                    </div>

                    <div v-else-if="confirmModalFIeld.id == 10">
                      <label class="control-label">
                        <p style="color: gray;">
                          長い旅の末に、とうとう伝承にまつわる城に辿り着いた。
                        </p>
                        探索を開始しますか？
                        <br>
                        <br>
                        <small>
                          <b>※戦闘画面に遷移します。<br>準備ができている場合、「出発する」を選択してください。
                          </b>
                        </small>
                      </label>
                    </div>

                    <div v-else>
                      <label class="control-label">
                        {{ confirmModalFIeld.name }}に向かいます。
                        <br>
                        <br>
                        <small>
                          <b>※戦闘画面に遷移します。<br>準備ができている場合、「出発する」を選択してください。
                          </b>
                        </small>
                      </label>
                    </div>
                  </div>
              </form>
            </div>
            <!-- Modal Actions -->
            <div class="modal-footer">
              <button type="button" class="btn btn-info" @click="startFirstStageBattle(confirmModalFIeld.id)">出発する</button>
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
        fieldList:[],
        confirmModalFIeld:{
          id: null,
          name: '',
        }
      }
    },
    created() {
      // 初期値をセット 
      this.$store.dispatch('setMenuAdventureStatus', 'start'); 
      // this.$store.dispatch('setScreen', 'menu');
      // ↑本来はこちらが必要だが、親側のMenu.vueでstateをmenuにしているので不要。
      // (通常adventure画面でreloadすると、stateがデフォルトのtitleに戻るためメニュー画面が出なくなる)
      this.getFieldList();
    },
    mounted() {
      console.log('Adventure.vue');
    },
    computed: {
      // menu.shop.status == 'start' の値がcomponentで呼べるようになる
      ...mapState({
        status: state => state.menu.adventure.status,
      }),
    },
    methods: {

      /**
       * セーブデータに応じたフィールドの一覧を取得する。
       */
      getFieldList() {
        axios.get('/api/game/rpg/field/list')
          .then(response => {
            this.fieldList = response.data;
            this.$store.dispatch('setMenuAdventureStatus', 'selectable');
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

<style>
.answer-option-form {
  margin-top: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
}

.answer-option-form button {
  text-align: center;
  min-width: 550px;
}

.btn-ending-style {
  color: white;
  background-color: #e879e5;
  border-color: #be52bb;
}

.btn-ending-style:hover {
  color: white;
  background-color: #8e448b;
  border-color: #943991;
}

.btn-ending-style:active {
  background-color: #8e448b !important;
  border-color: #943991 !important;
}


</style>


<template>
  <div v-if="ending.status == 'start'">
    <div class="row">
      <div class="col-12">
        <p>
          <hr>
          読み込み中...
        </p>
      </div>
      <br>
      <div class="col-12" style="text-align:right; margin-top: 30px;">
      </div>
    </div>
  </div>

  <div v-if="ending.status == 'loaded'">
    <div v-if="ending.view == 'end_monologue_1'">
      <div class="row">
        <div class="col-12">
          <hr>
          <p>
            「伝承に語られる魔物の城を見つけ、ついに解き明かしたパーティが現れたらしいぞ！」
          </p>
          <p>
            その話は我々が口にするまでもなく、既に街中に響き渡っている。<br>
            広場の店々は何かと理由をつけては商魂逞しくセールを打ち出し、<br>
            また調査ギルドはこれまでで一番の繁忙期を迎えたかのように、仕事が後を絶たないように見えた。
          </p>
          <p>
            「なあ！」<br>
            とある冒険者たちが声をかけてきた。<br>
            「今回もあんたらなんだろ？ 沼地や暗闇の森だって、結局最初に踏破してギルドに情報を渡したのはあんたらだったじゃないか。」
          </p>
          <hr>
          <p>
            我々の手元には、その証明となる金銀財宝がすでにある。どうしようか？
          </p>
        </div>
        <br>
        <div class="col-12">
          <div class="answer-option-form">
            <button class="btn btn-outline-info" @click="nextEndMonologue2(0)">私たちだと伝える</button>
            <div v-if="is_cleared_vast_expanse === false">
              <button class="btn btn-outline-info" @click="nextEndMonologue2(1)">黙っている</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="ending.view == 'end_monologue_2'">
      <div class="row">
        <div class="col-12">
          <hr>
          <div v-if="end_monologue_2_pattern === 0">
            <p>「やっぱり、あんたらだったか！」 </p>
          </div>
          <div v-if="end_monologue_2_pattern === 1">
            <p>
              「何黙ってんだよ！だったら、その荷物にがっぽり詰まった宝はなんだってんだ？」
            </p>
          </div>
          <p>
            古城の探索を成し遂げたという事実は瞬く間に広がる。<br>
            街の人々は驚きと羨望とを入り混じらせ、こちらを見つめている。
          </p>
          <p>
            我々の手には、確かに金銀財宝がある。<br>
            その輝きはただの富にとどまらず、この大陸の運命をも左右する重みを帯びていた。<br>
            今ならばーーどんな願いも、叶えられるかもしれない。
          </p>
          <hr>

          <p>この財を使って、望むことはー</p>

        </div>
        <br>
        <div class="col-12">
          <div v-if="this.is_cleared_vast_expanse == true" class="answer-option-form">
            <button class="btn btn-ending-style" @click="openConfirmModal(3)">{{ this.ending_button_pattern['ending_3'] }}</button>
            <button class="btn btn-danger" @click="openConfirmModal(4)">{{ this.ending_button_pattern['ending_4'] }}</button>
          </div>
          <div v-else class="answer-option-form">
            <button class="btn btn-primary" @click="openConfirmModal(1)">{{ this.ending_button_pattern['ending_1'] }}</button>
            <button class="btn btn-success" @click="openConfirmModal(2)">{{ this.ending_button_pattern['ending_2'] }}</button>
          </div>

        </div>
      </div>
    </div>

    <div v-if="ending.view == 'ending_1'">
      <div class="row">
        <div class="col-12">
          <hr>
          <p>エンディング1</p>
          <hr>
        </div>
        <br>
        <div class="col-12">
          <div class="answer-option-form">
            <button class="btn btn-primary">ゲームクリア！</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="ending.view == 'ending_2'">
      <div class="row">
        <div class="col-12">
          <hr>
          <p>エンディング2</p>
          <hr>
        </div>
        <br>
        <div class="col-12">
          <div class="answer-option-form">
            <button class="btn btn-primary">ゲームクリア！</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="ending.view == 'ending_3'">
      <div class="row">
        <div class="col-12">
          <hr>
          <p>エンディング3</p>
          <hr>
        </div>
        <br>
        <div class="col-12">
          <div class="answer-option-form">
            <button class="btn btn-primary">ゲームクリア！</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="ending.view == 'ending_4'">
      <div class="row">
        <div class="col-12">
          <hr>
          <p>エンディング4</p>
          <hr>
        </div>
        <br>
        <div class="col-12">
          <div class="answer-option-form">
            <button class="btn btn-primary">ゲームクリア！</button>
          </div>
        </div>
      </div>
    </div>

  </div>


  <!-- ステータス詳細モーダル -->
  <teleport to="body">
    <div class="modal fade" id="modal-ending-confirm" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title"><b>確認画面</b></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <p>以下の選択でよろしいですか？</p>
            <div>
              <span v-if="this.ending_pattern == 3" style="color:#e879e5;">
                <small><b>{{ this.modalContent.button_title }}</b></small>
              </span>
              <span v-else-if="this.ending_pattern == 4" style="color:red">
                <small><b>{{ this.modalContent.button_title }}</b></small>
              </span>
              <span v-else>
                <small><b>{{ this.modalContent.button_title }}</b></small>
              </span>
            </div>
          </div>

          <div class="modal-footer book-modal-footer">
            <span v-if="this.ending_pattern == 4">
              <button type="button" class="btn btn-outline-primary btn-sm" @click="closeConfirmModal">思い直す</button>
            </span>
            <span v-else>
              <button type="button" class="btn btn-outline-danger btn-sm" @click="closeConfirmModal">考え直す</button>
            </span>

            <span v-if="this.ending_pattern == 3">
              <button type="button" class="btn btn-ending-style btn-sm" @click="transitionEnding">選択する</button>
            </span>
            <span v-else-if="this.ending_pattern == 4">
              <button type="button" class="btn btn-danger btn-sm" @click="transitionEnding">選択する</button>
            </span>
            <span v-else>
              <button type="button" class="btn btn-secondary btn-sm" @click="transitionEnding">選択する</button>
            </span>
          </div>

        </div>
      </div>
    </div>
  </teleport>

</template>

<script>
  import $ from 'jquery';
  import { mapState } from 'vuex';
  import axios from 'axios';
  export default {
    data() { // script内で使用する変数を定義する。
      return {
        is_cleared_vast_expanse: false,
        end_monologue_2_pattern: null, // 0: 「私たちだと伝える」 or 1: 「黙っている」
        ending_pattern: null, //1, 2, 3, 4
        ending_button_pattern: {
          'ending_1': '自分たちと街のために使い、この街を魔物の手から退けるようにする',
          'ending_2': '魔物との交流のために使い、人間と魔物の共存する未来を目指す',
          'ending_3': '魔物との交流のために使い、人間と魔物の共存する未来を目指す',
          'ending_4': '自分達のためだけに使う',
        },
        modalContent: {
          'button_title': '',
        }
      }
    },
    computed: {
    ...mapState(['screen']),
    ...mapState(['ending']),
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
      this.$store.dispatch('setScreen', 'ending');
      this.$store.dispatch('setEndingView', 'end_monologue_1'); // ブラウザバックした時などに、最初の画面に戻しておく
      this.loadCanBeClearVastExpanse();
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log('Ending.vue', this.screen, this.ending);
      this.$store.dispatch('setEndingStatus', 'start');
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
      /**
       * ユーザーのセーブデータが隠し面をクリアしているのかのチェック
       * 
       * また、遷移してきたユーザーがURLベタ打ちでないかどうかもチェックする
       */
      loadCanBeClearVastExpanse() {
        console.log('loadCanBeClearVastExpanse');
        axios.get('/api/game/rpg/ending/can_be_clear_vast_expanse')
          .then(response => {
            console.log(`response.data: ${response.data['is_cleared_vast_expanse']}`);
            this.is_cleared_vast_expanse = response.data['is_cleared_vast_expanse'];
            this.$store.dispatch('setEndingStatus', 'loaded');
        })
        .catch(error => {
          console.log(`未クリアデータ`);
          this.$router.push('/game/rpg');
        });
      },

      /**
       * 「私たちだと伝える」 or 「黙っている」時のアクション
       */
      nextEndMonologue2(pattern) {
        this.end_monologue_2_pattern = pattern;
        this.$store.dispatch('setEndingView', 'end_monologue_2');
      },

      openConfirmModal(pattern) {
        this.ending_pattern = pattern;
        console.log(this.ending_pattern);
        this.modalContent.button_title = this.ending_button_pattern['ending_'+pattern];
        $('#modal-ending-confirm').modal('show');
      },

      closeConfirmModal() {
        $('#modal-ending-confirm').modal('hide');
      },

      transitionEnding() {
        console.log('transitionEnding');
        // クリアしたことをAPI経由でsavedataに保存。
        axios.post('/api/game/rpg/ending/store/clear')
          .then(response => {
            $('#modal-ending-confirm').modal('hide');
            console.log(`response.data: ${response.data['is_game_cleared']}`);
            this.$store.dispatch('setEndingView', 'ending_'+this.ending_pattern);
        })
        .catch(error => {
          console.log(`クリアデータの保存に失敗しました。`);
          this.$router.push('/game/rpg');
        });
      }

    }
  }
</script>

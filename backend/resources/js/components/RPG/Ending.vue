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
  width: 550px;
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
            「伝承に語られる魔物の城を見つけ、ついに解き明かした冒険者たちが現れたらしいぞ！」
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
        <div class="col-12" style="font-weight: bold; font-size: 0.95em;">
          <div style="padding: 0px 20px; background-color: #e1e8eb;;">
            <hr>
            <p>
              我々が得た富は三人で分け合ったとしても、一生を贅沢に暮らすには余りあるほどだった。 <br>
              そのため残された富を、街の復興のために投じることとした。
            </p>
            <p>
              広場には笑い声が絶えず、瓦礫の残る路地には陽が射し込みはじめた。<br>
              調査ギルドは新たな設備を整え、より安全に大陸を探索できるようになった。<br>
              かつて危険と隣り合わせであった探索も、今では非戦闘員でさえ危険に悩まされることは少ない。<br>
            </p>
            <p>
              要は、平和になったのだ。
            </p>
            <p>
              結果的にこの選択は正しかったのかもしれない。<br>
              人々が魔物の手に脅かされぬよう願う心だけは確かにあった。だからこの道を進むことを決意したのだ。<br>
            </p>
            <p>
              かつての英雄たちも富を得た時、似たような選択を迫られたのだろうか。
            </p>
            <hr>
          </div>
        </div>
        <div class="col-12 my-5">
          <p>―エンド1: 冒険者たちの決断―</p>
        </div>
        <div class="col-12">
          <div class="answer-option-form">
            <button class="btn btn-primary" @click="transitionAfterView">ゲームクリア！</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="ending.view == 'ending_2'">
      <div class="row">
        <div class="col-12" style="font-weight: bold; font-size: 0.95em;">
          <div style="padding: 0px 20px; background-color: #e1ebe1;">
            <hr>
            <p>
              得た富の使い道を考えるうちに、我々の心はひとつの疑問へと辿り着いた。<br>
              一体いつから、人間と魔物は争い合うようになったのだろうか。<br>
              なぜ、そうならざるを得なかったのだろうか。共に生きることは、本当に不可能なのだろうか。
            </p>
            <p>
              我々は魔物との共存を模索し、その施策に富を投じることを決意した。<br>
              調査は難航を極め、未だ目立った成果は得られていない。<br>
              街の人々も、せっかくの財を魔物のために費やす我々を半ば呆れた目で見ているようにも見える。
            </p>
            <p>
              甘い考えだっただろうか？<br>
              人と魔物は、伝承に語られる時代から互いを拒み続けてきたのだ。<br>
              理屈ではなく、本能として分かり合えないのだろうか。
            </p>
            <p>
              それでも。<br>
              もしも魔物と人間が共に歩める世界があるのなら、争いは終結し、真の平和が大陸に訪れるはずだ。<br>
              かつての英雄たちは富を得た時、この選択肢を選ばなかったのだろうか。
            </p>
            <hr>
          </div>
        </div>
        <div class="col-12 my-5">
          <p>―エンド2: 誰も知らない世界を求めて―</p>
        </div>
        <div class="col-12">
          <div class="answer-option-form">
            <button class="btn btn-success" @click="transitionAfterView">ゲームクリア！</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="ending.view == 'ending_3'">
      <div class="row">
        <div class="col-12" style="font-weight: bold; font-size: 0.95em;">
          <div style="background-color: #f5eff4; padding: 0px 20px; color: #4f4c4c;">
            <hr>
            <p>
              得た富の使い道を考えるうちに、我々の心はひとつの疑問へと辿り着いた。<br>
              一体いつから、人間と魔物は争い合うようになったのだろうか。<br>
              なぜ、そうならざるを得なかったのだろうか。共に生きることは、本当に不可能なのだろうか。<br>
            </p>
            <p>
              我々は魔物との共存を模索し、その施策に富を投じることを決意した。<br>
              その調査は難航を極めるに違いないだろう。<br>
            </p>
            <p>
              幸いにも我々には名声があり、魔物を助けることに疑問を持った街の人々を説得するに足る言葉を持ち合わせている。<br>
              乏しい証拠だったが、街やギルドの人々たちは我々の言葉に耳を傾けてくれ、やがて尊重してくれた。
            </p>
            <p>
              甘い考えに感じるだろうか。我々はそうは思わない。<br>
              伝承では、人間と魔物は互いを拒み続けてきた。だがしかし、その伝承自体が欺瞞である可能性があるのだ。<br>
              その上、人間を助ける魔物が実在することも知っている。
            </p>
            <p>
              であれば人間と魔物が共に歩める世界という、真の歴史を刻み直してみせよう。
            </p>
            <p>
              もしも生きているうちにそんな世界が訪れたなら、今度は彼らと肩を並べ、未知なる大陸を旅してみたい。<br>
              理由は不要だろう。我々は英雄ではなく、冒険者なのだから。
            </p>
            <hr>
          </div>
        </div>
        <div class="col-12 my-5">
          <p style="font-weight: bold; color: #df71dd;">―エンド3(TRUE): 刻まれた確かな伝承―</p>
        </div>
        <div class="col-12">
          <div class="answer-option-form">
            <button class="btn btn-ending-style" @click="transitionAfterView">ゲームクリア！</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="ending.view == 'ending_4'">
      <div class="row">
        <div class="col-12" style="font-weight: bold; font-size: 0.95em;">
          <div style="  background-color: #301717; padding: 0px 20px; color: #ff7c7c;">
            <hr>
            <p>
              我々は得た富は三人で分け合うこととした。<br>
              一生を贅沢に過ごすには余りある財宝。 伝承通りのことを成し遂げたという名声までも手に入れた。
            </p>
            <p>
              我々はその名誉を誇示するように、古き城を新たな都と定めることとした。<br>
              周辺を彷徨う魔物どもは目障りでしかない。力の差は圧倒的であり、討伐は対した苦にはならなかった。<br>
              その後余りある富を注ぎ込み、現在の拠点から城へ続く壮大な街道を築き上げ、人々を呼び寄せた。<br>
              寂れた城下町はたちまち復興し、かつての都を凌ぐ勢いで繁栄を極めた。ここは人間たちの大陸となったのだ。
            </p>
            <p>
              人々は我々を「伝承の英雄の再来」と讃える。<br>
              何もせずとも富は溢れ、誰一人として逆らう者はいない。<br>
              そう――我々こそ、伝承に語られた英雄そのものとなったのだ。
            </p>
            <p>
              その伝承が欺瞞であることなど、誰も疑うことはできない。真実を追求した、あの人間は既にこの世にいないのだろう。<br>
              伝承の真実を知る者はもはや我々だけだ。
            </p>
            <p>
              欺瞞を隠し通す道義心はいつからか消え失せた。<br>
              栄光の座に浸り続けることで得られる、この快楽を手放すことなどできはしない。
            </p>
            <hr>
          </div>
        </div>
        <div class="col-12 my-5">
          <p style="font-weight: bold; color:red">―エンド4(BAD): 英雄の後継者―</p>
        </div>
        <div class="col-12">
          <div class="answer-option-form">
            <button class="btn btn-danger" @click="transitionAfterView">ゲームクリア！</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="ending.view == 'after_1'">
      <div class="row">
        <div class="col-12" style="padding: 20px; text-align: center;">
          <div>
            <p><b>Epic Liquidation</b></p>
            <div style="font-size: 0.95em; color: gray; font-style: italic;">
              <ul style='list-style: none; padding-left: 0;'>
                <li> ストーリー・シナリオ: 降諏かあ</li>
                <li> キャラクター・敵・背景イラスト: 降諏かあ</li>
                <li>UI/UXデザイン・フロントエンド(Vue.js): 降諏かあ</li>
                <li>DB設計・サーバーサイド(Laravel): 降諏かあ</li>
                <li>インフラ構築・運用: 降諏かあ</li>
                <li>デバッグ・テストプレイ他: 降諏かあ, 本サイト利用者の皆さん</li>
              </ul>
            </div>
            <p class='text-muted' style='font-size: 0.9em;'>
              © 2025 
              <a href='https://x.com/skirplus' target='_blank'>降諏かあ</a>. All rights reserved.<br>
              <br>
              本作の権利はすべて作者に帰属します。自作発言や、作者を偽る形での利用・転載はお控えください。<br>
              All rights to this game and its content are reserved by the creator.<br>
              Unauthorized reproduction, redistribution, or misrepresentation of authorship is strictly prohibited.
            </p>
            <hr>
            <p>
              <b>ここまでプレイいただいて、ありがとうございました！</b>
            </p>
          </div>
        </div>

        <div class="col-12" style="padding: 20px; text-align: center;">
          <p style="font-size: 0.9em; color:blue">
            ★本作にエンディングは4種類存在します(ノーマル2つ, その他2つ)。<br>
            中心広場にある図書館の、「歴史神話学」の書籍をこまめにチェックしてみましょう。
          </p>
        </div>

        <div class="col-12 my-5">
          <div class="answer-option-form">
            <button class="btn btn-outline-success" @click="returnTitle">タイトルに戻る</button>
          </div>
        </div>
      </div>
    </div>

    <!-- TODO: backgrondにimageとかをつけると良さそうである -->
    <div v-if="ending.view == 'after_2'">
      <div class="row">
        <div class="col-12" style="padding: 20px; text-align: center;">
          <div>
            <p><b>Epic Liquidation</b></p>
            <div style="font-size: 0.95em; color: gray; font-style: italic;">
              <ul style='list-style: none; padding-left: 0;'>
                <li> ストーリー・シナリオ: 降諏かあ</li>
                <li> キャラクター・敵・背景イラスト: 降諏かあ</li>
                <li>UI/UXデザイン・フロントエンド(Vue.js): 降諏かあ</li>
                <li>DB設計・サーバーサイド(Laravel): 降諏かあ</li>
                <li>インフラ構築・運用: 降諏かあ</li>
                <li>デバッグ・テストプレイ他: 降諏かあ, 本サイト利用者の皆さん</li>
              </ul>
            </div>
            <p class='text-muted' style='font-size: 0.9em;'>
              © 2025 
              <a href='https://x.com/skirplus' target='_blank'>降諏かあ</a>. All rights reserved.<br>
              <br>
              本作の権利はすべて作者に帰属します。自作発言や、作者を偽る形での利用・転載はお控えください。<br>
              All rights to this game and its content are reserved by the creator.<br>
              Unauthorized reproduction, redistribution, or misrepresentation of authorship is strictly prohibited.
            </p>
            <hr>
            <p>
              <b>ここまでプレイいただいて、ありがとうございました！</b>
            </p>
          </div>
        </div>

        <div class="col-12 my-5">
          <div class="answer-option-form">
            <!-- TODO: モーダルを開いて感謝イラストを出すとかする -->
            <button class="btn btn-outline-info" style="width: 100px">おまけ</button>
            <button class="btn btn-outline-success" @click="returnTitle">タイトルに戻る</button>
          </div>
        </div>
      </div>
    </div>

  </div>

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
      },

      /**
       * あとがき遷移 (trueの画面だけ、何かいい感じの画像を載せられたらいいな〜)
       */
      transitionAfterView() {
        console.log('transitionAfterView');
        if (this.ending_pattern == 1 || this.ending_pattern == 2) {
          this.$store.dispatch('setEndingView', 'after_1');
        } else {
          this.$store.dispatch('setEndingView', 'after_2');
        }
      },

      returnTitle() {
        this.$store.dispatch('setScreen', 'title');
        this.$router.push('/game/rpg');
      }

    }
  }
</script>

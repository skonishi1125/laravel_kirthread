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
        <div class="col-12" style="font-weight: bold; font-size: 0.95em;">
            <div style="padding: 0px 20px; background-color: #e1e8eb;">
                <hr>
                <p>
                  「伝承に語られる魔物の城を散策し、ついに解き明かした冒険者たちが現れたらしいぞ！」
                </p>
                <p>
                  調査ギルドの出入り口には列ができ、報告や依頼でひっきりなしに賑わっていた。<br>
                  その噂は我々が口にするまでもなく、既に街中に響き渡っている。<br>
                  広場の店々は何かと理由をつけては銘打って値札を吊り替え商魂逞しくセールを打ち出し、<br>
                  また調査ギルドはこれまでで一番の繁忙期を迎えたかのように、報告や依頼でひっきりなしに賑わう様子が伺える。
                </p>
                <p>
                  「なあ！」<br>
                  とある冒険者たちが声をかけてきた。<br>
                  「今回もあんたらなんだろ？ 沼地や常夜の森だって、結局最初に踏破してギルドに情報を伝えたのはあんたらだったじゃないか。」
                </p>
                <hr>
            </div>
        </div>
        <br>
        <div class="col-12 my-3">
          <p>
            我々の手元には、その証明となる金銀財宝がある。どうしようか？
          </p>
        </div>
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
        <div class="col-12" style="font-weight: bold; font-size: 0.95em;">
          <div style="padding: 0px 20px; background-color: #e1e8eb;">
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
                古城を踏破したという報せは、瞬く間に街を包み込んだ。<br>
                街の人々は驚きと羨望とを入り混じらせ、こちらを見つめている。
              </p>
              <p>
                我々の手には、確かに金銀財宝がある。<br>
                それは古城の上層、祭壇の奥深くで幾多の危険と引き換えに手に入れたものだ。<br>
                無我夢中になり追い求めていたそれの重みを、ようやく知ることになったのだ。
              </p>
              <p>
                賑わう広場の喧騒の中で、ほんの一瞬、自分たちに静寂が降りた気がした。<br>
                旅の記憶が、遠い風のように脳裏を掠めていく。<br>
                草原や海、寂れた沼地。各地を巡ることで辿り着いた伝承に伝わりし古き城。
              </p>
              <p> 
                我々の手には、すべての答えが詰まっている。 <br>
                この輝きはただの富にとどまらず、この大陸の運命をも左右する重みを帯びているように感じる。<br></br>
                どう扱うかで、この世界の形すら変わってしまうだろう。 
              </p>
              <p>
                そう、今ならばーーどんな願いも叶えられるかもしれない。
              </p>
              <hr>
          </div>
        </div>
        <div class="col-12 my-3">
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
          <div style="padding: 0px 20px; background-color: #d5e3ff;">
            <hr>
            <p>
              我々が手にした富は、三人で分け合ってもなお余るほどの大きなものだった。<br>
              それぞれが胸に抱いていた目的を果たしたあと、残る富をどうするかを話し合うこととした。<br>
              その結果、街の復興や人々の暮らしのために投じることにした。
            </p>
            <p>
              広場には笑い声が絶えず、瓦礫の残る路地には陽が射し込みはじめた。<br>
              調査ギルドは新たな設備を整え、より安全に大陸を探索できるようになった。<br>
              かつて危険と隣り合わせであった探索も、今では非戦闘員でさえ危険に悩まされることは少ない。<br>
            </p>
            <p>
              要は、平和になったのだ。<br>
              話し合うことで選んだこの道は、想像しているよりずっと気分のいいものだった。<br>
              人々から感謝される日々は悪くない。
            </p>
            <p>
              ただしこの選択が本当に正しかったのか知る者はいない。<br>
              ...かつての英雄たちは富を得たとき、どう感じたのだろう。<br>
              英雄として恥じぬ振舞いを見せるため、人々の復興を目指し興国を選んだのだろうか。
            </p>
            <hr>
          </div>
        </div>
        <div class="col-12 my-5">
          <p style="font-style: italic;">Ending(1): 平穏の果てに</p>
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
              得た富の使い道を話し合ううちに、我々はそれぞれの目的を思い出していた。<br>
              誰かは名声のために、誰かは故郷のために、誰かはただ静かに余生を過ごすために。<br>
              けれど、どんな使い道を思い描いても、胸の奥に微かな引っかかりが残った。
            </p>
            <p> 
              いつからだろう。 人と魔物が互いを拒み合うようになったのは。<br> 
              彼らとは本当に争わなければ生きられぬ世界なのだろうか。その問い掛けは次第に頭から離れなくなっていった。
            </p>
            <p>
              我々は悩み抜いた末に、魔物との共存を模索する道を選択した。そのために富を使い手段を探り始めた。<br>
              調査は難航を極め、未だ目立った成果は得られていない。<br>
              街の人々も折角の財産を魔物のために費やす我々を半ば呆れた目で見ているようにも見える。
            </p>
            <p>
              人と魔物はあまりにも長く憎しみを重ねてきた。それは伝承からも読み取れる、紛れもない事実だ。<br>
              理屈ではなく本能として分かり合えないのかもしれない。
            </p>
            <p>
              甘い考えだっただろうか。<br>
              恐れ合わずに生きられる世界を夢見て選んだこの道は、結局、誤りだったのかもしれない。<br>
              ...英雄たちは富を手にしたとき、恐らく共に歩む未来を捨てたのだろう。
            </p>
            <hr>
          </div>
        </div>
        <div class="col-12 my-5">
          <p style="font-style: italic;">Ending(2): 僅かな祈りと共に</p>
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
              我々は魔物との共存を模索し、その施策に富を投じることを決意した。
              その調査は難航を極めるに違いないだろう。<br>
              だが幸いにも、我々には名声があり、そして人を助ける魔物の存在という揺るぎない証があった。<br>
              初めは半信半疑だった人々も、我々の言葉に耳を傾け、理解を示してくれた。
            </p>
            <p>
              伝承では、人と魔物は相容れぬ存在として語られてきた。<br>
              だが、我々は知っている。伝承そのものが誰かが編んだ物語に過ぎなかったのかもしれないということを。<br>
            </p>
            <p>
              ならば、人と魔物が戦いの末に手を取りあったという真の伝承をこの大陸に刻んでみせる。<br>
              それこそが、我々が冒険の果てにこの地で見出した答えになる。
            </p>
            <p>
              もしも生きているうちにそんな世界が訪れたなら、彼らと肩を並べて未知なる大陸を共に旅してみたい。<br>
              理由は不要だろう。我々は英雄ではなく、冒険者なのだから。
            </p>
            <hr>
          </div>
        </div>
        <div class="col-12 my-5">
          <p style="font-weight: bold; color: #df71dd; font-style: italic;">Ending(true): 確かなる伝承</p>
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
          <div style="  background-color: #dbcaca; padding: 0px 20px; color: #430f0f;">
            <hr>
            <p>
              我々は得た富は三人で分け合うこととした。一生を贅沢に過ごすには余りある富、<br>
              伝承通りの偉業を成し遂げたという名声、そして冒険で得た力。正当な報いであるそれらは、我々の心を蝕んでいった。<br>
              このままでは、あの日決心した冒険心そのものが薄れていくことも理解しているつもりではあった。<br>
            </p>
            <p>
              とある日、我々は古き城を新たな都と定めることとしていた。<br>
              周辺を彷徨う魔物は目障りと感じたため、討伐することとした。我々の前では苦にはならなかった。<br>
              余りある富を注ぎ込み、現在の拠点から城へ続く壮大な街道を築き上げ、人々を呼び寄せた。<br>
              寂れた城下町はたちまち復興し、かつての都を凌ぐ勢いで繁栄を極めた。気づけばここは、人間たちの大陸となっていた。
            </p>
            <p>
              人々は我々を「英雄の再来」と讃える。<br>
              何もせずとも富は溢れるようになり、誰一人として逆らう者はいない。気づけば我々は、伝承に語られた英雄そのものとなった。
            </p>
            <p>
              伝承には、人と魔物が相容れることは無い旨が記されていた。<br>
              それが真実ではないことを我々は知っている。実際にこの目で、人を助ける魔物が存在することを確認した。<br>
            </p>
            <p>
              しかし、いまさら誰にこの事実を伝えるというのか。<br>
              人々は我々を英雄と讃え、富と名声を惜しみなく捧げてくれる。それはあまりにも甘美で心地が良い。<br>
              伝承の欺瞞を追求した日記の人間も、恐らくもうこの世にはいないのだろう。もはや事実を知る者はいない。
            </p>
            <p>
              欺瞞を隠し通す道義心は、いつからか消え失せてしまった。<br>
              栄光の座に浸り続けることで得られる底知れぬ快楽。もはや、手放すことはできない。
            </p>
            <hr>
          </div>
        </div>
        <div class="col-12 my-3">
          <p style="font-weight: bold; color:#430f0f; font-style: italic;">Ending(3): 英雄の後継者</p>
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
                <li>UI/UXデザイン・フロントエンド実装(Vue.js): 降諏かあ</li>
                <li>DB設計・サーバーサイド実装(Laravel): 降諏かあ</li>
                <li>インフラ構築・運用(AWS): 降諏かあ</li>
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
                <li>UI/UXデザイン・フロントエンド実装(Vue.js): 降諏かあ</li>
                <li>DB設計・サーバーサイド実装(Laravel): 降諏かあ</li>
                <li>インフラ構築・運用(AWS): 降諏かあ</li>
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

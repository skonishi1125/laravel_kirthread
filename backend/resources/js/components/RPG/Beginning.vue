<style>
.next-page-form {
  margin-top: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
}

.next-page-button {
  text-align: center;
  width: 300px;
}

/* ベースの7角形 */
.parameter-base-wrapper {
  width: 200px;
  height: 200px;
  background-color: rgba(34, 129, 51, 0.5);
  clip-path: polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%);
}

.parameter-role {
  width: 200px;
  height: 200px;
}
.parameter-role.paint {
  background-color: rgba(255, 49, 49, 0.5);
}

.parameter-role.striker {
  clip-path: polygon(50% 10%, 66% 33%, 100% 60%, 60% 65%, 38% 65%, 0% 60%, 33% 33%);
}
.parameter-role.medic {
  clip-path: polygon(50% 20%, 84% 25%, 75% 56%, 65% 81%, 28% 92%, 30% 54%, 33% 36%);
}
.parameter-role.paladin {
  clip-path: polygon(50% 0%, 66% 33%, 82% 60%, 75% 100%, 38% 74%, 30% 57%, 33% 33%);
}
.parameter-role.mage {
  clip-path: polygon(50% 30%, 90% 20%, 60% 56%, 59% 77%, 25% 100%, 15% 58%, 33% 33%);
}
.parameter-role.ranger {
  clip-path: polygon(49% 14%, 79% 28%, 84% 58%, 60% 70%, 32% 80%, 9% 58%, 33% 33%);
}
.parameter-role.buffer {
  clip-path: polygon(49% 14%, 86% 23%, 84% 58%, 60% 70%, 32% 80%, 19% 57%, 33% 33%);
}

.role-picture {
  position: absolute;
  left: 0%;
  background-size: cover;
  min-width: 440px;
  min-height: 682px;
}

.role-description-wrapper {
  border-right: 1px dotted black;
}

.role-description-message {
  padding: 5px 10px;
}

</style>


<template>
  <div v-if="beginning.status == 'start'">
    <div class="row">
      <div class="col-12">
        <p>読み込み中...</p>
      </div>
      <br>
      <div class="col-12" style="text-align:right; margin-top: 30px;">
      </div>
    </div>
  </div>

  <div v-if="beginning.status == 'prologue'">
    <div class="row">
      <div class="col-12" style="font-weight: bold; font-size: 0.95em;">
        <div style="padding: 0px 20px; background-color: #e1e8eb;">
          <hr>
          <p>
            かつて栄華を誇った王国があった。<br>
            国は人々の笑い声が絶えず、城下町は昼も夜も賑わいに包まれていた。<br>
            王国にそびえ立つ壮麗なる城は、その繁栄の象徴であった。<br>
          </p>
          <p>
            しかし時が流れ、いつからかその城は魔物の巣窟と化した。<br>
            かつての誇りは失われ、石壁は苔に覆われ、ついには凋落した古城と成り果てた。<br>
            人々は魔物の手の届かぬ地に身を寄せ新たな集落を築き、ひとつの伝承を語り継いでいる。
          </p>
          <p>
            <span style="font-style: italic; color: gray;">
              "かつて王国を築きし英雄の財宝は、未だ古城の奥深くに眠っているーー。"</span>
          </p>
          <p>
            その言葉は時を越えて人々の心を捉え、命を賭して未開の地へ挑む者たちを生み出した。<br>
            財宝を求めし者、伝承の真実を求める探究をする者。<br>
            伝承に命を賭し、未開拓の地へ足を踏み入れる者たちを「冒険者」と呼んだ。
          </p>
          <hr>
        </div>
      </div>
      <div class="col-12 my-3">
        <p style="text-align: center;">
          あなたも今まさに冒険者として旅立つ意志を強く持っています。<br>
          同じ志を持つ仲間を募り、この世界の冒険へ向かいましょう。
        </p>
      </div>
      <div class="col-12">
        <div class="next-page-form">
          <button class="btn btn-outline-info next-page-button" @click="switchSetCharacter">進む</button>
        </div>
      </div>
    </div>
  </div>

  <div v-if="beginning.status == 'setCharacter'">
    <div v-if="errorMessage != null">
      <div class="alert alert-danger" role="alert">
        {{ errorMessage }}
      </div>
    </div>
    <div class="row">
      <div class="col-12" style="margin-bottom: 10px; border-bottom: 1px solid gray;">
        <div>
          <p>冒険へと旅立つパーティメンバーを<b>3人</b>決定しましょう。 ({{ displayCurrentDecidedMemberNumber }}/3)</p>
          <p>選択中メンバー:
            <span v-for="member in beginning.selectedRoleInformations">
              <span>{{ member['partyName'] }}【{{ member['roleClassJapanese'] }}】</span>
            </span>
          </p>
        </div>
      </div>

      <div class="col-6 role-description-wrapper">
        <div class="role-description-message">
          <p>
            【<span style="font-weight: bold;">{{ roleData[beginning.currentDisplayRoleIndex]['class_japanese'] }} </span>】
            <span>({{ roleData[beginning.currentDisplayRoleIndex]['class'] }})</span>
          </p>
          <p>{{ roleData[beginning.currentDisplayRoleIndex]['description'] }}</p>
        </div>
        <!-- 名前と確定ボタンの記入フォーム -->
        <div>
          <form class="form-horizontal" role="form">
            <div class="form-group">
            <label class="col-md-12 control-rabel">名前<small>※最大6文字</small></label>
              <div class="col-md-8">
                <div class="input-group">
                  <input type="text" class="form-control" maxlength="6" v-model="partyName">
                  <span class="input-group-btn">
                    <button class="btn btn-info" style="margin-left: 10px;" type="button" @click="setPlayerData(roleData[beginning.currentDisplayRoleIndex]['id'],roleData[beginning.currentDisplayRoleIndex]['class_japanese'], partyName)">決定</button>
                  </span>

                </div>
                <div style="margin-top: 10px;">
                  <span @click="displayStatusDetailModal()" style="border-bottom: 1px solid blue; cursor: pointer">
                    <small style="color: blue">※ステータスについて</small>
                  </span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="col-6" style="min-width: 440px; min-height: 682px;">
        <div>
          <div class="role-picture" :style="backgroundImageStyle"></div>
        </div>
      </div>

      <!-- beginning.currentDisplayRoleIndexを調整するボタン -->
      <button class="btn btn-secondary btn-lg" style="position: absolute; top: 50%; right: 3%; z-index: 10;" @click="adjustDisplayRole('increment')">→</button>
      <button class="btn btn-secondary btn-lg" style="position: absolute; top: 50%; left : 3%; z-index: 10;" @click="adjustDisplayRole('decrement')">←</button>
      <button class="btn btn-danger" style="position: absolute; bottom: 3%; right: 3%; z-index: 10;;" @click="resetData">リセットする</button>
    </div>
    
    <!-- パラメータ -->
    <div style="position: absolute; bottom: 5%; left: 5%;">
      <div class="parameter-base-wrapper">
        <div class="parameter-role">
          <div v-if="beginning.currentDisplayRoleIndex === 0">
            <div class="parameter-role paint striker"></div>
          </div>
          <div v-else-if="beginning.currentDisplayRoleIndex === 1">
            <div class="parameter-role paint medic"></div>
          </div>
          <div v-else-if="beginning.currentDisplayRoleIndex === 2">
            <div class="parameter-role paint paladin"></div>
          </div>
          <div v-else-if="beginning.currentDisplayRoleIndex === 3">
            <div class="parameter-role paint mage"></div>
          </div>
          <div v-else-if="beginning.currentDisplayRoleIndex === 4">
            <div class="parameter-role paint ranger"></div>
          </div>
          <div v-else-if="beginning.currentDisplayRoleIndex === 5">
            <div class="parameter-role paint buffer"></div>
          </div>
        </div>
      </div>
      <div>
        <p style="position:absolute; top:-13%; right: 44%;">HP </p>
        <p style="position:absolute; top:10%;  right: -3%;">AP </p>
        <p style="position:absolute; top:55%;  left: 104%;">STR</p>
        <p style="position:absolute; top:100%; left:  71%;">DEF</p>
        <p style="position:absolute; top:100%; left:  13%;">INT</p>
        <p style="position:absolute; top:54%;  left: -17%;">SPD</p>
        <p style="position:absolute; top:9%;   left:  -4%;">LUC</p>
      </div>

    </div>

  </div>

  <!-- 確認モーダル -->
   <!-- data-keyboard="false" data-backdrop="static" として、灰色の枠をクリックした場合でも閉じないようにする -->
  <teleport to="body">
    <div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">パーティメンバーの確認</h4>
            </div>

            <div class="modal-body">
            <p>
                以下のメンバーで確定してよろしいですか？ <br> 
                ※進行途中で変更することはできません
            </p>
            <ul>
                <span v-for="member in beginning.selectedRoleInformations">
                <li>{{ member['partyName'] }}【{{ member['roleClassJapanese'] }}】</li>
                </span>
            </ul>
            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="resetData">リセットする</button>
            <button type="button" class="btn btn-success" @click="postPlayerData">確定</button>
            </div>

        </div>
        </div>
    </div>
  </teleport>

  <div v-if="beginning.status == 'monologue'">
    <div class="row">
      <div class="col-12" style="font-weight: bold; font-size: 0.95em;">
        <div style="padding: 0px 20px; background-color: #e1e8eb;">
          <hr>
          <p>
            {{createdPartyMembers[0]['nickname']}}、{{createdPartyMembers[1]['nickname']}}、そして{{createdPartyMembers[2]['nickname']}}。<br>
            三人は互いを認め合い、同じ目的を抱く者としてひとつのパーティを結成した。 
          </p>
          <p>
            これから幾多の危険が、容赦なく彼らの前に立ちはだかるだろう。  <br>
            凶暴なる魔物はその力を試し、荒れ果てた未踏の大地は彼らの心をも揺さぶる。<br>
            見通しの利かぬ深き樹海には、想像を絶する困難が待ち受けているかもしれない。
          </p>
          <p>
            経験に乏しく、思わぬ苦境に立たされることもある。<br>
            しかしながら冒険者として最も大切な素養である、折れぬ意志と仲間を信じる心は、徐々に彼らの中に芽生えていくだろう。
          </p>
          <p>
            今まさに三人は、強き意志を掲げ、命を賭してこの世界の伝承へと挑もうとしている。<br>
            彼らの旅路がいかなる物語を紡ぐことになるのかは、まだ誰も知らない。
          </p>
          <hr>
        </div>
      </div>
      <div class="col-12 my-3">
        <p style="text-align: center;">
          あなた達の冒険はたった今から始まります。<br>
          まずは冒険者達が拠点とする集落に向かい、旅の支度を整えましょう。
        </p>
      </div>
      <div class="col-12">
        <div class="next-page-form">
          <button class="btn btn-outline-info next-page-button" @click="switchMenuScreen">集落へ向かう</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ステータス詳細モーダル -->
  <teleport to="body">
    <div class="modal fade" id="modal-status-detail" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title"><b>ステータスについて</b></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div>
              <p>【HP】: Hit Point (体力)<br>0になると戦闘不能になり、その戦闘中では原則戦えなくなります。</p>
              <p>【AP】: Ability Point (AP)<br>スキルを使う時に必要になるポイントで、多いほど豊富な手段で立ち回れます。</p>
              <p>【STR】: Strength (物理攻撃力)<br>高いほど通常攻撃、物理スキルのダメージが上昇します。</p>
              <p>
                【DEF】: Defence (物理防御力)<br>高いほど、相手から受ける物理防御力のダメージが低下します。<br>
                多少ですが敵の使用する魔法への耐性にも影響します。
              </p>
              <p>【INT】: Intelligence (知力)<br>高いほど、魔法攻撃スキルの威力が上昇します。<br>
                また、敵の使用する魔法への耐性にも大きく影響します。
              </p>
              <p>
                【SPD】: Speed(素早さ)<br>戦闘中に行動できる順番に影響します。<br>
                また、戦闘から逃走出来る確率にも影響します。
              </p>
              <p>【LUC】: Luck(運の良さ)<br>良いことが色々起こりやすくなります。</p>
            </div>
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
        roleData: {},
        partyName: "",
        displayCurrentDecidedMemberNumber: 1,
        createdPartyMembers: [],
        errorMessage: null,
      }
    },
    computed: {
    ...mapState(['screen']),
    ...mapState(['beginning']),
    backgroundImageStyle() {
      return {
        backgroundImage: `url(/image/rpg/character/portrait/${this.roleData[this.beginning.currentDisplayRoleIndex]['portrait_image_path']})`
      }
    }
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
      this.$store.dispatch('setScreen', 'beginning');
      this.prepareBeginning();
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      console.log('Beginning.vue', this.screen.current);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
      prepareBeginning() {
        console.log('prepareBeginning(): -----------------------');
        axios.get('/api/game/rpg/beginning/prepare_beginning')
          .then(response => {
            console.log(`response.data: ${response.data[0]}, ${response.data}`);
            const isExistData = response.data[0]; // || のケースを書くとfalseの時にそちらの値が代入されてしまう。
            this.roleData     = response.data[1] || [];
            console.log('roleData取得完了。');
            if (isExistData) {
              // すでに登録済みのユーザーがURL直打ちなどでアクセスした場合はリダイレクト
              console.log('savedata, party登録済みのためリダイレクト');
              this.$store.dispatch('setScreen', 'menu');
              this.$router.push('/game/rpg/menu');
            } else {
              console.log('party未設定のため、処理を続けます。');
              this.roleInformationSetup();
              this.$store.dispatch('setBeginningStatus', 'prologue');
            }
        });
      },
      switchSetCharacter() {
        console.log('switchSetCharacter(): -----------------------');
        this.$store.dispatch('setBeginningStatus', 'setCharacter');
      },
      adjustDisplayRole(type){
        /*
          現在画面に表示しているロールをすでに選択している場合、currentDisplayRoleIndexを+/-して別のロール情報を出す
          選択中のロールが入った配列: this.beginning.selectedRoleInformations idを参照したいなら['roleId']
            [ 
              { "roleId": 1, "roleClassJapanese": "格闘家", "partyName": "スト" }, 
              { "roleId": 2, "roleClassJapanese": "治療師", "partyName": "メディ" } 
            ]
          現在選択中のロール: this.roleData[this.beginning.currentDisplayRoleIndex] idを参照したいなら['id']
            Proxy(Object) {id: 2, class: 'medic', class_japanese: '治療師', default_name: 'メディ', growth_hp: 3, …}
        */
        switch(type) {
          case 'increment':
            console.log('adjusttDisplayRoleIndex(): + increment -----------------------');
            do {
              this.$store.dispatch('incrementCurrentDisplayRoleIndex');
            } while (this.isRoleAlreadySelected(this.roleData[this.beginning.currentDisplayRoleIndex]['id']));
            break;
          case 'decrement':
            console.log('adjusttDisplayRoleIndex(): - decrement -----------------------');
            do {
              this.$store.dispatch('decrementCurrentDisplayRoleIndex');
            } while (this.isRoleAlreadySelected(this.roleData[this.beginning.currentDisplayRoleIndex]['id']));
            break;
        }
        // inputに職業別デフォルトネームを設定
        this.partyName = this.roleData[this.beginning.currentDisplayRoleIndex]['default_name'];
      },
      isRoleAlreadySelected(roleId) {
        return this.beginning.selectedRoleInformations.some(selected => selected.roleId === roleId);
      },
      roleInformationSetup() {
        console.log('roleInformationSetup(): -----------------');
        this.partyName = this.roleData[this.beginning.currentDisplayRoleIndex]['default_name'];
        if (this.beginning.currentDecidedMemberIndex <= 2) {
          console.log('2以下なので処理開始。');
        } else {
          console.log('2以上');
          this.displayConfirmModal();
        }
      },
      setPlayerData(roleId, roleClassJapanese, partyName) {
        console.log(`setPlayerData(): ${roleId}, ${roleClassJapanese}, ${partyName} -----------------`);
        this.$store.dispatch('setSelectedRoleInformation', {roleId, roleClassJapanese, partyName} );

        // WARN: メディ > スト と選択すると、incrementCurrentDecidedMemberIndexが繰り上がり、再びメディが選べるようになってしまう
        // これは明らかにバグなので直す必要あり

        this.$store.dispatch('incrementCurrentDecidedMemberIndex');
        this.$store.dispatch('incrementCurrentDisplayRoleIndex'); // 選択後は画面には次のロール情報を映す
        this.displayCurrentDecidedMemberNumber++;
        if (this.displayCurrentDecidedMemberNumber > 3) this.displayCurrentDecidedMemberNumber = 3;
        console.log(`${this.beginning.currentDecidedMemberIndex}`);
        this.roleInformationSetup();
      },
      displayConfirmModal() {
        // このメンバーでいいですか？というダイアログを出す。 OKならpostPlayerData()を実行する。
        console.log(`displayConfirmModal(): -----------------`);
        $('#modal-confirm').modal('show');
      },
      displayStatusDetailModal() {
        // このメンバーでいいですか？というダイアログを出す。 OKならpostPlayerData()を実行する。
        console.log(`displayStatusDetailModal(): -----------------`);
        $('#modal-status-detail').modal('show');
      },
      postPlayerData() {
        console.log(`postPlayerData(): -----------------`);
        // axios.postで登録
        axios.post(`/api/game/rpg/beginning/create`,{
          selected_info: this.beginning.selectedRoleInformations
        })
        .then(response => {
          console.log(`通信OK`);
          this.createdPartyMembers = response.data;
          console.log(`作成完了。`, this.createdPartyMembers, this.createdPartyMembers[0]['nickname']);
          this.$store.dispatch('setBeginningStatus', 'monologue');
        })
        .catch(error => {
          console.log(`通信失敗。`);
          if (error.response && error.response.data) {
            this.errorMessage = error.response.data.message;
          } else {
            this.errorMessage = "予期しないエラーが発生しました。もう一度お試しください。"
          }
        });
        // 処理が終わった後はmodalを閉じる
        this.resetData();
        $('#modal-confirm').modal('hide');
      },
      resetData() {
        this.displayCurrentDecidedMemberNumber = 1;
        this.$store.dispatch('resetBeginningDecidedData');
      },
      switchMenuScreen() {
        this.$store.dispatch('setScreen', 'menu');
        this.$router.push('/game/rpg/menu');
      },

    }
  }
</script>

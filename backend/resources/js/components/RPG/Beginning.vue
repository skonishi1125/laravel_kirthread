<style>
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
.parameter-role.paradin {
  clip-path: polygon(50% 0%, 72% 28%, 85% 60%, 75% 100%, 43% 65%, 33% 55%, 29% 31%);
}
.parameter-role.mage {
  clip-path: polygon(50% 30%, 90% 20%, 60% 56%, 59% 77%, 25% 100%, 15% 58%, 27% 38%);
}
.parameter-role.ranger {
  clip-path: polygon(50% 10%, 78% 27%, 85% 59%, 65% 74%, 42% 74%, 10% 58%, 35% 38%);
}
.parameter-role.buffer {
  clip-path: polygon(50% 20%, 83% 24%, 66% 54%, 64% 81%, 31% 81%, 10% 59%, 33% 41%);
}

.role-picture {
  position: absolute;
  left: 0%;
  background-size: cover;
  min-width: 440px;
  min-height: 682px;
}

.role-description-wrapper {
  border: 1px dotted black;
}

.role-description-message {
  padding: 5px 10px;
}

</style>


<template>
  <div v-if="beginning.status == 'start'">
    <div class="row">
      <div class="col-12">
        <p>beginning.vue, start</p>
      </div>
    </div>
  </div>

  <div v-if="beginning.status == 'prologue'">
    <div class="row">
      <div class="col-12" style="border: 1px solid black">
        <p>
          <hr>
          かつて栄華を誇った王国があった。<br>
          王族が住んでいた壮麗なる城は、いつからか魔物の巣窟と化し、凋落した古城と成り果てた。<br>
          時は流れ、人々は魔物の手の届かぬ地に集落を築きつつも、ひとつの伝承が語り継がれている。<br>
          <br>
          "王族がその全盛期に手にした財宝は、未だ古城の奥深くに眠っている――。"<br>
          <br>
          <!--
          くどいか？
          魔物が支配する荒れ果てた地を越え、古城に近づくほどに強力な敵が待ち受けている。
          しかし古城に限らず未だ開拓されていないその地には、まだ誰も手をつけぬ秘宝や資源が眠っているという。 
          その噂は冒険者たちの心に火を灯し、彼らは命を賭してその地に挑む。
          -->
          伝承に命を賭し、未だ開拓されていない地へ足を踏み入れる者たちを「冒険者」と呼ぶ。<br>
          <br>
          <hr>
          <br>
          ...あなたも今まさに冒険者として旅立つ意志を強く持っています。同じ志を持つ仲間を募り、冒険へ向かいましょう。<br>
        </p>
      </div>
      <div class="col-12" style="text-align:right;">
        <button class="btn btn-secondary" @click="switchSetCharacter">→進む</button>
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
          <p>冒険へと旅立つパーティメンバーを決定しましょう。 ({{ displayCurrentDecidedMemberNumber }}/3)</p>
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
                    <button class="btn btn-success" style="margin-left: 10px;" type="button" @click="setPlayerData(roleData[beginning.currentDisplayRoleIndex]['id'],roleData[beginning.currentDisplayRoleIndex]['class_japanese'], partyName)">選択</button>
                  </span>

                </div>
                <a href=""><small>ステータスについて</small></a>
              </div>
            </div>
            <span class="input-group-btn" style="margin-left: 10px;">
              <button class="btn btn-info" type="button" @click="resetData">最初からやり直す</button>
            </span>
          </form>
        </div>
      </div>
 
      <div class="col-6" style="min-width: 440px; min-height: 682px;">
        <div>
          <div class="role-picture" :style="backgroundImageStyle"></div>
        </div>
      </div>

      <!-- beginning.currentDisplayRoleIndexを調整するボタン -->
      <button class="btn btn-primary btn-lg" style="position: absolute; top: 50%; right: 3%; z-index: 10;" @click="adjustDisplayRole('increment')">→</button>
      <button class="btn btn-primary btn-lg" style="position: absolute; top: 50%; left : 3%; z-index: 10;" @click="adjustDisplayRole('decrement')">←</button>
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
            <div class="parameter-role paint paradin"></div>
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
          <button type="button" class="btn btn-info" data-dismiss="modal" @click="resetData">最初からやり直す</button>
          <button type="button" class="btn btn-success" @click="postPlayerData">確定</button>
        </div>

      </div>
    </div>
  </div>

  <div v-if="beginning.status == 'monologue'">
    <div class="row">
      <div class="col-12" style="border: 1px solid black">
        <p>
          <hr>
          {{createdPartyMembers[0]['nickname']}}、{{createdPartyMembers[1]['nickname']}}、そして{{createdPartyMembers[2]['nickname']}}の三人は同じ目的を持つもの同士と認識し、ここにひとつのパーティを結成した。<br>
          <br>
          これから幾多の危険が彼らの前に立ちはだかることだろう。<br>
          凶暴な魔物は彼らの力を試し、荒れ果てた未開の大地は彼らの心をも試す。<br>
          見通せぬ暗闇には、想像を絶する困難が隠れているかもしれない。<br>
          <br>
          君たちはまだ経験に乏しく、思わぬ苦境に立たされることもあるだろう。<br>
          しかし、冒険者として最も必要な素養である強き意志はとうの昔から持ち合わせている。<br>
          <br>
          さあ、意志を強く持ちその先に進みたまえ！<br>
          <hr>
          <br>
          ...あなた達の冒険はたった今から始まります。<br>
          まずは冒険者達が拠点とする街に向かい、旅の支度を整えましょう。<br>
        </p>
      </div>
      <div class="col-12" style="text-align:right;">
        <button class="btn btn-success" @click="switchMenuScreen">→街へ向かう</button>
      </div>
    </div>
  </div>

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
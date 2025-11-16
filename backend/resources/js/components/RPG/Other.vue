<style scoped>
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

.weight-bold {
    font-weight: bold;
    font-size: 0.9rem;
    margin-bottom: 0.7rem;
}

</style>

<!-- 冒険、ショップ、スキル振りなどの一覧ページ。すべてこのページのレイアウトがベースになる -->
<template>
  <div class="sub-screen-wrapper">
    <div class="row sub-sucreen-text-space">
      <div class="col-12">
        <div>
          <p><small>本作に関するマニュアルやその他情報を確認することができます。</small></p>
        </div>
        <hr>
      </div>
    </div>

    <div class="row mt-3 sub-sucreen-main-space">
      <div class="col-12">
        <table class="table table-borderless table-hoverable">
          <thead>
            <tr>
              <th>項目一覧</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in manualItems" :key="index" @click="openManual(item)">
              <td class="weight-bold">{{ item.title }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <teleport to="body">
    <div class="modal fade" id="manual-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-backdrop-adjust" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title"><b>{{ modalItem?.title }}</b></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" v-html="modalItem?.content"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              閉じる
            </button>
          </div>
        </div>
      </div>
    </div>
  </teleport>

</template>

<script>
  import $ from 'jquery';
  import axios from 'axios';
  import { mapState } from 'vuex';
  export default {
    data() {
      return {
        manualItems: [
          {
            title: "本作について",
            content: "<p>現在のバージョンは1.0となります。</p> <p>本作は、育成や戦闘を楽しむRPG風ブラウザゲームです。</p><p>操作推奨環境はGoogle Chrome等のブラウザとなります。<br>スマートフォンでのプレイも可能ですが一部挙動(※)が正常に動作しませんので、<br>可能であればPC環境でのプレイをご検討ください。<br><br>※ボタンに対してマウスカーソルをホバーした時の説明文表示など。</p>"
          },
          {
            title: "ゲームの進めかた",
            content: "<p>左のメニューから自由に進めることができます。</p><p>「冒険に出る」では、大陸の開拓を進めることとなり、魔物との戦闘が発生します。<br>「ショップ」や「ステータス」では、戦闘の事前準備を行うことができます。</p><p>冒険で行き詰まってしまった場合はステータス画面を開き、<br>「ステータスポイント」や「スキルポイント」でキャラクターを成長させましょう。<br></p><p>パーティを結成した3人は、懐が寂しいながらもかき集めたお金が少しはあるようです。<br>「ミニポーション」は安価で序盤に役立つアイテムなので、<br>「ショップ」でいくつか購入しておくと戦闘時に役に立つでしょう。</p>"
          },
          {
            title: "戦闘について",
            content: "<p>「冒険に出る」からフィールドを選択することで、戦闘画面に移行します。<br> 戦闘は数回連続で発生し、最後に待ち受けるボスを倒すことでフィールドクリアとなります。<br> フィールドをクリアすると、新たなフィールドやショップの品揃えが開放されていきます。</p> <p>戦闘は各ステージの段階があり、ステージクリアするたびにHPとAPが少量回復します。<br>またレベルアップ時はHPとAPが全回復します。</p> <p>※以下の挙動は仕様となります。</p> <ul> <li>戦闘勝利時もしくは逃走時、使用アイテムや獲得EXP(経験値)が戦闘終了時点の状態で保存される。</li> <li>戦闘敗北時、使用アイテムや獲得EXPが戦闘前の状態に巻き戻る。</li> </ul> <p>アイテムを多く消費して結果的に負けてしまった時に無駄にアイテムが無くなってしまうこと、<br>低レベルでの攻略を目指した際に一度でも敗北してしまった場合に経験値が残ってしまうことを防ぐ目的があります。</p>"
          },
          {
            title: "ショップ・アイテムについて",
            content: "<p>ショップでは、戦闘中に使用可能な各種アイテムを購入できます。<br> アイテムごとに最大所持数、およびアイテム全体の最大所持数(10個)が決まっているため、<br>大量に所持することはできません。</p>"
          },
          {
            title: "ステータスポイント・スキルポイントについて",
            content: "<p>キャラクターのステータス・及びスキルを確認することができます。<br> 新規プレイ開始時点で、各キャラクターには「ステータスポイント」が4ポイント、<br> 「スキルポイント」が1ポイント与えられています。</p> <p>ステータスポイントはレベルアップのたびに4ポイントずつ取得できます。<br>スキルポイントは、プレイヤーレベルが2の倍数となった時に取得できます。<br> ポイントは自由に割り振ることが可能なため、<br>長所を伸ばすか、短所を補うか、あなたのプレイスタイルに合わせて調整しましょう。<br></p><p>※スキルによっては、取得のための前提スキルが用意されていることがあります。</p>"
          },
          {
            title: "問い合わせ・バグ報告などについて",
            content: "<p>不具合およびバグに遭遇した際のご報告、<br>その他お問い合わせ（「すぐ作る」機能で作ったユーザー情報を忘れたなど）は、<br>以下の2点いずれかにご連絡をお願いいただけますと幸いです。<ul><li><a href='https://kir-thread.site/' target='_blank'>かあスレッド</a> 投稿ページ</li><li><a href='https://x.com/skirplus' target='_blank'>作者 X アカウント</a> ダイレクトメッセージ</li></ul></p><p>各確認出来次第、対応を行わせていただきます。<br>感想や改善のご要望なども、お気軽にご記載いただけますと励みになります。</p>"
          },
          {
            title: "クレジット",
            content: "<div class='col-12' style='padding: 20px; text-align: center;'><p><b>Epic Reckoning</b></p><div style='font-size: 0.95em; color: gray; font-style: italic;'><ul style='list-style: none; padding-left: 0;'><li>シナリオ設定: 降諏かあ</li><li>レベルデザイン・デバッグ: 降諏かあ</li><li>UI/UX・フロントエンド(Vue.js): 降諏かあ</li><li>DB設計・サーバーサイド(Laravel): 降諏かあ</li><li>インフラ構築・運用(AWS): 降諏かあ</li><li> キャラクター・敵・背景イラスト: 降諏かあ</li><li>テストプレイ: 本サイト利用者の皆さん</li></ul></div><p class='text-muted' style='font-size: 0.9em;'>© 2025 <a href='https://x.com/skirplus' target='_blank'>降諏かあ</a>. All rights reserved.<br><br>本作の権利はすべて作者に帰属します。<br>自作発言や、作者を偽る形での利用・転載はお控えください。<br>All rights to this game and its content are reserved by the creator.<br>Unauthorized reproduction, redistribution, or misrepresentation of authorship is strictly prohibited.</p></div>" 
          },
        ],
        modalItem: {
          title: '',
          content: '',
        }
      }
    },
    created() {

    },
    computed: {

    },
    mounted() {

    },
    methods: {
      openManual(item) {
        this.modalItem = item;
        $('#manual-modal').modal('show');
      }
    }
  }
</script>

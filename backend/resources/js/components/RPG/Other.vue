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
              <th>タイトル</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in manualItems" :key="index" @click="openManual(item)">
              <td>{{ item.title }}</td>
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
            title: "（体験版）本作について",
            content: "<p>現在のバージョンは体験版となります。<br> 完成版がリリースされた際、データの引き継ぎができない可能性があることをご了承ください。</p> <p>本作は、育成や戦闘を楽しむRPG風のブラウザゲームです。<br> 体験版では「草原」ステージのプレイが可能です。<br>また、プレイヤーレベルは最大10レベルまで育成することができます。</p><p>操作推奨環境はGoogle Chrome等のブラウザとなります。<br>スマートフォンでのプレイも可能ですが一部挙動(※)が正常に動作しませんので、<br>可能であればPC環境を使用したブラウザでのプレイをご検討ください。<br><br>※ボタンに対してマウスカーソルをホバーした時の説明文表示など。</p>"
          },
          {
            title: "ゲームの進めかた",
            content: "<p>基本的に、左のメニューから自由に進めることができます。</p><p>「冒険に出る」でこの世界の開拓を進めても良いですし、<br>「ショップ」や「ステータス」で冒険の事前準備を行っていただくのも良いでしょう。<br> ただし準備を怠ったまま冒険に出た場合、相応に大変な思いをしてしまうかもしれません。</p><p>冒険で行き詰まってしまった場合は「ステータス」画面を開き、<br>「ステータスポイント」や「スキルポイント」でキャラクターを成長させましょう。<br><br>またパーティを結成した3人は、懐が寂しいながらもかき集めたお金が少しはあるようです。</p>"
          },
          {
            title: "戦闘について",
            content: "<p>「冒険に出る」からフィールドを選択することで、戦闘画面に移行します。<br> 戦闘は数回連続で発生し、最後に待ち受けるボスを倒すことでフィールドクリアとなります。<br> フィールドをクリアすると、新たなフィールドやショップの品揃えが開放されていきます。</p> <p>戦闘は各ステージの段階があり、ステージクリアするたびにHPとAPが少量回復します。<br>またレベルアップ時はHPとAPが全回復します。</p> <p>※以下の挙動は仕様となります。</p> <ul> <li>戦闘勝利時もしくは逃走時、使用アイテムや獲得経験値が戦闘終了時点の状態で保存される。</li> <li>戦闘敗北時、使用アイテムや獲得経験値が戦闘前の状態に巻き戻る。</li> </ul> <p>アイテムを多く消費して結果的に負けてしまった時に無駄にアイテムを消費してしまうこと、<br>低レベルクリア等を目指した際に一度でも敗北してしまった場合に達成が困難となってしまうことを防ぐ目的があります。</p>"
          },
          {
            title: "ショップについて",
            content: "<p>ショップでは、戦闘中に使用可能な各種アイテムを購入できます。<br> アイテムごとに最大所持数が決まっているため、大量に所持することはできません。</p>"
          },
          {
            title: "ステータス・スキルについて",
            content: "<p>キャラクターのステータス・及びスキルを確認することができます。<br> 新規プレイ開始時点で、各キャラクターには「ステータスポイント」が4ポイント、<br> 「スキルポイント」が1ポイント与えられています。</p> <p>ステータスポイントはレベルアップのたびに4ポイントずつ取得できます。<br>スキルポイントは、プレイヤーレベルが3の倍数になった場合に取得できます。<br> ポイントは自由に割り振ることが可能なため、<br>長所を伸ばすか、短所を補うか、あなたのプレイスタイルに合わせて調整しましょう。<br> <br> ※体験版では、一度振り分けたポイントは再振り分けすることができないのでご注意ください。<br>※スキルによっては、取得のための前提スキルが用意されていることがあります。</p>"
          },
          {
            title: "バグ報告・ご要望について",
            content: "<p>バグや進行不能になった場合は、<a href='https://kir-thread.site/' target='_blank'>かあスレッド</a>メインページにご記入ください。<br>セーブデータを確認しエラーの原因究明及び、修正対応を行わせていただきます。<br><br>また感想や改善のご要望なども、是非ご投稿いただければと思います。<br>今後の参考にさせていただきます。</p>"
          },
          {
            title: "クレジット",
            content: "<ul style='list-style: none; padding-left: 0;'> <li> UI・ゲーム・レベルデザインなど：降諏かあ</li> <li>DBテーブル設計・実装など：降諏かあ</li> <li>背景・キャラクター・敵イラストなど：降諏かあ</li> <li>デバッグ・テストプレイ他：降諏かあ</li></ul><p class='text-muted' style='font-size: 0.9em;'>© 2025 <a href='https://x.com/skirplus' target='_blank'>降諏かあ</a>. All rights reserved.<br><br>本作の権利はすべて作者に帰属します。自作発言や、作者を偽る形での利用・転載はお控えください。<br>All rights to this game and its content are reserved by the creator.<br>Unauthorized reproduction, redistribution, or misrepresentation of authorship is strictly prohibited.</p>" },
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

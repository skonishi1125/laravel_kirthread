<style scoped>
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

<!-- 冒険、ショップ、スキル振りなどの一覧ページ。すべてこのページのレイアウトがベースになる -->
<template>
  <div class="container">
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
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title"><b>{{ modalItem?.title }}</b></h6>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
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
            content: "<p>現在のバージョンは体験版となります。<br> 完成版がリリースされた際、データの引き継ぎができない可能性があることをご了承ください。<br> また、スマートフォンでもプレイ可能ですが、操作性の観点からPCでのプレイを推奨しています。</p> <p>本作は、育成や戦闘を楽しむRPG風のブラウザゲームです。<br> 体験版では「草原」ステージのプレイが可能です。<br>また、プレイヤーレベルは最大10レベルまで育成することができます。</p>"
          },
          {
            title: "ゲームの進めかた",
            content: "基本的に自由に進めることができます。<br>「冒険に出る」でいきなり冒険に出ても良いですし、「ショップ」で買い物したり、「ステータス」で事前準備を進めるか自由です。<br> <br> ただし準備を怠ったまま、冒険に出た場合はそれなりに大変かもしれません。"
          },
          {
            title: "戦闘について",
            content: "<p>「冒険に出る」からステージを選択することで、戦闘画面に移行します。<br> 戦闘は数回連続で発生し、最後に待ち受けるボスを倒すことでステージクリアとなります。<br> ステージをクリアすると、新たなステージやショップの品揃えが開放されていきます。</p> <p>※以下の挙動は仕様となります。</p> <ul> <li>戦闘勝利時もしくは逃走時、使用アイテムや獲得経験値が戦闘終了時点の状態で保存される。</li> <li>戦闘敗北時、使用アイテムや獲得経験値が戦闘前の状態に巻き戻る。</li> </ul> <p>アイテムを多く消費して結果的に負けてしまった時に無駄にアイテムを消費してしまうこと、<br>低レベルクリア等を目指した際に一度でも敗北してしまった場合に達成が困難となってしまうことを防ぐ目的があります。</p>"
          },
          {
            title: "ショップについて",
            content: "<p>ショップでは、戦闘中に使用可能な各種アイテムを購入できます。<br> アイテムごとに最大所持数が決まっており、同アイテムを大量に買い込むことはできません。</p>"
          },
          {
            title: "ステータス・スキルについて",
            content: "<p>キャラクターのステータス・及びスキルを確認することができます。<br> 新規プレイ開始時点で、各キャラクターには「ステータスポイント」が4ポイント、<br> 「スキルポイント」が1ポイント与えられています。</p> <p>これらのポイントは自由に割り振ることが可能です。<br> 長所を伸ばすか、短所を補うか、あなたのプレイスタイルに合わせて調整しましょう。<br> <br> ※体験版では、一度振り分けたポイントは再振り分けすることができないのでご注意ください。<br>※スキルによっては、取得のための前提スキルが用意されていることがあります。</p>"
          },
          {
            title: "バグ報告・ご要望について",
            content: "<p>バグや進行不能になった場合は、<a href='https://kir-thread.site/' target='_blank'>かあスレッド</a>メインページに書き込んでいただれば、<br>セーブデータを確認しエラーの原因究明及び、修正対応を行わせていただきます。<br>また感想や改善のご要望なども、今後の参考にさせて頂きますので是非ご投稿ください。</p>"
          },
          {
            title: "クレジット",
            content: "<ul style='list-style: none; padding-left: 0;'> <li> UI・ゲーム・レベルデザインなど：降諏かあ</li> <li>データベーステーブル設計・実装など：降諏かあ</li> <li>背景・キャラクター・敵イラストなど：降諏かあ</li> <li>デバッグ・テストプレイ他：降諏かあ</li></ul><p class='text-muted' style='font-size: 0.9em;'>© 2025 降諏かあ. All rights reserved.<br><br>本作の権利はすべて作者に帰属します。自作発言や、作者を偽る形での利用・転載はお控えください。<br>All rights to this game and its content are reserved by the creator.<br>Unauthorized reproduction, redistribution, or misrepresentation of authorship is strictly prohibited.</p>" },
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

<style scoped>
.sub-screen-back-img {
  background-image: url('/image/rpg/menu/plaza.png'); 
  background-size: cover;
}

.position-relative {
  position: relative;
}

.clickable-marker {
  position: absolute;
  background-color: rgba(22, 159, 177, 0.9); /* 半透明青 */
  color: white;
  font-size: 18px;
  font-weight: bold;
  border: 2px solid white;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  text-align: center;
  line-height: 36px;
  cursor: pointer;
  transform: translate(-50%, -50%);
  box-shadow: 0 0 8px rgba(22, 159, 177, 0.7);
  transition: transform 0.2s, box-shadow 0.2s;
}

.clickable-marker:hover {
  transform: translate(-50%, -50%) scale(1.1);
  box-shadow: 0 0 12px rgba(22, 159, 177, 1);
}

.clickable-disable-marker {
  position: absolute;
  background-color: rgba(177, 22, 22, 0.9); /* 半透明青 */
  color: white;
  font-size: 18px;
  font-weight: bold;
  border: 2px solid white;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  text-align: center;
  line-height: 36px;
  cursor: pointer;
  transform: translate(-50%, -50%);
  box-shadow: 0 0 8px rgba(22, 159, 177, 0.7);
  transition: transform 0.2s, box-shadow 0.2s;
}

.clickable-disable-marker:hover {
  transform: translate(-50%, -50%) scale(1.1);
  box-shadow: 0 0 12px rgba(22, 159, 177, 1);
}

</style>

<template>

  <div class="sub-screen-wrapper">

    <div v-if="status.status == 'start'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>読み込み中...</small></p>
          </div>
          <hr>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space sub-screen-back-img">
        <div class="col-12"></div>
      </div>
    </div>

    <div v-if="status.status == 'loaded'">
      <div class="row sub-sucreen-text-space">
        <div class="col-12">
          <div>
            <p><small>広場に到着した。用があるのは...</small></p>
          </div>
          <hr>
          <div style="font-size: 0.9em;">
            <p>
              <b>{{ facilityInfo.name }}</b>
              <br>
              <span>{{ facilityInfo.description }}</span>
            </p>
          </div>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space sub-screen-back-img">
        <div class="col-12">
          <!-- 図書館 -->
          <div class="clickable-marker" 
            style="top: 5%; left: 50%;" 
            @click="selectDestination('Library')"
            @mouseover="showFacilityInfo('Library')"
          ></div>
          <!-- 冒険者掲示板 -->
          <div class="clickable-marker" 
            style="top: 40%; left: 50%;" 
            @click="selectDestination('Bbs')"
            @mouseover="showFacilityInfo('Bbs')"
          ></div>
          <!-- 癒しの館 -->
          <div v-if="isEnableRefresh == true">
            <div class="clickable-marker" 
              style="top: 30%; left: 76%; background-color: blue;" 
              @click="selectDestination('Refresh')"
              @mouseover="showFacilityInfo('Refresh')"
            ></div>
          </div>
          <div v-else>
            <div class="clickable-disable-marker" 
              style="top: 30%; left: 76%;" 
              @mouseover="showFacilityInfo('disabledRefresh')"
            ></div>
          </div>

          <!-- 日雇いギルド -->
          <div class="clickable-marker"
            style="top: 22%; left: 20%;" 
            @click="selectDestination('Job')"
            @mouseover="showFacilityInfo('Job')"
          ></div>
          <!-- 冒険 -->
          <div class="clickable-marker"
            style="top: 90%; left: 50%;"
            @click="selectDestination('Adventure')"
            @mouseover="showFacilityInfo('Adventure')"
          ></div>

        </div>
      </div>
    </div>

  </div>

    <!-- クリックした施設は、施設ごとのVueで管理する -->
    <div v-if="status.status == 'moved'">
      <router-view></router-view>
    </div>

</template>

<script>
  import $ from 'jquery';
  import { mapState } from 'vuex';
  import axios from 'axios';
  export default {
    data() { // script内で使用する変数を定義する。
      return {
        // マウスオーバーで表示させる施設情報
        facilityInfo: {
          name: '',
          description: '',
        },
        isEnableRefresh: false,
      }
    },
    // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
    // vuexに存在するmapStateメソッドでstore.jsで定義したstate.screen.currentを取得。thisで参照できるようになる。
    computed: { 
      ...mapState({
          status: state => state.menu.plaza
      }),
    },
    created() { // DOMに依存しない処理を書く(state処理など。)
      this.$store.dispatch('setMenuPlazaStatus', 'start');
      this.checkPlazaStatus();
    },
    mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
      this.$store.dispatch('setMenuPlazaStatus', 'start');
      console.log(`Plaza.vue ${this.status.status}`);
    },
    methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
      checkPlazaStatus() {
        console.log(`checkPlazaStatus(): --------`);
        axios.get('/api/game/rpg/menu/plaza/check_status')
          .then(response => {
            this.isEnableRefresh = response.data[0];
            this.$store.dispatch('setMenuPlazaStatus', 'loaded');
          });
      },

      selectDestination(destination) {
        switch (destination) {
          case 'Library':
            this.$router.push({ name: 'menu_plaza_library' });
            break;
          case 'Bbs':
            this.$router.push({ name: 'menu_plaza_bbs' });
            break;
          case 'Refresh':
            this.$router.push({ name: 'menu_plaza_refresh' });
            break;
          case 'Job':
            this.$router.push({ name: 'menu_plaza_job' });
            break;
          case 'Adventure':
            this.$router.push({ name: 'menu_adventure'});
        }
        this.$store.dispatch('setMenuPlazaStatus', 'moved');

      },

      // マウスオーバーした時、その施設の情報を説明欄に表示する
      showFacilityInfo(facility) {
        console.log(`showFacilityInfo(): ${facility} `);
        switch (facility) {
          case 'Library':
            this.facilityInfo.name = '図書館';
            this.facilityInfo.description = '大陸にまつわる伝承や、冒険に役立つ書籍を閲読できます。';
            break;
          case 'Bbs':
            this.facilityInfo.name = '冒険者掲示板';
            this.facilityInfo.description = '他の冒険者たちの風聞を見たり、自身が書き込んだりすることができます。';
            break;
          case 'disabledRefresh':
            this.facilityInfo.name = '？？？';
            this.facilityInfo.description = '改装中のようです。冒険を進めたら再度訪れてみましょう。';
            break;
          case 'Refresh':
            this.facilityInfo.name = '癒しの館';
            this.facilityInfo.description = '心身をリフレッシュし、スッキリ爽快できます。';
            break;
          case 'Job':
            this.facilityInfo.name = '日雇いギルド';
            this.facilityInfo.description = '仕事をひたすらこなし、お金を得ることができます。';
            break;
          case 'Adventure':
            this.facilityInfo.name = '中央通り表門';
            this.facilityInfo.description = '街から離れ、冒険に出かけることができます。';
        }
      }


    }
  }
</script>

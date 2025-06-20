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
  color: rgb(34, 132, 218);
  font-size: 32px;
  cursor: pointer;
  transform: translate(-50%, -50%);
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
            <p><small>中央広場では、街の様々な施設を利用することができます。</small></p>
          </div>
          <hr>
        </div>
      </div>

      <div class="row mt-3 sub-sucreen-main-space sub-screen-back-img">
        <div class="col-12">
          <!-- 図書館 -->
          <div class="clickable-marker" style="top: 5%; left: 50%;" @click="selectDestination('Library')">⚫︎</div>
          <!-- 中央掲示板 -->
          <div class="clickable-marker" style="top: 30%; left: 50%;" @click="selectDestination('Bbs')">⚫︎</div>
          <!-- リフレッシュ場 -->
          <div class="clickable-marker" style="top: 24%; left: 83%;" @click="selectDestination('Refresh')">⚫︎</div>
          <!-- バイト -->
          <div class="clickable-marker" style="top: 22%; left: 18%;" @click="selectDestination('Job')">⚫︎</div>
          <!-- 冒険 -->
          <div class="clickable-marker" style="top: 90%; left: 50%;" @click="selectDestination('Adventure')">⚫︎</div>

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
        axios.get('/api/game/rpg/parties/information')
          .then(response => {
            console.log(`response`);
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

      }


    }
  }
</script>

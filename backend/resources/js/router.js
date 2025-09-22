import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import store from './store';
const BASE_URL = '';

// ベースとなるコンポーネントに組み込むパーツとルーティングを定義
import Title from './components/RPG/Title.vue';
import Beginning from './components/RPG/Beginning.vue';
import Ending from './components/RPG/Ending.vue';
import Menu from './components/RPG/Menu.vue';
import Adventure from './components/RPG/Adventure.vue';
import Shop from './components/RPG/Shop.vue';
import Status from './components/RPG/Status.vue';
import Battle from './components/RPG/Battle.vue';
import Other from './components/RPG/Other.vue';

// 中心広場
import Plaza from './components/RPG/Plaza.vue';
import Library from './components/RPG/Library.vue';
import Bbs from './components/RPG/Bbs.vue';
import Refresh from './components/RPG/Refresh.vue';
import Job from './components/RPG/Job.vue';


const routes = [
  { 
      path: '/game/rpg',
      name: 'game.rpg',
      component: Title
  },
  { 
      path: '/game/rpg/beginning',
      component: Beginning
  },
  { 
      path: '/game/rpg/ending',
      component: Ending
  },
  { 
      path: '/game/rpg/menu',
      name: 'game.rpg.menu',
      component: Menu,
      children: [
        {
          path: 'adventure',
          name: 'menu_adventure',
          component: Adventure
        },
        // ----------------- 中心広場 -----------------
          {
            path: 'plaza',
            name: 'menu_plaza',
            component: Plaza,
          },
          {
            path: 'library',
            name: 'menu_plaza_library',
            component: Library
          },
          {
            path: 'bbs',
            name: 'menu_plaza_bbs',
            component: Bbs
          },
          {
            path: 'refresh',
            name: 'menu_plaza_refresh',
            component: Refresh
          },
          {
            path: 'job',
            name: 'menu_plaza_job',
            component: Job
          },
        // ----------------- 中心広場 -----------------
        {
          path: 'shop',
          name: 'menu_shop',
          component: Shop
        },
        {
          path: 'status',
          name: 'menu_status',
          component: Status
        },
        {
          path: 'other',
          name: 'menu_other',
          component: Other
        }
      ]
  },
  { 
      // path: '/game/rpg/battle',
      // path: '/game/rpg/battle/:field_id',
      path: '/game/rpg/battle/:fieldId/:stageId',
      name: 'game.rpg.battle',
      component: Battle
  },
];

// ルーター作成
const router = createRouter({
  history: createWebHistory(BASE_URL),
  routes,
});

// ベースとなるコンポーネントをimportし、createApp, use, mountする
import App from './components/RPG/App.vue';
const app = createApp(App);
app.use(router);
app.use(store); // vuex使用
app.mount('#rpg-components-app');

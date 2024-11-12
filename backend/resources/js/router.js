import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import store from './store';
const BASE_URL = '';

// ベースとなるコンポーネントに組み込むパーツとルーティングを定義
import Title from './components/RPG/Title.vue';
import Beginning from './components/RPG/Beginning.vue';
import Menu from './components/RPG/Menu.vue';
import Adventure from './components/RPG/Adventure.vue';
import Shop from './components/RPG/Shop.vue';
import Status from './components/RPG/Status.vue';
import Battle from './components/RPG/Battle.vue';
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
      path: '/game/rpg/menu',
      name: 'game.rpg.menu',
      component: Menu,
      children: [
        {
          path: 'adventure',
          component: Adventure
        },
        {
          path: 'shop',
          component: Shop
        },
        {
          path: 'status',
          component: Status
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
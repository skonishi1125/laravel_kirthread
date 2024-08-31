import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import store from './store';
const BASE_URL = '';

// ベースとなるコンポーネントに組み込むパーツとルーティングを定義
import Title from './components/RPG/Title.vue';
import Adventure from './components/RPG/Adventure.vue';
import Shop from './components/RPG/Shop.vue';
import Skill from './components/RPG/Skill.vue';
import Battle from './components/RPG/Battle.vue';
const routes = [
  { 
      path: '/game/rpg',
      name: 'game.rpg',
      component: Title
  },
  { 
      path: '/game/rpg/adventure',
      name: 'game.rpg.adventure',
      component: Adventure
  },
  { 
      path: '/game/rpg/shop',
      name: 'game.rpg.shop',
      component: Shop
  },
  { 
      path: '/game/rpg/skill',
      name: 'game.rpg.skill',
      component: Skill
  },
  { 
      path: '/game/rpg/battle/:field_id',
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
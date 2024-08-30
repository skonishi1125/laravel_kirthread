import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
const BASE_URL = '';

// ベースとなるコンポーネントに組み込むパーツとルーティングを定義
import Adventure from './components/RPG/Adventure.vue';
import Shop from './components/RPG/Shop.vue';
import Skill from './components/RPG/Skill.vue';
const routes = [
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
app.mount('#rpg-components-app');
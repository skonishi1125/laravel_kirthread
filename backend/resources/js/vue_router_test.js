
import { createApp } from 'vue';

// vue-router 動作テスト
import { createRouter, createWebHistory } from 'vue-router';
const BASE_URL = '';

import About from './components/study/vue-router/About.vue';
import NotFound from './components/study/vue-router/NotFound.vue';

const routes = [
  { 
      path: '/study/vue-router/about',
      name: 'study.vue-router.about',
      component: About
  },
  {
      path: '/study/vue-router/404',
      name: 'study.vue-router.404',
      component: NotFound
  },
  {
      path: '/study/vue-router/:pathMatch(.*)',
      redirect: '/study/vue-router/404',
  }
];

// ルーター作成
const test_router = createRouter({
  history: createWebHistory(BASE_URL),
  routes,
});

import VueRouterTestApp from './components/study/vue-router/VueRouterTestApp.vue';
const vue_router_test_app = createApp(VueRouterTestApp);
vue_router_test_app.use(test_router);
vue_router_test_app.mount('#vue-router-test-app');
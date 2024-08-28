import { createRouter, createWebHistory } from 'vue-router';
const BASE_URL = '/study/vue-router';

import About from './components/study/vue-router/About.vue';
import NotFound from './components/study/vue-router/NotFound.vue';

const routes = [
  { 
      path: '/about',
      name: 'study.vue-router.about',
      component: About
  },
  {
      path: '/404',
      name: 'study.vue-router.404',
      component: NotFound
  },
  {
      path: '/:pathMatch(.*)',
      redirect: '/404',
  }
];

// ルーター作成
const router = createRouter({
  history: createWebHistory(BASE_URL),
  routes,
});

export default router
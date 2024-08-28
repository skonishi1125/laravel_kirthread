import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
const BASE_URL = '';

// ベースとなるコンポーネントに組み込むパーツとルーティングを定義
// import About from './components/study/vue-router/About.vue';
const routes = [
  // { 
  //     path: '/study/vue-router/about',
  //     name: 'study.vue-router.about',
  //     component: About
  // },
];

// ルーター作成
const router = createRouter({
  history: createWebHistory(BASE_URL),
  routes,
});

// ベースとなるコンポーネントをimportし、createApp, use, mountする
// import VueRouterTestApp from './components/study/vue-router/VueRouterTestApp.vue';
// const vue_router_test_app = createApp(VueRouterTestApp);
// vue_router_test_app.use(test_router);
// vue_router_test_app.mount('#vue-router-test-app');
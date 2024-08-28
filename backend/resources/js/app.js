import { createApp } from 'vue';
import './import_selfmade';
import './import_sample_component';

// vue-router 動作テスト
import VueRouterTestApp from './components/study/vue-router/VueRouterTestApp.vue';
import router from './router'
const vue_router_test_app = createApp(VueRouterTestApp);
vue_router_test_app.use(router);
vue_router_test_app.mount('#vue-router-test-app');
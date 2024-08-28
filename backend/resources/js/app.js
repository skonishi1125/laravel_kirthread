import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';
import PurchasesComponent from './components/PurchasesComponent.vue';
import VueRouterTestApp from './components/study/vue-router/VueRouterTestApp.vue';

// BootstrapのJavaScriptとCSSをインポート
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

// jQuery読み込み
// ただしこれはapp.jsの範囲でjQueryを書けるだけで、
// その他ファイルでjQueryを使う場合は都度importする必要がある。
import $ from 'jquery';
$(document).ready(function() {
  console.log('vue3.4 and jQuery check and compile_in_app_container');
});

// 汎用のjsファイルを読み込む
// game/panel.jsはそのページでしか使わないので、そちらで読み込ませる。
import './selfmade/script.js';
import './selfmade/stopwatch.js';
import './selfmade/csv_download.js';
import './selfmade/reaction.js';

document.addEventListener('DOMContentLoaded', () => {
// 条件付きでマウントすることで、必要なページでのみコンポーネントを表示する
  const exampleComponentElement = document.getElementById('exampleComponent');
  if (exampleComponentElement) {
      createApp(ExampleComponent).mount('#exampleComponent');
  }

  // テストページ
  const purchasesComponentElement = document.getElementById('purchasesComponent');
  if (purchasesComponentElement) {
    createApp(PurchasesComponent).mount('#purchasesComponent');
  }
});

import router from './router'
const vue_router_test_app = createApp(VueRouterTestApp);
vue_router_test_app.use(router);
vue_router_test_app.mount('#vue-router-test-app');
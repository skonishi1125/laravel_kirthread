// BootstrapのJavaScriptとCSSをインポート
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';
import PurchasesComponent from './components/PurchasesComponent.vue';

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
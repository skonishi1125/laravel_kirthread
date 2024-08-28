import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import './import_selfmade';

// サンプル 不要な場合はコメントアウトして構わない。
// import './import_sample_component'; // テストで作ったpurchaseなどの動作確認用
// import './vue_router_test'; // vue-routerのサンプル

// vue宣言
import { createApp } from 'vue';
import './router'; // 出すコンポーネントなどは`./router.js`で管理する。

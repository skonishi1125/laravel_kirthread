/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default); 下記に書き直した
Vue.component('purchases-component', require('./components/PurchasesComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';
import TestComponent from './components/TestComponent.vue';
import MessageEditor from './components/MessageEditor.vue';
import IfTest from './components/IfTest.vue';
import LoopTest from './components/study/techbook/vue/LoopTest.vue';

const app = new Vue({
    el: '#app',
    components: {
        'test-component': TestComponent,
        'message-editor': MessageEditor
    }
});

const ifTest = new Vue({
  el: '#if_test', 
  components: {
      'if-test': IfTest
  },
});

const loopTest = new Vue({
  el: '#loop_test', // htmlのid部分 <div id="loop_test">
  components: {
      'loop-test': LoopTest // コンポーネントを呼ぶ値の部分 <loop-test>
  }
});

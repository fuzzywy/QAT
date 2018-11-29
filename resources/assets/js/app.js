window._ = require('lodash');
try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {}

window.axios = require('axios');

window.qs = require('qs');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue = require('vue');

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI, { size: 'big', zIndex: 3000 });

import router from './routes.js';

import store from './store.js';

window.BootstrapVue  = require('bootstrap-vue');

window.VueHighcharts = require('vue-highcharts');
window.Highcharts = require('highcharts');

//data transform
const bus = new Vue()
Vue.prototype.bus = bus

const app = new Vue({
  router,
  store
}).$mount('#app')

//Vue
window.Vue = require('vue');
//插件
import './plugins';
//路由
import router from './routes.js';
//vuex
import store from './store.js';
const app = new Vue({
  router,
  store
}).$mount('#app')
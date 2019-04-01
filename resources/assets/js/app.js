//Vue
window.Vue = require('vue');
//插件
import './plugins';
//路由
import router from './routes.js';
//vuex
import store from './store.js';
window.BootstrapVue  = require('bootstrap-vue');//bootstrap-vue必须用这种方式才有起作用
const app = new Vue({
  router,
  store
}).$mount('#app')
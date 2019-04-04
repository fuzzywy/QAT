//Vue
window.Vue = require('vue');
//插件
import './plugins';
//路由
import router from './routes.js';
//状态管理 
import store from './store.js';

const app = new Vue({
  router,
  store
}).$mount('#app')
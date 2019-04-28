//Vue
window.Vue = require('vue');
//插件
import './plugins';
//路由
import router from './routes.js';
//状态管理 
import store from './store.js';

import i18n from './i18n/i18n.js';

const app = new Vue({
  router,
  store,
  i18n
}).$mount('#app')
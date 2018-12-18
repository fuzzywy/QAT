/**
 * Import Vue and Vuex
 */
window.Vue = require('vue');
window.Vuex = require('vuex');
/**
 * Initializes Vuex on Vue.
 */
Vue.use( Vuex );
/**
 * Imports all of the modules used in the application to build the data store.
 */
import { uploadCog } from './modules/uploadCog.js'
import { qatSubnet } from './modules/qat/getQatSubnet.js'
import { qatTemplate } from './modules/qat/getQatTemplate.js'
import { qatCity } from './modules/qat/getQatCity.js'
import { qatTime } from './modules/qat/getQatTime.js'
import { qatLocation } from './modules/qat/getQatLocation.js'
import { qatData } from './modules/qat/getQatData.js'
/**
 * Export the data store.
 */
export default new Vuex.Store({
    modules: {
        uploadCog,
        qatSubnet,
        qatTemplate,
        qatCity,
        qatTime,
        qatLocation,
        qatData
    }
});
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
import { qatKget } from './modules/qat/getQatKget.js'
import { qatParam } from './modules/qat/getQatParam.js'
import { qatTask } from './modules/qat/getQatTask.js'
import { qatUser } from './modules/qat/getQatUser.js'
import { qatSite } from './modules/qat/getQatSite.js'

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
        qatData,
        qatKget,
        qatParam,
        qatTask,
        qatUser,
        qatSite

    }
});
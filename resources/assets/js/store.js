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
//import { test } from './modules/test.js'
import { citys } from './modules/getCity.js'
import { birdSideBar } from './modules/getBirdSideBar.js'
import { bKpiCard } from './modules/getBKpiCard.js'
import { bLineChart } from './modules/getBLineChart.js'
import { uploadCog } from './modules/uploadCog.js'

import { qatSubnet } from './modules/qat/getQatSubnet.js'
import { qatMoban } from './modules/qat/getQatMoban.js'
import { qatCity } from './modules/qat/getQatCity.js'
import { qatTime } from './modules/qat/getQatTime.js'
import { qatLocation } from './modules/qat/getQatLocation.js'
import { qatData } from './modules/qat/getQatData.js'
/**
 * Export the data store.
 */
export default new Vuex.Store({
    modules: {
    	citys,
    	birdSideBar,
    	bKpiCard,
    	bLineChart,
    	uploadCog,

    	qatSubnet,
    	qatMoban,
    	qatCity,
        qatTime,
        qatLocation,
        qatData
    }
});
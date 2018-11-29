/*
 |-------------------------------------------------------------------------------
 | routes.js
 |-------------------------------------------------------------------------------
 | Contains all of the routes for the application
 */

/**
 * Imports Vue and VueRouter to extend with the routes.
 */
window.Vue = require('vue');
window.VueRouter = require('vue-router');

/**
 * Extends Vue to use Vue Router
 */
Vue.use( VueRouter )

//Qat
import qat from './components/qatOverview/QatComponent.vue';
//配置界面
import bcog from './components/scaleOverview/BCogFormComponent.vue';
import bcogback from './components/scaleOverview/BCogFormBackComponent.vue';
import bcogtable from './components/scaleOverview/BCogFormTableComponent.vue';
//饼图
import bpie from './components/scaleOverview/BPieChartComponent.vue';

/**
 * Makes a new VueRouter that we will use to run all of the routes for the app.
 */
export default new VueRouter({
    routes: [
        { 
            path: '/',
            name: '',
            components: {
                /*HomeComponent:Vue.component('homeComponent', require('./components/scaleOverview/HomeComponent.vue')),*/
                // default:qat,
                QatComponent: qat,
                BCogForm: bcog
            },
            children: [
        		{
        			path: 'eniqtdd'
                    /*redirect: to => {
                        if ( window.location.hash != '/') {
                            return '/';
                        }
                    }*/
        			// component: Vue.component('formgroup', require('./components/qatOverview/qats/Formgroup.vue')),
                    // props: { name: 'world' }
        		},{
        			path: 'eniqfdd'
        			// component: Vue.component('formgroupfdd', require('./components/qatOverview/qats/Formgroupfdd.vue'))
        		},{
        			path: 'eniqgsm'
        		},{
        			path: 'eniqnbiot'
        		},{
        			path: 'eniqalarm'
        		},{
        			path: 'nbmtdd'
        		},{
        			path: 'nbmfdd'
        		},{
        			path: 'nbmnbiot'
        		}
            ]
        },{
            path: '/cog',
            name: 'cog', 
            components: {
                BCogFormBack: bcogback,
                BCogFormTable: bcogtable
            }
        },{
            path: '/piechart',
            name: 'BPieChart',
            components: {
                BPieChart: bpie
            }
        }
    ]
});

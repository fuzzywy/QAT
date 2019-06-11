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
                //主界面
                QatComponent: qat,
            },
            children: [
                {
                    path: 'eniqtdd'
                },{
                    path: 'eniqfdd'
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
            path: '/piechart',
            name: 'BPieChart',
            components: {
                BPieChart: bpie
            }
        }
    ]
});

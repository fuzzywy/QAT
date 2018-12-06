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
//模板
import template from './components/qatOverview/QatTemplateComponent.vue';
//crontab
import crontab from './components/qatOverview/QatCrontabComponent.vue';
//
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
                //配置
                BCogForm: bcog,
                //模板
                TempalteComponent: template,
                //crontab
                CrontabComponent: crontab
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
        },{
            path: '/template',
            name: 'template',
            components: {
                TempalteComponent: template
            }
        },{
            path: '/crontab',
            name: 'crontab',
            components: {
                CrontabComponent: crontab
            } 
        }
    ]
});

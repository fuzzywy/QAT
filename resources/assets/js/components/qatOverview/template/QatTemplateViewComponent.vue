<style scoped>
    
</style>
<template>
    <el-container>
        <el-aside width="25%">
            <template-manage :datasource="datasource" :datatype="datatype"></template-manage>
        </el-aside>
        <el-aside width="25%">
            <kpi-list></kpi-list>
        </el-aside>
        <el-aside width="50%">
            <formula-manage></formula-manage>
        </el-aside>
    </el-container>
</template>
<script>
    import TemplateManage from './TemplateManage.vue';
    import KpiList from './KpiList.vue';
    import FormulaManage from './FormulaManage.vue';
    export default {
        data() {
            return {
                //datasource: this.$route.query.datasource,
                //datatype: this.$route.query.datatype,
                datasource: 'ENIQ',
                dataType: 'TDD',
                templateName: '',
                parent: '',
                grandparent: ''
            }
        },
        components: {
            TemplateManage,
            KpiList,
            FormulaManage
        },
        mounted() {
            // this.bus.$on('templateName', type=>{
            //     this.templateName = type.templateName;
            //     this.parent = type.parent;
            //     this.grandparent = type.grandparent;
            // });
            // if( this.$store.getters.loadQatElementDataStatus == 2 ) {}
        }
    }
</script>
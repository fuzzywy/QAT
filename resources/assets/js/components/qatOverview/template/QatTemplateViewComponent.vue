<style scoped>
    
</style>
<template>
    <el-row >
        <el-col :span="6">
            <template-manage :datasource="datasource" :datatype="datatype"></template-manage>
        </el-col>
        <el-col :span="6">
            <kpi-list></kpi-list>
        </el-col>
        <el-col :span="12">
            <formula-manage></formula-manage>
        </el-col>   
    </el-row>
</template>
<script>
    import TemplateManage from './TemplateManage.vue';
    import KpiList from './KpiList.vue';
    import FormulaManage from './FormulaManage.vue';
    export default {
        data() {
            return {
                datasource: this.$route.query.datasource,
                datatype: this.$route.query.datatype,
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
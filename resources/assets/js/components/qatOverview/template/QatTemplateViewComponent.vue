<style scoped>
    .top {
        top: 30px;
    }
</style>
<template>
    <div>
        <el-row :gutter="20"  class="top">
            <el-col :span="6">
                <tree-data :datasource="datasource" :datatype="datatype"></tree-data>
            </el-col>
            <el-col :span="6">
                <table-element></table-element>
            </el-col>
            <el-col :span="12">
                <table-data></table-data>
            </el-col>   
        </el-row>
    </div>
</template>
<script>
    import TreeData from './TreeData.vue';
    import TableElement from './TableElement.vue';
    import TableData from './TableData.vue';
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
            TreeData,
            TableElement,
            TableData
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
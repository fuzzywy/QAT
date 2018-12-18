<style scoped>
    .top {
        /*width: 100%;
        min-height: 600px;
        background-color: #fff;*/
        /*border: 1px #000 solid;*/
    }
</style>
<template>
    <div>
        <el-card class="box-card" shadow="hover">
            <div slot="header" class="clearfix">
              <span>指标</span>
            </div>
            <el-tree :data="elementData" @node-click="handleNodeClick" :getElementData="getElementData">
            </el-tree>
        </el-card>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                elementData: []
            };
        },
        methods: {
          handleNodeClick(data) {
            console.log(data);
          }
        },
        computed: {
            //loading加载。还未做
            getElementData(){
                if( this.$store.getters.loadQatElementDataStatus == 2 ) {
                    this.elementData = this.$store.getters.loadQatElementData;
                }
            }
        }
    }
</script>
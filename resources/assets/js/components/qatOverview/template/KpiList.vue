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
            <el-tree 
                :data="elementData" 
                @node-click="handleNodeClick" 
                :getElementData="getElementData"
                @node-drag-end="handleDragEnd"
                :draggable="draggable"
                >
                <span class="custom-tree-node" slot-scope="{ node, data }">
                <span>{{ node.label }}</span>
                <span>
                  <el-button v-show="data.showRemove" type="text" size="mini" @click="() => remove(node, data)">
                    Delete
                  </el-button>
                </span>
              </span>
            </el-tree>
        </el-card>
    </div>
</template>
<script>
    import {common} from '../../../mixin/common/commonTemplate.js';
    export default {
        mixins: [
            common
        ],
        data() {
            return {
                elementData: [],
                templateName: '',
                parent: '',
                grandparent: '',
                showRemove: false,
                draggable: true
            };
        },
        methods: {
          handleNodeClick(data) {
            // console.log(this.$store.getters.qatLoginUser);
            // this.processLoadFormulaData(data.id, data.label, this.templateName, this.parent, this.grandparent, this.$store.getters.qatLoginUser);
          },
          remove(node, data) {
            const parent = node.parent;
            const children = parent.data.children || parent.data;
            const index = children.findIndex(d => d.id === data.id);
            // this.processremoveTemplateName(this.$store.getters.qatLoginUser, data.label);
            children.splice(index, 1);
            // if ( this.treeData.length <= 2 ) {
            //   this.treeData[0]['showAppend'] = true
            // }
          },
          handleDragEnd(draggingNode, dropNode, dropType, ev) {
            //防止出现在节点内部
            if ( dropType === 'inner' ) {
                for (var i = 0; i < this.elementData.length; i++) {
                    if(this.elementData[i].hasOwnProperty('children')) {
                        this.elementData.splice(i+1, 0, {"showRemove": true, "label": this.elementData[i].children[0].label, "id":this.elementData[i].children[0].id});
                        this.elementData[i] = {"showRemove": true, "label": this.elementData[i].label, "id":this.elementData[i].id};
                        // console.log(this.elementData);
                        this.processLoadElementOrder(this.elementData, this.templateName, this.parent, this.grandparent, this.$store.getters.qatLoginUser);
                        return;
                    }
                } 
                return;
            }
            // console.log(this.elementData);
            this.processLoadElementOrder(this.elementData, this.templateName, this.parent, this.grandparent, this.$store.getters.qatLoginUser);
            // console.log(dropNode, dropNode.label);
            // console.log('tree drag end: ', dropNode && dropNode.label, dropType);
          }
        },
        computed: {
            //loading加载。还未做
            getElementData(){
                if (this.$store.getters.loadQatElementDataStatus == 2) {
                    switch(this.$store.getters.orderQatElementDataStatus) {
                        case 1:
                            break;
                        case 2:
                            this.elementData = this.$store.getters.orderQatElementData;
                            break;
                        case 3:
                            break;
                        default:
                            break;
                    }
                }

                switch(this.$store.getters.loadQatElementDataStatus) {
                    case 1:
                        break;
                    case 2:
                        this.elementData = this.$store.getters.loadQatElementData;
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }

                //用户可拖拽权限
                if ( this.grandparent !== this.$store.getters.qatLoginUser ) {
                    this.draggable = false;
                }else {
                    this.draggable = true;
                }
            }
        },
        mounted() {
            this.bus.$on('templateName', 
            type=>{ 
                this.templateName = type.templateName, 
                this.parent = type.parent,
                this.grandparent = type.grandparent 
            });
        }
    }
</script>
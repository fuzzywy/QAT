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
                    <i class="el-icon-remove"></i>
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
                draggable: true,
                clickFormulaGrandparent: '',
                clickFormulaParent: '',
                clickFormulaRows: []
                // multipleSelection: [],
                // multipleSelectionFlag: 0
            };
        },
        methods: {
          handleNodeClick(data) {
            //点击数据/全部element数据
            this.processSelectKpiFormula(data, this.elementData);
            // console.log(this.elementData)
            // console.log(this.$store.getters.qatLoginUser);
            // console.log(data.id, data.label, this.templateName, this.parent, this.grandparent, this.$store.getters.qatLoginUser)
            // let d = this.elementData.map(item=>{
            //     if ( item.id === data.id ) {
            //         return item;
            //     }
            // }).filter(function(val){
            //     return !(val === undefined || val === "");
            // })[0];
            // console.log(d);
            /*this.bus.$emit('kpiFormulaName', {
                formulaId: data.id,
                formulaName: data.label,
                templateName: this.templateName,
                parent: this.parent,
                grandparent: this.grandparent,
                loginUser: this.$store.getters.qatLoginUser,
                preventRepeatedExecution: 1
            });*/

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
                        /*this.bus.$emit('elementData', {
                            element: this.elementData
                        })*/
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }
                

                //添加删除kpi list
                // if ( this.grandparent === this.$store.getters.qatLoginUser ) {
                    /*let arr = [];
                    this.elementData = [];
                    // console.log(this.multipleSelection)
                    for (var i = 0; i < this.multipleSelection.length; i++) {
                        if( this.grandparent != this.$store.getters.qatLoginUser ) {
                            arr = { id: this.multipleSelection[i]['id'], label: this.multipleSelection[i]['name'], showRemove: false };
                        }else{
                            arr = { id: this.multipleSelection[i]['id'], label: this.multipleSelection[i]['name'], showRemove: true };
                        }
                        this.elementData.push(arr);
                    }*/
                // }else {
                //     this.$message({
                //         message: '你没有权限修改'+this.grandparent,
                //         type: 'warning'
                //     });
                // }

                //用户可拖拽权限
                if ( this.$store.getters.qatLoginUser == 'admin' ) {
                    this.draggable = true;
                }else if ( this.grandparent !== this.$store.getters.qatLoginUser ) {
                    this.draggable = false;
                }else {
                    this.draggable = true;
                }
            }
        },
        watch: {
            clickFormulaRows() {
                // let expect = this.elementData.map(item=>{
                //     if ( item.parent == this.clickFormulaParent && item.grandparent == this.clickFormulaGrandparent ) {
                //         return item.id;
                //     }
                // }).filter(function(val){
                //     return !(val === undefined || val === "");
                // });
                // console.log(expect);
                let t = this.elementData.map(item=>{
                    if ( item.parent != this.clickFormulaParent || item.grandparent != this.clickFormulaGrandparent ) {
                        return item;
                    }
                }).filter(function(val){
                    return !(val === undefined || val === "");
                });
                // console.log(this.elementData);
                // console.log(this.clickFormulaParent, this.clickFormulaGrandparent );
                // console.log(t);
                this.elementData = [];
                let s = this.clickFormulaRows.map(item=>{
                    return {'id': item.id, 'kpiName': item.name, 'format': this.clickFormulaParent, 'user': this.clickFormulaGrandparent, 'showRemove': 'true', 'label': item.name, 'parent': this.clickFormulaParent, 'grandparent': this.clickFormulaGrandparent};
                });
                // console.log(s);
                this.elementData = s.concat(t);
                this.processInsertElement(this.templateName, this.parent, this.grandparent, this.elementData.map(item=>{
                    return item.id;
                }));
            }
            /*multipleSelection() {
                if(this.multipleSelectionFlag == 1) {
                    // this.elementData = this.multipleSelection;
                    // console.log(this.elementData);
                    // console.log(this.multipleSelection);
                    // console.log(this.parent, this.grandparent)
                    let d = this.elementData.map(item=>{
                        if( item.grandparent == this.grandparent && item.parent == this.parent ) {
                            
                        }else {
                            return item;
                        }
                    });
                    // console.log(d);
                    // console.log(this.multipleSelection, this.elementData)
                    this.multipleSelectionFlag = 0;
                }
            }*/
        },
        mounted() {
            this.bus.$on('templateName', 
            type=>{ 
                this.templateName = type.templateName, 
                this.parent = type.parent,
                this.grandparent = type.grandparent
            });
            /*this.bus.$on('multipleSelection', 
                type=>{
                    this.multipleSelection = type.multipleSelection,
                    this.multipleSelectionFlag = type.multipleSelectionFlag
                });*/
            this.bus.$on('selectFormulaByClick', item=>{
                this.clickFormulaGrandparent = item.clickFormulaGrandparent;
                this.clickFormulaParent = item.clickFormulaParent;
                this.clickFormulaRows = item.clickFormulaRows;
            });
        }
    }
</script>
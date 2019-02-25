<template>
    <div>
        <el-card class="box-card" shadow="hover">
            <div slot="header" class="clearfix">
              <span>指标 {{templateName}}</span>
            </div>
            <!-- <el-tree 
                :data="elementData" 
                @node-click="handleNodeClick" 
                :getElementData="getElementData"
                @node-drag-end="handleDragEnd"
                :draggable="draggable"
                >
                <span class="custom-tree-node" style="width: -webkit-fill-available" slot-scope="{ node, data }">
                    <span style="float: left;">{{ node.label }}</span>
                    <span style="float: right;">
                      <el-button v-show="data.showRemove" type="text" size="mini" @click="() => remove(node, data)">
                        <i class="el-icon-delete"></i>
                      </el-button>
                    </span>
                </span>
            </el-tree> -->
            <el-scrollbar :native="false" wrapStyle="" wrapClass="" viewClass="" viewStyle="" tag="section">
                <div style="max-height: -webkit-fill-available;">
                    <el-tree
                        v-loading="elementLoading"
                        :data="elementData" 
                        @node-click="handleNodeClick" 
                        :getElementData="getElementData"
                        @node-drag-end="handleDragEnd"
                        :draggable="draggable"
                        :render-content="renderContent">
                    </el-tree>
                </div>
            </el-scrollbar>
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
                clickFormulaRows: [],
                clickFormulaRowsFlag: 0,
                elementLoading: false
            };
        },
        methods: {
          renderContent(h, { node, data, store }) {
                return h('span', {
                    style: {
                        color: "",
                        width: '-webkit-fill-available',
                        float: 'left'
                    },
                    on: {
                        'mouseenter': () => {
                          data.showRemove = true;
                        },
                        'mouseleave': () => {
                          data.showRemove = false;
                        }
                    }
                }, [
                        h('span', {
                        }, node.label),
                        h('span', {
                            style: {
                                float: 'right',
                                display: data.showRemove ? '' : 'none',
                            },
                        }, [
                            h('el-button', {
                                props: {
                                    type: 'text',
                                    size: 'small',
                                },
                                style: {
                                    margin: '0 10px 0 0'
                                },
                                on: {
                                    click: () => {
                                        this.remove(node, data)
                                    }
                                }
                            }, [h('i', {class:'el-icon-delete'})]),
                        ]),
                    ]);
          },
          handleNodeClick(data) {
            //点击数据/全部element数据
            this.processSelectKpiFormula(data, this.elementData);
          },
          remove(node, data) {
            const parent = node.parent;
            const children = parent.data.children || parent.data;
            const index = children.findIndex(d => d.id === data.id);
            this.processDeleteElement(children[index]['id'], this.templateName, this.parent, this.grandparent);
            children.splice(index, 1);
          },
          handleDragEnd(draggingNode, dropNode, dropType, ev) {
            //防止出现在节点内部
            if ( dropType === 'inner' ) {
                for (var i = 0; i < this.elementData.length; i++) {
                    if(this.elementData[i].hasOwnProperty('children')) {
                        this.elementData.splice(i+1, 0, {"showRemove": true, "label": this.elementData[i].children[0].label, "id":this.elementData[i].children[0].id});
                        this.elementData[i] = {"showRemove": true, "label": this.elementData[i].label, "id":this.elementData[i].id};
                        this.processLoadElementOrder(this.elementData, this.templateName, this.parent, this.grandparent, this.$store.getters.qatLoginUser);
                        return;
                    }
                } 
                return;
            }
            this.processLoadElementOrder(this.elementData, this.templateName, this.parent, this.grandparent, this.$store.getters.qatLoginUser);
          }
        },
        computed: {
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
                if (this.clickFormulaRowsFlag == 1) {
                    switch(this.$store.getters.insertQatElementStatus) {
                        case 1:
                            break;
                        case 2:
                            this.elementData = this.$store.getters.insertQatElement;
                            this.clickFormulaRowsFlag = 0;
                            this.processloadElementData( this.templateName, this.parent, this.grandparent, this.$store.getters.qatLoginUser );
                            break;
                        case 3:
                            break;
                        default:
                            break;
                    }  
                }          
                switch(this.$store.getters.loadQatElementDataStatus) {
                    case 1:
                        this.elementLoading = true;
                        break;
                    case 2:
                        this.elementLoading = false;
                        this.elementData = this.$store.getters.loadQatElementData;
                        break;
                    default:
                        this.elementLoading = false;
                        break;
                }
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
            elementData() {
                this.bus.$emit('elementData', {
                    element: this.elementData
                });
            },
            clickFormulaRows() {
                let t = this.elementData.map(item=>{
                    if ( item.parent != this.clickFormulaParent || item.grandparent != this.clickFormulaGrandparent ) {
                        return item;
                    }
                }).filter(function(val){
                    return !(val === undefined || val === "");
                });
                this.elementData = [];
                let s = this.clickFormulaRows.map(item=>{
                    return {'id': item.id, 'kpiName': item.name, 'format': this.clickFormulaParent, 'user': this.clickFormulaGrandparent, 'showRemove': 'true', 'label': item.name, 'parent': this.clickFormulaParent, 'grandparent': this.clickFormulaGrandparent};
                });
                this.elementData = s.concat(t);
                this.processInsertElement(this.templateName, this.parent, this.grandparent, this.elementData.map(item=>{
                    return item.id;
                }));
                this.clickFormulaRowsFlag = 1;
            }
        },
        mounted() {
            this.bus.$on('templateName', 
            type=>{ 
                this.templateName = type.templateName, 
                this.parent = type.parent,
                this.grandparent = type.grandparent
            });
            this.bus.$on('selectFormulaByClick', item=>{
                this.clickFormulaGrandparent = item.clickFormulaGrandparent;
                this.clickFormulaParent = item.clickFormulaParent;
                this.clickFormulaRows = item.clickFormulaRows;
            });
        }
    }
</script>
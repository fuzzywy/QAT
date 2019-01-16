<template>
    <div>
      <input style="display: none;" :loadTemplateData="computedLoadTemplateData">
      <el-card class="box-card" shadow="hover">
        <div slot="header" class="clearfix">
          <span>模板</span>
            <el-button 
                  style="float: right; padding: 3px 0"
                  type="text" 
                  @click="dialogFormVisible = true"
              ><i class="el-icon-plus"></i>
            </el-button>
            <el-dialog title="新建模板" :visible.sync="dialogFormVisible">
                <el-form :model="form">
                    <el-form-item label="名称" :label-width="formLabelWidth">
                        <el-input placeholder="请输入模板名" v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="制式" :label-width="formLabelWidth">
                        <el-select v-model="form.mode" placeholder="请输入制式">
                            <el-option label="TDD" value="tdd"></el-option>
                            <el-option label="FDD" value="fdd"></el-option>
                            <el-option label="NBIOT" value="nbiot"></el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="dialogFormVisible = false">取 消</el-button>
                    <el-button type="primary" @click="newTemplate()">确 定</el-button>
                </div>
            </el-dialog>
        </div>
        <el-input
          placeholder="输入关键字进行过滤"
          v-model="filterText">
        </el-input>
        <!-- <el-tree 
          accordion
          :user="getLoginUser"
          :data="treeData"
          @node-click="nodeClick"
          node-key="id"
          :expand-on-click-node="false"
          :default-expand-all="false"
          :filter-node-method="filterNode"
          ref="tree"
          :highlight-current="true"
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
        <el-tree
              accordion
              :user="getLoginUser"
              :data="treeData"
              @node-click="nodeClick"
              node-key="id"
              :expand-on-click-node="false"
              :default-expand-all="false"
              :filter-node-method="filterNode"
              ref="tree"
              :highlight-current="true"
              :render-content="renderContent">
          </el-tree>
      </el-card>
    </div>
</template>
<script>
  import {common} from '../../../mixin/common/commonTemplate.js';
  let id = -1;
  export default {
    props: {
      datasource: {
        type: String,
        default: "ENIQ"
      },
      datatype: {
        type: String,
        default: "TDD"
      }
    },
    mixins: [
      common
    ],
    data() {
      const data = [{
        id: 1,
        label: '通用模板',
        showRemove: false,
        children: [{
          id: 4,
          label: 'test4'
        }]
      }, {
        id: 3,
        label: '系统模板',
        showRemove: false,
        children: [{
          id: 7,
          label: 'test7'
        }, {
          id: 8,
          label: 'test8'
        }]
      }];
      return {
        filterText: '',
        treeData: [],
        dialogFormVisible: false,
        form: {
          name: '',
          mode: 'TDD'
        },
        formLabelWidth: '50px',
        addFlag: 0
      };
    },
    watch: {
      filterText(val) {
        this.$refs.tree.filter(val);
      },
      addFlag() {
        if(this.addFlag == 1) {
          this.addFlag = 0;
          this.processLoadTemplateData(this.datasource, this.datatype);
        }
      }
    },
    computed: {
      getLoginUser() {
        this.processLoadingLoginUser();
      },
      computedLoadTemplateData() {
        //显示
        switch( this.$store.getters.qatTemplateDataStatus ) {
            case 1:
                break;
            case 2:
                this.treeData = this.$store.getters.qatTemplateData;
                break;
            case 3:
                break;
            default:
                break;
        }
        //增加
        switch(this.$store.getters.addQatTemplateStatus) {
          case 1:
          case 2:
              break;
          case 3:
              break;
          default:
              break;
        }
        //插入
        /*switch( this.$store.getters.insertTemplateNameStatus ) {
            case 1:
                break;
            case 2:
                this.treeData = this.$store.getters.insertTemplateName;
                break;
            case 3:
                break;
            default:
                break;
        }*/
        //删除
        switch( this.$store.getters.removeTemplateNameStatus ) {
            case 1:
                break;
            case 2:
                this.treeData = this.$store.getters.removeTemplateName;
                break;
            case 3:
                break;
            default:
                break;
        }
      }
    },
    mounted() {
        this.processLoadTemplateData(this.datasource, this.datatype);
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
      filterNode(value, data) {
        if (!value) return true;
        return data.label.indexOf(value) !== -1;
      },
      /*append(data) {
        //如果用户目录下没有模板
        if ( data.label === '通用模板' || this.treeData.length <= 2 ) {
          //获取登陆用户
          if ( this.$store.getters.qatLoginUserStatus == 2 ) {
            this.treeData.push( { id: id--, label: this.$store.getters.qatLoginUser, showAppend: true, showRemove: true} );
            data.showAppend = false;
          }else {
            this.$message.error('添加失败,获取不到用户数据,请重试！');
          }
          return;
        }
        this.$prompt('请输入模板名称', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          inputPattern: /^[_a-zA-Z\u4e00-\u9fa5][0-9a-zA-Z\u4e00-\u9fa5]*$/,//以中英文_开头并只能包含数字中英文
          inputErrorMessage: '模板格式不正确'
        }).then(({ value }) => {
          this.$message({
            type: 'success',
            message: '你输入的模板是: ' + value
          });
          //processload
          for (var i = 0; i < data.children.length; i++) {
            if(data.children[i].label === value) {
                this.$message({
                    type: 'warning',
                    message: '该模板已存在,请重新命名！'
                });
                return;
            }
          }
          //这里需要进一步优化代码
          this.processinsertTemplateName(this.$store.getters.qatLoginUser, value, data.label);
          // const newChild = { id: id--, label: value, children: [], showAppend: false, showRemove: true };
          const newChild = { id: id--, label: value, children: [], showRemove: true };
          if (!data.children) {
            this.$set(data, 'children', []);
          }
          data.children.push(newChild);
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '取消输入'
          });       
        });
      },*/
      remove(node, data) {
        const parent = node.parent;
        const children = parent.data.children || parent.data;
        const index = children.findIndex(d => d.id === data.id);
        this.$confirm('此操作将永久删除 '+ data.label +' 模板, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.processremoveTemplateName(this.$store.getters.qatLoginUser, data.label, data.id);
          children.splice(index, 1);
          // if ( this.treeData.length <= 2 ) {
          //   this.treeData[0]['showAppend'] = true
          // }
          this.$message({
            type: 'success',
            message: '删除成功!'
          });
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消删除'
          });          
        });
      },
      nodeClick(node, data, self) {
        // console.log(node, this.treeData)
        if( !node.hasOwnProperty('children') ) {
          this.bus.$emit('templateName', 
          { 
            templateName: node.label, 
            parent: data.parent.data.label,
            grandparent: data.parent.parent.data.label,
            flag: 1
          });
          this.processloadElementData( node.label, data.parent.data.label, data.parent.parent.data.label, this.$store.getters.qatLoginUser );
        } else {
          this.$message({
            type: 'warning',
            message: '请选择子目录！'
          });
          return;
        }
      },
      newTemplate() {
        this.dialogFormVisible = false;
        this.addFlag = 1;
        this.processAddTempalte(this.form.name, this.form.mode);
      }
    }
  };
</script>

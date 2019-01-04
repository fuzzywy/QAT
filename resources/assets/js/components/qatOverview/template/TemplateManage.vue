<style scoped>
    .top {
        /*width: 100%;*/
        /*min-height: 600px;*/
        /*background-color: #fff;*/
        /*border: 1px #000 solid;*/
    }/*,
    .custom-tree-node {
      flex: 1;*/
      /*display: flex;*/
      /*align-items: center;
      justify-content: space-between;
      font-size: 14px;
      padding-right: 8px;
    }*/
</style>
<template>
    <div class="top">
      <input style="display: none;" :loadTemplateData="computedLoadTemplateData">
      <el-card class="box-card" shadow="hover">
        <div slot="header" class="clearfix">
          <span>模板</span>
        </div>
        <el-input
          placeholder="输入关键字进行过滤"
          v-model="filterText">
        </el-input>
        <el-tree 
          accordion
          :user="getLoginUser"
          :data="treeData"
          @node-click="nodeClick"
          node-key="id"
          :expand-on-click-node="false"
          default-expand-all
          :filter-node-method="filterNode"
          ref="tree"
          >
          <span class="custom-tree-node" slot-scope="{ node, data }">
            <span>{{ node.label }}</span>
            <span class="test">
              <el-button v-show="data.showAppend" type="text" size="mini" @click="() => append(data)">
                <i class="el-icon-circle-plus"></i>
              </el-button>
              <el-button v-show="data.showRemove" type="text" size="mini" @click="() => remove(node, data)">
                <i class="el-icon-remove"></i>
              </el-button>
              <!-- <i class="el-icon-circle-plus" v-show="data.showAppend" size="mini" @click="() => append(data)"></i>
              <i class="el-icon-remove" v-show="data.showAppend" size="mini" @click="() => remove(node, data)"></i> -->

            </span>
          </span>
        </el-tree>
      </el-card>
      <!-- {{this.$router.params.datasource}} -->
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
        showAppend: true,
        showRemove: false,
        children: [{
          id: 4,
          label: 'test4'
        }]
      }/*, {
        id: 2,
        label: '用户模板',
        showAppend: true,
        showRemove: true,
        children: [{
          id: 5,
          label: 'test5'
        }, {
          id: 6,
          label: 'test6'
        }]
      }*/, {
        id: 3,
        label: '系统模板',
        showAppend: false,
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
        // treeData: JSON.parse(JSON.stringify(data))
        treeData: []
      };
    },
    watch: {
      filterText(val) {
        this.$refs.tree.filter(val);
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
        //插入
        switch( this.$store.getters.insertTemplateNameStatus ) {
            case 1:
                break;
            case 2:
                this.treeData = this.$store.getters.insertTemplateName;
                break;
            case 3:
                break;
            default:
                break;
        }
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
      filterNode(value, data) {
        if (!value) return true;
        return data.label.indexOf(value) !== -1;
      },
      append(data) {
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
          const newChild = { id: id--, label: value, children: [], showAppend: false, showRemove: true };
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
      },
      remove(node, data) {
        const parent = node.parent;
        const children = parent.data.children || parent.data;
        const index = children.findIndex(d => d.id === data.id);
        this.processremoveTemplateName(this.$store.getters.qatLoginUser, data.label, data.id);
        children.splice(index, 1);
        if ( this.treeData.length <= 2 ) {
          this.treeData[0]['showAppend'] = true
        }
      },
      nodeClick(node, data, self) {
        if( !node.hasOwnProperty('children') ) {
          this.bus.$emit('templateName', 
          { 
            templateName: node.label, 
            parent: data.parent.data.label,
            grandparent: data.parent.parent.data.label
          });
          this.processloadElementData( node.label, data.parent.data.label, data.parent.parent.data.label, this.$store.getters.qatLoginUser );
        } else {
          this.$message({
            type: 'warning',
            message: '请选择子目录！'
          });
          return;
        }
      }
    }
  };
</script>

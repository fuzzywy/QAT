<style scoped>
    .demo-table-expand {
        font-size: 0;
    }
    .demo-table-expand label {
        width: 90px;
        color: #99a9bf;
    }
    .demo-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 50%;
    }
</style>
<template>
    <div>
        <input style="display: none;" id="input" :getFormulaData="loadQatFormulaData" :filterFormula="filterFormula">
        <el-card class="box-card" shadow="hover">
            <div slot="header" class="clearfix">
                <span>公式</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="newFormula">新建</el-button>
            </div>
                <!-- .filter(data => !search || 
                        (
                            data.name.toLowerCase().includes(search.toLowerCase())
                        )
                    ) -->
            <el-table
                :data="tableData"
                style="width: 100%"
                
                :row-key="getRowKeys" 
                :expand-row-keys="expands" 
                @expand-change="expandChange"
                >
                <el-table-column type="expand">
                    <el-table
                        :show-header=false
                        :data="typeData"

                        :row-key="getFormulaKeys" 
                        :expand-row-keys="expandsFormula" 
                        @expand-change="expandFormulaChange"
                    >
                        <el-table-column type="expand">
                            <el-table
                                ref="multipleTable"
                                tooltip-effect="dark"
                                @selection-change="handleSelectionChange"
                                :show-header=false
                                :data="formulaData"
                                @cell-mouse-enter="cellMouseEnter"
                                @cell-mouse-leave="cellMouseLeave"
                                :row-key="getDetailedKeys" 
                                :expand-row-keys="expandsDetailed" 
                            >
                                <el-table-column
                                    type="selection"
                                    width="55">
                                </el-table-column>
                                <el-table-column
                                    label="Type"
                                    prop="name">
                                </el-table-column>
                                <el-table-column type="expand">
                                    <template slot-scope="props">
                                        <el-form label-position="left" inline class="demo-table-expand" v-show="showColumn">
                                            <el-form-item label="Id">
                                                <span>{{ props.row.id }}</span>
                                            </el-form-item>
                                            <el-form-item label="Name">
                                                <span>{{ props.row.name }}</span>
                                            </el-form-item>
                                            <el-form-item label="Formula">
                                                <span>{{ props.row.formula }}</span>
                                            </el-form-item>
                                            <el-form-item label="Precision">
                                                <span>{{ props.row.precision }}</span>
                                            </el-form-item>
                                        </el-form>
                                        <el-form label-position="left" inline class="demo-table-expand" v-show="showModifyColumn">
                                            <el-form-item label="Id">
                                                <span>{{ props.row.id }} 123</span>
                                            </el-form-item>
                                            <el-form-item label="Name">
                                                <span>{{ props.row.name }} 456</span>
                                            </el-form-item>
                                            <el-form-item label="Formula">
                                                <span>{{ props.row.formula }} 779</span>
                                            </el-form-item>
                                            <el-form-item label="Precision">
                                                <span>{{ props.row.precision }}</span>
                                            </el-form-item>
                                        </el-form>
                                    </template>
                                </el-table-column>
                                <el-table-column label="操作">
                                    <template slot-scope="scope">
                                        <el-button 
                                            v-show="showEdit"
                                            size="mini"
                                            @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                                        <el-button
                                            v-show="showDelete"
                                            size="mini"
                                            type="danger"
                                            @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-table-column>
                        <el-table-column
                            label="Type"
                            prop="type">
                        </el-table-column>
                    </el-table>
                </el-table-column>
                <el-table-column
                    label="Name"
                    prop="name">
                </el-table-column>
                <el-table-column
                    align="right">
                    <template slot="header" slot-scope="scope">
                        <el-input
                            v-model="search"
                            size="mini"
                            placeholder="输入关键字搜索"/>
                    </template>
                </el-table-column>
            </el-table>
        </el-card>
        <!-- <div style="margin-top: 20px">
            <el-button @click="toggleSelection([formulaData[0], formulaData[1]])">切换第二、第三行的选中状态</el-button>
            <el-button @click="toggleSelection()">取消选择</el-button>
        </div> -->
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
                search: '',
                tableData: [
                    {
                        id: 0,
                        name: '通用模板'
                    }
                ],
                typeData: [
                    {
                        id: 0,
                        type: "TDD"
                    },{
                        id: 1,
                        type: "FDD"
                    },{
                        id: 2,
                        type: "NB"
                    }
                ],
                formulaData: [],
                multipleSelection: [],
                getRowKeys(row) {
                    return row.id;
                },
                getFormulaKeys(row) {
                    return row.id;
                },
                getDetailedKeys(row) {
                    return row.id;
                },
                expands: [],
                expandsFormula: [],
                expandsDetailed: [],
                id: '',
                name: '',
                showEdit: false,
                showDelete: false,
                showColumn: true,
                showModifyColumn: false,

                formulaId: '',
                formulaName: '',
                templateName: '',
                parent: '',
                grandparent: '',
                loginUser: '',
                preventRepeatedExecution: 0,
                handleFormulaTableOpen: 0,

                elementData: []
            }
        },
        computed: {
            loadQatFormulaData() {
                switch(this.$store.getters.loadQatFormulaDataStatus) {
                    case 1:
                        break;
                    case 2:
                        this.tableData = this.$store.getters.loadQatFormulaData;
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }
            },
            filterFormula() {
                let str = this.search.toLowerCase().trim();
                this.expands = [];
                this.expandsFormula = [];
                if ( str === '' ) {
                    this.tableData = [];
                    if (this.$store.getters.loadQatFormulaDataStatus == 2) {
                        this.tableData = this.$store.getters.loadQatFormulaData;
                    }
                } else {
                    let f = item => {
                        if ( item['children'] ) {
                            item['children'] = item['children'].filter(f);
                            return true;
                        }  else if ( item['name'] || item['formula'] ) {
                            return (item['name'].toLowerCase().indexOf(str) !== -1) 
                                || (item['formula'].toLowerCase().indexOf(str) !== -1);
                        } else {
                            return false;
                        }
                    }
                    this.tableData  = this.tableData.filter(f);
                }
            }
        },
        watch: {
            search() {
                this.processLoadFormulaData();
            },
            formulaId() {
                if(this.preventRepeatedExecution==1) {
                    this.preventRepeatedExecution = 0;
                    this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
                }
            },
            formulaName() {
                if(this.preventRepeatedExecution==1) {
                    this.preventRepeatedExecution = 0;
                    this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
                }
            },
            // templateName() {
            //     if(this.preventRepeatedExecution==1) {
            //         this.preventRepeatedExecution = 0;
            //         this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
            //     }
            // }, 
            // parent() {
            //     if(this.preventRepeatedExecution==1) {
            //         this.preventRepeatedExecution = 0;
            //         // this.preventRepeatedExecution = 'parent';
            //         this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
            //     }
            // },
            // grandparent() {
            //     if(this.preventRepeatedExecution==1) {
            //         this.preventRepeatedExecution = 0;
            //         this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
            //     }
            // },
            // loginUser() {
            //     if(this.preventRepeatedExecution==1) {
            //         this.preventRepeatedExecution = 0;
            //         this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
            //     }
            // },
            elementData() {
                this.expands = [];
                this.expandsFormula = [];
                let expandsId = this.tableData.map(item=>{
                    if ( item.name === this.grandparent ) {
                        return item.id;
                    }
                }).filter(function(val){
                    return !(val === undefined || val === "");
                });
                this.expands = [expandsId[0]];
                let expandsFormulaId = this.tableData[expandsId[0]].children.map(item=>{
                    if ( item.name === this.parent ) {
                        return item.id;
                    }
                }).filter(function(val){
                    return !(val === undefined || val === "");
                });
                this.expandsFormula = [expandsFormulaId[0]];
                this.formulaData = this.tableData[expandsId[0]].children[expandsFormulaId[0]].children.map(formula=> {
                    return {id: formula.id, name: formula.name, formula: formula.formula, precision:formula.precision};
                });
                let elementData = this.elementData;
                let data = this.formulaData;
                let f = this.toggleSelection;
                setTimeout( function(){
                    f(data.map(item=>{
                        for(var i=0; i<elementData.length; i++) {
                            if( item.name == elementData[i].label && item.id == elementData[i].id ) {
                                return item;
                            }
                        }
                    }).filter(function(val){
                        return !(val === undefined || val === "");
                    }));
                }, 0);
            },
            multipleSelection() {
                this.bus.$emit('multipleSelection', {
                    multipleSelection: this.multipleSelection,
                    templateName: this.templateName,
                    parent: this.parent,
                    grandparent: this.grandparent,
                    multipleSelectionFlag: 1
                });
            }
        },
        mounted() {
            this.processLoadFormulaData();
            //获取kpilist点击数据
            this.bus.$on('kpiFormulaName', type=>{
                this.formulaId = type.formulaId,
                this.formulaName = type.formulaName,
                // this.templateName = type.templateName,
                // this.parent = type.parent,
                // this.grandparent = type.grandparent,
                this.loginUser = type.loginUser,
                this.preventRepeatedExecution = type.preventRepeatedExecution
            });
            //获取template点击数据
            this.bus.$on('templateName', type=>{ 
                this.templateName = type.templateName, 
                this.parent = type.parent,
                this.grandparent = type.grandparent 
            });
            //获取all kpilist
            this.bus.$on('elementData', type=>{
                this.elementData = type.element
            })
        },
        methods: {
            handleFormulaTable(formulaId, formulaName, templateName, parent, grandparent, loginUser){
                this.handleFormulaTableOpen = 1;
                this.expands = [];
                this.expandsFormula = [];
                let expandsId = this.tableData.map(item=>{
                    if ( item.name === grandparent ) {
                        return item.id;
                    }
                }).filter(function(val){
                    return !(val === undefined || val === "");
                });
                this.expands = [expandsId[0]];
                let expandsFormulaId = this.tableData[expandsId[0]].children.map(item=>{
                    if ( item.name === parent ) {
                        return item.id;
                    }
                }).filter(function(val){
                    return !(val === undefined || val === "");
                });
                this.expandsFormula = [expandsFormulaId[0]];
                this.formulaData = this.tableData[expandsId[0]].children[expandsFormulaId[0]].children.map(formula=> {
                    return {id: formula.id, name: formula.name, formula: formula.formula, precision:formula.precision};
                });
                // var data = this.formulaData;
                // var f = this.toggleSelection;
                // setTimeout( function(){
                //     f(data.map(item=>{return item;}))
                // }, 0 );
                this.expandsDetailed = [formulaId];
                let elementData = this.elementData;
                let data = this.formulaData;
                let f = this.toggleSelection;
                setTimeout( function(){
                    f(data.map(item=>{
                        for(var i=0; i<elementData.length; i++) {
                            if( item.name == elementData[i].label && item.id == elementData[i].id ) {
                                return item;
                            }
                        }
                    }).filter(function(val){
                        return !(val === undefined || val === "");
                    }))
                }, 0);
            },
            expandChange(row, expandedRows) {
                if( this.handleFormulaTableOpen == 1 ) {
                    this.expands = [];
                    this.expandsFormula = [];
                    this.handleFormulaTableOpen = 0;
                    return;
                }
                let temp = this.expands;
                this.expands = [];
                this.typeData = [];
                this.expands.push(row.id);
                if(temp.length === 1 && temp[0] === row.id){     // 收起展开行
                    this.expands = [];
                }else {
                    let arr = this.tableData.map(item=> {
                        if( row.id === item.id ) {
                            return item;
                        }
                    });
                    for (var i = arr.length - 1; i >= 0; i--) {
                        if( typeof(arr[i]) !== "undefined" ){
                            this.id = arr[i].id;
                            this.name = arr[i].name;
                            this.typeData = arr[i].children.map(type=> {
                                return {id: type.id, type: type.name};
                            });
                        }
                    }
                }
            },
            expandFormulaChange(row, expandedRows) {
                if( this.handleFormulaTableOpen == 1 ) {
                    this.expands = [];
                    this.expandsFormula = [];
                    this.handleFormulaTableOpen = 0;
                    return;
                }
                let temp = this.expandsFormula;
                this.expandsFormula = [];
                this.formulaData = [];
                this.expandsFormula.push(row.id);
                if(temp.length === 1 && temp[0] === row.id){     // 收起展开行
                    this.expandsFormula = [];
                }else {
                    let arr = this.tableData.map(item=> {
                        if( this.id === item.id ) {
                            return item;
                        }
                    });
                    let filter = [];
                    for (var i = arr.length - 1; i >= 0; i--) {
                        if( typeof(arr[i]) !== "undefined" ){
                            filter = arr[i];
                        }
                    }

                    let arrs = filter.children.map(item=> {
                        if( row.id === item.id ) {
                            return item;
                        }
                    });

                    for (var i = arrs.length - 1; i >= 0; i--) {
                        if( typeof(arrs[i]) !== "undefined" ){
                            this.formulaData = arrs[i].children.map(formula=> {
                                return {id: formula.id, name: formula.name, formula: formula.formula, precision:formula.precision};
                            });
                        }
                    }
                }
            },
            cellMouseEnter( row, column, cell, event ) {
                this.showEdit = false;
                this.showDelete = false;
                let name = this.tableData.map(name=>{
                    if( name.id === this.expands[0] ) {
                        return name.name;
                    }
                }).filter(function(val){
                    return !(!val || val === "");
                });
                if ( this.$store.getters.qatLoginUserStatus == 2 && this.$store.getters.qatLoginUser == name[0] ) {
                    this.showEdit = true;
                    this.showDelete = true;
                }
            },
            cellMouseLeave( row, column, cell, event ) {
                this.showEdit = false;
                this.showDelete = false;
            },
            handleEdit(index, row) {
                this.expandsDetailed = [];
                this.showColumn = false;
                this.showModifyColumn = true;
                this.expandsDetailed.push(row.id);
            },
            handleDelete(index, row) {
                //删除公式
            },
            newFormula() {
                //新建公式
                // this.$prompt('请输入邮箱', '提示', {
                //     confirmButtonText: '确定',
                //     cancelButtonText: '取消',
                //     inputPattern: /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/,
                //     inputErrorMessage: '邮箱格式不正确'
                // }).then(({ value }) => {
                //     this.$message({
                //         type: 'success',
                //         message: '你的邮箱是: ' + value
                //     });
                // }).catch(() => {
                //     this.$message({
                //         type: 'info',
                //         message: '取消输入'
                //     });       
                // });
            },
            toggleSelection(rows) {
                if (rows) {
                    rows.forEach(row => {
                        this.$refs.multipleTable.toggleRowSelection(row);
                    });
                } else {
                    this.$refs.multipleTable.clearSelection();
                }
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            }
        }
    }

</script>
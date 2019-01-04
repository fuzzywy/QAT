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
                <!-- <el-button style="float: right; padding: 3px 0" type="text" @click="newFormula">新建</el-button> -->
                <el-button 
                    style="float: right; padding: 3px 0"
                    type="text" 
                    @click="dialogFormVisible = true"
                ><i class="el-icon-plus"></i></el-button>
                <el-dialog title="新建公式" :visible.sync="dialogFormVisible">
                    <el-form :model="form">
                        <el-form-item label="名称" :label-width="formLabelWidth">
                            <el-input placeholder="请输入公式名" v-model="form.name" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="公式" :label-width="formLabelWidth">
                            <!-- <el-input v-model="form.formula" autocomplete="off"></el-input> -->
                            <el-input type="textarea" :rows="2" placeholder="请输入公式" v-model="form.formula" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="精度" :label-width="formLabelWidth">
                            <el-input placeholder="请输入精度" v-model="form.precision" autocomplete="off"></el-input>
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
                        <el-button type="primary" @click="newFormula()">确 定</el-button>
                    </div>
                </el-dialog>
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
                                @select="select"
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
                                        <!-- <el-form label-position="left" inline class="demo-table-expand" v-show="showModifyColumn"> -->
                                        <el-row :gutter="10" v-show="showModifyColumn">
                                            <!-- <el-form-item label="Id"> -->
                                            <!-- <el-col :span="12">                                                
                                                <el-input v-model="props.row.id" :disabled="true">
                                                    <template slot="prepend">Id</template>
                                                </el-input>
                                            </el-col> -->
                                            <!-- </el-form-item>
                                            <el-form-item label="Name"> -->
                                            <el-col :span="14">
                                                <el-input v-model="props.row.name">
                                                    <template slot="prepend">Name</template>
                                                </el-input>
                                            </el-col>
                                            <el-col :span="10">
                                                <el-input v-model="props.row.precision">
                                                    <template slot="prepend">Precision</template>
                                                </el-input>
                                            </el-col>
                                            <!-- </el-form-item>
                                            <el-form-item label="Formula"> -->
                                            <el-col :span="24">
                                                <div style="margin-top: 10px;"> </div>
                                            </el-col>
                                            <el-col :span="24">
                                                <el-input
                                                  type="textarea"
                                                  :rows="2"
                                                  placeholder="请输入公式"
                                                  v-model="props.row.formula">
                                                </el-input>
                                                <!-- <el-input v-model="props.row.formula">
                                                    <template slot="prepend">Formula</template>
                                                </el-input> -->
                                            </el-col>
                                            <el-col :span="24">
                                                <div style="margin-top: 10px;"> </div>
                                            </el-col>
                                            <el-col :span="8" :offset="8">
                                                <!-- @click.stop="toggleStartIcon($event)"
                                                    :loading="loading.qatStart" -->
                                                <el-button 
                                                    style="width: -webkit-fill-available"
                                                    type="primary"
                                                    @click="modifyFormula(props.row)">
                                                    <span>修改</span><!--  :class="toogle.startIcon" -->
                                                </el-button>
                                            </el-col>
                                            <el-col :span="8">
                                                <el-button 
                                                    style="width: -webkit-fill-available"
                                                    type="primary"
                                                    @click="cancel()">
                                                    <span>取消</span><!--  :class="toogle.startIcon" -->
                                                </el-button>
                                            </el-col>
                                            <!-- </el-form-item>
                                            <el-form-item label="Precision"> -->
                                            
                                            <!-- </el-form-item> -->
                                        </el-row>
                                        <!-- </el-form> -->
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
                    },
                    {
                        id: 1,
                        name: '系统模板'
                    },
                    {
                        id: 2,
                        name: ''
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
                // handleFormulaTableOpen: 0,

                elementData: [],

                dialogFormVisible: false,
                form: {
                    name: '',
                    formula: '',
                    precision: 0,
                    mode: 'TDD'
                },
                formLabelWidth: '50px',

                selectKpiFormula: []
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
                switch(this.$store.getters.selectKpiFormulaStatus) {
                    case 1:
                        break;
                    case 2:
                        // this.expands = [];
                        // this.expandsFormula = [];
                        // this.expandsDetailed = [];
                        // this.tableData = this.$store.getters.loadQatFormulaData;
                        // this.isFormulaClick = 0;
                        this.selectKpiFormula = this.$store.getters.selectKpiFormula;
                        // console.log(this.selectKpiFormula)
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }
                switch(this.$store.getters.addQatFormulaStatus) {
                    case 1:
                        break;
                    case 2:
                        this.search = this.$store.getters.addQatFormula[0];
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }
                switch(this.$store.getters.deleteQatFormulaStatus) {
                    case 1:
                        break;
                    case 2:
                        this.search = '';
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }
                switch(this.$store.getters.modifyQatFormulaStatus) {
                    case 1:
                        break;
                    case 2:
                        this.search = this.$store.getters.modifyQatFormula;
                        this.showModifyColumn = false;
                        this.showColumn = true;
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
            selectKpiFormula() {
                // if( this.$store.getters.selectKpiFormulaStatus != 2 ) {
                //     return;
                // }
                let formulaId = this.selectKpiFormula.click[0].id;
                let formulaName = this.selectKpiFormula.click[0].kpiName;
                let parent = this.selectKpiFormula.click[0].format;
                let grandparent = this.selectKpiFormula.click[0].user;
                this.parent = this.selectKpiFormula.click[0].format;
                this.grandparent = this.selectKpiFormula.click[0].user;
                this.handleFormulaTable(formulaId, formulaName, parent, grandparent);
            },
            /*formulaId() {
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
            templateName() {
                if(this.preventRepeatedExecution==1) {
                    this.preventRepeatedExecution = 0;
                    this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
                }
            }, 
            parent() {
                if(this.preventRepeatedExecution==1) {
                    this.preventRepeatedExecution = 0;
                    // this.preventRepeatedExecution = 'parent';
                    this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
                }
            },
            grandparent() {
                if(this.preventRepeatedExecution==1) {
                    this.preventRepeatedExecution = 0;
                    this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
                }
            },
            loginUser() {
                if(this.preventRepeatedExecution==1) {
                    this.preventRepeatedExecution = 0;
                    this.handleFormulaTable(this.formulaId, this.formulaName, this.templateName, this.parent, this.grandparent, this.loginUser);
                }
            },*/
            /*elementData() {
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

                // console.log(this.tableData)
                // console.log(this.expands)
                // console.log(this.expandsFormula)
                // console.log(this.tableData[expandsId[0]].children[expandsFormulaId[0]].children)
                let arrs = this.tableData[expandsId[0]].children.map(formula=>{
                    return formula;
                });
                let arrs1 = arrs.map(formula=>{
                    if(formula.id == expandsFormulaId[0]) {
                        return formula;
                    }
                }).filter(function(val){
                    return !(val === undefined || val === "");
                });
                // console.log(arrs1[0].children)
                this.formulaData = arrs1[0].children.map(formula=>{
                    return {id: formula.id, name: formula.name, formula: formula.formula, precision:formula.precision};
                })
                // console.log(this.formulaData)
                // return;
                // this.formulaData = this.tableData[expandsId[0]].children[expandsFormulaId[0]].children.map(formula=> {
                //     return {id: formula.id, name: formula.name, formula: formula.formula, precision:formula.precision};
                // });
                // console.log(this.formulaData)
                let elementData = this.elementData;
                let data = this.formulaData;
                let f = this.toggleSelection;
                //setTimeout作用类似异步，让其最后执行。不然加载不到elementData
                setTimeout( function(){
                    f(data.map(item=>{
                        for(var i=0; i<elementData.length; i++) {
                            // console.log()
                            if( item.name == elementData[i].label && item.id == elementData[i].id ) {
                                return item;
                            }
                        }
                    }).filter(function(val){
                        return !(val === undefined || val === "");
                    }));
                }, 0);
                // this.bus.$emit('backElementData', {
                //     elementData: this.elementData
                // });
            },*/
            /*multipleSelection() {
                this.bus.$emit('multipleSelection', {
                    multipleSelection: this.multipleSelection,
                    templateName: this.templateName,
                    parent: this.parent,
                    grandparent: this.grandparent,
                    multipleSelectionFlag: 1
                });
            }*/
        },
        mounted() {
            this.processLoadFormulaData();
            //获取kpilist点击数据
            /*this.bus.$on('kpiFormulaName', type=>{
                this.formulaId = type.formulaId,
                this.formulaName = type.formulaName,
                this.templateName = type.templateName,
                this.parent = type.parent,
                this.grandparent = type.grandparent,
                this.loginUser = type.loginUser,
                this.preventRepeatedExecution = type.preventRepeatedExecution
            });*/
            //获取template点击数据-暂时不需要，感觉复杂
            /*this.bus.$on('templateName', type=>{ 
                this.templateName = type.templateName, 
                this.parent = type.parent,
                this.grandparent = type.grandparent 
            });*/
            //获取all kpilist
            // this.bus.$on('elementData', type=>{
            //     this.elementData = type.element
            // })
        },
        methods: {
            // handleFormulaTable(formulaId, formulaName, templateName, parent, grandparent, loginUser){
            handleFormulaTable(formulaId, formulaName, parent, grandparent){
                // console.log(this.tableData)
                if( grandparent == 'admin' ) {
                    grandparent = '通用模板';
                }
                if ( grandparent == 'system' ) {
                    grandparent = '系统模板';
                }
                // this.handleFormulaTableOpen = 1;
                this.expands = [];
                this.expandsFormula = [];
                this.expandsDetailed = [];
                //format id/format id/Formula arr/arrs/arrs/key/expandsId
                let expArr,expandsArr,expandsFormulaArr,arrs, arrs1, key,expandsId;
                expArr = this.tableData.map(item=>{
                    if ( item.name === 'admin' || item.name === grandparent ) {
                        return item.id;
                    }
                });
                for( let i=0; i<expArr.length; i++ ) {
                    if ( typeof expArr[i] != 'undefined' ) {
                        key = i;
                        break;
                    }
                }
                expandsArr = this.tableData.map(item=>{
                    if ( item.name === 'admin' || item.name === grandparent ) {
                        return item;
                    }
                }).filter(function(val){
                    return !(val === undefined || val === "");
                })[0];
                expandsId = expandsArr['id'];
                this.expands = [expandsId];
                expandsFormulaArr = expandsArr.children.map(item=>{
                    if ( item.name === parent ) {
                        return item.id;
                    }
                });
                for( let i=0; i<expandsFormulaArr.length; i++ ) {
                    if ( typeof expandsFormulaArr[i] != 'undefined' ) {
                        this.expandsFormula = [i];
                        break;
                    }
                }
                arrs = this.tableData[key].children.map(formula=>{
                    return formula;
                });
                arrs1 = arrs.map(formula=>{
                    if(formula.id == expandsFormulaArr[this.expandsFormula]) {
                        return formula;
                    }
                }).filter(function(val){
                    return !(val === undefined || val === "");
                });
                this.formulaData = arrs1[0].children.map(formula=>{
                    return {id: formula.id, name: formula.name, formula: formula.formula, precision:formula.precision};
                })
                // console.log(formulaId)
                this.expandsDetailed = [formulaId];

                let elementData = this.selectKpiFormula.elements;
                let data = this.formulaData;
                // console.log(this.expands, this.expandsFormula, this.expandsDetailed)
                // console.log(data)
                let f = this.toggleSelection;
                // let f = this.getToggleSelection;
                setTimeout( function(){
                    f(data.map(item=>{
                        for(var i=0; i<elementData.length; i++) {
                            if( item.name == elementData[i].kpiName && item.id == elementData[i].id ) {
                                return item;
                            }
                        }
                    }).filter(function(val){
                        return !(val === undefined || val === "");
                    }))
                }, 0);
                /*let elementData = this.elementData;
                let data = this.formulaData;
                let f = this.toggleSelection;
                setTimeout( function(){
                    f(data.map(item=>{
                        for(var i=0; i<elementData.length; i++) {
                            if( item.name == elementData[i].label && item.id == elementData[i].id ) {
                                return item;
                                // return elementData[i];
                            }
                        }
                    }).filter(function(val){
                        return !(val === undefined || val === "");
                    }))
                }, 0);*/
            },
            expandChange(row, expandedRows) {
                let temp = this.expands;
                // console.log(temp, row);
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
                if ( this.$store.getters.qatLoginUserStatus == 2 && (this.$store.getters.qatLoginUser == name[0] || this.$store.getters.qatLoginUser == 'admin') ) {
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
                this.processDeleteFormula(row.id);
            },
            cancel() {
                //取消编辑
                this.showColumn = true;
                this.showModifyColumn = false;
            },
            modifyFormula(row) {
                //修改公式
                this.processModifyFormula(row.id, row.name, row.formula, row.precision);
            },
            newFormula() {
                this.dialogFormVisible = false;
                this.processAddFormula(this.form.name, this.form.formula, this.form.precision, this.form.mode);
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
            },
            select(rows) {
                // console.log(this.grandparent,this.parent )
                this.bus.$emit('selectFormulaByClick', {
                    clickFormulaGrandparent: this.grandparent,
                    clickFormulaParent: this.parent,
                    clickFormulaRows: rows
                });
            }
        }
    }

</script>
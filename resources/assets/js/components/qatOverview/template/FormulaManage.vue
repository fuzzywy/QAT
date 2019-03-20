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
            <el-scrollbar :native="false" wrapStyle="" wrapClass="" viewClass="" viewStyle="" tag="section">
                <div style="max-height: -webkit-fill-available;">
                    <el-table
                        v-loading="formulaLoading"
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
                                        @expand-change="expandDetailChange"
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
                                                <el-row :gutter="10" v-show="showModifyColumn">
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
                                                    </el-col>
                                                    <el-col :span="24">
                                                        <div style="margin-top: 10px;"> </div>
                                                    </el-col>
                                                    <el-col :span="8" :offset="8">
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
                                                </el-row>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="操作">
                                            <template slot-scope="scope">
                                                <el-button 
                                                    v-show="showEdit && is_show(scope)"
                                                    size="mini"
                                                    @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                                                <el-button
                                                    v-show="showDelete && is_show(scope)"
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

                elementData: [],

                dialogFormVisible: false,
                form: {
                    name: '',
                    formula: '',
                    precision: 0,
                    mode: 'TDD'
                },
                formLabelWidth: '50px',

                selectKpiFormula: [],

                flag: 0,

                templateGrandparent: '',

                expendFlag: '',

                showId: 0,

                formulaLoading: false
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
                        this.formulaLoading = true;
                        break;
                    case 2:
                        this.formulaLoading = false;
                        this.selectKpiFormula = this.$store.getters.selectKpiFormula;
                        break;
                    case 3:
                        this.formulaLoading = false;
                        break;
                    default:
                        this.formulaLoading = false;
                        break;
                }
                if( this.search != '' ) {
                    switch(this.$store.getters.addQatFormulaStatus) {
                        case 1:
                            // break;
                        case 2:
                            // this.tableData = this.$store.getters.loadQatFormulaData;
                            this.search = '';
                            // this.processLoadFormulaData();
                            break;
                        case 3:
                            break;
                        default:
                            break;
                    }
                }
                if( this.search != '' ) {
                    switch(this.$store.getters.deleteQatFormulaStatus) {
                        case 1:
                            break;
                        case 2:
                            this.search = '';
                            // this.tableData = this.$store.getters.loadQatFormulaData;
                            break;
                        case 3:
                            break;
                        default:
                            break;
                    }
                }
                if( this.search != '' ) {
                    switch(this.$store.getters.modifyQatFormulaStatus) {
                        case 1:
                            break;
                        case 2:
                            this.search = '';//this.$store.getters.modifyQatFormula[0];
                            if( this.$store.getters.modifyQatFormula[0] == 'Permission denied' ) {
                                this.$message({
                                    message: '错误:你没有权限修改本公式!',
                                    type: 'error'
                                });
                            }else {
                                this.processloadElementData( this.templateName, this.parent, this.grandparent, this.$store.getters.qatLoginUser, this.$store.state.qatData.qatDataSource );
                            }
                            break;
                        case 3:
                            break;
                        default:
                            break;
                    }
                }
            },
            filterFormula() {
                let str = '';
                // if( this.search == '' ) return;
                if( typeof this.search == 'string' ) {
                    str = this.search.trim();
                } else {
                    str = this.search[0].trim();
                }
                this.expands = [];
                this.expandsFormula = [];

                this.tableData = [];
                if (this.$store.getters.loadQatFormulaDataStatus == 2) {
                    this.tableData = this.$store.getters.loadQatFormulaData;
                }
                // if ( !str ) {
                //     this.tableData = [];
                //     if (this.$store.getters.loadQatFormulaDataStatus == 2) {
                //         this.tableData = this.$store.getters.loadQatFormulaData;
                //     }
                // } else {
                    // console.log(this.tableData);
                    // console.log(this.search)
                let f = item => {
                    if ( item['children'] ) {
                        item['children'] = item['children'].filter(f);
                        return true;
                    }  else if ( item['name'] || item['formula'] ) {
                        return (item['name'].indexOf(str) !== -1) 
                            || (item['formula'].indexOf(str) !== -1);
                    } else {
                        return false;
                    }
                }
                this.tableData  = this.tableData.filter(f);
                // }
            }
        },
        watch: {
            search() {
                this.processLoadFormulaData(this.$store.state.qatData.qatDataSource);
            },
            selectKpiFormula() {
                let formulaId = this.selectKpiFormula.click[0].id;
                let formulaName = this.selectKpiFormula.click[0].kpiName;
                let parent = this.selectKpiFormula.click[0].format;
                let grandparent = this.selectKpiFormula.click[0].user;
                this.parent = this.selectKpiFormula.click[0].format;
                this.grandparent = this.selectKpiFormula.click[0].user;
                this.handleFormulaTable(formulaId, formulaName, parent, grandparent);
            },
            templateName() {
                if (this.flag == 1) {
                    this.expands = [];
                    this.expandsFormula = [];
                    this.flag = 0;
                }
            }, 
            parent() {
                if (this.flag == 1) {
                    this.expands = [];
                    this.expandsFormula = [];
                    this.flag = 0;
                }
            },
            grandparent() {
                if (this.flag == 1) {
                    this.expands = [];
                    this.expandsFormula = [];
                    this.flag = 0;
                }
            }
        },
        mounted() {
            this.processLoadFormulaData(this.$store.state.qatData.qatDataSource);
            //获取template点击数据-暂时不需要，感觉复杂
            this.bus.$on('templateName', type=>{ 
                this.templateName = type.templateName, 
                this.parent = type.parent,
                this.grandparent = type.grandparent,
                this.flag = type.flag
            });
            this.bus.$on('elementData', type=>{ 
                this.elementData = type.element
            });
            this.bus.$on('templateName', 
            type=>{ 
                this.templateGrandparent = type.grandparent
            });
        },
        methods: {
            is_show(scope){
                return scope.row.id == this.showId;
            },
            handleFormulaTable(formulaId, formulaName, parent, grandparent){
                if( grandparent == 'admin' ) {
                    grandparent = '通用模板';
                }
                if ( grandparent == 'system' ) {
                    grandparent = '系统模板';
                }
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
                this.expandChange(expandsArr, expandsId);
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

                let positions = arrs1[0]['children'];
                let str;
                for( var i=0; i<positions.length; i++ ) {
                    if( positions[i].id == formulaId ) {
                        str = positions.splice(i, 1);
                    }
                }
                positions.unshift(str[0]);
                
                this.expandFormulaChange( { 'id': arrs1[0]['id'], 'type': arrs1[0]['name'] }, this.expandsFormula[0] )
                this.expandsDetailed = [formulaId];
            },
            expandChange(row, expandedRows) {
                this.showModifyColumn = false;
                this.showColumn = true;
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
                this.showModifyColumn = false;
                this.showColumn = true;
                let temp = this.expandsFormula;
                this.expendFlag = row.type;
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
                let elementData = this.elementData;
                let data = this.formulaData;
                let f = this.toggleSelection;
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
            },
            expandDetailChange() {
                this.showModifyColumn = false;
                this.showColumn = true;
            },
            cellMouseEnter( row, column, cell, event ) {
                this.showId = row.id;
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
                this.$confirm('此操作将永久删除 '+ row.name +' 公式, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    // this.processLoadFormulaData();
                    this.search = row.id;
                    this.processDeleteFormula(row.id, this.$store.state.qatData.qatDataSource);
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
            cancel() {
                //取消编辑
                this.showColumn = true;
                this.showModifyColumn = false;
            },
            modifyFormula(row) {
                //修改公式
                // this.search = '';
                this.search = row.name;
                this.processModifyFormula(row.id, row.name, row.formula, row.precision, this.$store.state.qatData.qatDataSource);
                this.showModifyColumn = false;
                this.showColumn = true;
            },
            newFormula() {
                // this.search = '';
                this.dialogFormVisible = false;
                this.search = this.form.name;
                this.processAddFormula(this.form.name, this.form.formula, this.form.precision, this.form.mode, this.$store.state.qatData.qatDataSource);
                // this.processLoadFormulaData();
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
                let p = this.templateGrandparent;
                if( p == '通用模板' ) {
                    p = 'admin';
                }
                if( p == '系统模板' ) {
                    p = 'system';
                }
                if( p != this.$store.getters.qatLoginUser ) {
                    this.$message({
                        message: '警告:没有权限修改 '+this.templateGrandparent+' 下的用户模板！',
                        type: 'warning'
                    });
                    return;
                }
                if( this.expendFlag != this.parent ) {
                    this.$message({
                        message: '错误:当前选择制式 <'+this.expendFlag+'> 与目标制式: <' + this.parent + '> 不符，请重新选择！',
                        type: 'error'
                    });
                    return;
                }
                // this.processSelectKpiFormula(data, this.elementData);
                this.bus.$emit('selectFormulaByClick', {
                    clickFormulaGrandparent: this.grandparent,
                    clickFormulaParent: this.parent,
                    clickFormulaRows: rows
                });
            }
        }
    }

</script>
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
        <input style="display: none;" :getFormulaData="loadQatFormulaData">
        <el-card class="box-card" shadow="hover">
            <div slot="header" class="clearfix">
                <span>公式</span>
                <el-button style="float: right; padding: 3px 0" type="text">新建</el-button>
            </div>
            <el-table
                :data="tableData.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase()))"
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
                                :show-header=false
                                :data="formulaData"
                            >
                                <el-table-column
                                    label="Type"
                                    prop="name">
                                </el-table-column>
                                <el-table-column type="expand">
                                    <template slot-scope="props">
                                        <el-form label-position="left" inline class="demo-table-expand">
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
                                    </template>
                                </el-table-column>
                                <!-- <template slot-scope="props">
                                    <el-form label-position="left" inline class="demo-table-expand">
                                        <el-form-item label="商品名称">
                                            <span>{{ props.row.name }}</span>
                                        </el-form-item>
                                    </el-form>
                                </template> -->
                            </el-table>
                        </el-table-column>
                        <el-table-column
                            label="Type"
                            prop="type">
                        </el-table-column>
                    </el-table>
                </el-table-column>

               <!--  <el-table-column type="expand" :row-key="test">
                    <el-table
                        :show-header="false"
                        :data="tableData.type"
                        style="width: 100%"
                    >
                        <el-table-column
                            prop="name">
                        </el-table-column>
                    </el-table>
                </el-table-column> -->
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
                getRowKeys(row) {
                    return row.id;
                },
                getFormulaKeys(row) {
                    return row.id;
                },
                expands: [],
                expandsFormula: [],
                id: '',
                name: ''
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
                // console.log(this.$store.getters.loadQatFormulaDataStatus, this.$store.getters.loadQatFormulaData);
            }
        },
        mounted() {
            this.processLoadFormulaData();
        },
        methods: {
            expandChange(row, expandedRows) {
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
                            this.typeData = arr[i].type.map(type=> {
                                return {id: type.id, type: type.name};
                            });
                        }
                    }
                }
            },
            expandFormulaChange(row, expandedRows) {
                console.log(this.id, this.name)
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

                    let arrs = filter.type.map(item=> {
                        if( row.id === item.id ) {
                            return item;
                        }
                    });

                    for (var i = arrs.length - 1; i >= 0; i--) {
                        if( typeof(arrs[i]) !== "undefined" ){
                            this.formulaData = arrs[i].formula.map(formula=> {
                                return {id: formula.id, name: formula.name, formula: formula.formula, precision:formula.precision};
                            });
                        }
                    }
                }
            }
        }
    }

</script>
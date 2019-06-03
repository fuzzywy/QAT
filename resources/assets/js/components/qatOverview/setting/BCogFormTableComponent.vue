<template>
    <el-card style="height: 100%;">
        <el-row :gutter="20">
            <el-col :span="12">
                <el-form label-width="80px">
                    <el-form-item horizontal label="Filter">
                    <!-- <el-input v-model="filter" placeholder="Type to Search" @change="onFiltered"> -->
                        <el-input v-model="filter" placeholder="Type to Search">
                            <template slot="append"><el-button :disabled="!filter" @click.stop="clearFilter">Clear</el-button></template>
                        </el-input>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="12">
                <el-form label-width="150px">
                    <el-form-item horizontal label="Add host address" class="mb-0">
                        <el-button type="primary" @click.stop="addColumn">Add</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>

        <el-dialog title="add/modify"
            :visible.sync="modifyVisible"
            :close-on-click-modal="false"
            :before-close="handleClose"
            :modal-append-to-body="false"
            >

            <el-row class="mb-2" v-for="(value,key) in modify" :key="key" v-if="key !== 'id'">
                <el-col :span="6" class="text-sm-right"><b>{{ key }}:</b></el-col>
                <el-col :span="15" :offset="1">
                    <el-input size="mini" v-model="modify[key]" v-if="key !== 'type' && key !== 'conn'"/>
                    <el-input size="mini" :disabled="disabled" v-model="modify[key]" v-if="key === 'conn'"/>
                    <el-select size="mini" v-model="modify[key]" :disabled="disabled" v-if="key === 'type'">
                        <el-option
                          v-for="item in types"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <div slot="footer" class="dialog-footer">
                <el-button @click.stop="handleCancel()">{{$t('messages.common.cancel')}}</el-button>
                <el-button type="primary" @click.stop="handleUpdate()">{{$t('messages.common.ok')}}</el-button>
            </div>
        </el-dialog>

        <el-table
            v-loading="loading.showCogStatus"
            :data="tableData.slice((currentPage-1)*pageSize,currentPage*pageSize)"
            border
            style="margin: auto;"
            @sort-change="sortChange"
            :options="getData">
            <!--tableData.filter(data => !filter || data.type.toLowerCase().includes(filter.toLowerCase()))-->

            <el-table-column v-for="(value,key) in header" width="180" :key="key" :prop="key" :label="key" show-overflow-tooltip sortable="custom" v-if="key !== 'id'" >
            </el-table-column>

            <el-table-column align="center" width="180" v-if="total > 0">
                <template slot="header" slot-scope="scope" >
                    Actions
                </template>
                <template slot-scope="scope">
                    <el-button size="mini"
                        @click="handleEdit(scope.$index, scope.row)">modify</el-button>
                    <el-button size="mini" type="danger"
                        @click="handleDelete(scope.$index, scope.row)">delete</el-button>
                </template>
            </el-table-column>
        </el-table>

        <el-pagination
            @size-change="size_change"
            @current-change="current_change"
            :current-page="currentPage"
            :page-sizes="[5, 10, 50, 100]"
            :page-size="5"
            layout="total, sizes, prev, pager, next, jumper"
            :total="total">
        </el-pagination>
    </el-card>
</template>
<style>
</style>
<script>
    var tableData = [];
    export default {
        data() {
            return {
                loading: {
                    showCogStatus: false
                },
                tableData: tableData,
                total:0,//默认数据总数
                pageSize:5,//每页的数据条数
                currentPage:1,//默认开始页面
                header:[],
                filter:'',
                modify: [],
                modifyVisible: false,
                disabled:false,
                delete:[],
                types:[{label:'LTE',value:'LTE'},{label:'GSM',value:'GSM'}]
            }
        },
        watch: {
            filter(val) {
                this.tableData = this.$store.getters.getShowCog.filter(data => !this.filter || data.type.toLowerCase().includes(val.toLowerCase()))
                this.total = this.tableData.length;
                this.currentPage = 1;
            }
        },
        created() {
            this.$store.dispatch('showCog')
        },
        methods: {
            current_change: function(currentPage) {
                this.currentPage = currentPage;
            },
            size_change: function(sizeChange) {
                this.pageSize = sizeChange;
            },
            clearFilter () {
                this.$store.dispatch('showCog');
                this.filter = '';
            },
            sortChange: function(column, prop, order) {
                switch (column.order){
                    case 'ascending':  
                        this.tableData = this.tableData.sort(function(a, b) {
                            if (typeof(a[column.prop]) == "number") {
                                return a[column.prop]-(b[column.prop])
                            } else {
                                return a[column.prop].localeCompare(b[column.prop])
                            }
                        })
                    break;
                    case 'descending':
                        this.tableData = this.tableData.sort(function(a, b) {
                            if (typeof(a[column.prop]) == "number") {
                                return b[column.prop]-(a[column.prop])
                            } else {
                                return b[column.prop].localeCompare(a[column.prop])
                            }
                        }) 
                    break;
                }
            },
            handleEdit(index, row) {
                this.disabled = true;
                this.modifyVisible = true;
                this.modify = Object.assign({}, row);
            },
            handleClose(done) {
                this.modifyVisible = false;
            },
            handleCancel() {
                this.modifyVisible = false;
            },
            handleUpdate() { 
                if ( !this.modify.conn ) {
                    this.$message({
                        showClose: true,
                        message: this.$t('messages.setting.connTip'),
                        type: 'warning'
                    });
                    return;
                }
                if ( !this.modify.type ) {
                    this.$message({
                        showClose: true,
                        message: this.$t('messages.setting.typeTip'),
                        type: 'warning'
                    });
                    return;
                }
                this.$store.dispatch('uploadCog', this.modify);
                if ( this.modify.id == -1 ) {//add
                    this.$store.dispatch('showCog');
                } else {//modify
                    for (var i = this.tableData.length - 1; i >= 0; i--) {
                        if( this.tableData[i].id == this.modify.id ) {
                            this.tableData[i] = this.modify;
                        } 
                    }
                }
                this.modifyVisible = false;
                this.$alert(this.$t('messages.common.modifySucc'), {
                    confirmButtonText: this.$t('messages.common.ok')
                });
            },
            handleDelete(index, row) {
                this.delete = Object.assign({}, row);
                this.$confirm("<pre>"+JSON.stringify(this.delete, null, 2)+"</pre>", this.$t('messages.common.deleteConfirm'), {
                    confirmButtonText: this.$t('messages.common.ok'),
                    cancelButtonText: this.$t('messages.common.cancel'),
                    dangerouslyUseHTMLString: true
                }).then(() => {
                    this.$store.dispatch('deleteCog', this.delete);
                    tableData = [];
                    for (var i = 0 ; i <= this.tableData.length - 1; i++) {
                        if( this.tableData[i].id !== this.delete.id ) {
                            tableData.push(this.tableData[i]);
                        }
                    }
                    this.tableData = tableData;
                    this.total =  this.tableData.length;
                    if ( this.tableData.length/this.pageSize < this.currentPage ) {
                        this.currentPage = Math.ceil(this.tableData.length/this.pageSize);
                    }
                    this.$message({
                        type: 'success',
                        message: this.$t('messages.common.deleteSuccess')
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: this.$t('messages.common.Undelete')
                    });          
                });
            },
            addColumn () {
                this.currentPage = 1;
                this.modify = { id: '-1', conn: '', city: '', host: '', port: '', dbName: '', userName: '', password: '', subNetworkTdd: '', subNetworkFdd: '', subNetworkNbiot: '', type: '' };
                //限制add数量只能是一个
                this.disabled = false;
                this.modifyVisible = true;
            }
        },
        computed: {
            getData() {
                if ( this.$store.getters.getShowCogStatus == 2 ) {
                    tableData =  this.$store.getters.getShowCog;
                    this.tableData = tableData;
                    this.total = tableData.length;
                    this.currentPage = 1;
                    this.header = tableData[0];
                }
            }
        }
    }
</script>
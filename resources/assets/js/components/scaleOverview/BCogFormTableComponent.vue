<template>
    <div class="top-cog">
        <el-row :gutter="20">
            <el-col :span="12">
                <el-form label-width="80px">
                    <el-form-item horizontal label="Filter" class="mb-0">
                        <el-input v-model="filter" placeholder="Type to Search" @change="onFiltered">
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
                    <el-input size="mini" :disabled="disabled" placeholder="LTE or GSM" v-model="modify[key]" v-if="key === 'type'"/>
                </el-col>
            </el-row>
            <div slot="footer" class="dialog-footer">
                <el-button @click.stop="handleCancel()">cancel</el-button>
                <el-button type="primary" @click.stop="handleUpdate()">modify</el-button>
            </div>
        </el-dialog>

        <el-table
            v-loading="loading.showCogStatus"
            :data="tableData.slice((currentPage-1)*pageSize,currentPage*pageSize)"
            border
            style="margin: auto;"
            max-height="500"
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
    </div>
</template>
<style>
    .top-cog {
        background-color: white; 
        padding: 15px 15px 15px 15px;
        margin-top: 25px;
    }
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
                delete:[]
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
            onFiltered () {
                this.tableData = this.$store.getters.getShowCog.filter(data => !this.filter || data.type.toLowerCase().includes(this.filter.toLowerCase()))
                this.total = this.tableData.length;
                this.currentPage = 1;
            },
            clearFilter () {
                this.filter = '';
                this.onFiltered ();
            },
            sortChange: function(column, prop, order) {
                //console.log(column + '-' + column.prop + '-' + column.order)
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
            //点击编辑
            handleEdit(index, row) {
                this.disabled = true;
                this.modifyVisible = true;
                this.modify = Object.assign({}, row);
            },
            //点击关闭dialog
            handleClose(done) {
                this.modifyVisible = false;
            },
            //点击取消
            handleCancel() {
                this.modifyVisible = false;
            },
            //点击更新
            handleUpdate() { 
                console.log(this.currentPage);
                //更新的时候就把弹出来的表单中的数据写到要修改的表格中
                this.$store.dispatch('uploadCog', this.modify)
                //这里再向后台发个post请求重新渲染表格数据
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
                this.$alert('修改成功', {
                    confirmButtonText: '确定'
                });
            },
            handleDelete(index, row) {
                this.delete = Object.assign({}, row);
                //console.log(this.delete);
                this.$confirm("<pre>"+JSON.stringify(this.delete, null, 2)+"</pre>", '确认删除', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
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
                        message: '删除成功!'
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });          
                });
            },
            addColumn () {
                this.currentPage = 1;
                this.modify = [];
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
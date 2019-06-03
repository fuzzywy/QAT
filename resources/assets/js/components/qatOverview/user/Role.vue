<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="12">
                <el-form label-width="80px">
                    <el-form-item horizontal label="Filter">
                        <el-input v-model="filter" placeholder="role to Search">
                            <template slot="append"><el-button :disabled="!filter" @click.stop="clearFilter">Clear</el-button></template>
                        </el-input>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="10" :offset="2">
                <el-form label-width="150px">
                    <el-form-item horizontal>
                        <el-button type="primary" @click.stop="addRole">{{$t('messages.common.add')}}</el-button>
                        <el-button type="primary" @click.stop="handleEdit()">{{$t('messages.common.modify')}}</el-button>
                        <el-button type="danger" @click.stop="handleDelete()">{{$t('messages.common.delete')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>

        <el-dialog :title="this.$t('messages.menu.Role')"
            :visible.sync="dialogVisible"
            :close-on-click-modal="false"
            :before-close="handleClose"
            :modal-append-to-body="false"
            >

            <el-row class="mb-2" v-for="(value,key) in modify" :key="key" v-if="key !== 'id'">
                
                <el-col :span="6" class="text-sm-right"><b>{{ key }}:</b></el-col>
                <el-col :span="15" :offset="1">
                    <el-input size="mini" v-model="modify[key]" :disabled="disabled" v-if="key == 'type'"/>
                    <el-input size="mini" v-model="modify[key]" v-if="key !== 'type'"/>
                </el-col>
            </el-row>
            <div slot="footer" class="dialog-footer">
                <el-button @click.stop="handleCancel()">{{$t('messages.common.cancel')}}</el-button>
                <el-button type="primary" @click.stop="handleUpdate()">{{$t('messages.common.ok')}}</el-button>
            </div>
        </el-dialog>

        <el-table
            v-loading="loading.showRoleDataStatus"
            :data="tableData.slice((currentPage-1)*pageSize,currentPage*pageSize)"
            border
            highlight-current-row
            @current-change="handleCurrentChange"
            style="margin: auto;"
            @sort-change="sortChange"
            :options="getData">

            <el-table-column v-for="(value,key) in tableData[0]" :key="key" :prop="key" :label="key" show-overflow-tooltip sortable="custom" v-if="key !== 'id'" >
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
   
</style>
<script>
    var tableData = [];
    export default {
        data() {
            return {
                loading: {
                    showRoleDataStatus: false
                },
                tableData: tableData,
                total:0,//默认数据总数
                pageSize:5,//每页的数据条数
                currentPage:1,//默认开始页面
                filter:'',
                modify: [],
                dialogVisible: false,
                disabled:false,
                delete:[],
                errors: []
            }
        },
        watch: {
            filter(val) {
                this.tableData = this.$store.getters.showRoleData.filter(data => !this.filter || data.type.toLowerCase().includes(val.toLowerCase()))
                this.total = this.tableData.length;
                this.currentPage = 1;
            }
        },
        mounted() {
            this.$store.dispatch('showRole');
        },
        methods: {
            handleCurrentChange(val) {
                this.modify = val;
                this.delete = val;
            },
            current_change: function(currentPage) {
                this.currentPage = currentPage;
            },
            size_change: function(sizeChange) {
                this.pageSize = sizeChange;
            },
            clearFilter () {
                this.$store.dispatch('showRole');
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
            //点击编辑
            handleEdit() {
                if(this.modify.length == 0){
                    this.$message.warning(this.$t('messages.user.userTip'));
                    return;
                }
                this.disabled = true;
                this.dialogVisible = true;
            },
            //点击关闭dialog
            handleClose(done) {
                this.dialogVisible = false;
            },
            //点击取消
            handleCancel() {
                this.dialogVisible = false;
            },
            //点击更新
            handleUpdate() {
                var _this = this;
                this.$store.dispatch('modifyRole', this.modify).then(function(){
                    _this.modifyRole();
                });
            },
            modifyRole() {
                switch (this.$store.getters.modifyRoleStatus) {
                    case 2 :
                        this.dialogVisible = false;
                        this.$store.dispatch('showRole');
                        this.$message.success(this.$t('messages.common.success'));
                    break;
                    default:
                        this.$message.success(this.$t('messages.common.fail'));
                    break;
                }
            },
            handleDelete() {
                this.$confirm("<pre>"+JSON.stringify(this.delete, null, 2)+"</pre>", this.$t('messages.common.deleteConfirm'), {
                    confirmButtonText: this.$t('messages.common.ok'),
                    cancelButtonText: this.$t('messages.common.cancel'),
                    dangerouslyUseHTMLString: true
                }).then(() => {
                    var _this = this;
                    this.$store.dispatch('deleteRole', this.delete).then(function(){
                        _this.deleteRole();
                    });
                    
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: this.$t('messages.common.Undelete')
                    });          
                });
            },
            deleteRole() {
                switch (this.$store.getters.deleteRoleStatus) {
                    case 2 :
                        this.$store.dispatch('showRole');
                        this.$message.success(this.$t('messages.common.deleteSuccess'));
                    break;
                    default:
                        this.$message.success(this.$t('messages.common.deleteFailed'));
                    break;
                }
            },
            addRole () {
                this.modify = [];
                this.modify = { id: '-1', type: '', description: ''};
                //限制add数量只能是一个
                this.disabled = false;
                this.dialogVisible = true;
            }
        },
        computed: {
            getData() {
                switch (this.$store.getters.showRoleDataStatus) {
                    case 1 :
                        this.loading.showRoleDataStatus = true;
                    break;
                    case 2 :
                        tableData =  this.$store.getters.showRoleData;
                        this.tableData = tableData;
                        this.total = tableData.length;
                        this.currentPage = 1;
                    default:
                         this.loading.showRoleDataStatus = false;
                    break;
                }
            }
        }
    }
</script>
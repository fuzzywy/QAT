<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="12">
                <el-form label-width="80px">
                    <el-form-item horizontal label="Filter">
                        <el-input v-model="filter" placeholder="Name to Search">
                            <template slot="append"><el-button :disabled="!filter" @click.stop="clearFilter">Clear</el-button></template>
                        </el-input>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="8" :offset="4">
                <el-form label-width="150px">
                    <el-form-item horizontal>
                        <el-button type="primary" @click.stop="handleEdit()" :disabled="disabledEdit">{{$t('messages.common.modify')}}</el-button>
                        <el-button type="danger" @click.stop="handleDelete()" :disabled="disabledEdit">{{$t('messages.common.delete')}}</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>

        <el-dialog :title="this.$t('messages.menu.User')"
            :visible.sync="dialogVisible"
            :close-on-click-modal="false"
            :before-close="handleClose"
            :modal-append-to-body="false"
            :getRole="getRole"
            >

            <el-row class="mb-2" v-for="(value,key) in modify" :key="key" v-if="key !== 'id'">
                <el-col :span="6" class="text-sm-right"><b>{{ key }}:</b></el-col>
                <el-col :span="15" :offset="1">
                    <el-input size="mini" :disabled="disabled" v-model="modify[key]" v-if="key !== 'type'"/>
                    <el-select size="mini" v-model="modify[key]" v-if="key === 'type'">
                        <el-option
                          v-for="item in roles"
                          :key="item.value"
                          :label="item.label"
                          :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <div slot="footer" class="dialog-footer">
                <el-button @click.stop="handleClose()">{{$t('messages.common.cancel')}}</el-button>
                <el-button type="primary" @click.stop="handleUpdate()">{{$t('messages.common.ok')}}</el-button>
            </div>
        </el-dialog>

        <el-table
            v-loading="loading.showUserDataStatus"
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
                    showUserDataStatus: false
                },
                tableData: tableData,
                total:0,//默认数据总数
                pageSize:5,//每页的数据条数
                currentPage:1,//默认开始页面

                filter:'',
                disabled: true,
                roles: [],
                modify: [],
                dialogVisible: false,
                delete:[],

                disabledEdit: true
            }
        },
        watch: {
            filter(val) {
                this.tableData = this.$store.getters.showUserData.filter(data => !this.filter || data.name.toLowerCase().includes(val.toLowerCase()))
                this.total = this.tableData.length;
                this.currentPage = 1;
            }
        },
        mounted() {
            this.$store.dispatch('showUser');
            this.$store.dispatch('getRole');
        },
        methods: {
            handleCurrentChange(val) {
                this.disabledEdit = val ? false : true;
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
            handleEdit() {
                this.dialogVisible = true;
            },
            handleClose(done) {
                this.dialogVisible = false;
            },
            handleUpdate() { 
                var _this = this;
                this.$store.dispatch('modifyUser', {'email':this.modify.email,'type':this.modify.type}).then(function(){
                    _this.modifyUser();
                });
            },
            modifyUser() {
                switch (this.$store.getters.modifyUserStatus) {
                    case 2 :
                        this.dialogVisible = false;
                        this.$store.dispatch('showUser');
                        this.filter = '';
                        this.$message.success(this.$t('messages.common.modifySucc'));
                    break;
                    case 3 :
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.modifyUser,
                            type: 'error'
                          });
                    break;
                    default:
                        this.$message.error(this.$t('messages.common.modifyFailed'));
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
                    this.$store.dispatch('deleteUser', {'email':this.delete.email}).then(function(){
                        _this.deleteUser();
                    });
                    
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: this.$t('messages.common.Undelete')
                    });          
                });
            },
            deleteUser() {
                switch (this.$store.getters.deleteUserStatus) {
                    case 2 :
                        this.$store.dispatch('showUser');
                        this.$message.success(this.$t('messages.common.deleteSuccess'));
                    break;
                    case 3 :
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.deleteUser,
                            type: 'error'
                          });
                    break;
                    default:
                        this.$message.error(this.$t('messages.common.deleteFailed'));
                    break;
                }
            }
        },
        computed: {
            getData() {
                switch (this.$store.getters.showUserDataStatus) {
                    case 1 :
                        this.loading.showUserDataStatus = true;
                    break;
                    case 2 :
                        this.loading.showUserDataStatus = false;
                        tableData =  this.$store.getters.showUserData;
                        this.tableData = tableData;
                        this.total = tableData.length;
                        this.currentPage = 1;
                        break;
                    case 3 :
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.showUserData,
                            type: 'error'
                          });
                    break;
                    default:
                         this.loading.showUserDataStatus = false;
                    break;
                }
            },
            getRole() {
                switch (this.$store.getters.getRoleDataStatus) {
                    case 1 :
                        this.loading.getRoleDataStatus = true;
                    break;
                    case 2 :
                        this.roles =  this.$store.getters.getRoleData;
                    break;
                    case 3 :
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.getRoleData,
                            type: 'error'
                          });
                    break;
                    default:
                         this.loading.getRoleDataStatus = false;
                    break;
                }
            }
        }
    }
</script>
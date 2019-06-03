<template>
    <div >
        <el-row :gutter="20">
            <el-col :span="12">
                <el-form label-width="80px">
                    <el-form-item horizontal label="Filter">
                    <!-- <el-input v-model="filter" placeholder="Name to Search" @change="onFiltered"> -->
                        <el-input v-model="filter" placeholder="Name to Search">
                            <template slot="append"><el-button :disabled="!filter" @click.stop="clearFilter">Clear</el-button></template>
                        </el-input>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="4" :offset="8">
                <el-button type="primary" @click.stop="review">{{$t('messages.menu.Review')}}</el-button>
            </el-col>
        </el-row>

        <el-dialog :title="this.$t('messages.menu.Review')"
            :visible.sync="dialogVisible"
            :close-on-click-modal="false"
            :before-close="handleClose"
            :modal-append-to-body="false"
            :getRole="getRole"
            >

            <el-row class="mb-2" v-for="(value,key) in currentRow" :key="key" v-if="key !== 'id'">
                <el-col :span="6" class="text-sm-right"><b>{{ key }}:</b></el-col>
                <el-col :span="15" :offset="1">
                    <el-input size="mini" :disabled="disabled" v-model="currentRow[key]" v-if="key !== 'type'"/>
                    <el-select v-loading="loading.getRoleDataStatus" size="mini" v-model="currentRow[key]" v-if="key === 'type'">
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
                <el-button @click.stop="handleCancel()">{{$t('messages.common.cancel')}}</el-button>
                <el-button type="primary" @click.stop="handleUpdate()">{{$t('messages.common.ok')}}</el-button>
            </div>
        </el-dialog>

        <el-table
            v-loading="loading.showReviewDataStatus"
            :data="tableData.slice((currentPage-1)*pageSize,currentPage*pageSize)"
            border
            highlight-current-row
            @current-change="handleCurrentChange"
            style="margin: auto;"
            @sort-change="sortChange"
            :options="getData">
            <el-table-column v-for="(value,key) in tableData[0]" width="180" :key="key" :prop="key" :label="key" show-overflow-tooltip sortable="custom" v-if="key !== 'id'" >
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
                    showReviewDataStatus: false,
                    getRoleDataStatus: false
                },
                tableData: tableData,
                total:0,//默认数据总数
                pageSize:5,//每页的数据条数
                currentPage:1,//默认开始页面
                filter:'',
                currentRow: [],
                roles: [],
                disabled:true,
                dialogVisible: false,
            }
        },
        watch: {
            filter(val) {
                this.tableData = this.$store.getters.showReviewData.filter(data => !this.filter || data.name.toLowerCase().includes(val.toLowerCase()))
                this.total = this.tableData.length;
                this.currentPage = 1;
            }
        },
        mounted() {
            this.$store.dispatch('showReview');
            this.$store.dispatch('getRole');
        },
        methods: {
            handleCurrentChange(val) {
                this.currentRow = val;
            },
            current_change: function(currentPage) {
                this.currentPage = currentPage;
            },
            size_change: function(sizeChange) {
                this.pageSize = sizeChange;
            },
            clearFilter () {
                this.$store.dispatch('showReview');
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
            review() {
                if ( this.currentRow.length == 0){
                    this.$message.warning(this.$t('messages.user.reviewTip'));
                    return;
                }
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
                this.$store.dispatch('reviewUser', this.currentRow).then(function(){
                    _this.reviewUser();
                });
            },
            reviewUser(){
                switch (this.$store.getters.reviewUserStatus) {
                    case 2 :
                        this.dialogVisible = false;
                        this.$store.dispatch('showReview');
                        this.$message.success(this.$t('messages.user.reviewSucc'));
                    break;
                    default:
                        this.$message.success(this.$t('messages.user.reviewFailed'));
                    break;
                }
            },
        },
        computed: {
            getData() {
                switch (this.$store.getters.showReviewDataStatus) {
                    case 1 :
                        this.loading.showReviewDataStatus = true;
                    break;
                    case 2 :
                        tableData =  this.$store.getters.showReviewData;
                        this.tableData = tableData;
                        this.total = tableData.length;
                        this.currentPage = 1;
                    default:
                         this.loading.showReviewDataStatus = false;
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
                    default:
                         this.loading.getRoleDataStatus = false;
                    break;
                }
            }
        }
    }
</script>
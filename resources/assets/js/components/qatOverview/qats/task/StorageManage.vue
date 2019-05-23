<style>
   	.el-input__count-inner{
   		height: 90%
   	}
</style>
<template>
    <el-container>
	  	<el-aside width="20%">
	  		<el-card class="box-card">
	  			<div slot="header" class="clearfix">
		    		<span>{{$t('messages.task.StorageType')}}</span>
		    		<el-input :runTaskData="runTaskData" :stopTaskData="stopTaskData" style="display:none"></el-input>
		  		</div>
			  	<div class="text item">
			    	<el-tree
	              		v-loading="loading.qatTaskStorageTypeStatus"
	              		class="filter-tree"
	              		:data="treeData"
	              		:props="defaultProps"
	              		node-key="id"
	              		default-expand-all
	              		highlight-current
	              		:expand-on-click-node="false"
	              		ref="tree"
	              		:options="getTaskStorageTypeData"
	              		@node-click="handleNodeClick">
	            	</el-tree>
			  	</div>
			</el-card>
	  	</el-aside>
	  	<el-container style="height: 100%;">
	  		<el-main style="max-height: 50%;border: 1px solid #eee">
	  			<el-dialog :title="dialogTitle"
                :visible.sync="addTaskVisible"
                :close-on-click-modal="false"
                :before-close="handleClose"
                :modal-append-to-body="false"
                :append-to-body="true"
                width="80%"
                :center="true"
                >
                	<el-form label-width="100px">
                    	<el-form-item horizontal :label="this.$t('messages.task.taskName')">
	                        <el-input
	                        	type="text"
					            class="full-width"
					            :placeholder="this.$t('messages.task.taskNamePlaceholder')"
					            v-model="taskName"
					            maxlength="18"
					            show-word-limit
					            >
					        </el-input>

	                    </el-form-item>
	                    <el-form-item horizontal>
	                    	<el-input v-model="filterText" :placeholder="this.$t('messages.task.filter')">
				                <template slot="append"><el-button :disabled="!filterText" @click.stop="clearFilter">{{$t('messages.task.clear')}}</el-button></template>
				            </el-input>
	                    </el-form-item>
	                    <el-form-item horizontal>
	                    	<el-tree
				              v-loading="loading.loadStorageDirectoryStatus"
				              class="filter-tree"
				              :data="directoryTreeData"
				              :props="defaultProps"
				              node-key="id"
				              default-expand-all
				              highlight-current
				              :expand-on-click-node="true"
				              ref="tree"
				              :filter-node-method="filterNode"
				              :options="getDirectoryTreeData"
				              @node-click="handleStorageDirClick"
				              >
				            </el-tree>
	                    </el-form-item>
	            	</el-form>
		            <span slot="footer" class="dialog-footer">
					    <el-button @click="addTaskVisible = false">{{$t('messages.task.cancel')}}</el-button>
					    <el-button type="primary" @click="saveTask" >{{$t('messages.task.ok')}}</el-button>
					</span>
                </el-dialog>
	  			<el-card class="box-card">
				  	<div slot="header" class="clearfix">
				    	<span>{{$t('messages.task.taskInfo')}}</span>
				    	<el-button size="small" type="primary" style="float:right;margin-left: 10px;" @click="stopTask" >{{$t('messages.task.stop')}}</el-button>
				    	<el-button size="small" type="primary" style="float:right" @click="runTask" >{{$t('messages.task.run')}}</el-button>
				    	<el-button size="small" type="primary" style="float:right" @click="deleteTask" :loading="loading.deleteTaskStatus">{{$t('messages.task.delete')}}</el-button>
                    	<el-button size="small" type="primary" style="float:right" @click="addTask">{{$t('messages.task.add')}}</el-button>
				  	</div>
				  	<el-table
			            v-loading="loading.qatTaskDataStatus"
			            :data="tasks.slice((currentPage-1)*pageSize,currentPage*pageSize)"
			            max-height="320"
			            border
			            highlight-current-row
			            @current-change="handleCurrentChange"
			            :options="getTaskData"
			            style="margin: auto;">
			            <el-table-column v-for="(value, key) in tasks[0]" min-width="180" :key="key" :prop="key" :label="key" show-overflow-tooltip>
			            </el-table-column >
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
				<!--预留CTR--->
				<!--<el-card class="box-card">
				  	<div slot="header" class="clearfix">
				    	<span>日志</span>
				    	<el-button style="float: right; padding: 3px 0" type="text">导出</el-button>
				  	</div>
				  	<el-table
			            v-loading="loading.qatTaskStatus"
			            :data="taskDirFiles"
			            max-height="320"
			            border
			            :options=""
			            style="margin: auto;">
			            <el-table-column v-for="(value, key) in taskDirFiles[0]" min-width="180" :key="key" :prop="key" :label="key" show-overflow-tooltip>
			            </el-table-column >
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
				</el-card>-->
	  		</el-main>
	    	
	  	</el-container>
	</el-container>
</template>
<script>
	export default {
		data () {
			return {
				defaultProps: {
		            children: 'children',
		            label: 'label'
		        },
				loading: {
					loadStorageDirectoryStatus : false,
					qatTaskDataStatus: false,
					deleteTaskStatus: false
				},
				directory: '',
				storageType: '',
				taskType: '',

				treeData: [],

				addTaskVisible: false,
				dialogTitle: '',
				taskName: '',
				filterText: '',
				directoryTreeData: [],

				tasks: [],
				total:0,
                pageSize:5,
                currentPage:1,

                currentRow: {}
			}
		},
        watch: {
            filterText(val) {
                this.$refs.tree.filter(val);
            }
        },
		methods : {
			current_change: function(currentPage) {
                this.currentPage = currentPage;
            },
            size_change: function(sizeChange) {
                this.pageSize = sizeChange;
            },
			handleNodeClick (data) {
                this.directory = data.directory;
                this.storageType = data.label;
                this.taskType = data.taskType;
                this.$store.dispatch( 'loadQatTaskData',{'taskType':this.taskType});
                this.$store.dispatch( 'loadQatTaskDirTreeData',{'directory':this.directory});
            },
            addTask () {
            	if( this.directory.length == 0){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.taskTypeTip')
                    }); 
                    return false;
                }

                if( this.directoryTreeData.length == 0) {
                	this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.TaskDirTip')
                    }); 
                    return false;
                }
                this.taskName = '';
                this.storageDir = '';
                this.addTaskVisible = true;
            },
            //点击关闭dialog
            handleClose(done) {
                this.addTaskVisible = false;
            },
            clearFilter () {
                this.filterText = '';
            },
            filterNode(value, data) {
		        if (!value) return true;
		        return data.label.indexOf(value) !== -1;
		    },
			handleStorageDirClick (data) {
				this.storageDir = data.directory;           
            },
		    saveTask() {
		    	var _this = this;
		    	if( !this.taskName ){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.taskNameTip')
                    }); 
                    return false;
                }
                if( !this.storageDir ){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.storageDirTip')
                    }); 
                    return false;
                }

                this.$store.dispatch( 'saveTaskData',{'taskName':this.taskName,'storageDir':this.storageDir,'storageType':this.storageType,'taskType':this.taskType}).then(function(){
                	_this.saveTaskData();
                });
		    	this.addTaskVisible = false;
		    },
            saveTaskData () {
            	switch( this.$store.getters.saveTaskDataStatus ) {
                    case 1:
                        break;
                    case 2:
                        if (this.$store.getters.saveTaskData.code) {
                        	this.$message({
		                        type: 'success',
		                        message: this.$t('messages.task.'+this.$store.getters.saveTaskData.msg)
		                    });
		                    this.$store.dispatch( 'loadQatTaskData',{'taskType':this.taskType});
                        } else {
                        	this.$message({
		                        type: 'error',
		                        message: this.$t('messages.task.'+this.$store.getters.saveTaskData.msg)
		                    });
                        }
                        break;
                    case 3:
                    default:
                        break;
	            }
	        },
		    deleteTask() {
		    	var _this = this;
		    	if( !this.currentRow){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.taskTip')
                    }); 
                    return false;
                }
                if( this.currentRow.status == 'ongoing'){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.deleteWarning')
                    }); 
                    return false;
                }
		    	this.$confirm(this.$t('messages.task.deleteConfirm')+'：'+this.currentRow.taskName+'?', this.$t('messages.task.tip'), {
		          	confirmButtonText: this.$t('messages.task.ok'),
		          	cancelButtonText: this.$t('messages.task.cancel'),
		          	type: 'warning'
		        }).then(() => {
		          	this.$store.dispatch( 'deleteTaskData',{'taskName':this.currentRow.taskName}).then(function(){
		          		_this.deleteTaskData();
		          	});
		        }).catch(() => {
		          	this.$message({
		            	type: 'info',
		            	message: this.$t('messages.task.Undelete')
		          	});          
		        });
		    	
		    },
	        deleteTaskData () {
	        	switch( this.$store.getters.deleteTaskDataStatus ) {
                    case 1:
                        break;
                    case 2:
                        if (this.$store.getters.deleteTaskData) {
                        	this.$message({
		                        type: 'success',
		                        message: this.$t('messages.task.'+this.$store.getters.deleteTaskData.msg)
		                    });
                        	this.$store.dispatch( 'loadQatTaskData',{'taskType':this.taskType});
                        } else {
                    		this.$message({
		                        type: 'error',
		                        message: this.$t('messages.task.'+this.$store.getters.deleteTaskData.msg)
		                    });
                        }
                        break;
                    case 3:
                    default:
                        break;
	            }
	        },
		    handleCurrentChange(val) {
		        this.currentRow = val;
		    },
		    runTask() {
		    	if( !this.currentRow){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.taskTip')
                    }); 
                    return false;
                }
                if( this.currentRow.status == 'ongoing' || this.currentRow.status == 'complete'){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.runTip')
                    }); 
                    return false;
                }
                this.currentRow.status = 'ongoing';
                this.$store.dispatch( 'runTaskData',{'taskName':this.currentRow.taskName,'storageType':this.storageType});
		    },
		    stopTask() {
		    	if( !this.currentRow.taskName){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.taskTip')
                    }); 
                    return false;
                }
                if( this.currentRow.status != 'ongoing'){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.stopTip')
                    }); 
                    return false;
                }
                this.$store.dispatch( 'stopTaskData',{'taskName':this.currentRow.taskName});
		    }
		},
		mounted () {
            this.$store.dispatch( 'loadQatTaskStorageTypeData' );
            this.dialogTitle = this.$t('messages.task.add');
        },
        computed: {
        	getTaskStorageTypeData () {
                switch( this.$store.getters.qatTaskStorageTypeDataStatus ) {
                    case 1:
                        this.loading.qatTaskStorageTypeStatus = true;
                        break;
                    case 2:
                        this.treeData = this.$store.getters.qatTaskStorageTypeData;
                    case 3:
                    default:
                        this.loading.qatTaskStorageTypeStatus = false;
                        break;
                }
            },
            getDirectoryTreeData () {
            	switch( this.$store.getters.qatTaskDirTreeDataStatus ) {
                    case 1:
                        this.loading.loadStorageDirectoryStatus = true;
                        break;
                    case 2:
                    	this.directoryTreeData = this.$store.getters.qatTaskDirTreeData;
                    case 3:
                    default:
                        this.loading.loadStorageDirectoryStatus = false;
                        break;
	            }
	        },
	        getTaskData () {
	        	switch( this.$store.getters.qatTaskDataStatus ) {
                    case 1:
                        break;
                    case 2:
                        this.tasks = this.$store.getters.qatTaskData;
                        this.total = this.tasks.length;
                        break;
                    case 3:
                    default:
                        break;
	            }
	        },
	        runTaskData () {
	        	switch( this.$store.getters.runTaskDataStatus ) {
                    case 1:
                        break;
                    case 2:
                        this.$store.dispatch( 'loadQatTaskData',{'taskType':this.taskType});
                        break;
                    case 3:
                    default:
                        break;
	            }
	        },
	        stopTaskData () {
	        	switch( this.$store.getters.stopTaskDataStatus ) {
                    case 1:
                        break;
                    case 2:
                        this.$store.dispatch( 'loadQatTaskData',{'taskType':this.taskType});
                        break;
                    case 3:
                    default:
                        break;
	            }
	        }

	    }
	}
</script>
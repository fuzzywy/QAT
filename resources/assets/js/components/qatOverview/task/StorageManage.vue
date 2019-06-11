<style>
   	.el-input__count-inner{
        height:35px;
    }
    .el-upload--text{
        width:100%;
    }
</style>
<template>
    <el-container style="height: 100%;">
  		<el-main style="max-height: 100%;border: 1px solid #eee">
  			<el-dialog :title="this.$t('messages.common.add')"
            :visible.sync="addTaskVisible"
            :close-on-click-modal="false"
            :before-close="handleClose"
            :modal-append-to-body="false"
            :append-to-body="true"
            width="80%"
            :center="true"
            >
            	<el-form label-width="100px">
            		<el-row>
            			<el-col>
	                    	<el-form-item horizontal :label="this.$t('messages.task.storageType')">
		                        <el-select
                                    class="full-width"
                                    v-model="storageType"
                                    filterable
                                    remote
                                    reserve-keyword
                                    :placeholder="this.$t('messages.task.storageType')"
                                    :remote-method="remoteMethod"
                                    :loading="loading.storageTypeStatus"
                                    :showData="getStorageType">
                                    <el-option
                                      v-for="item in storageTypeOptions"
                                      :key="item.value"
                                      :label="item.label"
                                      :value="item.value">
                                    </el-option>
                                </el-select>
		                    </el-form-item>
		                </el-col>
                    </el-row>
                    <el-row>
                        <el-col>
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
		                </el-col>
                    </el-row>
                    <el-row>
		                <el-col>
		                    <el-form-item horizontal :label="this.$t('messages.common.selectFile')">
		                    	<el-upload
						            ref="upload"
						            accept=".gz"
						            :action="uploadTaskFileUrl"
						            :data="myData"
						            :before-upload="onBeforeUpload"
						            :multiple="true"
						            :on-success="handleSuccess"
                                    :auto-upload="false"
						            :headers="myHeader"
						            :show-file-list=true
						            :file-list="fileList">
						            <!--<el-button size="small" type="primary">{{$t('messages.common.upload')}}</el-button>-->
                                    <el-input type="text"
                                    :placeholder="this.$t('messages.common.uploadPlaceholder')">
                                    </el-input>
			                        <div slot="tip" class="el-upload__tip">{{$t('messages.task.uploadTip')}}</div>
						        </el-upload>
		                    </el-form-item>
                    	</el-col>
                    </el-row>
            	</el-form>
	            <span slot="footer" class="dialog-footer">
				    <el-button @click="addTaskVisible = false">{{$t('messages.common.cancel')}}</el-button>
				    <el-button type="primary" @click="saveTask" >{{$t('messages.common.ok')}}</el-button>
				</span>
            </el-dialog>
  			<el-card class="box-card" style="height: 100%;">
			  	<div slot="header" class="clearfix">
			    	<span>{{$t('messages.task.taskInfo')}}</span>
			    	<el-button size="small" type="primary" style="float:right;margin-left: 10px;" @click="stopTask" :disabled="stopDisabled">{{$t('messages.common.stop')}}</el-button>
			    	<el-button size="small" type="primary" style="float:right" @click="runTask" :runTaskData="runTaskData" :disabled="runDisabled">{{$t('messages.common.run')}}</el-button>
			    	<el-button size="small" type="primary" style="float:right" @click="deleteTask" :loading="loading.deleteTaskStatus" :disabled="delDisabled">{{$t('messages.common.delete')}}</el-button>
                	<el-button size="small" type="primary" style="float:right" @click="addTask">{{$t('messages.common.add')}}</el-button>
			  	</div>
			  	<el-table
		            v-loading="loading.qatTaskDataStatus"
		            :data="tasks.slice((currentPage-1)*pageSize,currentPage*pageSize)"
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
  		</el-main>
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
					qatTaskDataStatus: false,
					deleteTaskStatus: false,
					storageTypeStatus: false
				},

				addTaskVisible: false,
				taskName: '',
				filterText: '',
				directoryTreeData: [],

				tasks: [],
				total:0,
                pageSize:5,
                currentPage:1,

                currentRow: {},

                uploadTaskFileUrl: 'storage/uploadTaskFile',
                fileList: [],
                myData:{},

                storageType: '',
                storageTypeOptions: [],
                storageTypeList: [],

                stopDisabled: true,
                runDisabled: true,
                delDisabled: true
			}
		},
        watch: {
            filterText(val) {
                this.$refs.tree.filter(val);
            },
            statusChange(val){
                switch (val) {
                    case 'prepare':
                    case 'abort':
                        this.stopDisabled = true;
                        this.runDisabled = false;
                        this.delDisabled = false;
                    break;
                    case 'ongoing':
                        this.stopDisabled = false;
                        this.runDisabled = true;
                        this.delDisabled = true;
                    break;
                    case 'complete':
                        this.stopDisabled = true;
                        this.runDisabled = true;
                        this.delDisabled = false;
                    break;
                    default:
                    break;
                }
            }
        },
		methods : {
			current_change: function(currentPage) {
                this.currentPage = currentPage;
            },
            size_change: function(sizeChange) {
                this.pageSize = sizeChange;
            },
            addTask () {
                this.storageType = '',
                this.taskName = '';
                this.fileList = [];
                this.addTaskVisible = true;
            },
            handleClose(done) {
                this.addTaskVisible = false;
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
                for(var i in this.tasks) {
					if (this.taskName == this.tasks[i].taskName){
						this.$message.warning(this.$t('messages.task.taskNameExists')); 
						return false;
					}
				}
				if( !this.storageType ){
					this.$message.warning(this.$t('messages.task.taskTypeTip'));
                    return false;
                }
                this.$store.dispatch( 'saveTaskData',{'taskName':this.taskName,'storageType':this.storageType}).then(function(){
                	_this.saveTaskData();
                });
		    	this.addTaskVisible = false;
		    },
            saveTaskData () {
            	switch( this.$store.getters.saveTaskDataStatus ) {
                    case 2:
                        this.$refs.upload.submit();
                    	this.$store.dispatch( 'loadQatTaskData');
                    break;
                    case 3:
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.saveTaskData,
                            type: 'error'
                          });
                        break;
                    default:
                    break;
	            }
	        },
		    deleteTask() {
		    	var _this = this;
		    	this.$confirm(this.$t('messages.task.deleteConfirm')+'：'+this.currentRow.taskName+'?', this.$t('messages.common.tip'), {
		          	confirmButtonText: this.$t('messages.common.ok'),
		          	cancelButtonText: this.$t('messages.common.cancel'),
		          	type: 'warning'
		        }).then(() => {
		          	this.$store.dispatch( 'deleteTaskData',{taskName:this.currentRow.taskName,tracePath:this.currentRow.tracePath}).then(function(){
		          		_this.deleteTaskData();
		          	});
		        }).catch(() => {
		          	this.$message({
		            	type: 'info',
		            	message: this.$t('messages.common.Undelete')
		          	});          
		        });
		    	
		    },
	        deleteTaskData () {
	        	switch( this.$store.getters.deleteTaskDataStatus ) {
                    case 2:
                        this.$store.dispatch( 'loadQatTaskData');
                        break;
                    case 3:
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.deleteTaskData,
                            type: 'error'
                          });
                        break;
                    default:
                        break;
	            }
	        },
		    handleCurrentChange(val) {
		        this.currentRow = val;
		    },
		    runTask() {
                this.$store.dispatch( 'runTaskData',this.currentRow);
                this.currentRow.status = 'ongoing';
		    },
		    stopTask() {
                var _this = this;
                this.$store.dispatch( 'stopTaskData',{'taskName':this.currentRow.taskName}).then(function(){
                    _this.stopTaskData();
                });
		    },
            stopTaskData () {
                switch( this.$store.getters.stopTaskDataStatus ) {
                    case 2:
                        this.currentRow.status = 'abort';
                        break;
                    case 3:
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.stopTaskData,
                            type: 'error'
                          });
                        break;
                    default:
                        break;
                }
            },
            onBeforeUpload(file){
            	if( !this.taskName ){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.taskNameTip')
                    }); 
                    return false;
                }
                for(var i in this.tasks) {
					if (this.taskName == this.tasks[i].taskName){
						this.$message.warning(this.$t('messages.task.taskNameExists')); 
						return false;
					}
				}
				this.myData.taskName = this.taskName;
				if( !this.storageType ){
					this.$message.warning(this.$t('messages.task.taskTypeTip'));
                    return false;
                }
                this.myData.storageType = this.storageType;

                const isGZ = file.type === 'application/x-gzip';
                const isLt10M = file.size / 1024 / 1024  < 10;

                if (!isGZ) {
                  this.$message.error(this.$t('messages.task.isGZ'));
                }
                if (!isLt10M) {
                  this.$message.error(this.$t('messages.task.isLt'));
                }
                return isGZ && isLt10M;
            },
            handleSuccess(res,file,fileList) {
             	//this.$refs.upload.clearFiles()
                if(res){
                    this.$message({
                        message: this.$t('messages.common.uploadSucc'),
                        type: 'success'
                    });
                }else {
                    this.$message({
                        message: this.$t('messages.common.uploadFailed'),
                        type: 'error'
                    });
                }
            },
            remoteMethod(query) {
                if (query !== '') {
                    this.loading.storageTypeStatus = true;
                    setTimeout(() => {
                        this.loading.storageTypeStatus = false;
                        this.storageTypeOptions = this.storageTypeList.filter(item => {
                            return item.label.toLowerCase().indexOf(query.toLowerCase()) > -1;
                        });
                    }, 200);  
                } else {
                    this.storageTypeOptions = this.storageTypeList;
                }
            }
		},
		mounted () {
            this.$store.dispatch( 'loadQatTaskStorageTypeData' );
            this.$store.dispatch( 'loadQatTaskData');
        },
        computed: {
            statusChange() {
                if (this.currentRow) {
                    if(this.$store.getters.getUserData.type == 'admin' || this.$store.getters.getUserData.name == this.currentRow.owner ) {
                        return this.currentRow.status;
                    }
                }
                this.stopDisabled = true;
                this.runDisabled = true;
                this.delDisabled = true;
            },
	        getTaskData () {
	        	switch( this.$store.getters.qatTaskDataStatus ) {
                    case 2:
                        this.tasks = this.$store.getters.qatTaskData;
                        this.total = this.tasks.length;
                        break;
                    case 3:
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.qatTaskData,
                            type: 'error'
                          });
                        break;
                    default:
                        break;
	            }
	        },
	        runTaskData () {
	        	switch( this.$store.getters.runTaskDataStatus ) {
                    case 2:
                        this.$store.dispatch( 'loadQatTaskData');
                        break;
                    case 3:
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.runTaskData,
                            type: 'error'
                          });
                        break;
                    default:
                        break;
	            }
	        },
            getStorageType () {
                switch( this.$store.getters.qatTaskStorageTypeDataStatus ) {
                    case 1:
                        this.loading.storageTypeStatus = true;
                        break;
                    case 2:
                        this.loading.storageTypeStatus = false;
                        this.storageTypeList = this.$store.getters.qatTaskStorageTypeData;
                        this.storageTypeOptions = this.storageTypeList;
                        break;
                    case 3:
                        this.$message({
                            showClose: true,
                            message: this.$store.getters.qatTaskStorageTypeData,
                            type: 'error'
                          });
                        break;
                    default:
                        this.loading.storageTypeStatus = false;
                        break;
                }
            },
            myHeader () {
                return {
                    "authToken":window.sessionStorage.getItem('authToken')
                }
            }

	    }
	}
</script>
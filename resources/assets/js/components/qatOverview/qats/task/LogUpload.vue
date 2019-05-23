<style>
   	.el-form-item{
        margin-bottom: 0px;
    }
    .el-col-2{
        padding-right: 10px;
        padding-left: 10px;
    }
    .form {
		padding-bottom: 0px;
	}
</style>
<template>
    <el-container>
	  	<el-aside width="20%">
	  		<el-card class="box-card">
		  		<div slot="header" class="clearfix">
		    		<span>{{$t('messages.task.directory')}}</span>
                    <el-button size="small" type="primary" style="float:right" @click="deleteDir" :loading="loading.deleteTaskFileDirStatus" >{{$t('messages.task.delete')}}</el-button>
                    <el-button size="small" type="primary" style="float:right" @click="addDir" :loading="loading.addTaskFileDirStatus" >{{$t('messages.task.add')}}</el-button><!--:deleteTaskFileDir="deleteTaskFileDir":addTaskFileDir="addTaskFileDir"-->
		  		</div>
			  	<div class="text item">
			    	<el-tree
	              		v-loading="loading.qatTaskDirStatus"
	              		class="filter-tree"
	              		:data="treeData"
	              		:props="defaultProps"
	              		node-key="id"
	              		default-expand-all
	              		highlight-current
	              		:expand-on-click-node="false"
	              		ref="tree"
	              		:options="getDirData"
	              		@node-click="handleNodeClick">
	            	</el-tree>
			  	</div>
			</el-card>
	  	</el-aside>
	  	<el-container style="height: 100%;">
	  		<el-main style="max-height: 50%;border: 1px solid #eee">
	  			<el-card class="box-card">
				  	<div slot="header" class="clearfix">
				    	<span>{{$t('messages.task.fileList')}}</span>
				    	<el-upload 
				            style="float: right;"
				            ref="upload"
				            accept=".gz"
				            :action="uploadTaskFileUrl()"
				            :data="myData"
				            :before-upload="onBeforeUpload"
				            :multiple="true"
				            :on-success="handleSuccess"
				            :headers="myHeader"
				            :show-file-list=false
				            :file-list="fileList">
				            <el-button size="small" type="primary">{{$t('messages.task.upload')}}</el-button>
                            <div slot="tip" class="el-upload__tip">只能上传gz文件，且不超过10MB</div>
				        </el-upload>
				  	</div>
				  	<el-table
			            v-loading="loading.qatTaskDirFileDataStatus"
			            :data="taskDirFiles.slice((currentPage-1)*pageSize,currentPage*pageSize)"
			            max-height="320"
			            border
			            :options="getTaskDirFile"
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
			            :options="getTaskDirFile"
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
        props: {
            dataSource: {
                type: String,
                default: "TDD"
            },
            dataType: {
                type: String,
                default: "KGET"
            }
        },
		data () {
			return {
				defaultProps: {
		            children: 'children',
		            label: 'label'
		        },
				loading: {
					addTaskFileDirStatus : false,
					deleteTaskFileDirStatus : false,
					qatTaskDirStatus: false,
					qatTaskDirFileDataStatus: false

				},
				user: '',
				storageType: '',
				directory: '',
				treeData: [],

				taskDirFiles: [],
				total:0,
                pageSize:5,
                currentPage:1,

                fileList: [],
                myData:{},

                nextFlag: false

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
				this.taskDirFiles = [];
				this.user = data.user;
				this.storageType = data.label;                
                this.directory = data.directory;
                this.myData = {'directory':this.directory};
                this.$store.dispatch( 'loadQatTaskDirFileData',this.myData);
            },
            addDir () {
            	var _this = this;
            	if( this.directory.length == 0){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.storageDirTip')
                    }); 
                    return false;
                }
                this.$prompt(this.$t('messages.task.dirPrompt'), this.$t('messages.task.tip'), {
			        confirmButtonText: this.$t('messages.task.ok'),
			        cancelButtonText: this.$t('messages.task.cancel'),
			        inputPattern: /^[a-zA-Z0-9_$]+$/,
			        inputErrorMessage: this.$t('messages.task.inputErrorMessage')
		        }).then(({ value }) => {
		          	this.$store.dispatch( 'addTaskFileDir',{'directory':this.directory+'/'+value}).
		          	then(function(){
		          		_this.addTaskFileDir();
		          	});
		        }).catch(() => {
		          	this.$message({
		            	type: 'info',
		            	message: this.$t('messages.task.Cancelled')
		          	});       
		        });
            },
            addTaskFileDir () {
            	switch( this.$store.getters.addTaskFileDirStatus ) {
                    case 1:
                        this.loading.addTaskFileDirStatus = true;
                        break;
                    case 2:
                		if(this.$store.getters.addTaskFileDir) {
            				this.$message({
		                        type: 'success',
		                        message: this.$t('messages.task.addSucc')
		                    });
		                    this.$store.dispatch( 'loadQatTaskDirData' );
	                    	this.directory = '';
                        } else {
                        	this.$message({
		                        type: 'error',
		                        message: this.$t('messages.task.existed')
		                    })
                        }
                        
                    case 3:
                    default:
                        this.loading.addTaskFileDirStatus = false;
                        break;
	            }
            },
            deleteDir () {
            	var _this = this;
            	if( this.directory.length == 0){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.storageDirTip')
                    }); 
                    return false;
                }
                this.$confirm(this.$t('messages.task.deleteDirTip')+this.directory.substring(39)+'?', this.$t('messages.task.tip'), {
		          	confirmButtonText: this.$t('messages.task.ok'),
		          	cancelButtonText: this.$t('messages.task.cancel'),
		          	type: 'warning'
		        }).then(() => {
		          	this.$store.dispatch( 'deleteTaskFileDir',{'directory':this.directory})
		          	.then(function(){
		          		_this.deleteTaskFileDir();
		          	});
		        }).catch(() => {
		          	this.$message({
		            	type: 'info',
		            	message: this.$t('messages.task.Cancelled')
		          	});          
		        });
            },
            deleteTaskFileDir () {
            	switch( this.$store.getters.deleteTaskFileDirStatus ) {
                    case 1:
                        this.loading.deleteTaskFileDirStatus = true;
                        break;
                    case 2:
                		if(this.$store.getters.deleteTaskFileDir) {
                        	this.$message({
		                        type: 'success',
		                        message: this.$t('messages.task.deleteSuccess')
		                    });
		                    this.$store.dispatch( 'loadQatTaskDirData' );
                			this.taskDirFiles = [];
                			this.directory = '';
                        } else {
                        	this.$message({
		                        type: 'error',
		                        message: this.$t('messages.task.notExist')
		                    })
                        }
                    case 3:
                    default:
                        this.loading.deleteTaskFileDirStatus = false;
                        break;
	            }
            },
            uploadTaskFileUrl() {
                return "log/uploadTaskFile";
            },
            handleSuccess(res,file,fileList) {
             	//this.$refs.upload.clearFiles()
                if(res.code===20000){
                    this.$message({
                        message: this.$t('messages.task.uploadSucc'),
                        type: 'success'
                    });
                    this.$store.dispatch( 'loadQatTaskDirFileData',this.myData);
                }else {
                    this.$message({
                        message: res.msg,
                        type: 'error'
                    });
                }
            },
            handleExceed(files, fileList) {
            	if (files.length > 10 ){
            		this.$message.warning(this.$t('messages.task.fileLimitTip'));
            	}
            },
            onBeforeUpload(file){
                if( this.directory.length == 0 || this.directory.endsWith(this.storageType+"/"+this.user)){
                    this.$message({
                        type: 'warning',
                        message: this.$t('messages.task.storageDirTip')
                    }); 
                    return false;
                }

                const isGZ = file.type === 'application/x-gzip';
                const isLt10M = file.size / 1024 / 1024  < 10;

                if (!isGZ) {
                  this.$message.error(this.$t('messages.task.isGZ'));
                }
                if (!isLt10M) {
                  this.$message.error(this.$t('messages.task.isLt'));
                }
                return isGZ && isLt10M;
            }
		},
		mounted () {
            this.$store.dispatch( 'loadQatTaskDirData' );
        },
        computed: {
        	getDirData () {
                switch( this.$store.getters.qatTaskDirDataStatus ) {
                    case 1:
                        this.loading.qatTaskDirStatus = true;
                        break;
                    case 2:
                        this.treeData = this.$store.getters.qatTaskDirData;
                    case 3:
                    default:
                        this.loading.qatTaskDirStatus = false;
                        break;
                }
            },
            getTaskDirFile () {
            	switch( this.$store.getters.qatTaskDirFileDataStatus) {
                    case 1:
                        this.loading.qatTaskDirFileDataStatus = true;
                        break;
                    case 2:
                    	if (this.directory != ''){
                    		this.taskDirFiles = this.$store.getters.qatTaskDirFileData;
                        	this.total = this.taskDirFiles.length;
                    	}
                    case 3:
                    default:
                        this.loading.qatTaskDirFileDataStatus = false;
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